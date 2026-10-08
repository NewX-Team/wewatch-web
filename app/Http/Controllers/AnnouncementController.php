<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display the announcements and notifications feed for users.
     */
    public function userAnnouncements(Request $request): View
    {
        $userRole = auth()->user()?->role?->value ?? 'user';

        $announcements = Announcement::where('is_active', true)
            ->where(function ($query) use ($userRole) {
                $query->where('target_role', 'all')
                    ->orWhere('target_role', $userRole);
            })
            ->latest()
            ->get();

        return view('announcements.user-index', compact('announcements'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', 'string', 'in:info,promo,warning,event'],
            'target_role' => ['required', 'string', 'in:all,user,creator'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_role' => $validated['target_role'],
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Pengumuman baru berhasil diterbitkan!');
    }

    /**
     * Toggle active status of the specified announcement.
     */
    public function toggleStatus(Announcement $announcement): RedirectResponse
    {
        $announcement->update([
            'is_active' => ! $announcement->is_active,
        ]);

        $statusMessage = $announcement->is_active
            ? 'Pengumuman telah diaktifkan kembali!'
            : 'Pengumuman telah dinonaktifkan!';

        return redirect()->back()->with('success', $statusMessage);
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus dari sistem!');
    }
}
