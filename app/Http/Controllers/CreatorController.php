<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreatorController extends Controller
{
    /**
     * Display the specified creator channel page using real database data.
     */
    public function show(string $id, Request $request): View
    {
        // Try finding user by ID or name/email
        $user = null;

        if (is_numeric($id)) {
            $user = User::find($id);
        }

        if (! $user) {
            $user = User::where('name', 'like', "%{$id}%")
                ->orWhere('email', 'like', "%{$id}%")
                ->first();
        }

        // Fallback to currently authenticated user if still null
        if (! $user) {
            $user = $request->user();
        }

        // Fetch real published movies created by this creator
        $movies = $user->movies()->with('episodes')->orderBy('id', 'desc')->get();

        $creator = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'handle' => $user->handle ?: ('@'.strtolower(str_replace(' ', '', $user->name))),
            'subscribers' => '0 Subscribers',
            'uploads_count' => $movies->count(),
            'joined_date' => $user->created_at ? $user->created_at->format('F Y') : date('F Y'),
            'bio' => $user->bio ?: ('Channel Resmi Kreator '.$user->name.' di WeWatch Cinema. Menyajikan tayangan sinematik berkualitas tinggi.'),
            'banner' => $user->banner_url ?: asset('images/hero_banner.jpg'),
            'avatar' => $user->avatar_url,
            'tagline' => $user->tagline ?: 'Produksi Film Sinematik Quality 4K UHD',
        ];

        return view('creators.show', compact('creator', 'movies', 'user'));
    }
}
