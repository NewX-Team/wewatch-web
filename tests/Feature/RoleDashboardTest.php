<?php

use App\Models\User;

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('super admin user is redirected to super admin dashboard and can access it', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->get('/dashboard');
    $response->assertRedirect('/admin/dashboard');

    $adminResponse = $this->actingAs($superAdmin)->get('/admin/dashboard');
    $adminResponse->assertStatus(200);
    $adminResponse->assertSee('System Control');
});

test('creator user is redirected to creator dashboard and can access it', function () {
    $creator = User::factory()->creator()->create();

    $response = $this->actingAs($creator)->get('/dashboard');
    $response->assertRedirect('/creator/dashboard');

    $creatorResponse = $this->actingAs($creator)->get('/creator/dashboard');
    $creatorResponse->assertStatus(200);
    $creatorResponse->assertSee('CREATOR STUDIO HUB');
});

test('standard user is redirected to user dashboard and can access it', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertRedirect('/user/dashboard');

    $userResponse = $this->actingAs($user)->get('/user/dashboard');
    $userResponse->assertStatus(200);
    $userResponse->assertSee('Subscribed');
    $userResponse->assertSee('WEWATCH');
});

test('standard user cannot access super admin or creator dashboards', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)->get('/admin/dashboard')->assertStatus(403);
    $this->actingAs($user)->get('/creator/dashboard')->assertStatus(403);
});

test('creator user cannot access super admin dashboard', function () {
    $creator = User::factory()->creator()->create();

    $this->actingAs($creator)->get('/admin/dashboard')->assertStatus(403);
});
