<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Announcement;
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

        // 4. Default Broadcast Announcements
        Announcement::updateOrCreate(
            ['title' => '🍿 Promo Spesial: Diskon 50% Langganan VIP Cinema 4K!'],
            [
                'content' => 'Nikmati seluruh tayangan film sinematik 4K UHD & audio Dolby Atmos tanpa gangguan iklan dengan harga hemat 50% khusus bulan ini. Gunakan kode voucher WEWATCH50 saat pembayaran!',
                'type' => 'promo',
                'target_role' => 'all',
                'is_active' => true,
            ]
        );

        Announcement::updateOrCreate(
            ['title' => '📢 Pembaruan Sistem & Pemeliharaan Server WeWatch v2.4'],
            [
                'content' => 'Halo Penonton & Kreator! Kami telah menyelesaikan pembaruan infrastruktur jaringan streaming. Performa pemutaran video kini 2x lebih cepat dengan dukungan Spatial Cinema Sound.',
                'type' => 'info',
                'target_role' => 'all',
                'is_active' => true,
            ]
        );

        Announcement::updateOrCreate(
            ['title' => '🎬 Kompetisi Film Pendek Sinematik WeWatch 2026'],
            [
                'content' => 'Bagi para kreator film mandiri dan studio independen, daftarkan karya sinema terbaru Anda! Menangkan hibah dana produksi total Rp 50.000.000 dan kesempatan tayang eksklusif di WeWatch Originals.',
                'type' => 'event',
                'target_role' => 'all',
                'is_active' => true,
            ]
        );
    }
}
