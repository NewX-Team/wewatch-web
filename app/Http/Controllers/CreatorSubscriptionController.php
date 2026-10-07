<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreatorSubscriptionController extends Controller
{
    /**
     * Toggle subscription status for a creator channel.
     */
    public function toggle(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $subscriber = $request->user();

        // Prevent self-subscription
        if ($subscriber->id === $user->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mensubscribe channel milik Anda sendiri.',
                ], 422);
            }

            return back()->with('status', 'Anda tidak dapat mensubscribe channel milik Anda sendiri.');
        }

        $isSubscribed = $subscriber->isSubscribedTo($user->id);

        if ($isSubscribed) {
            $subscriber->subscribedCreators()->detach($user->id);
            $newStatus = false;
            $msg = 'Batal mensubscribe channel '.$user->name.'.';
        } else {
            $subscriber->subscribedCreators()->attach($user->id);
            $newStatus = true;
            $msg = 'Berhasil mensubscribe channel '.$user->name.'!';
        }

        $subscribersCount = $user->subscribersCount();
        $subscribersFormatted = $user->subscribersCountFormatted();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'subscribed' => $newStatus,
                'subscribers_count' => $subscribersCount,
                'subscribers_formatted' => $subscribersFormatted,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
