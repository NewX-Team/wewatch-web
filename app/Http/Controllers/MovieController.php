<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Display the specified movie detail page.
     */
    public function show(string $id): View
    {
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
                    'id' => 'neotokyo-studios',
                    'name' => 'NeoTokyo Studios',
                    'handle' => '@neotokyostudios',
                    'subscribers' => '128.5K Subscribers',
                ],
                'genres' => ['Sci-Fi', 'Cyberpunk', 'Dystopian', 'Action Thriller', 'Futuristic'],
                'episodes' => [
                    ['number' => 1, 'title' => 'Ep 1: Neon Genesis', 'duration' => '48m', 'thumb' => asset('images/hero_banner.jpg')],
                    ['number' => 2, 'title' => 'Ep 2: Cybernetic Pulse', 'duration' => '52m', 'thumb' => asset('images/poster_action.jpg')],
                    ['number' => 3, 'title' => 'Ep 3: The Ghost Core', 'duration' => '45m', 'thumb' => asset('images/poster_fantasy.jpg')],
                    ['number' => 4, 'title' => 'Ep 4: Dystopian Breach', 'duration' => '50m', 'thumb' => asset('images/poster_documentary.jpg')],
                    ['number' => 5, 'title' => 'Ep 5: Protocol Overdrive', 'duration' => '56m', 'thumb' => asset('images/hero_banner.jpg')],
                ],
            ],
            'midnight-drift' => [
                'id' => 'midnight-drift',
                'title' => 'Midnight Drift',
                'year' => '2026',
                'rating' => '4.9',
                'match' => '97%',
                'quality' => 'FULL HD',
                'duration' => '1h 52m',
                'category' => 'Action Thriller',
                'banner' => asset('images/poster_action.jpg'),
                'description' => 'High-stakes underground street racing through futuristic neon Tokyo highways where outlaw drivers compete in customized hypercars with rocket boosters for supreme glory and survival.',
                'director' => 'Marcus Sterling',
                'cast' => 'Kenji Takahashi, Maya Lin, Leo Vance',
                'studio' => 'Apex Racing Films',
                'creator' => [
                    'id' => 'neotokyo-studios',
                    'name' => 'Apex Racing Films',
                    'handle' => '@apexracing',
                    'subscribers' => '95K Subscribers',
                ],
                'genres' => ['Action', 'Underground Racing', 'Thriller', 'Speed', 'Tokyo Night'],
                'episodes' => [
                    ['number' => 1, 'title' => 'Feature Movie: Full Cut', 'duration' => '1h 52m', 'thumb' => asset('images/poster_action.jpg')],
                    ['number' => 2, 'title' => 'Bonus: Directors Commentary', 'duration' => '25m', 'thumb' => asset('images/hero_banner.jpg')],
                ],
            ],
            'deep-ocean-abyss' => [
                'id' => 'deep-ocean-abyss',
                'title' => 'Deep Ocean Abyss 4K',
                'year' => '2026',
                'rating' => '5.0',
                'match' => '98%',
                'quality' => '4K ULTRA HD',
                'duration' => '1h 24m',
                'category' => 'Documentary',
                'banner' => asset('images/poster_documentary.jpg'),
                'description' => 'A breathtaking cinematic journey into the deepest marine trenches of the Mariana Trench, featuring bioluminescent sea creatures, ancient underwater caverns, and alien-like ocean ecosystems.',
                'director' => 'Dr. Aris Thorne',
                'cast' => 'Narrated by David Attenborough',
                'studio' => 'Oceanic Horizon Docs',
                'creator' => [
                    'id' => 'neotokyo-studios',
                    'name' => 'Oceanic Horizon Docs',
                    'handle' => '@oceanichorizon',
                    'subscribers' => '210K Subscribers',
                ],
                'genres' => ['Documentary', 'Nature', 'Oceanic', 'Bioluminescence', 'Exploration'],
                'episodes' => [
                    ['number' => 1, 'title' => 'Feature Documentary: Abyss', 'duration' => '1h 24m', 'thumb' => asset('images/poster_documentary.jpg')],
                    ['number' => 2, 'title' => 'Behind the Scenes: Submersible Tech', 'duration' => '32m', 'thumb' => asset('images/poster_fantasy.jpg')],
                ],
            ],
            'realm-of-eldoria' => [
                'id' => 'realm-of-eldoria',
                'title' => 'Realm of Eldoria',
                'year' => '2026',
                'rating' => '4.8',
                'match' => '95%',
                'quality' => 'FULL HD',
                'duration' => '10 Episodes',
                'category' => 'Fantasy Epic',
                'banner' => asset('images/poster_fantasy.jpg'),
                'description' => 'A dormant millennium war reawakens as a young knight claims the ancient glowing citadel and unleashes mythical elemental beasts against an encroaching dark legion.',
                'director' => 'Cedric Vance',
                'cast' => 'Arthur Pendelton, Freya Frost, Gareth Blackwood',
                'studio' => 'Mythic Citadel Media',
                'creator' => [
                    'id' => 'neotokyo-studios',
                    'name' => 'Mythic Citadel Media',
                    'handle' => '@mythiccitadel',
                    'subscribers' => '175K Subscribers',
                ],
                'genres' => ['Fantasy', 'Epic Saga', 'Mythology', 'Knights', 'Magic'],
                'episodes' => [
                    ['number' => 1, 'title' => 'Ep 1: The Citadel Awakes', 'duration' => '54m', 'thumb' => asset('images/poster_fantasy.jpg')],
                    ['number' => 2, 'title' => 'Ep 2: Flame of Eldoria', 'duration' => '49m', 'thumb' => asset('images/poster_action.jpg')],
                    ['number' => 3, 'title' => 'Ep 3: The Dark Encroach', 'duration' => '51m', 'thumb' => asset('images/hero_banner.jpg')],
                    ['number' => 4, 'title' => 'Ep 4: Blade of Light', 'duration' => '58m', 'thumb' => asset('images/poster_documentary.jpg')],
                ],
            ],
        ];

        $movie = $movies[$id] ?? $movies['cyberpunk-shadows'];

        return view('movies.show', compact('movie'));
    }
}
