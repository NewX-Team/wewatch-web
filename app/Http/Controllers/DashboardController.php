<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
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
     * Super Admin Dashboard view.
     */
    public function superAdmin(): View
    {
        return view('dashboards.super-admin');
    }

    /**
     * Creator Studio Dashboard view.
     */
    public function creator(): View
    {
        return view('dashboards.creator');
    }

    /**
     * Standard User Dashboard view.
     */
    public function user(): View
    {
        return view('dashboards.user');
    }
}
