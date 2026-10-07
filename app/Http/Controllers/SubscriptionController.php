<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription membership upgrade page.
     */
    public function index(): View
    {
        return view('subscription.index');
    }

    /**
     * Display dedicated page for creator channels subscribed by the logged-in user.
     */
    public function userSubscriptions(Request $request): View
    {
        $user = $request->user();
        $subscribedCreators = $user->subscribedCreators()->withCount(['subscribers', 'movies'])->get();

        return view('subscriptions.user-subscriptions', compact('subscribedCreators'));
    }

    /**
     * Upgrade logged in user subscription tier (free, pro, vip).
     */
    public function upgrade(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tier' => 'required|in:free,pro,vip',
        ]);

        $request->user()->update([
            'subscription_tier' => $validated['tier'],
        ]);

        $tierLabel = strtoupper($validated['tier']);

        return back()->with('success', "Selamat! Akun Anda berhasil di-upgrade ke keanggotaan Paket {$tierLabel}.");
    }
}
