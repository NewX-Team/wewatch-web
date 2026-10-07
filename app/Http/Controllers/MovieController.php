<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Display the specified movie detail page.
     */
    public function show(string $id): View
    {
        // 1. First, search in Database for real Creator-published movie
        $movieModel = Movie::with(['creator', 'episodes'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->first();

        if ($movieModel) {
            $creatorUser = $movieModel->creator;

            $episodesArray = [];
            if ($movieModel->episodes->isNotEmpty()) {
                foreach ($movieModel->episodes as $index => $ep) {
                    $episodesArray[] = [
                        'number' => $ep->episode_number ?: ($index + 1),
                        'title' => $ep->title ?: ('Episode '.($index + 1)),
                        'duration' => $ep->duration ?: 'Auto',
                        'access_tier' => strtolower($ep->access_tier ?: 'free'),
                        'thumb' => asset($movieModel->poster_url ?: 'images/hero_banner.jpg'),
                        'video_url' => $ep->video_url,
                    ];
                }
            } else {
                $episodesArray[] = [
                    'number' => 1,
                    'title' => 'Episode 1: '.$movieModel->title,
                    'duration' => 'Auto',
                    'access_tier' => strtolower($movieModel->access_tier ?: 'free'),
                    'thumb' => asset($movieModel->poster_url ?: 'images/hero_banner.jpg'),
                    'video_url' => null,
                ];
            }

            $creatorSlug = $creatorUser ? ($creatorUser->handle ? ltrim($creatorUser->handle, '@') : $creatorUser->id) : 1;
            $isSubscribed = (auth()->check() && $creatorUser) ? auth()->user()->isSubscribedTo($creatorUser->id) : false;

            $creatorData = [
                'id' => $creatorUser ? $creatorUser->id : 1,
                'name' => $creatorUser ? $creatorUser->name : 'Kreator Studio',
                'handle' => $creatorUser ? ($creatorUser->handle ?: ('@'.strtolower(str_replace(' ', '', $creatorUser->name)))) : '@kreator',
                'slug' => $creatorSlug,
                'subscribers' => $creatorUser ? $creatorUser->subscribersCountFormatted() : '0 Subscribers',
                'is_subscribed' => $isSubscribed,
                'avatar' => $creatorUser ? $creatorUser->avatar_url : null,
                'is_verified' => $creatorUser ? $creatorUser->isVerified() : false,
            ];

            $movie = [
                'id' => $movieModel->id,
                'slug' => $movieModel->slug ?: $movieModel->id,
                'title' => $movieModel->title,
                'year' => $movieModel->release_year ?: date('Y'),
                'rating' => number_format($movieModel->rating ?: 4.9, 1),
                'match' => '99%',
                'quality' => '4K ULTRA HD',
                'duration' => $movieModel->episodes->count().' Episode',
                'category' => $movieModel->genre ?: 'Sinema',
                'banner' => asset($movieModel->poster_url ?: ($movieModel->banner_url ?: 'images/hero_banner.jpg')),
                'description' => $movieModel->description ?: 'Tidak ada deskripsi sinopsis film.',
                'director' => $creatorUser ? $creatorUser->name : 'WeWatch Director',
                'cast' => 'Pemeran Sinematik WeWatch',
                'studio' => $creatorUser ? $creatorUser->name : 'WeWatch Studio',
                'creator' => $creatorData,
                'genres' => array_filter(explode(',', $movieModel->genre ?: 'Film,Sinema')),
                'episodes' => $episodesArray,
            ];

            // Fetch real registered users from DB for realistic comments section if available
            $recentUsers = User::latest()->take(3)->get();
            $initialComments = [];
            $sampleCommentTexts = [
                'Gokil sinematografi nya dapet banget vibes-nya! Episode selanjutnya paling epic pertarungannya 🔥',
                'Editing suara dan soundtracknya juara sih, pas banget dikombinasiin sama visualnya.',
                'Alur ceritanya padat dan ga bertele-tele. Ditunggu kelanjutan episode selanjutnya min!',
            ];

            foreach ($recentUsers as $idx => $rUser) {
                $initialComments[] = [
                    'id' => $rUser->id,
                    'name' => $rUser->name,
                    'initial' => strtoupper(substr($rUser->name, 0, 1)),
                    'color' => $idx === 0 ? 'bg-red-600' : ($idx === 1 ? 'bg-purple-600' : 'bg-emerald-600'),
                    'time' => (($idx + 1) * 2).'h ago',
                    'content' => $sampleCommentTexts[$idx % count($sampleCommentTexts)],
                    'likes' => (3 - $idx) * 4,
                    'liked' => false,
                ];
            }

            return view('movies.show', compact('movie', 'initialComments'));
        }

        // Fallback for legacy test route or missing movies: get first published movie or create mock
        $fallbackMovie = Movie::with(['creator', 'episodes'])->first();
        if ($fallbackMovie) {
            return $this->show((string) $fallbackMovie->id);
        }

        return redirect()->route('user.dashboard');
    }
}
