<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Upgrade Membership Tiers — WeWatch</title>

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
        <div class="fixed top-0 left-1/4 w-[700px] h-[350px] bg-red-600/10 rounded-full blur-[160px] pointer-events-none z-0"></div>
        <div class="fixed top-1/3 right-1/4 w-[700px] h-[350px] bg-amber-500/10 rounded-full blur-[160px] pointer-events-none z-0"></div>

        <!-- Floating Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-3xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-5xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Brand Logo & Back -->
            <div class="flex items-center gap-4">
                <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>{{ Auth::user()->isSuperAdmin() ? 'Kembali ke Admin Console' : 'Kembali ke Dashboard' }}</span>
                </a>
            </div>

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md">
                    W
                </div>
                <span class="font-black text-base tracking-tight text-white hidden sm:inline">WEWATCH PREMIUM</span>
            </div>
        </nav>

        <!-- Main Upgrade Subscription Section -->
        <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-12">

            <!-- HERO HEADER -->
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[11px] uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    <span>Pilih Paket Membership Kamu</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                    Tingkatkan Pengalaman Sinematik & Akses Tanpa Batas
                </h1>

                <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed">
                    Nikmati tayangan streaming kualitas tinggi, kuota komentar lebih luas, fitur subscribe kreator, hingga pesan langsung (Direct Message) khusus anggota VIP.
                </p>
            </div>

            <!-- 3 MENU PRICING CARDS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto pt-4">

                <!-- 1. FREE TIER (PALING KIRI) -->
                <div class="bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-7 flex flex-col justify-between space-y-8 relative backdrop-blur-xl transition hover:border-zinc-700">
                    <div class="space-y-6">
                        <!-- Header -->
                        <div class="space-y-2 border-b border-zinc-800/80 pb-5">
                            <span class="px-2.5 py-1 rounded-md bg-zinc-800 text-zinc-400 font-bold text-[10px] uppercase tracking-wider">PAKET DASAR</span>
                            <h3 class="text-2xl font-black text-white tracking-tight">FREE</h3>
                            <p class="text-xs text-zinc-400">Akses terbatas untuk pengguna baru</p>

                            <div class="pt-3">
                                <span class="text-3xl font-black text-white">Rp 0</span>
                                <span class="text-xs text-zinc-500 font-medium">/ bulan</span>
                            </div>
                        </div>

                        <!-- Feature Checklist -->
                        <div class="space-y-3.5 text-xs text-zinc-300">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                <span>Akses film & serial <strong class="text-zinc-200">terbatas</strong></span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                <span>Maksimal <strong class="text-zinc-200">1 komentar</strong> per video</span>
                            </div>
                            <div class="flex items-start gap-2.5 opacity-50">
                                <svg class="w-4 h-4 text-zinc-600 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                <span class="line-through">Tidak dapat subscribe ke kreator</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Kualitas Standar SD/HD 720p</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    @if(Auth::user()->getEffectiveSubscriptionTier() === 'free')
                        <button disabled class="w-full py-3 px-4 rounded-xl bg-zinc-800 text-zinc-500 font-bold text-xs cursor-default text-center border border-zinc-700/50">
                            Paket Aktif Saat Ini
                        </button>
                    @else
                        <form method="POST" action="{{ route('subscription.upgrade') }}">
                            @csrf
                            <input type="hidden" name="tier" value="free">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs text-center border border-zinc-700 transition">
                                Downgrade ke Free
                            </button>
                        </form>
                    @endif
                </div>


                <!-- 2. PRO TIER (DI TENGAH - RECOMMENDED BADGE) -->
                <div class="bg-zinc-900/95 border-2 border-red-600/80 rounded-3xl p-7 flex flex-col justify-between space-y-8 relative backdrop-blur-xl shadow-2xl shadow-red-600/15 scale-[1.03] lg:scale-[1.04] z-20">
                    <!-- Recommended Badge -->
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-red-600 text-white font-extrabold text-[10px] uppercase tracking-widest shadow-lg shadow-red-600/40 border border-red-400 flex items-center gap-1.5">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <span>RECOMMENDED</span>
                    </div>

                    <div class="space-y-6">
                        <!-- Header -->
                        <div class="space-y-2 border-b border-zinc-800/80 pb-5 pt-1">
                            <span class="px-2.5 py-1 rounded-md bg-red-600/20 border border-red-600/40 text-red-400 font-bold text-[10px] uppercase tracking-wider">POPULER</span>
                            <h3 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                                <span>PRO</span>
                            </h3>
                            <p class="text-xs text-zinc-300">Akses luas & fitur subscribe terbuka</p>

                            <div class="pt-3">
                                <span class="text-3xl font-black text-white">Rp 50.000</span>
                                <span class="text-xs text-zinc-400 font-medium">/ bulan</span>
                            </div>
                        </div>

                        <!-- Feature Checklist -->
                        <div class="space-y-3.5 text-xs text-zinc-200">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Akses <strong class="text-white">luas perpustakaan film & serial</strong></span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Akses <strong class="text-white">kreator khusus tertentu</strong></span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Akses <strong class="text-white">lebih dari 100 komentar</strong> per video</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span><strong class="text-red-400">Dapat Subscribe</strong> ke kreator disukai</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Kualitas Full HD 1080p tanpa iklan</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    @if(Auth::user()->getEffectiveSubscriptionTier() === 'pro')
                        <button disabled class="w-full py-3.5 px-4 rounded-xl bg-red-600/30 text-red-300 font-bold text-xs cursor-default text-center border border-red-500/40">
                            Paket Aktif Saat Ini (PRO)
                        </button>
                    @else
                        <form method="POST" action="{{ route('subscription.upgrade') }}">
                            @csrf
                            <input type="hidden" name="tier" value="pro">
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 text-center">
                                Upgrade ke Pro Sekarang
                            </button>
                        </form>
                    @endif
                </div>


                <!-- 3. VIP TIER (PALING KANAN - GLOSSY MEWAH / LUXURY GOLD) -->
                <div class="bg-gradient-to-b from-amber-500/15 via-zinc-900/95 to-zinc-950 border-2 border-amber-500/60 rounded-3xl p-7 flex flex-col justify-between space-y-8 relative backdrop-blur-2xl shadow-2xl shadow-amber-500/15 overflow-hidden group hover:border-amber-400 transition duration-500">

                    <!-- Glossy Reflective Sheen Layer -->
                    <div class="absolute -inset-full top-0 block h-full w-1/2 -skew-x-12 bg-gradient-to-r from-transparent via-white/10 to-transparent group-hover:left-full transition-all duration-1000 ease-in-out"></div>

                    <!-- Luxury Crown Badge -->
                    <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-yellow-300 text-zinc-950 font-black text-[9px] uppercase tracking-wider shadow-md flex items-center gap-1">
                        <svg class="w-3 h-3 fill-current text-zinc-950" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                        <span>VIP MEWAH</span>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <!-- Header -->
                        <div class="space-y-2 border-b border-amber-500/30 pb-5">
                            <span class="px-2.5 py-1 rounded-md bg-amber-500/20 border border-amber-500/40 text-amber-300 font-extrabold text-[10px] uppercase tracking-wider">ULTIMATE VIP</span>
                            <h3 class="text-2xl font-black text-amber-200 tracking-tight flex items-center gap-2">
                                <span>VIP EXCLUSIVE</span>
                            </h3>
                            <p class="text-xs text-amber-100/80">Layanan khusus tak terbatas & Direct Message</p>

                            <div class="pt-3">
                                <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-400 to-yellow-200">Rp 150.000</span>
                                <span class="text-xs text-amber-300 font-medium">/ bulan</span>
                            </div>
                        </div>

                        <!-- Feature Checklist -->
                        <div class="space-y-3.5 text-xs text-zinc-100">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>Akses <strong class="text-amber-200">seluruh video & katalog tanpa batas</strong></span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>Komentar <strong class="text-amber-200">tak terbatas</strong> di semua video</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                <span><strong class="text-amber-300">Direct Message (DM)</strong> pesan ke Kreator tujuan</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                <span><strong class="text-amber-300">Direct Message (DM)</strong> langsung ke Admin</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                                <span><strong class="text-amber-200">Pelayanan khusus tak terbatas</strong> hanya milik VIP</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>Kualitas Sinema 4K Ultra HD & Dolby Atmos</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button (Glossy Gold) -->
                    @if(Auth::user()->getEffectiveSubscriptionTier() === 'vip')
                        <button disabled class="w-full py-3.5 px-4 rounded-xl bg-amber-500/30 text-amber-300 font-bold text-xs cursor-default text-center border border-amber-500/40 relative z-10">
                            Paket Aktif Saat Ini (VIP MEWAH)
                        </button>
                    @else
                        <form method="POST" action="{{ route('subscription.upgrade') }}" class="relative z-10">
                            @csrf
                            <input type="hidden" name="tier" value="vip">
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-300 hover:to-yellow-300 text-zinc-950 font-black text-xs shadow-xl shadow-amber-500/20 transition transform active:scale-95 text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 fill-current text-zinc-950" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                                <span>Upgrade ke VIP Luxury</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </main>
    </body>
</html>
