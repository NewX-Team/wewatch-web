<?php

use App\Models\Message;
use App\Models\User;

test('unauthenticated user is redirected when accessing messages hub', function () {
    $response = $this->get('/messages');
    $response->assertRedirect('/login');
});

test('all users including free user can message admin support team during open tier preview', function () {
    $freeUser = User::factory()->user()->create(['subscription_tier' => 'free']);

    $response = $this->actingAs($freeUser)
        ->postJson('/messages/send', [
            'message' => 'Halo admin, saya butuh bantuan.',
            'is_admin_chat' => true,
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);
});

test('vip user can message admin support team', function () {
    $vipUser = User::factory()->user()->create(['subscription_tier' => 'vip']);

    $response = $this->actingAs($vipUser)
        ->postJson('/messages/send', [
            'message' => 'Halo admin, saya pengguna VIP butuh bantuan.',
            'is_admin_chat' => true,
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);

    expect(Message::where('sender_id', $vipUser->id)->where('is_admin_chat', true)->exists())->toBeTrue();
});

test('all users can message creator during open tier preview', function () {
    $creator = User::factory()->creator()->create(['dm_access_tier' => 'pro']);
    $freeUser = User::factory()->user()->create(['subscription_tier' => 'free']);
    $proUser = User::factory()->user()->create(['subscription_tier' => 'pro']);
    $vipUser = User::factory()->user()->create(['subscription_tier' => 'vip']);

    // 1. Free user tries to DM creator -> success (200) during open tier preview
    $response1 = $this->actingAs($freeUser)
        ->postJson('/messages/send', [
            'receiver_id' => $creator->id,
            'message' => 'Halo kreator!',
        ]);

    $response1->assertStatus(200);

    // 2. Pro user tries to DM creator -> success (200)
    $response2 = $this->actingAs($proUser)
        ->postJson('/messages/send', [
            'receiver_id' => $creator->id,
            'message' => 'Halo kreator!',
        ]);

    $response2->assertStatus(200);

    // 3. VIP user tries to DM creator -> success (200)
    $response3 = $this->actingAs($vipUser)
        ->postJson('/messages/send', [
            'receiver_id' => $creator->id,
            'message' => 'Halo kreator dari VIP!',
        ]);

    $response3->assertStatus(200);
});

test('creator can update dm_access_tier in studio settings', function () {
    $creator = User::factory()->creator()->create(['dm_access_tier' => 'pro']);

    $response = $this->actingAs($creator)
        ->post('/creator/settings/dm-tier', [
            'dm_access_tier' => 'vip',
        ]);

    $response->assertRedirect();
    expect($creator->fresh()->dm_access_tier)->toBe('vip');
});
