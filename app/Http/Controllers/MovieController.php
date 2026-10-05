<?php

namespace App\Http\Controllers;

use App\Models\Movie;
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
                        'duration' => $ep->duration ?: '45m',
                        'thumb' => asset($movieModel->poster_url ?: 'images/hero_banner.jpg'),
                        'video_url' => $ep->video_url,
                    ];
                }
            } else {
                $episodesArray[] = [
                    'number' => 1,
                    'title' => 'Episode 1: '.$movieModel->title,
                    'duration' => '45m',
                    'thumb' => asset($movieModel->poster_url ?: 'images/hero_banner.jpg'),
                    'video_url' => null,
                ];
            }

            $creatorData = [
                'id' => $creatorUser ? $creatorUser->id : 1,
                'name' => $creatorUser ? $creatorUser->name : 'Kreator Studio',
                'handle' => $creatorUser ? ($creatorUser->handle ?: ('@'.strtolower(str_replace(' ', '', $creatorUser->name)))) : '@kreator',
                'subscribers' => '0 Subscribers',
                'avatar' => $creatorUser ? $creatorUser->avatar_url : null,
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

            return view('movies.show', compact('movie'));
        }

        // 2. Fallback for legacy static sample titles
        $movies = [
            'cyberpunk-shadows' => [
                'id' => 'cyberpunk-shadows',
                'title' => 'Cyberpunk Shadows',
                'year' => '2026',
                'rating' => '4.9',
                'match' => '99%',
                'quality' => '4K ULTRA HD',
                'duration' => '8 Episodes',
                'category' => 'Sci-Fi Series',
                'banner' => asset('images/hero_banner.jpg'),
                'description' => 'In a neon-soaked dystopian future of 2099 Tokyo, three rogue operatives with cybernetic enhancements unite to infiltrate and dismantle the world\'s most dangerous mega-corporation before an AI weapon is unleashed upon humanity.',
                'director' => 'Elena Vance',
                'cast' => 'Kaito Tanaka, Sarah Connor, Jax Thorne',
                'studio' => 'NeoTokyo Studios',
                'creator' => [
                    'id' => 1,
                    'name' => 'NeoTokyo Studios',
                    'handle' => '@neotokyostudios',
                    'subscribers' => '128.5K Subscribers',
                    'avatar' => null,
                ],
                'genres' => ['Sci-Fi', 'Cyberpunk', 'Dystopian', 'Action Thriller', 'Futuristic'],
                'episodes' => [
                    ['number' => 1, 'title' => 'Ep 1: Neon Genesis', 'duration' => '48m', 'thumb' => asset('images/hero_banner.jpg')],
                    ['number' => 2, 'title' => 'Ep 2: Cybernetic Pulse', 'duration' => '52m', 'thumb' => asset('images/poster_action.jpg')],
                ],
            ],
        ];

        $movie = $movies[$id] ?? $movies['cyberpunk-shadows'];

        return view('movies.show', compact('movie'));
    }
}
