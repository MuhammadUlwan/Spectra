<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('user.preference')->orderBy('created_at','desc');

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        $withdraws = $query->get()->map(function($wd){
            $prefs = $wd->user->preference ?? null;
            return [
                'id' => $wd->id,
                'user_id' => $wd->user_id,
                'user_name' => $wd->user->name ?? '-',
                'user_wallet' => $wd->user->wallet_address ?? '-',
                'amount' => $wd->amount,
                'type' => $wd->type,
                'status' => $wd->status ?? 'pending',
                'processed_at' => $wd->processed_at,
                'proof_transfer' => $wd->proof_transfer,
                'method' => $wd->withdraw_method ?? 'wallet',
                'bank_name' => $prefs->bank_name ?? '-',      // tampil saja, tidak simpan di DB
                'bank_account' => $prefs->bank_number ?? '-', // tampil saja, tidak simpan di DB
                'is_due' => $wd->type === 'profit' ? Carbon::now()->gte($wd->period_end ?? Carbon::now()) : true,
            ];
        });

        return Inertia::render('Admin/Withdraws', [
            'withdraws' => $withdraws,
            'auth' => ['user' => Auth::user()],
            'logoutUrl'  => route('logout'),
            'profileUrl'    => route('admin.profile'),
        ]);
    }

    public function process(Request $request, $id)
    {
        $withdraw = Withdrawal::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,rejected',
            'method' => 'required|in:wallet,bank',
            'proof_transfer' => 'nullable|file|max:2048',
        ]);

        // Update field yang relevan saja
        $withdraw->status = $request->status;                // menjadi 'completed'
        $withdraw->withdraw_method = $request->input('method'); // 'wallet' atau 'bank'
        $withdraw->processed_by = Auth::id();
        $withdraw->processed_at = now();

        // Upload bukti transfer jika ada
        if ($request->hasFile('proof_transfer')) {
            $file = $request->file('proof_transfer');
            $filename = time().'_'.$file->getClientOriginalName();
            
            // simpan di storage/app/public/withdraw_proofs
            $file->storeAs('withdraw_proofs', $filename, 'public'); 
            
            // path untuk diakses via browser (mengikuti storage link)
            $withdraw->proof_transfer = '/public/storage/withdraw_proofs/'.$filename;
        }

        $withdraw->save();

        return redirect()->back()->with('success', 'Withdrawal berhasil diperbarui');
    }

    public function export(Request $request)
    {
        $query = Withdrawal::with('user');

        if ($request->filled('type')) $query->where('type',$request->type);
        if ($request->filled('status')) $query->where('status',$request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at','>=',$request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at','<=',$request->date_to);

        $withdraws = $query->get();

        $csvData = "User,Type,Amount,Status,Method,Processed At\n";
        foreach($withdraws as $wd){
            $method = $wd->withdraw_method === 'wallet' ? 'Wallet' : 'Bank';
            $csvData .= "{$wd->user->name},{$wd->type},{$wd->amount},{$wd->status},{$method},";
            $csvData .= ($wd->processed_at ? $wd->processed_at : '-')."\n";
        }

        $filename = 'withdraws_'.date('Ymd_His').'.csv';
        return response($csvData)
            ->header('Content-Type','text/csv')
            ->header('Content-Disposition','attachment; filename="'.$filename.'"');
    }
}
