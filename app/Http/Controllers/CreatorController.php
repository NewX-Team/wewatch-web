<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CreatorController extends Controller
{
    /**
     * Display the specified creator channel page using real database data.
     */
    public function show(string $id, Request $request): View
    {
        $user = null;
        $cleanId = ltrim(urldecode($id), '@');

        // 1. Find by numeric ID
        if (is_numeric($id)) {
            $user = User::find($id);
        }

        // 2. Find by handle (e.g. @aerellgaming or aerellgaming)
        if (! $user) {
            $user = User::where('handle', $id)
                ->orWhere('handle', '@'.$cleanId)
                ->orWhere('handle', $cleanId)
                ->first();
        }

        // 3. Find by Name or Email
        if (! $user) {
            $user = User::where('name', $cleanId)
                ->orWhere('email', $cleanId)
                ->orWhere('name', 'like', "%{$cleanId}%")
                ->first();
        }

        // 4. Find by Slugified Name
        if (! $user) {
            $allUsers = User::all();
            foreach ($allUsers as $u) {
                if (Str::slug($u->name) === Str::slug($cleanId)) {
                    $user = $u;
                    break;
                }
            }
        }

        // 5. Fallback to currently authenticated user if still null
        if (! $user) {
            $user = $request->user();
        }

        // Fetch real published movies created by this creator
        $movies = $user->movies()->with('episodes')->orderBy('id', 'desc')->get();

        $creatorHandle = $user->handle ?: ('@'.Str::slug($user->name, ''));
        if (! str_starts_with($creatorHandle, '@')) {
            $creatorHandle = '@'.$creatorHandle;
        }

        $creator = [
            'id' => $user->id,
            'slug' => $user->handle ? ltrim($user->handle, '@') : Str::slug($user->name),
            'name' => $user->name,
            'email' => $user->email,
            'handle' => $creatorHandle,
            'subscribers' => '0 Subscribers',
            'uploads_count' => $movies->count(),
            'joined_date' => $user->created_at ? $user->created_at->format('F Y') : date('F Y'),
            'bio' => $user->bio ?: ('Channel Resmi Kreator '.$user->name.' di WeWatch Cinema. Menyajikan tayangan sinematik berkualitas tinggi.'),
            'banner' => $user->banner_url ?: asset('images/hero_banner.jpg'),
            'avatar' => $user->avatar_url,
            'tagline' => $user->tagline ?: 'Produksi Film Sinematik Quality 4K UHD',
            'is_verified' => $user->isVerified(),
        ];

        return view('creators.show', compact('creator', 'movies', 'user'));
    }
}
