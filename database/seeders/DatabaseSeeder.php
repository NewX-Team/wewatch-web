<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin Mutlak Utama Demo Account
        User::updateOrCreate(
            ['email' => 'admin@wewatch.test'],
            [
                'name' => 'Alexandre Vance (Super Admin Mutlak)',
                'password' => Hash::make('password'),
                'role' => UserRole::SuperAdmin,
                'is_root_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Creator Studio Demo Account
        User::updateOrCreate(
            ['email' => 'creator@wewatch.test'],
            [
                'name' => 'Elena Rostova (Lead Creator)',
                'password' => Hash::make('password'),
                'role' => UserRole::Creator,
                'is_root_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // 3. Standard User Demo Account
        User::updateOrCreate(
            ['email' => 'user@wewatch.test'],
            [
                'name' => 'Marcus Chen (Pro Subscriber)',
                'password' => Hash::make('password'),
                'role' => UserRole::User,
                'is_root_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
