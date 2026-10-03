<?php

use App\Enums\UserRole;
use App\Models\User;

test('super admin can create a new user account with role', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $response = $this->actingAs($superAdmin)->post('/admin/users', [
        'name' => 'New Creator Account',
        'email' => 'newcreator@example.com',
        'role' => 'creator',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertDatabaseHas('users', [
        'name' => 'New Creator Account',
        'email' => 'newcreator@example.com',
        'role' => UserRole::Creator->value,
    ]);
});

test('super admin can toggle suspend status for a creator or user account', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $targetUser = User::factory()->user()->create(['is_suspended' => false]);

    // Suspend user
    $this->actingAs($superAdmin)
        ->patch("/admin/users/{$targetUser->id}/toggle-suspend")
        ->assertRedirect('/admin/dashboard');

    expect($targetUser->fresh()->is_suspended)->toBeTrue();

    // Unsuspend user
    $this->actingAs($superAdmin)
        ->patch("/admin/users/{$targetUser->id}/toggle-suspend")
        ->assertRedirect('/admin/dashboard');

    expect($targetUser->fresh()->is_suspended)->toBeFalse();
});

test('suspended user is redirected to login with popup error message', function () {
    $suspendedUser = User::factory()->user()->create(['is_suspended' => true, 'name' => 'Suspended Member']);

    $this->actingAs($suspendedUser)
        ->get('/user/dashboard')
        ->assertRedirect('/login')
        ->assertSessionHas('error');
});

test('suspended user login attempt fails and redirects back to login with popup error', function () {
    $suspendedUser = User::factory()->user()->create([
        'email' => 'blocked@example.com',
        'password' => bcrypt('password123'),
        'is_suspended' => true,
    ]);

    $this->post('/login', [
        'email' => 'blocked@example.com',
        'password' => 'password123',
    ])
        ->assertRedirect('/login')
        ->assertSessionHas('error');

    $this->assertGuest();
});

test('super admin can delete accounts below super admin role', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $targetCreator = User::factory()->creator()->create();

    $this->actingAs($superAdmin)
        ->delete("/admin/users/{$targetCreator->id}")
        ->assertRedirect('/admin/dashboard');

    $this->assertDatabaseMissing('users', [
        'id' => $targetCreator->id,
    ]);
});

test('root super admin can delete sub admin account', function () {
    $rootAdmin = User::factory()->superAdmin()->create(['is_root_admin' => true]);
    $subAdmin = User::factory()->superAdmin()->create(['is_root_admin' => false]);

    $this->actingAs($rootAdmin)
        ->delete("/admin/users/{$subAdmin->id}")
        ->assertRedirect('/admin/dashboard')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('users', [
        'id' => $subAdmin->id,
    ]);
});

test('regular sub admin cannot delete another admin account', function () {
    $subAdmin1 = User::factory()->superAdmin()->create(['is_root_admin' => false]);
    $subAdmin2 = User::factory()->superAdmin()->create(['is_root_admin' => false]);

    $this->actingAs($subAdmin1)
        ->delete("/admin/users/{$subAdmin2->id}")
        ->assertRedirect('/admin/dashboard')
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', [
        'id' => $subAdmin2->id,
    ]);
});

test('nobody can delete the root super admin account', function () {
    $rootAdmin = User::factory()->superAdmin()->create(['is_root_admin' => true]);
    $subAdmin = User::factory()->superAdmin()->create(['is_root_admin' => false]);

    $this->actingAs($subAdmin)
        ->delete("/admin/users/{$rootAdmin->id}")
        ->assertRedirect('/admin/dashboard')
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', [
        'id' => $rootAdmin->id,
    ]);
});
