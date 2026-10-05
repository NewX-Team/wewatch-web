<?php

use App\Models\User;

test('super admin can toggle creator verification status', function () {
    $admin = User::factory()->superAdmin()->create();
    $creator = User::factory()->creator()->create([
        'is_verified' => false,
    ]);

    $response = $this->actingAs($admin)->patch('/admin/users/'.$creator->id.'/toggle-verification');

    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('success');
    expect($creator->fresh()->is_verified)->toBeTrue();

    // Toggle back
    $response2 = $this->actingAs($admin)->patch('/admin/users/'.$creator->id.'/toggle-verification');
    $response2->assertRedirect(route('admin.dashboard'));
    expect($creator->fresh()->is_verified)->toBeFalse();
});

test('regular user cannot toggle creator verification', function () {
    $user = User::factory()->user()->create();
    $creator = User::factory()->creator()->create([
        'is_verified' => false,
    ]);

    $response = $this->actingAs($user)->patch('/admin/users/'.$creator->id.'/toggle-verification');

    $response->assertStatus(403);
    expect($creator->fresh()->is_verified)->toBeFalse();
});

test('unverified creator sees locked revenue in studio hub', function () {
    $creator = User::factory()->creator()->create([
        'is_verified' => false,
    ]);

    $response = $this->actingAs($creator)->get('/creator/dashboard');

    $response->assertStatus(200);
    $response->assertSee('CREATOR STUDIO HUB (BASIC)');
    $response->assertSee('Fitur Pendapatan');
    $response->assertSee('TERKUNCI 🔒');
});

test('verified creator sees unlocked revenue and verified badge in studio hub', function () {
    $creator = User::factory()->creator()->create([
        'is_verified' => true,
    ]);

    $response = $this->actingAs($creator)->get('/creator/dashboard');

    $response->assertStatus(200);
    $response->assertSee('VERIFIED CREATOR STUDIO');
    $response->assertSee('Estimasi Pendapatan');
    $response->assertSee('Rp 0');
    $response->assertSee('Monetisasi Terverifikasi');
});

test('public channel displays blue verified badge when creator is verified', function () {
    $creator = User::factory()->creator()->create([
        'name' => 'Neotokyo Creator',
        'is_verified' => true,
    ]);

    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/creators/'.$creator->id);

    $response->assertStatus(200);
    $response->assertSee('Neotokyo Creator');
    $response->assertSee('Akun Kreator Terverifikasi (Official Verified Channel)');
});

test('unverified creator sees locked payout tab in profile settings while verified creator sees unlocked payout tab', function () {
    $unverifiedCreator = User::factory()->creator()->create(['is_verified' => false]);
    $verifiedCreator = User::factory()->creator()->create(['is_verified' => true]);

    $responseUnverified = $this->actingAs($unverifiedCreator)->get('/profile');
    $responseUnverified->assertStatus(200);
    $responseUnverified->assertSee('Fitur Monetisasi');
    $responseUnverified->assertSee('Penarikan Saldo Terkunci');

    $responseVerified = $this->actingAs($verifiedCreator)->get('/profile');
    $responseVerified->assertStatus(200);
    $responseVerified->assertSee('Program Monetisasi Resmi');
    $responseVerified->assertSee('Simpan Rekening Payout');
});
