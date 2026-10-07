<?php

use App\Models\User;

test('user can subscribe and unsubscribe to a creator in real-time', function () {
    $user = User::factory()->user()->create();
    $creator = User::factory()->creator()->create(['name' => 'Aerell Gaming']);

    expect($creator->subscribersCount())->toBe(0);
    expect($user->isSubscribedTo($creator))->toBeFalse();

    // 1. Subscribe
    $response = $this->actingAs($user)->post("/creators/{$creator->id}/toggle-subscription");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect($creator->fresh()->subscribersCount())->toBe(1);
    expect($user->fresh()->isSubscribedTo($creator))->toBeTrue();
    expect($user->subscribedCreators->contains($creator->id))->toBeTrue();

    // 2. Unsubscribe
    $response2 = $this->actingAs($user)->post("/creators/{$creator->id}/toggle-subscription");

    $response2->assertRedirect();
    expect($creator->fresh()->subscribersCount())->toBe(0);
    expect($user->fresh()->isSubscribedTo($creator))->toBeFalse();
});

test('user cannot subscribe to their own channel', function () {
    $creator = User::factory()->creator()->create();

    $response = $this->actingAs($creator)->post("/creators/{$creator->id}/toggle-subscription");

    $response->assertSessionHas('status', 'Anda tidak dapat mensubscribe channel milik Anda sendiri.');
    expect($creator->subscribersCount())->toBe(0);
});

test('dedicated user subscriptions page displays subscribed creators list', function () {
    $user = User::factory()->user()->create();
    $creator1 = User::factory()->creator()->create(['name' => 'Neotokyo Studio']);
    $creator2 = User::factory()->creator()->create(['name' => 'Aerell Gaming']);

    $user->subscribedCreators()->attach($creator1->id);

    $response = $this->actingAs($user)->get('/user/subscriptions');

    $response->assertStatus(200);
    $response->assertSee('Neotokyo Studio');
    $response->assertSee('Koleksi Channel Kreator Favorit Kamu');
});

test('creator channel view displays real-time subscriber count', function () {
    $creator = User::factory()->creator()->create(['name' => 'Aerell Gaming']);
    $user1 = User::factory()->user()->create();
    $user2 = User::factory()->user()->create();

    $user1->subscribedCreators()->attach($creator->id);
    $user2->subscribedCreators()->attach($creator->id);

    $response = $this->actingAs($user1)->get("/creators/{$creator->id}");

    $response->assertStatus(200);
    $response->assertSee('2 Subscribers');
});
