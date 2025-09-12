<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    // tampilkan halaman admin announcements
    public function index()
    {
        $announcements = Announcement::orderBy('order')->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'active' => (bool) $a->active,
                'order' => $a->order,
                'image_url' => $a->image_url,
            ]);

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    // simpan upload
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable','string','max:255'],
            'image' => ['required','image','mimes:jpg,jpeg,png','max:5120'], // max 5MB
            'order' => ['nullable','integer'],
            'active' => ['nullable','boolean'],
        ]);

        $file = $request->file('image');
        $filename = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('announcements', $filename, 'public'); // storage/app/public/announcements/...

        $announcement = Announcement::create([
            'title' => $request->input('title'),
            'image_path' => $path,
            'active' => $request->boolean('active', true),
            'order' => $request->input('order', 0),
        ]);

        return redirect()->back()->with('success', 'Announcement berhasil diupload.');
    }

    // hapus
    public function destroy($id)
    {
        $a = Announcement::findOrFail($id);

        // hapus file bila ada di disk public
        if ($a->image_path && !str_starts_with($a->image_path, 'http')) {
            Storage::disk('public')->delete($a->image_path);
        }

        $a->delete();

        return redirect()->back()->with('success', 'Announcement dihapus.');
    }

    // toggle active (bisa dipanggil via POST)
    public function toggle($id)
    {
        $a = Announcement::findOrFail($id);
        $a->active = !$a->active;
        $a->save();

        return redirect()->back();
    }
}
