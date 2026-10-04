<?php

use App\Enums\UserRole;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register as standard user', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'user',
    ]);

    $this->assertAuthenticated();
    $user = auth()->user();
    expect($user->role)->toBe(UserRole::User);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users can register as creator studio', function () {
    $response = $this->post('/register', [
        'name' => 'Studio Kreator Indera',
        'email' => 'kreator@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'creator',
    ]);

    $this->assertAuthenticated();
    $user = auth()->user();
    expect($user->role)->toBe(UserRole::Creator);

    // Following redirect from /dashboard to /creator/dashboard
    $this->get(route('dashboard'))
        ->assertRedirect(route('creator.dashboard'));
});
