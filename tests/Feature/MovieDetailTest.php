<?php

use App\Models\Movie;
use App\Models\User;

test('authenticated user can view movie detail page', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/movies/cyberpunk-shadows');

    $response->assertStatus(200);
    $response->assertSee('Cyberpunk Shadows');
    $response->assertSee('Overview');
    $response->assertSee('Episodes');
    $response->assertSee('Genres');
});

test('user can view real creator published movie detail and link to creator channel', function () {
    $creator = User::factory()->creator()->create([
        'name' => 'Aerell Gaming Studio',
        'handle' => '@aerellgaming',
    ]);

    $movie = Movie::create([
        'user_id' => $creator->id,
        'title' => 'Speed Racer 2026',
        'slug' => 'speed-racer-2026',
        'description' => 'Film balapan sinematik kecepatan tinggi.',
        'genre' => 'Action',
        'status' => 'ongoing',
        'access_tier' => 'free',
        'is_published' => true,
    ]);

    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/movies/'.$movie->id);

    $response->assertStatus(200);
    $response->assertSee('Speed Racer 2026');
    $response->assertSee('Aerell Gaming Studio');
    $response->assertSee('@aerellgaming');
    $response->assertSee(route('creators.show', $creator->id));
});
