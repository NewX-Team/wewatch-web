<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pengaturan Akun & Preferensi — WeWatch</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden">

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[850px] h-[400px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-3xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-5xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Left: Brand Logo & Back -->
            <div class="flex items-center gap-4">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>

            <!-- Right: Tier Upgrade Badge & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">FREE</span>
                    <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                </a>

                <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
        </nav>

        <!-- Main Account Settings Content -->
        <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8"
              x-data="{
                  activeTab: 'profile',
                  quality: '1080p',
                  subtitle: 'id',
                  audio: 'dolby',
                  autoplay: true,
                  ageRating: '13',
                  emailNotify: true,
                  premiereNotify: true,
                  savedPreferences: false,
                  savePreferences() {
                      this.savedPreferences = true;
                      setTimeout(() => this.savedPreferences = false, 2500);
                  }
              }">

            <!-- Header Section -->
            <div class="space-y-2 border-b border-zinc-800/80 pb-6">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[11px] uppercase tracking-wider">
                        Account Settings
                    </span>
                    <span class="text-xs font-mono text-zinc-400 font-bold px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800">
                        WeWatch Cinema Engine
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Pengaturan Akun & Preferensi Cinema
                </h1>
                <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                    Kelola profil personal, kualitas streaming default, opsi subtitle & audio, status membership, serta keamanan akun kamu dalam satu tempat.
                </p>
            </div>

            <!-- Tabbed Settings Layout (Left Sidebar Tabs + Right Content Panel) -->
            <div class="flex flex-col md:flex-row gap-8 items-start">

                <!-- Left Settings Sidebar Menu -->
                <div class="w-full md:w-64 bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-2.5 space-y-1 shrink-0 shadow-lg backdrop-blur-xl">
                    <button @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'bg-red-600/15 text-red-400 border-red-600/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                            class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        <span>Profil & Persona</span>
                    </button>

                    <button @click="activeTab = 'cinema'"
                            :class="activeTab === 'cinema' ? 'bg-red-600/15 text-red-400 border-red-600/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                            class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H9l2 4H8L6 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                        <span>Pemutar & Streaming</span>
                    </button>

                    <button @click="activeTab = 'subscription'"
                            :class="activeTab === 'subscription' ? 'bg-red-600/15 text-red-400 border-red-600/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                            class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                            <span>Membership & Billing</span>
                        </div>
                        <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[9px] font-mono font-bold">FREE</span>
                    </button>

                    <button @click="activeTab = 'notifications'"
                            :class="activeTab === 'notifications' ? 'bg-red-600/15 text-red-400 border-red-600/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                            class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                        <span>Notifikasi Rilis</span>
                    </button>

                    <button @click="activeTab = 'security'"
                            :class="activeTab === 'security' ? 'bg-red-600/15 text-red-400 border-red-600/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                            class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        <span>Keamanan & Sandi</span>
                    </button>
                </div>

                <!-- Right Main Settings Panels -->
                <div class="flex-1 w-full bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl backdrop-blur-xl">

                    <!-- TAB 1: PROFIL & PERSONA -->
                    <div x-show="activeTab === 'profile'" class="space-y-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- TAB 2: PEMUTAR & STREAMING PREFERENCES -->
                    <div x-show="activeTab === 'cinema'" class="space-y-6" style="display: none;">
                        <header class="space-y-1">
                            <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H9l2 4H8L6 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                                <span>Preferensi Pemutar Video & Audio Sinema</span>
                            </h2>
                            <p class="text-xs text-zinc-400">
                                Sesuaikan kualitas resolusi streaming bawaan, subtitle otomatis, serta mode audio sesuai perangkat kamu.
                            </p>
                        </header>

                        <div class="space-y-5 pt-2">
                            <!-- Resolution Quality -->
                            <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-3">
                                <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Kualitas Resolusi Streaming Default</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <button @click="quality = '4k'" :class="quality === '4k' ? 'border-red-600 bg-red-600/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span>4K Ultra HD</span>
                                            <span class="px-1.5 py-0.2 rounded bg-red-600 text-white font-mono text-[8px]">UHD</span>
                                        </div>
                                        <span class="text-[10px] text-zinc-400 font-normal block">Kualitas visual tertinggi tanpa kompromi</span>
                                    </button>

                                    <button @click="quality = '1080p'" :class="quality === '1080p' ? 'border-red-600 bg-red-600/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span>Full HD 1080p</span>
                                            <span class="px-1.5 py-0.2 rounded bg-emerald-600 text-white font-mono text-[8px]">Rekomendasi</span>
                                        </div>
                                        <span class="text-[10px] text-zinc-400 font-normal block">Seimbang antara kejernihan & penggunaan data</span>
                                    </button>

                                    <button @click="quality = '720p'" :class="quality === '720p' ? 'border-red-600 bg-red-600/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span>Hemat Data 720p</span>
                                            <span class="px-1.5 py-0.2 rounded bg-zinc-700 text-zinc-300 font-mono text-[8px]">SD/HD</span>
                                        </div>
                                        <span class="text-[10px] text-zinc-400 font-normal block">Cocok untuk koneksi internet terbatas</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtitle & Audio Options -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Bahasa Subtitle Otomatis</label>
                                    <select x-model="subtitle" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-red-600 transition">
                                        <option value="id">Bahasa Indonesia (Utama)</option>
                                        <option value="en">English (Subtitle)</option>
                                        <option value="off">Matikan Subtitle Secara Default</option>
                                    </select>
                                </div>

                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Mode Output Audio Engine</label>
                                    <select x-model="audio" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-red-600 transition">
                                        <option value="dolby">Dolby Atmos Surround 7.1</option>
                                        <option value="stereo">Stereo 2.0 High Dynamic Range</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Autoplay Switch & Content Age Filter -->
                            <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-white text-xs">Putar Episode Selanjutnya Secara Otomatis (Autoplay)</h4>
                                    <p class="text-[10px] text-zinc-400">Putar episode berikutnya setelah credit scene film selesai</p>
                                </div>
                                <button @click="autoplay = !autoplay" :class="autoplay ? 'bg-red-600' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                    <div :class="autoplay ? 'translate-x-6 bg-white' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                </button>
                            </div>

                            <div class="pt-2 flex items-center gap-4">
                                <button @click="savePreferences()" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/20 transition active:scale-95 flex items-center gap-2">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                    <span>Simpan Preferensi Cinema</span>
                                </button>

                                <span x-show="savedPreferences" x-transition class="text-xs font-bold text-emerald-400 flex items-center gap-1" style="display: none;">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    Preferensi tersimpan!
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: MEMBERSHIP & BILLING STATUS -->
                    <div x-show="activeTab === 'subscription'" class="space-y-6" style="display: none;">
                        <header class="space-y-1">
                            <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                                <span>Status Membership & Langganan Aktif</span>
                            </h2>
                            <p class="text-xs text-zinc-400">
                                Ringkasan statistik akun membership dan tombol upgrade ke paket Pro / VIP Mewah.
                            </p>
                        </header>

                        <!-- Active Membership Card -->
                        <div class="p-6 rounded-2xl bg-zinc-950/90 border border-zinc-800 space-y-4 shadow-inner">
                            <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-xs">
                                        FREE
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-white text-sm">Paket WeWatch Free</h3>
                                        <span class="text-[10px] text-zinc-400 font-mono">Status: Aktif Selamanya</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded bg-zinc-800 text-zinc-300 font-mono font-bold text-xs">Rp 0 / bulan</span>
                            </div>

                            <div class="space-y-2 text-xs text-zinc-400">
                                <span class="font-bold text-zinc-300 uppercase tracking-wider text-[10px] block">Batasan Paket Free:</span>
                                <ul class="space-y-1.5 list-disc list-inside">
                                    <li>Koleksi katalog film & serial terbatas</li>
                                    <li>Maksimal 1 komentar per video</li>
                                    <li>Fitur Subscribe ke Kreator dinonaktifkan</li>
                                </ul>
                            </div>

                            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('subscription.index') }}" class="py-3 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>Upgrade ke Pro (Rp 50.000) atau VIP Mewah</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: NOTIFIKASI RILIS FILM -->
                    <div x-show="activeTab === 'notifications'" class="space-y-6" style="display: none;">
                        <header class="space-y-1">
                            <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                <span>Pemberitahuan Rilis & Live Premiere</span>
                            </h2>
                            <p class="text-xs text-zinc-400">
                                Atur jenis notifikasi rilis film baru yang ingin kamu terima melalui email & browser.
                            </p>
                        </header>

                        <div class="space-y-4 pt-2">
                            <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-white text-xs">Email Rilis Film & Episode Baru</h4>
                                    <p class="text-[10px] text-zinc-400">Dapatkan email otomatis saat episode baru rilis di platform</p>
                                </div>
                                <button @click="emailNotify = !emailNotify" :class="emailNotify ? 'bg-red-600' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                    <div :class="emailNotify ? 'translate-x-6 bg-white' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                </button>
                            </div>

                            <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-white text-xs">Notifikasi Live Premiere Kreator</h4>
                                    <p class="text-[10px] text-zinc-400">Notifikasi saat kreator favorit mengunggah karya premiere</p>
                                </div>
                                <button @click="premiereNotify = !premiereNotify" :class="premiereNotify ? 'bg-red-600' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                    <div :class="premiereNotify ? 'translate-x-6 bg-white' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: KEAMANAN & PASSWORD & DELETE -->
                    <div x-show="activeTab === 'security'" class="space-y-8" style="display: none;">
                        @include('profile.partials.update-password-form')

                        <div class="border-t border-zinc-800/80 pt-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </body>
</html>
