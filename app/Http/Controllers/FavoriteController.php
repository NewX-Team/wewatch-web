<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display the user's favorite movies list page.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Real favorited movies
        $favorites = $user->favoriteMovies()
            ->with(['creator', 'episodes'])
            ->latest('favorites.created_at')
            ->get();

        // Recommendations (published movies not yet favorited)
        $favoritedIds = $favorites->pluck('id')->toArray();
        $recommendations = Movie::where('is_published', true)
            ->whereNotIn('id', $favoritedIds)
            ->with(['creator', 'episodes'])
            ->latest()
            ->take(6)
            ->get();

        return view('favorites.index', compact('favorites', 'recommendations'));
    }

    /**
     * Toggle real-time favorite status for a movie.
     */
    public function toggle(Request $request, Movie $movie): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        $isFavorited = $user->toggleFavorite($movie);

        $message = $isFavorited
            ? "'{$movie->title}' telah ditambahkan ke favorit!"
            : "'{$movie->title}' telah dihapus dari favorit!";

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'status' => 'success',
                'is_favorite' => $isFavorited,
                'message' => $message,
                'favorites_count' => $movie->favoritesCount(),
                'user_favorites_count' => $user->favoriteMovies()->count(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
