<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dispatch user to their role-specific dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match ($user->role) {
            UserRole::SuperAdmin => redirect()->route('admin.dashboard'),
            UserRole::Creator => redirect()->route('creator.dashboard'),
            UserRole::User => redirect()->route('user.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }

    /**
     * Super Admin Dashboard view with real database metrics.
     */
    public function superAdmin(): View
    {
        $users = User::orderBy('id', 'desc')->get();
        $announcements = Announcement::orderBy('id', 'desc')->get();

        $stats = [
            'total_users' => User::count(),
            'super_admins' => User::where('role', UserRole::SuperAdmin)->count(),
            'creators' => User::where('role', UserRole::Creator)->count(),
            'regular_users' => User::where('role', UserRole::User)->count(),
            'active_users' => User::where('is_suspended', false)->count(),
            'suspended_users' => User::where('is_suspended', true)->count(),
            'total_announcements' => Announcement::count(),
            'active_announcements' => Announcement::where('is_active', true)->count(),
        ];

        return view('dashboards.super-admin', compact('users', 'stats', 'announcements'));
    }

    /**
     * Creator Studio Dashboard view with creator's published movies.
     */
    public function creator(Request $request): View
    {
        $movies = $request->user()->movies()->with('episodes')->orderBy('id', 'desc')->get();

        return view('dashboards.creator', compact('movies'));
    }

    /**
     * Standard User Dashboard view with creator published movies.
     */
    public function user(Request $request): View
    {
        $user = $request->user();
        $roleValue = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        $currentSessionId = $request->session()->getId();
        $hasBeenServedThisSession = $request->session()->get('announcements_served_session_id') === $currentSessionId;

        if (! $hasBeenServedThisSession) {
            // First time landing on dashboard in this login session
            $request->session()->put('announcements_served_session_id', $currentSessionId);

            $announcements = Announcement::where('is_active', true)
                ->whereIn('target_role', ['all', $roleValue])
                ->orderBy('id', 'asc')
                ->get();
        } else {
            // Already served in this login session (refreshed page, navigated from movie/other pages)
            $announcements = collect();
        }

        $movies = Movie::with(['creator', 'episodes'])
            ->where('is_published', true)
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboards.user', compact('announcements', 'movies'));
    }
}
