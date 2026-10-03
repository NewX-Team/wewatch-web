<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $user->isCreator() ? 'Pengaturan Studio & Branding Creator' : 'Pengaturan Akun & Preferensi' }} — WeWatch</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden">
        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Light Spotlights -->
        @if($user->isCreator())
            <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[850px] h-[400px] bg-amber-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        @else
            <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[850px] h-[400px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        @endif

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-3xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-5xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Left: Brand Logo & Back -->
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>{{ $user->isCreator() ? 'Kembali ke Studio Hub' : 'Kembali ke Dashboard' }}</span>
                </a>
            </div>

            <!-- Right: Tier / Role Badge & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                @if($user->isCreator())
                    <a href="{{ route('creators.show', 'neotokyo-studios') }}" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500/20 transition group">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span class="text-[11px] font-black tracking-wider uppercase text-amber-300">CREATOR HUB</span>
                        <span class="text-[9px] font-bold text-amber-200 bg-amber-600/30 border border-amber-500/40 px-1.5 py-0.5 rounded">LIHAT CHANNEL</span>
                    </a>
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-amber-600 to-amber-400 text-zinc-950 font-black text-xs flex items-center justify-center shadow-lg shadow-amber-500/20">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @else
                    <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">FREE</span>
                        <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                    </a>
                    <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </nav>

        @if($user->isCreator())
            <!-- CREATOR SPECIFIC SETTINGS EXPERIENCE -->
            <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8"
                  x-data="{
                      activeTab: 'branding',
                      savedToast: false,
                      savedMsg: 'Pengaturan berhasil diperbarui!',
                      notifySaved(msg) {
                          this.savedMsg = msg || 'Pengaturan berhasil diperbarui!';
                          this.savedToast = true;
                          setTimeout(() => this.savedToast = false, 3500);
                      },
                      // Creator State with LocalStorage Persistence & Safe JS Escaping
                      studioName: {{ Js::from($user->name) }},
                      handle: localStorage.getItem('creator_handle') || ('@' + {{ Js::from(Str::slug($user->name, '_')) }} + '_films'),
                      tagline: localStorage.getItem('creator_tagline') || 'Produksi Film Independen Quality 4K UHD & Epik Cinema',
                      bio: localStorage.getItem('creator_bio') || 'Studio film independen yang memproduksi film pendek berkualitas sinematik 4K UHD. Spesialis genre Sci-Fi, Action, dan Thriller.',
                      primaryGenre: localStorage.getItem('creator_genre') || 'Sci-Fi',
                      // Payout State
                      payoutMethod: localStorage.getItem('creator_payoutMethod') || 'bank',
                      bankName: localStorage.getItem('creator_bankName') || 'bca',
                      accountNo: localStorage.getItem('creator_accountNo') || '8830192841',
                      accountName: localStorage.getItem('creator_accountName') || {{ Js::from(strtoupper($user->name)) }},
                      autoPayout: localStorage.getItem('creator_autoPayout') || 'semi_monthly',
                      cashoutThreshold: localStorage.getItem('creator_cashoutThreshold') || '100000',
                      // Defaults State
                      defaultTier: localStorage.getItem('creator_defaultTier') || 'pro',
                      watermark: localStorage.getItem('creator_watermark') !== null ? JSON.parse(localStorage.getItem('creator_watermark')) : true,
                      commentModeration: localStorage.getItem('creator_commentModeration') || 'spam',
                      masterQuality: localStorage.getItem('creator_masterQuality') || '4k',
                      // Social Links State
                      website: localStorage.getItem('creator_website') || 'https://wewatch.id/creators/neotokyo-studios',
                      youtube: localStorage.getItem('creator_youtube') || ('https://youtube.com/@' + {{ Js::from(Str::slug($user->name, '')) }}),
                      instagram: localStorage.getItem('creator_instagram') || ('@' + {{ Js::from(Str::slug($user->name, '')) }} + '.cinema'),
                      twitter: localStorage.getItem('creator_twitter') || ('@' + {{ Js::from(Str::slug($user->name, '')) }} + '_films'),
                      discord: localStorage.getItem('creator_discord') || 'https://discord.gg/wewatch-creators',
                      // Notifications State
                      notifySubscribers: localStorage.getItem('creator_notifySubscribers') !== null ? JSON.parse(localStorage.getItem('creator_notifySubscribers')) : true,
                      notifyComments: localStorage.getItem('creator_notifyComments') !== null ? JSON.parse(localStorage.getItem('creator_notifyComments')) : true,
                      notifyPayouts: localStorage.getItem('creator_notifyPayouts') !== null ? JSON.parse(localStorage.getItem('creator_notifyPayouts')) : true,
                      notifyAnalytics: localStorage.getItem('creator_notifyAnalytics') !== null ? JSON.parse(localStorage.getItem('creator_notifyAnalytics')) : true,

                      // Storage Helper Actions
                      saveBrandingExtra() {
                          localStorage.setItem('creator_handle', this.handle);
                          localStorage.setItem('creator_tagline', this.tagline);
                          localStorage.setItem('creator_bio', this.bio);
                          localStorage.setItem('creator_genre', this.primaryGenre);
                          this.notifySaved('Identitas & branding studio tersimpan!');
                      },
                      savePayouts() {
                          localStorage.setItem('creator_payoutMethod', this.payoutMethod);
                          localStorage.setItem('creator_bankName', this.bankName);
                          localStorage.setItem('creator_accountNo', this.accountNo);
                          localStorage.setItem('creator_accountName', this.accountName);
                          localStorage.setItem('creator_autoPayout', this.autoPayout);
                          localStorage.setItem('creator_cashoutThreshold', this.cashoutThreshold);
                          this.notifySaved('Rekening & pengaturan payout tersimpan!');
                      },
                      saveDefaults() {
                          localStorage.setItem('creator_defaultTier', this.defaultTier);
                          localStorage.setItem('creator_watermark', JSON.stringify(this.watermark));
                          localStorage.setItem('creator_commentModeration', this.commentModeration);
                          localStorage.setItem('creator_masterQuality', this.masterQuality);
                          this.notifySaved('Default upload & hak cipta tersimpan!');
                      },
                      saveSocials() {
                          localStorage.setItem('creator_website', this.website);
                          localStorage.setItem('creator_youtube', this.youtube);
                          localStorage.setItem('creator_instagram', this.instagram);
                          localStorage.setItem('creator_twitter', this.twitter);
                          localStorage.setItem('creator_discord', this.discord);
                          this.notifySaved('Tautan sosial media tersimpan!');
                      },
                      saveNotifications() {
                          localStorage.setItem('creator_notifySubscribers', JSON.stringify(this.notifySubscribers));
                          localStorage.setItem('creator_notifyComments', JSON.stringify(this.notifyComments));
                          localStorage.setItem('creator_notifyPayouts', JSON.stringify(this.notifyPayouts));
                          localStorage.setItem('creator_notifyAnalytics', JSON.stringify(this.notifyAnalytics));
                          this.notifySaved('Notifikasi Creator Studio tersimpan!');
                      }
                  }">

                <!-- Toast Notification Popup -->
                <div x-show="savedToast"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="fixed bottom-6 right-6 z-50 bg-zinc-900 border border-amber-500/40 text-amber-300 px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 backdrop-blur-xl"
                     style="display: none;">
                    <div class="w-7 h-7 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-white">Pengaturan Disimpan</h4>
                        <p class="text-[11px] text-zinc-400" x-text="savedMsg"></p>
                    </div>
                </div>

                <!-- Header Section -->
                <div class="space-y-2 border-b border-zinc-800/80 pb-6">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded bg-amber-500/15 border border-amber-500/30 text-amber-400 font-extrabold text-[11px] uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            CREATOR STUDIO SETTINGS
                        </span>
                        <span class="text-xs font-mono text-zinc-400 font-bold px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800">
                            Creator Verified
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>Pengaturan Studio & Branding Creator</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                        Kelola identitas channel sinema, branding studio, opsi pencairan pendapatan (payouts), hak cipta video default, sosial media, dan keamanan akun kreator kamu.
                    </p>
                </div>

                <!-- Tabbed Settings Layout (Left Sidebar Tabs + Right Content Panel) -->
                <div class="flex flex-col md:flex-row gap-8 items-start">

                    <!-- Left Creator Settings Sidebar Menu -->
                    <div class="w-full md:w-72 bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-2.5 space-y-1 shrink-0 shadow-lg backdrop-blur-xl">

                        <button @click="activeTab = 'branding'"
                                :class="activeTab === 'branding' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 20.3c-.15.35.11.7.46.7h3.69c.14 0 .28-.06.38-.16l.82-.82C10.42 20.67 11.2 21 12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-4.5 9c-.83 0-1.5-.67-1.5-1.5S6.67 9 7.5 9s1.5.67 1.5 1.5S8.33 12 7.5 12zm3 4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm0-8c-.83 0-1.5-.67-1.5-1.5S9.67 6.5 10.5 6.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm3 4c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                            <span>Branding & Identitas Channel</span>
                        </button>

                        <button @click="activeTab = 'payouts'"
                                :class="activeTab === 'payouts' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                                <span>Monetisasi & Rekening Payout</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[9px] font-mono font-bold">AKTIF</span>
                        </button>

                        <button @click="activeTab = 'defaults'"
                                :class="activeTab === 'defaults' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                            <span>Default Upload & Hak Cipta</span>
                        </button>

                        <button @click="activeTab = 'socials'"
                                :class="activeTab === 'socials' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                            <span>Tautan Sosial & Portofolio</span>
                        </button>

                        <button @click="activeTab = 'notifications'"
                                :class="activeTab === 'notifications' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                            <span>Notifikasi Creator Studio</span>
                        </button>

                        <button @click="activeTab = 'security'"
                                :class="activeTab === 'security' ? 'bg-amber-500/15 text-amber-400 border-amber-500/30 font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60 border-transparent'"
                                class="w-full px-3.5 py-3 rounded-xl border text-xs text-left transition flex items-center gap-3">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                            <span>Keamanan & Sandi Akun</span>
                        </button>
                    </div>

                    <!-- Right Main Creator Settings Panels -->
                    <div class="flex-1 w-full bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl backdrop-blur-xl">

                        <!-- TAB 1: BRANDING & IDENTITAS CHANNEL (Integrated with Real Profile Form) -->
                        <div x-show="activeTab === 'branding'" class="space-y-6">
                            <header class="space-y-1">
                                <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 20.3c-.15.35.11.7.46.7h3.69c.14 0 .28-.06.38-.16l.82-.82C10.42 20.67 11.2 21 12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9z"/></svg>
                                    <span>Branding Studio & Identitas Channel</span>
                                </h2>
                                <p class="text-xs text-zinc-400">
                                    Atur nama studio, email resmi, cover banner, logo studio, dan deskripsi publik yang akan dilihat oleh penonton dan subscriber kamu.
                                </p>
                            </header>

                            @if (session('status') === 'profile-updated')
                                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    <span>Profil dan nama studio berhasil diperbarui ke database!</span>
                                </div>
                            @endif

                            <form method="post" action="{{ route('profile.update') }}" @submit="saveBrandingExtra()" class="space-y-6 pt-2">
                                @csrf
                                @method('patch')

                                <!-- Banner Header Upload Zone -->
                                <div class="space-y-2">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Banner Sampul Channel (Cover Art)</label>
                                    <div class="relative w-full h-40 rounded-2xl bg-gradient-to-r from-zinc-900 via-amber-950/30 to-zinc-900 border border-zinc-800 overflow-hidden group flex items-center justify-center">
                                        <div class="absolute inset-0 bg-cover bg-center opacity-40 group-hover:scale-105 transition duration-700" style="background-image: url('https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1200&q=80');"></div>
                                        <div class="relative z-10 text-center space-y-1 bg-zinc-950/70 p-4 rounded-xl border border-zinc-800/80 backdrop-blur-md">
                                            <svg class="w-6 h-6 text-amber-400 mx-auto fill-current" viewBox="0 0 24 24"><path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/></svg>
                                            <span class="text-xs font-bold text-white block">Unggah Cover Banner Studio</span>
                                            <span class="text-[10px] text-zinc-400 block">Rekomendasi resolusi 1280 x 360 px (Format JPG, PNG max 5MB)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo Avatar & Identity Fields -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-3">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Logo Studio / Avatar Profil</label>
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-600 to-amber-400 text-zinc-950 font-black text-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 border-2 border-amber-400/50 shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="space-y-1">
                                                <button type="button" @click="notifySaved('Gambar logo studio diperbarui!')" class="py-1.5 px-3 rounded-lg bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 text-xs font-bold text-white transition">
                                                    Ganti Logo Studio
                                                </button>
                                                <span class="text-[10px] text-zinc-500 block">Format PNG transparan disarankan</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Tagline Singkat Studio</label>
                                        <input type="text" x-model="tagline" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition" placeholder="Contoh: Produksi Film Independen Quality 4K">
                                    </div>
                                </div>

                                <!-- Real Laravel Fields: Name & Email -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label for="name" class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Nama Studio Sinema (Profile Name)</label>
                                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-bold">
                                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="email" class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Alamat Email Resmi Studio</label>
                                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                        <x-input-error class="mt-1" :messages="$errors->get('email')" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Custom Handle Studio (@)</label>
                                        <input type="text" x-model="handle" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-amber-400 font-mono py-2.5 px-3 focus:outline-none focus:border-amber-500 transition">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Kategori Genre Utama Studio</label>
                                        <select x-model="primaryGenre" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-bold">
                                            <option value="Sci-Fi">Sci-Fi & Cyberpunk Dystopia</option>
                                            <option value="Action">Action Thriller & Martial Arts</option>
                                            <option value="Documentary">Documentary & Nature Cinema</option>
                                            <option value="Fantasy">Fantasy Epic & Mythological</option>
                                            <option value="Drama">Drama & Romance Sinema</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Biografi & Deskripsi Profil Studio</label>
                                    <textarea x-model="bio" rows="3" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition leading-relaxed" placeholder="Ceritakan tentang studio dan karya film kamu..."></textarea>
                                </div>

                                <div class="pt-2 flex items-center gap-3">
                                    <button type="submit" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                        <span>Simpan Branding & Profil Studio</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: MONETISASI & REKENING PAYOUT -->
                        <div x-show="activeTab === 'payouts'" class="space-y-6" style="display: none;">
                            <header class="space-y-1">
                                <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                                    <span>Monetisasi Studio & Rekening Penarikan (Payouts)</span>
                                </h2>
                                <p class="text-xs text-zinc-400">
                                    Pengaturan akun bank / e-wallet pencairan hasil bagi hasil tontonan film & karya kamu.
                                </p>
                            </header>

                            <!-- Monetization Status Banner -->
                            <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-zinc-950 to-zinc-950 border border-amber-500/30 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-800/80 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center font-black text-xs">
                                            80%
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-white text-sm">Program Monetisasi Kreator</h3>
                                            <span class="text-[10px] text-emerald-400 font-mono font-bold flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                Status: Terverifikasi & Aktif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-left sm:text-right">
                                        <span class="text-[10px] text-zinc-400 block uppercase font-mono font-bold">Saldo Siap Cair</span>
                                        <span class="text-lg font-black text-amber-400 font-mono">Rp 14.850.000</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-zinc-400">
                                    <span>Skema Bagi Hasil: <strong class="text-white font-mono">80% Creator / 20% WeWatch Platform</strong></span>
                                    <button type="button" @click="notifySaved('Permintaan pencairan dana telah dikirim!')" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow transition active:scale-95">
                                        Tarik Saldo
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-5 pt-2">
                                <!-- Payout Method Selection -->
                                <div class="space-y-3">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Pilih Metode Pencairan Pendapatan</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <button @click="payoutMethod = 'bank'" :class="payoutMethod === 'bank' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:text-white'" class="p-3.5 rounded-2xl border text-xs font-bold text-left transition space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>Transfer Bank Rekening Direct</span>
                                                <span class="px-1.5 py-0.5 rounded bg-zinc-800 text-amber-400 font-mono text-[9px]">BCA / Mandiri / BNI</span>
                                            </div>
                                            <span class="text-[10px] text-zinc-400 font-normal block">Pencairan langsung ke rekening utama bank nasional</span>
                                        </button>

                                        <button @click="payoutMethod = 'ewallet'" :class="payoutMethod === 'ewallet' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:text-white'" class="p-3.5 rounded-2xl border text-xs font-bold text-left transition space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>E-Wallet Instan</span>
                                                <span class="px-1.5 py-0.5 rounded bg-zinc-800 text-emerald-400 font-mono text-[9px]">GoPay / OVO / DANA</span>
                                            </div>
                                            <span class="text-[10px] text-zinc-400 font-normal block">Proses pencairan instan 24 jam via nomor HP e-wallet</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bank / E-Wallet Account Info Form -->
                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="space-y-1">
                                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Penyedia Bank / E-Wallet</label>
                                            <select x-model="bankName" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-bold">
                                                <option value="bca">Bank BCA (Central Asia)</option>
                                                <option value="mandiri">Bank Mandiri</option>
                                                <option value="bni">Bank BNI</option>
                                                <option value="bri">Bank BRI</option>
                                                <option value="gopay">GoPay (E-Wallet)</option>
                                                <option value="ovo">OVO (E-Wallet)</option>
                                                <option value="dana">DANA (E-Wallet)</option>
                                            </select>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Nomor Rekening / No. HP</label>
                                            <input type="text" x-model="accountNo" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 font-mono py-2.5 px-3 focus:outline-none focus:border-amber-500 transition">
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Nama Pemilik Rekening</label>
                                            <input type="text" x-model="accountName" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-bold">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-zinc-800/80 pt-4">
                                        <div class="space-y-1">
                                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Ambang Batas Cashout Minimum</label>
                                            <select x-model="cashoutThreshold" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition">
                                                <option value="100000">Rp 100.000 (Default Minimum)</option>
                                                <option value="500000">Rp 500.000</option>
                                                <option value="1000000">Rp 1.000.000</option>
                                            </select>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Jadwal Penarikan Otomatis</label>
                                            <select x-model="autoPayout" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition">
                                                <option value="semi_monthly">Otomatis Setiap Tgl 1 & 15 Bulanan</option>
                                                <option value="monthly">Otomatis Setiap Akhir Bulan</option>
                                                <option value="manual">Manual Cashout Sesuai Keinginan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button @click="savePayouts()" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                        <span>Simpan Rekening Payout</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: DEFAULT UPLOAD & HAK CIPTA -->
                        <div x-show="activeTab === 'defaults'" class="space-y-6" style="display: none;">
                            <header class="space-y-1">
                                <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                                    <span>Default Upload & Pengaturan Hak Cipta</span>
                                </h2>
                                <p class="text-xs text-zinc-400">
                                    Tentukan level aksesibilitas bawaan, moderasi komentar, dan watermark otomatis untuk film baru yang diunggah.
                                </p>
                            </header>

                            <div class="space-y-5 pt-2">
                                <!-- Default Access Tier -->
                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-3">
                                    <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Tier Akses Penonton Default untuk Video Baru</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <button @click="defaultTier = 'free'" :class="defaultTier === 'free' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>Free (Publik)</span>
                                                <span class="px-1.5 py-0.2 rounded bg-zinc-800 text-zinc-300 font-mono text-[8px]">Semua</span>
                                            </div>
                                            <span class="text-[10px] text-zinc-400 font-normal block">Dapat diakses oleh semua penonton gratis</span>
                                        </button>

                                        <button @click="defaultTier = 'pro'" :class="defaultTier === 'pro' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>Eksklusif Pro</span>
                                                <span class="px-1.5 py-0.2 rounded bg-amber-500 text-zinc-950 font-mono text-[8px]">PRO</span>
                                            </div>
                                            <span class="text-[10px] text-zinc-400 font-normal block">Hanya untuk subscriber & member Pro</span>
                                        </button>

                                        <button @click="defaultTier = 'vip'" :class="defaultTier === 'vip' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white'" class="p-3 rounded-xl border text-xs font-bold text-left transition space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span>VIP Exclusive</span>
                                                <span class="px-1.5 py-0.2 rounded bg-purple-600 text-white font-mono text-[8px]">VIP</span>
                                            </div>
                                            <span class="text-[10px] text-zinc-400 font-normal block">Pengalaman khusus kasta tertinggi VIP Mewah</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Watermark & Comment Moderation -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                        <div>
                                            <h4 class="font-bold text-white text-xs">Watermark Studio Otomatis</h4>
                                            <p class="text-[10px] text-zinc-400">Sematkan logo studio di pojok video saat pemutaran</p>
                                        </div>
                                        <button @click="watermark = !watermark" :class="watermark ? 'bg-amber-500' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                            <div :class="watermark ? 'translate-x-6 bg-zinc-950' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                        </button>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Moderasi Komentar Bawaan</label>
                                        <select x-model="commentModeration" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2 px-3 focus:outline-none focus:border-amber-500 transition">
                                            <option value="all">Izinkan Semua Komentar Pengguna</option>
                                            <option value="spam">Tahan Komentar Berpotensi Spam / Offensif</option>
                                            <option value="subscribers">Khusus Komentar Subscriber Pro/VIP</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button @click="saveDefaults()" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                        <span>Simpan Opsi Upload Default</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: TAUTAN SOSIAL & PORTOFOLIO -->
                        <div x-show="activeTab === 'socials'" class="space-y-6" style="display: none;">
                            <header class="space-y-1">
                                <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                                    <span>Tautan Sosial Media & Portofolio Sinema</span>
                                </h2>
                                <p class="text-xs text-zinc-400">
                                    Tampilkan link portofolio studio, channel YouTube, Instagram, dan komunitas sosial di halaman profil kreator kamu.
                                </p>
                            </header>

                            <div class="space-y-4 pt-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Website / Portofolio Studio</label>
                                        <input type="text" x-model="website" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">YouTube Channel URL</label>
                                        <input type="text" x-model="youtube" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Instagram Handle</label>
                                        <input type="text" x-model="instagram" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">X / Twitter Handle</label>
                                        <input type="text" x-model="twitter" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300">Discord Server Invite Link</label>
                                        <input type="text" x-model="discord" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl text-xs text-zinc-100 py-2.5 px-3 focus:outline-none focus:border-amber-500 transition font-mono">
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button @click="saveSocials()" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                        <span>Simpan Tautan Sosial</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 5: NOTIFIKASI CREATOR STUDIO -->
                        <div x-show="activeTab === 'notifications'" class="space-y-6" style="display: none;">
                            <header class="space-y-1">
                                <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                                    <span>Pemberitahuan Notifikasi Creator Studio</span>
                                </h2>
                                <p class="text-xs text-zinc-400">
                                    Atur preferensi notifikasi subscriber baru, komentar video, dan status payout studio kamu.
                                </p>
                            </header>

                            <div class="space-y-4 pt-2">
                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-white text-xs">Alert Email Subscriber Baru</h4>
                                        <p class="text-[10px] text-zinc-400">Kirim pemberitahuan email saat penonton baru mensubscribe channel</p>
                                    </div>
                                    <button @click="notifySubscribers = !notifySubscribers; saveNotifications()" :class="notifySubscribers ? 'bg-amber-500' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                        <div :class="notifySubscribers ? 'translate-x-6 bg-zinc-950' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                    </button>
                                </div>

                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-white text-xs">Alert Komentar & Diskusi Baru</h4>
                                        <p class="text-[10px] text-zinc-400">Notifikasi langsung saat ada penggemar meninggalkan komentar di video</p>
                                    </div>
                                    <button @click="notifyComments = !notifyComments; saveNotifications()" :class="notifyComments ? 'bg-amber-500' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                        <div :class="notifyComments ? 'translate-x-6 bg-zinc-950' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                    </button>
                                </div>

                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-white text-xs">Alert Pembayaran & Payout Berhasil</h4>
                                        <p class="text-[10px] text-zinc-400">Kirim email konfirmasi setiap kali dana pencairan telah ditransfer ke bank</p>
                                    </div>
                                    <button @click="notifyPayouts = !notifyPayouts; saveNotifications()" :class="notifyPayouts ? 'bg-amber-500' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                        <div :class="notifyPayouts ? 'translate-x-6 bg-zinc-950' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                    </button>
                                </div>

                                <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-white text-xs">Laporan Ringkasan Analitik Bulanan</h4>
                                        <p class="text-[10px] text-zinc-400">Laporan performa statistik tayangan & pendapatan studio setiap awal bulan</p>
                                    </div>
                                    <button @click="notifyAnalytics = !notifyAnalytics; saveNotifications()" :class="notifyAnalytics ? 'bg-amber-500' : 'bg-zinc-800'" class="w-12 h-6 rounded-full p-1 transition duration-300 relative focus:outline-none">
                                        <div :class="notifyAnalytics ? 'translate-x-6 bg-zinc-950' : 'translate-x-0 bg-zinc-400'" class="w-4 h-4 rounded-full transition duration-300 shadow"></div>
                                    </button>
                                </div>

                                <div class="pt-2">
                                    <button @click="saveNotifications()" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                                        <span>Simpan Preferensi Notifikasi</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 6: KEAMANAN & PASSWORD & DELETE (Focused Security Form) -->
                        <div x-show="activeTab === 'security'" class="space-y-8" style="display: none;">
                            @include('profile.partials.update-password-form')

                            <div class="border-t border-zinc-800/80 pt-6">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        @else
            <!-- STANDARD USER SETTINGS EXPERIENCE -->
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

                                <!-- Autoplay Switch -->
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
        @endif

    </body>
</html>
