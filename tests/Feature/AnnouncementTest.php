<?php

use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\User;

test('super admin can view announcements and create new announcement', function () {
    $admin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.announcements.store'), [
        'title' => 'Diskon VIP Cinema 50%',
        'content' => 'Gunakan kode WEWATCH50 untuk diskon langganan VIP.',
        'type' => 'promo',
        'target_role' => 'all',
        'is_active' => '1',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('announcements', [
        'title' => 'Diskon VIP Cinema 50%',
        'type' => 'promo',
        'is_active' => true,
    ]);
});

test('super admin can toggle announcement status', function () {
    $admin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $announcement = Announcement::create([
        'title' => 'Maintenance System',
        'content' => 'Server maintenance scheduled.',
        'type' => 'info',
        'target_role' => 'all',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.announcements.toggle', $announcement->id));

    $response->assertRedirect();
    expect($announcement->fresh()->is_active)->toBeFalse();
});

test('super admin can delete announcement', function () {
    $admin = User::factory()->create([
        'role' => UserRole::SuperAdmin,
    ]);

    $announcement = Announcement::create([
        'title' => 'Test Promo Delete',
        'content' => 'Content to be deleted.',
        'type' => 'promo',
        'target_role' => 'all',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.announcements.destroy', $announcement->id));

    $response->assertRedirect();
    $this->assertDatabaseMissing('announcements', [
        'id' => $announcement->id,
    ]);
});

test('standard users receive active announcements on dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    Announcement::create([
        'title' => 'Pengumuman Penting Penonton',
        'content' => 'Konten pengumuman penonton.',
        'type' => 'info',
        'target_role' => 'all',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get(route('user.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('announcements');
});
