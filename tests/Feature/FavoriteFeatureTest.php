<?php

use App\Models\User;

test('unauthenticated user is redirected when toggling favorite or accessing favorites page', function () {
    $creator = User::factory()->creator()->create();
    $movie = $creator->movies()->create([
        'title' => 'Cyberpunk Shadows',
        'description' => 'Deskripsi film sinematik test.',
        'genre' => 'Sci-Fi',
        'status' => 'Ongoing',
        'access_tier' => 'free',
        'is_published' => true,
    ]);

    $response = $this->get('/user/favorites');
    $response->assertRedirect('/login');

    $response2 = $this->post("/movies/{$movie->id}/toggle-favorite");
    $response2->assertRedirect('/login');
});

test('authenticated user can toggle favorite on a real movie via real-time JSON response', function () {
    $user = User::factory()->user()->create();
    $creator = User::factory()->creator()->create();
    $movie = $creator->movies()->create([
        'title' => 'Cyberpunk Shadows',
        'description' => 'Deskripsi film sinematik test.',
        'genre' => 'Sci-Fi',
        'status' => 'Ongoing',
        'access_tier' => 'free',
        'is_published' => true,
    ]);

    expect($user->isFavorite($movie))->toBeFalse();

    // 1. Add to favorite
    $response = $this->actingAs($user)
        ->postJson("/movies/{$movie->id}/toggle-favorite");

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'is_favorite' => true,
    ]);

    expect($user->fresh()->isFavorite($movie))->toBeTrue();

    // 2. Access favorites page and see the movie title
    $favResponse = $this->actingAs($user)->get('/user/favorites');
    $favResponse->assertStatus(200);
    $favResponse->assertSee('Cyberpunk Shadows');
    $favResponse->assertSee('Koleksi Film', false);

    // 3. Toggle again (Remove from favorite)
    $response2 = $this->actingAs($user)
        ->postJson("/movies/{$movie->id}/toggle-favorite");

    $response2->assertStatus(200);
    $response2->assertJson([
        'status' => 'success',
        'is_favorite' => false,
    ]);

    expect($user->fresh()->isFavorite($movie))->toBeFalse();
});

test('favorites index page displays empty state when user has no favorited movies', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/user/favorites');

    $response->assertStatus(200);
    $response->assertSee('Belum Ada Tayangan Disukai');
});
