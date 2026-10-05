<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('creator channel profile information can be updated', function () {
    $creator = User::factory()->creator()->create();

    $response = $this
        ->actingAs($creator)
        ->patch('/profile', [
            'name' => 'Aerell Gaming Studio',
            'email' => $creator->email,
            'handle' => '@aerellgaming',
            'bio' => 'Channel Gaming & Cinema Aerell Gaming',
            'tagline' => 'Gaming 4K UHD & Cinematic',
            'avatar_url' => 'images/aerell_avatar.png',
            'banner_url' => 'images/aerell_banner.jpg',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $creator->refresh();

    $this->assertSame('Aerell Gaming Studio', $creator->name);
    $this->assertSame('@aerellgaming', $creator->handle);
    $this->assertSame('Channel Gaming & Cinema Aerell Gaming', $creator->bio);
    $this->assertSame('Gaming 4K UHD & Cinematic', $creator->tagline);
    $this->assertSame('images/aerell_avatar.png', $creator->avatar_url);
    $this->assertSame('images/aerell_banner.jpg', $creator->banner_url);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
