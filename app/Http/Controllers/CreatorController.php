<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CreatorController extends Controller
{
    /**
     * Display the specified creator channel page.
     */
    public function show(string $id): View
    {
        $creators = [
            'neotokyo-studios' => [
                'id' => 'neotokyo-studios',
                'name' => 'NeoTokyo Studios',
                'handle' => '@neotokyostudios',
                'subscribers' => '128.5K',
                'uploads_count' => 14,
                'joined_date' => 'January 2025',
                'bio' => 'Official Indie Creator Channel for Cyberpunk, Sci-Fi, and Dystopian High-Tech Cinema. Creating 4K cinematic stories for futuristic dreamers.',
                'banner' => asset('images/hero_banner.jpg'),
                'featured_movie' => [
                    'id' => 'cyberpunk-shadows',
                    'title' => 'Cyberpunk Shadows',
                    'category' => 'Sci-Fi Series',
                    'rating' => '4.9',
                    'views' => '840K views',
                    'time' => '2 weeks ago',
                    'banner' => asset('images/hero_banner.jpg'),
                    'description' => 'In a neon-soaked dystopian future of 2099 Tokyo, three rogue operatives with cybernetic enhancements unite to infiltrate the world\'s most dangerous mega-corporation.',
                ],
                'uploads' => [
                    [
                        'id' => 'cyberpunk-shadows',
                        'title' => 'Cyberpunk Shadows',
                        'category' => 'Sci-Fi Series',
                        'duration' => '8 Ep',
                        'views' => '840K views',
                        'rating' => '4.9',
                        'banner' => asset('images/hero_banner.jpg'),
                    ],
                    [
                        'id' => 'midnight-drift',
                        'title' => 'Midnight Drift',
                        'category' => 'Action Thriller',
                        'duration' => '1h 52m',
                        'views' => '520K views',
                        'rating' => '4.9',
                        'banner' => asset('images/poster_action.jpg'),
                    ],
                    [
                        'id' => 'deep-ocean-abyss',
                        'title' => 'Deep Ocean Abyss 4K',
                        'category' => 'Documentary',
                        'duration' => '1h 24m',
                        'views' => '310K views',
                        'rating' => '5.0',
                        'banner' => asset('images/poster_documentary.jpg'),
                    ],
                    [
                        'id' => 'realm-of-eldoria',
                        'title' => 'Realm of Eldoria',
                        'category' => 'Fantasy Epic',
                        'duration' => '10 Ep',
                        'views' => '690K views',
                        'rating' => '4.8',
                        'banner' => asset('images/poster_fantasy.jpg'),
                    ],
                ],
            ],
        ];

        $creator = $creators[$id] ?? $creators['neotokyo-studios'];

        return view('creators.show', compact('creator'));
    }
}
