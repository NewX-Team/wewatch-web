<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreatorMovieController extends Controller
{
    /**
     * Store a new published film / series from creator.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string|max:100',
            'status' => 'required|in:ongoing,completed',
            'access_tier' => 'required|in:free,pro,vip',
            'poster_url' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'initial_episode_title' => 'nullable|string|max:255',
            'initial_episode_access_tier' => 'nullable|in:free,pro,vip',
            'video_url' => 'nullable|string',
        ]);

        $posterUrl = $request->input('poster_url') ?: asset('images/hero_banner.jpg');
        $bannerUrl = $request->input('banner_url') ?: $posterUrl;

        $movie = $request->user()->movies()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'description' => $validated['description'],
            'genre' => $validated['genre'],
            'status' => $validated['status'],
            'access_tier' => $validated['access_tier'],
            'poster_url' => $posterUrl,
            'banner_url' => $bannerUrl,
            'release_year' => date('Y'),
            'rating' => 5.0,
            'is_published' => true,
        ]);

        // Create initial episode #1
        $episodeTitle = $request->input('initial_episode_title') ?: 'Episode 1: Perdana';
        $episodeAccessTier = $request->input('initial_episode_access_tier') ?: $validated['access_tier'];
        $videoUrl = $request->input('video_url') ?: 'https://www.youtube.com/embed/dQw4w9WgXcQ';

        $movie->episodes()->create([
            'episode_number' => 1,
            'title' => $episodeTitle,
            'description' => 'Episode perdana film '.$movie->title,
            'duration' => 'Auto',
            'access_tier' => $episodeAccessTier,
            'video_url' => $videoUrl,
            'thumbnail_url' => $posterUrl,
        ]);

        return back()->with('success', 'Film "'.$movie->title.'" berhasil diterbitkan! Episode 1 (Akses '.strtoupper($episodeAccessTier).') siap diakses penonton.');
    }

    /**
     * Add a new episode (e.g. Episode 2 next week) to an ongoing movie.
     */
    public function addEpisode(Request $request, Movie $movie): RedirectResponse
    {
        if ($movie->user_id !== $request->user()->id && ! $request->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'access_tier' => 'required|in:free,pro,vip',
            'video_url' => 'nullable|string',
        ]);

        $nextNum = $movie->episodes()->max('episode_number') + 1;
        $accessTier = $validated['access_tier'];

        $movie->episodes()->create([
            'episode_number' => $nextNum,
            'title' => $validated['title'],
            'description' => 'Episode '.$nextNum.' dari serial '.$movie->title,
            'duration' => 'Auto',
            'access_tier' => $accessTier,
            'video_url' => $request->input('video_url') ?: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'thumbnail_url' => $movie->poster_url,
        ]);

        return back()->with('success', 'Episode #'.$nextNum.' ("'.$validated['title'].'" - Tier '.strtoupper($accessTier).') berhasil ditambahkan ke film '.$movie->title.'!');
    }

    /**
     * Toggle status between ongoing and completed.
     */
    public function toggleStatus(Request $request, Movie $movie): RedirectResponse
    {
        if ($movie->user_id !== $request->user()->id && ! $request->user()->isSuperAdmin()) {
            abort(403);
        }

        $newStatus = $movie->status === 'ongoing' ? 'completed' : 'ongoing';
        $movie->update(['status' => $newStatus]);

        $label = $newStatus === 'completed' ? 'Tamat / Selesai' : 'Ongoing (Masih Berlanjut)';

        return back()->with('status', 'Status rilis film "'.$movie->title.'" diperbarui menjadi: '.$label);
    }

    /**
     * Delete a creator's published movie.
     */
    public function destroy(Request $request, Movie $movie): RedirectResponse
    {
        if ($movie->user_id !== $request->user()->id && ! $request->user()->isSuperAdmin()) {
            abort(403);
        }

        $movieTitle = $movie->title;
        $movie->delete();

        return back()->with('status', 'Film "'.$movieTitle.'" berhasil dihapus dari platform.');
    }
}
