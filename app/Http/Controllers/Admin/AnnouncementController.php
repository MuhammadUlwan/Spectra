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
    /**
     * Tampilkan daftar pengumuman untuk admin
     */
    public function index()
    {
        $announcements = Announcement::orderBy('order', 'asc')->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'title'     => $a->title,
                'active'    => (bool) $a->active,
                'order'     => $a->order,
                'image_url' => $a->image_url,
            ]);

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Simpan pengumuman baru (upload gambar)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable','string','max:255'],
            'image' => ['required','image','mimes:jpg,jpeg,png','max:5120','dimensions:width=1200,height=400'],
            'order' => ['nullable','integer'],
            'active' => ['nullable','boolean'],
        ]);

        $file = $request->file('image');
        $filename = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('announcements', $filename, 'public');

        Announcement::create([
            'title'      => $request->input('title'),
            'image_path' => $path,
            'active'     => $request->boolean('active', true),
            'order'      => $request->input('order', 0),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Hapus pengumuman
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->image_path && !str_starts_with($announcement->image_path, 'http')) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $announcement->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Toggle aktif/nonaktif pengumuman
     */
    public function toggle($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->active = !$announcement->active;
        $announcement->save();

        return redirect()->back()->with('success', 'Status pengumuman diperbarui.');
    }
}