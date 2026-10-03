<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    /**
     * Store a newly created user account (Super Admin action).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_suspended' => false,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Akun baru berhasil dibuat!');
    }

    /**
     * Toggle suspend status of a user (Creator or Standard User).
     */
    public function toggleSuspend(Request $request, User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Super Admin tidak dapat di-suspend!');
        }

        $user->is_suspended = ! $user->is_suspended;
        $user->save();

        $statusMsg = $user->is_suspended
            ? "Akun {$user->name} berhasil di-suspend!"
            : "Suspensi akun {$user->name} berhasil dibuka!";

        return redirect()->route('admin.dashboard')->with('success', $statusMsg);
    }

    /**
     * Remove the specified user account.
     * Root Super Admin can delete sub-admins, creators, and users.
     * Regular admins can delete creators and users, but NOT fellow admins.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $currentUser = $request->user();

        if ($user->isRootAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Super Admin Mutlak Utama dilindungi dan tidak dapat dihapus!');
        }

        if ($user->isSuperAdmin() && ! $currentUser->isRootAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya Super Admin Mutlak Utama yang berhak menghapus akun Admin!');
        }

        if ($currentUser->id === $user->id) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $userName = $user->name;
        $roleLabel = $user->role instanceof UserRole ? $user->role->label() : (string) $user->role;
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', "Akun {$userName} ({$roleLabel}) berhasil dihapus dari sistem!");
    }
}
