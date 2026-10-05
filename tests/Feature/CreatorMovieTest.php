<?php

use App\Models\Movie;
use App\Models\User;

test('unauthenticated user cannot store creator movie', function () {
    $response = $this->post('/creator/movies', [
        'title' => 'Test Film',
        'description' => 'Test sinopsis',
        'genre' => 'Sci-Fi',
        'status' => 'ongoing',
        'access_tier' => 'free',
    ]);

    $response->assertRedirect('/login');
});

test('creator user can store new published movie and initial episode', function () {
    $creator = User::factory()->creator()->create();

    $response = $this->actingAs($creator)->post('/creator/movies', [
        'title' => 'Cyberpunk Dystopia 2099',
        'description' => 'Sinopsis petualangan sinematik cyberpunk di Tokyo.',
        'genre' => 'Sci-Fi Series',
        'status' => 'ongoing',
        'access_tier' => 'free',
        'poster_url' => 'https://example.com/poster.jpg',
        'initial_episode_title' => 'Ep 1: Pembukaan',
        'video_url' => 'https://www.youtube.com/embed/test',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('movies', [
        'user_id' => $creator->id,
        'title' => 'Cyberpunk Dystopia 2099',
        'status' => 'ongoing',
        'access_tier' => 'free',
    ]);

    $movie = Movie::where('title', 'Cyberpunk Dystopia 2099')->first();
    expect($movie)->not->toBeNull();
    expect($movie->episodes->count())->toBe(1);
    expect($movie->episodes->first()->title)->toBe('Ep 1: Pembukaan');
});

test('creator user can add next week episode to existing movie', function () {
    $creator = User::factory()->creator()->create();

    $movie = $creator->movies()->create([
        'title' => 'Sci-Fi Chronicle',
        'slug' => 'sci-fi-chronicle',
        'description' => 'Sinopsis petualangan luar angkasa.',
        'genre' => 'Sci-Fi Series',
        'status' => 'ongoing',
        'access_tier' => 'pro',
    ]);

    $movie->episodes()->create([
        'episode_number' => 1,
        'title' => 'Ep 1: Misi Pertama',
        'duration' => '45m',
    ]);

    $response = $this->actingAs($creator)->post("/creator/movies/{$movie->id}/episodes", [
        'title' => 'Ep 2: Pertempuran Dimulai (Minggu Ke-2)',
        'duration' => '50m',
        'video_url' => 'https://www.youtube.com/embed/test2',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect($movie->fresh()->episodes->count())->toBe(2);
    $ep2 = $movie->episodes()->where('episode_number', 2)->first();
    expect($ep2->title)->toBe('Ep 2: Pertempuran Dimulai (Minggu Ke-2)');
});

test('creator user can toggle movie status between ongoing and completed', function () {
    $creator = User::factory()->creator()->create();

    $movie = $creator->movies()->create([
        'title' => 'Action Movie',
        'slug' => 'action-movie',
        'description' => 'Sinopsis film aksi.',
        'genre' => 'Action',
        'status' => 'ongoing',
    ]);

    $response = $this->actingAs($creator)->patch("/creator/movies/{$movie->id}/toggle-status");
    $response->assertRedirect();

    expect($movie->fresh()->status)->toBe('completed');
});

test('user dashboard displays empty state when no creator movies exist and list when published', function () {
    $user = User::factory()->user()->create();

    // When empty
    $response = $this->actingAs($user)->get('/user/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Belum Ada Film yang Diterbitkan');

    // Create published movie
    $creator = User::factory()->creator()->create();
    $creator->movies()->create([
        'title' => 'Judul Film Kreator Perdana',
        'slug' => 'judul-film-kreator-perdana',
        'description' => 'Sinopsis film perdana.',
        'genre' => 'Fantasy',
        'status' => 'ongoing',
    ]);

    $response2 = $this->actingAs($user)->get('/user/dashboard');
    $response2->assertStatus(200);
    $response2->assertSee('Judul Film Kreator Perdana');
});
