<?php

use App\Models\Episode;
use App\Models\Movie;
use App\Models\User;

test('episode can be created with access_tier without requiring duration', function () {
    $creator = User::factory()->creator()->create();

    $response = $this->actingAs($creator)->post('/creator/movies', [
        'title' => 'Galactic War',
        'description' => 'Sinopsis film perang antar galaksi.',
        'genre' => 'Sci-Fi',
        'status' => 'ongoing',
        'access_tier' => 'pro',
        'initial_episode_title' => 'Ep 1: Serangan Pertama',
        'initial_episode_access_tier' => 'free',
    ]);

    $response->assertRedirect();
    $movie = Movie::where('title', 'Galactic War')->first();
    expect($movie)->not->toBeNull();
    expect($movie->episodes->first()->access_tier)->toBe('free');

    // Add Episode 2 with VIP access tier without providing duration
    $response2 = $this->actingAs($creator)->post("/creator/movies/{$movie->id}/episodes", [
        'title' => 'Ep 2: Boss Battle',
        'access_tier' => 'vip',
        'video_url' => 'https://www.youtube.com/embed/test',
    ]);

    $response2->assertRedirect();
    $ep2 = $movie->episodes()->where('episode_number', 2)->first();
    expect($ep2)->not->toBeNull();
    expect($ep2->access_tier)->toBe('vip');
});

test('episode access rights according to user subscription tier', function () {
    $epFree = new Episode(['access_tier' => 'free']);
    $epPro = new Episode(['access_tier' => 'pro']);
    $epVip = new Episode(['access_tier' => 'vip']);

    $userFree = User::factory()->user()->create(['subscription_tier' => 'free']);
    $userPro = User::factory()->user()->create(['subscription_tier' => 'pro']);
    $userVip = User::factory()->user()->create(['subscription_tier' => 'vip']);
    $creator = User::factory()->creator()->create();
    $superAdmin = User::factory()->superAdmin()->create();

    // Free user tests
    expect($epFree->canBeAccessedBy($userFree))->toBeTrue();
    expect($epPro->canBeAccessedBy($userFree))->toBeFalse();
    expect($epVip->canBeAccessedBy($userFree))->toBeFalse();

    // Pro user tests
    expect($epFree->canBeAccessedBy($userPro))->toBeTrue();
    expect($epPro->canBeAccessedBy($userPro))->toBeTrue();
    expect($epVip->canBeAccessedBy($userPro))->toBeFalse();

    // VIP user tests
    expect($epFree->canBeAccessedBy($userVip))->toBeTrue();
    expect($epPro->canBeAccessedBy($userVip))->toBeTrue();
    expect($epVip->canBeAccessedBy($userVip))->toBeTrue();

    // Creator & SuperAdmin tests (Full access)
    expect($epVip->canBeAccessedBy($creator))->toBeTrue();
    expect($epVip->canBeAccessedBy($superAdmin))->toBeTrue();
});

test('user can upgrade subscription tier via subscription upgrade action', function () {
    $user = User::factory()->user()->create(['subscription_tier' => 'free']);

    $response = $this->actingAs($user)->post('/subscription/upgrade', [
        'tier' => 'pro',
    ]);

    $response->assertRedirect();
    expect($user->fresh()->subscription_tier)->toBe('pro');

    $response2 = $this->actingAs($user)->post('/subscription/upgrade', [
        'tier' => 'vip',
    ]);

    $response2->assertRedirect();
    expect($user->fresh()->subscription_tier)->toBe('vip');
});
