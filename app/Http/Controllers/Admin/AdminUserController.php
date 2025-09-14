<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna
     */
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return $this->mapUser($user);
        });

        return Inertia::render('Admin/Users', [
            'auth'       => ['user' => Auth::user()],
            'logoutUrl'  => route('logout'),
            'profileUrl'    => route('admin.profile'),
            'users'      => $users,
        ]);
    }

    /**
     * Tampilkan detail user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Admin/UserDetail', [
            'user' => $this->mapUser($user),
        ]);
    }

    /**
     * Update status user (aktif/nonaktif)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['active', 'inactive'])) {
            return back()->with('error', 'Status tidak valid');
        }

        $user->is_active = $status === 'active';
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Status berhasil diperbarui');
    }

    /**
     * Mapping user data untuk tampilan
     */
    private function mapUser(User $user)
    {
        $roleLabels = [
            'admin'    => 'Admin',
            'investor' => $user->is_consultant ? 'Konsultan' : 'Investor',
        ];

        return [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'role'           => $user->role,
            'is_consultant'  => $user->is_consultant,
            'role_label'     => $roleLabels[$user->role] ?? '-',
            'is_active'      => (bool) $user->is_active,
            'status_label'   => $user->is_active ? 'Aktif' : 'Nonaktif',
            'last_login'     => $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-',
            'created_at'     => $user->created_at->format('d M Y H:i'),
            'updated_at'     => $user->updated_at->format('d M Y H:i'),
            'wallet_address' => $user->wallet_address ?? '',
        ];
    }

    /**
     * Update seluruh detail user (role + wallet + status)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role'           => 'required|in:admin,investor,konsultan',
            'wallet_address' => 'nullable|string|max:255',
            'is_active'      => 'nullable|boolean',
        ]);

        $user->role = $request->role;
        $user->wallet_address = $request->wallet_address;
        if (!is_null($request->is_active)) {
            $user->is_active = $request->is_active;
        }
        $user->save();

        // Redirect ke halaman sama dengan flash message
        return Inertia::location(route('admin.users.index'));
    }
}
