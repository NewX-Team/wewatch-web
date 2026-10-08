<?php

use App\Models\Announcement;
use App\Models\User;

test('unauthenticated user is redirected when accessing user announcements page', function () {
    $response = $this->get('/user/announcements');
    $response->assertRedirect('/login');
});

test('authenticated user can view active admin announcements on user announcements page', function () {
    $user = User::factory()->user()->create();

    $announcement = Announcement::create([
        'title' => 'Update Fitur Streaming WeWatch 2026',
        'content' => 'Selamat menikmati tayangan kualitas 4K Ultra HD dan Dolby Atmos di platform WeWatch.',
        'type' => 'info',
        'target_role' => 'all',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/user/announcements');

    $response->assertStatus(200);
    $response->assertSee('Pusat Pemberitahuan & Pengumuman Admin', false);
    $response->assertSee('Update Fitur Streaming WeWatch 2026');
    $response->assertSee('Selamat menikmati tayangan kualitas 4K Ultra HD');
});

test('inactive announcements are not displayed to users', function () {
    $user = User::factory()->user()->create();

    Announcement::create([
        'title' => 'Pengumuman Rahasia Yang Dinonaktifkan',
        'content' => 'Konten pengumuman lama yang telah dinonaktifkan.',
        'type' => 'warning',
        'target_role' => 'all',
        'is_active' => false,
    ]);

    $response = $this->actingAs($user)->get('/user/announcements');

    $response->assertStatus(200);
    $response->assertDontSee('Pengumuman Rahasia Yang Dinonaktifkan');
});
