<?php

use App\Models\User;

test('unauthenticated user cannot access favorites page', function () {
    $response = $this->get('/user/favorites');
    $response->assertRedirect('/login');
});

test('authenticated user can access favorites page and sees empty state when empty', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/user/favorites');
    $response->assertStatus(200);
    $response->assertSee('Koleksi Film Favorit');
    $response->assertSee('Belum Ada Film Favorit');
    $response->assertSee('Jelajahi Beranda Utama');
    $response->assertSee('Rekomendasi Untuk Favoritmu');
});
