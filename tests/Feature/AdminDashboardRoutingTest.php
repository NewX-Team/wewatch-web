<?php

use App\Models\User;

test('super admin accessing generic dashboard route is redirected to admin dashboard', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->get('/dashboard');

    $response->assertRedirect('/admin/dashboard');
});

test('super admin profile page links back to admin dashboard', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->get('/profile');

    $response->assertStatus(200);
    $response->assertSee(route('admin.dashboard'));
    $response->assertSee('Kembali ke Admin Console');
});

test('super admin super admin dashboard profile dropdown links to admin dashboard', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->get('/admin/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Control Console Admin');
    $response->assertDontSee('Lihat Mode Penonton');
});
