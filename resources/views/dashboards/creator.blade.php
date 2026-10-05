<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Creator Studio Hub — {{ config('app.name', 'WeWatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden"
          x-data="{
              showUploadModal: false,
              showEpisodeModal: false,
              selectedMovie: null,
              selectedTab: 'all',
              replyText: '',
              openAddEpisode(movie) {
                  this.selectedMovie = movie;
                  this.showEpisodeModal = true;
              }
          }">

        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/3 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed top-1/2 right-1/4 w-[600px] h-[300px] bg-amber-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-4xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-6xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Brand Logo & Studio Tag -->
            <div class="flex items-center gap-4">
                <a href="{{ route('creator.dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md transition group-hover:scale-105">
                        W
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-black text-base tracking-tight text-white group-hover:text-red-500 transition block leading-none">
                            WEWATCH
                        </span>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-red-500">CREATOR STUDIO</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-2 pl-4 border-l border-zinc-800">
                    <a href="{{ route('creators.show', Auth::user()->handle ? ltrim(Auth::user()->handle, '@') : Str::slug(Auth::user()->name)) }}" class="px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white hover:border-zinc-700 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        <span>Lihat Channel Publik</span>
                    </a>
                </div>
            </div>

            <!-- Action Controls & User Profile Dropdown -->
            <div class="flex items-center space-x-3 shrink-0">
                <button @click="showUploadModal = true" class="py-2 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    <span>Terbitkan Film Baru</span>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-zinc-700 transition">
                        <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl p-2 text-xs text-zinc-300 space-y-1 z-50" style="display: none;">
                        <div class="px-3 py-2 border-b border-zinc-800">
                            <div class="font-bold text-white truncate">{{ Auth::user()->name }}</div>
                            <span class="px-1.5 py-0.2 rounded bg-amber-500/20 text-amber-400 font-mono text-[9px] font-bold border border-amber-500/30">Verified Creator</span>
                        </div>
                        <a href="{{ route('creator.dashboard') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Creator Studio Hub</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Pengaturan Akun</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-xl hover:bg-red-600/20 text-red-400 font-semibold transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Studio Container -->
        <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

            <!-- Welcome Header Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-800/80 pb-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        @if(Auth::user()->isVerified())
                            <span class="px-2.5 py-0.5 rounded bg-blue-500/15 border border-blue-500/30 text-blue-400 font-extrabold text-[10px] uppercase tracking-wider flex items-center gap-1" title="Kreator Terverifikasi Centang Biru">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <span>VERIFIED CREATOR STUDIO</span>
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded bg-amber-500/15 border border-amber-500/30 text-amber-400 font-extrabold text-[10px] uppercase tracking-wider">
                                CREATOR STUDIO HUB (BASIC)
                            </span>
                        @endif
                        <span class="text-xs font-mono text-zinc-400 font-bold">Creator: {{ Auth::user()->email }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <h1 class="text-3xl font-black text-white tracking-tight">
                            Studio Manajemen Film &amp; Episode — {{ Auth::user()->name }}
                        </h1>
                        @if(Auth::user()->isVerified())
                            <svg class="w-7 h-7 fill-blue-500 shrink-0" title="Official Verified Badge" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-400">
                        Terbitkan film baru, kelola status rilis (Ongoing / Tamat), dan tambahkan episode mingguan untuk penonton kamu.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button @click="showUploadModal = true" class="py-3 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        <span>+ Terbitkan Film / Serial Baru</span>
                    </button>
                </div>
            </div>

            <!-- CREATOR METRICS ANALYTICS GRID (4 CARDS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Metric 1: Total Published Movies -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Film Diterbitkan</span>
                        <div class="w-8 h-8 rounded-lg bg-red-600/10 text-red-500 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white font-mono tracking-tight">{{ count($movies) }} <span class="text-xs text-zinc-400 font-normal">Film</span></div>
                    <div class="text-[11px] text-zinc-400 font-medium">Tersedia di katalog utama</div>
                </div>

                <!-- Metric 2: Ongoing Series -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Status Ongoing</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-amber-400 font-mono tracking-tight">{{ $movies->filter(fn($m) => $m->isOngoing())->count() }} <span class="text-xs text-zinc-400 font-normal">Judul</span></div>
                    <div class="text-[11px] text-amber-400/80 font-medium">Siap tambah episode minggu depan</div>
                </div>

                <!-- Metric 3: Total Episodes -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Episode</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-600/10 text-purple-400 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l5.5 4.5-5.5 4.5z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white font-mono tracking-tight">{{ $movies->sum(fn($m) => $m->episodes->count()) }} <span class="text-xs text-zinc-400 font-normal">Ep</span></div>
                    <div class="text-[11px] text-zinc-400 font-medium">Konten episode rilis</div>
                </div>

                <!-- Metric 4: Revenue Status (Unlocked at Rp 0 if verified, Locked if unverified) -->
                @if(Auth::user()->isVerified())
                    <div class="bg-zinc-900/90 border border-emerald-500/30 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-emerald-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Estimasi Pendapatan</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-600/10 text-emerald-400 flex items-center justify-center">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-emerald-400 font-mono tracking-tight">Rp 0</div>
                        <div class="flex items-center justify-between text-[10px] text-zinc-400">
                            <span>Monetisasi Terverifikasi</span>
                            <span class="text-emerald-400 font-bold">Aktif</span>
                        </div>
                    </div>
                @else
                    <div class="bg-zinc-900/90 border border-amber-500/30 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-amber-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">Fitur Pendapatan</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                            </div>
                        </div>
                        <div class="text-lg font-black text-zinc-300 font-mono tracking-tight flex items-center gap-1.5">
                            <span>TERKUNCI 🔒</span>
                        </div>
                        <div class="text-[10px] text-amber-400/80 font-medium">Perlu Verifikasi SuperAdmin</div>
                    </div>
                @endif
            </div>

            <!-- MEDIA LIBRARY TABLE & CONTENT MANAGEMENT -->
            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden shadow-2xl backdrop-blur-xl space-y-4">
                
                <!-- Table Header Bar -->
                <div class="p-6 border-b border-zinc-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-white tracking-tight">Katalog Film & Serial Hasil Karya Kamu</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Kelola episode mingguan, status rilis (Ongoing vs Complete), dan link video tontonan</p>
                    </div>

                    <button @click="showUploadModal = true" class="py-2.5 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-md transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        <span>Terbitkan Film Baru</span>
                    </button>
                </div>

                <!-- Table View -->
                <div class="overflow-x-auto">
                    @if($movies->isEmpty())
                        <!-- Empty State Inside Creator Studio -->
                        <div class="p-12 text-center space-y-4 max-w-md mx-auto">
                            <div class="w-16 h-16 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center mx-auto text-zinc-400">
                                <svg class="w-8 h-8 fill-current text-red-500" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-base font-bold text-white">Belum Ada Film yang Diterbitkan</h4>
                                <p class="text-xs text-zinc-400 leading-relaxed">
                                    Kamu belum menerbitkan film atau serial karya studio kamu. Klik tombol di bawah untuk memasukkan film perdana!
                                </p>
                            </div>
                            <button @click="showUploadModal = true" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg transition inline-flex items-center gap-2">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                <span>+ Terbitkan Film Perdana</span>
                            </button>
                        </div>
                    @else
                        <table class="w-full text-left text-xs text-zinc-300">
                            <thead class="bg-zinc-950/80 text-zinc-400 uppercase tracking-wider text-[10px] border-b border-zinc-800/80 font-bold">
                                <tr>
                                    <th class="px-6 py-3.5">Judul Film & Sampul</th>
                                    <th class="px-6 py-3.5">Genre</th>
                                    <th class="px-6 py-3.5">Status Rilis</th>
                                    <th class="px-6 py-3.5">Jumlah Episode</th>
                                    <th class="px-6 py-3.5">Akses Tier</th>
                                    <th class="px-6 py-3.5 text-right">Manajemen Studio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/80">
                                @foreach($movies as $movie)
                                    <tr class="hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <div class="w-14 h-16 rounded-lg overflow-hidden bg-zinc-800 shrink-0 border border-zinc-700 relative shadow">
                                                <img src="{{ asset($movie->poster_url) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm hover:text-red-400 transition truncate max-w-xs">
                                                    {{ $movie->title }}
                                                </div>
                                                <p class="text-[11px] text-zinc-400 line-clamp-1 max-w-sm mt-0.5">{{ $movie->description }}</p>
                                                <div class="text-[10px] text-zinc-500 font-mono mt-1">Rilis {{ $movie->release_year }} • Rating {{ $movie->rating }}★</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-purple-400">
                                            {{ $movie->genre }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($movie->isOngoing())
                                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-amber-500/15 text-amber-400 border border-amber-500/30 flex items-center gap-1 w-fit">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                                    Ongoing (Berlanjut)
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center gap-1 w-fit">
                                                    Completed (Tamat)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-bold text-white bg-zinc-950 px-2.5 py-1 rounded-lg border border-zinc-800">
                                                {{ $movie->episodes->count() }} Episode
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 uppercase font-bold text-[10px]">
                                            @if($movie->access_tier === 'vip')
                                                <span class="text-amber-300 bg-amber-500/20 border border-amber-500/40 px-2 py-0.5 rounded">VIP LUXURY</span>
                                            @elseif($movie->access_tier === 'pro')
                                                <span class="text-red-400 bg-red-600/20 border border-red-600/40 px-2 py-0.5 rounded">PRO MEMBER</span>
                                            @else
                                                <span class="text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded">FREE ACCES</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                                <!-- Add Next Episode Button -->
                                                <button type="button"
                                                        @click="openAddEpisode({{ json_encode(['id' => $movie->id, 'title' => $movie->title, 'next_ep' => $movie->episodes->count() + 1]) }})"
                                                        class="px-2.5 py-1.5 rounded-lg bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-600/40 font-bold text-[11px] transition flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                                    <span>+ Ep Mingguan</span>
                                                </button>

                                                <!-- Toggle Ongoing vs Complete Form -->
                                                <form method="POST" action="{{ route('creator.movies.toggle-status', $movie->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-[11px] border border-zinc-700 transition">
                                                        {{ $movie->isOngoing() ? 'Set Tamat' : 'Set Ongoing' }}
                                                    </button>
                                                </form>

                                                <!-- Delete Movie Form -->
                                                <form method="POST" action="{{ route('creator.movies.destroy', $movie->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film ini beserta seluruh episodenya?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg bg-zinc-950 text-zinc-500 hover:text-red-400 border border-zinc-800 transition" title="Hapus Film">
                                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- AUDIENCE COMMENTS SECTION -->
            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-6 space-y-4 shadow-xl backdrop-blur-xl">
                <div class="flex items-center justify-between border-b border-zinc-800/80 pb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-white tracking-tight">Komentar Penonton Terbaru pada Karya Kamu</h3>
                        <p class="text-xs text-zinc-400">Balas langsung komentar penggemar di film kamu</p>
                    </div>
                </div>

                @php
                    $latestAudienceUser = \App\Models\User::where('role', \App\Enums\UserRole::User)->latest()->first() ?? Auth::user();
                @endphp

                <div class="space-y-3">
                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center overflow-hidden">
                                    @if(!empty($latestAudienceUser->avatar_url))
                                        <img src="{{ asset($latestAudienceUser->avatar_url) }}" alt="{{ $latestAudienceUser->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($latestAudienceUser->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="font-bold text-white text-xs">{{ $latestAudienceUser->name }}</span>
                                    <span class="text-[10px] text-zinc-500 ml-1">pada film tayangan kamu</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-zinc-500 font-mono">Baru Saja</span>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed pl-9">Gokil sinematografi nya dapet banget vibes-nya! Episode selanjutnya ditunggu min 🔥</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- FORM MODAL 1: PUBLISH NEW MOVIE / SERIAL -->
        <div x-show="showUploadModal" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">
            <div @click="showUploadModal = false" x-show="showUploadModal" x-transition class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="showUploadModal" x-transition class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-2xl w-full relative z-10 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-red-600/15 border border-red-600/30 text-red-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight">Terbitkan Film / Serial Baru</h3>
                            <p class="text-xs text-zinc-400">Isi metadata film, genre, status rilis, dan episode perdana</p>
                        </div>
                    </div>

                    <button type="button" @click="showUploadModal = false" class="p-1 rounded-xl bg-zinc-800 text-zinc-400 hover:text-white transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                </div>

                <!-- Form Real Action -->
                <form method="POST" action="{{ route('creator.movies.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Judul Film / Serial *</label>
                        <input type="text" name="title" required placeholder="Contoh: Cyberpunk Shadows 2099" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>

                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Deskripsi / Sinopsis Film *</label>
                        <textarea name="description" rows="3" required placeholder="Tuliskan sinopsis singkat cerita film kamu..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Genre Film *</label>
                            <select name="genre" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 focus:outline-none focus:border-red-600 transition">
                                <option value="Sci-Fi Series">Sci-Fi Series</option>
                                <option value="Action Thriller">Action Thriller</option>
                                <option value="Fantasy Epic">Fantasy Epic</option>
                                <option value="Documentary">Documentary</option>
                                <option value="Romance Drama">Romance Drama</option>
                                <option value="Horror Mystery">Horror Mystery</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Akses Penonton *</label>
                            <select name="access_tier" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 focus:outline-none focus:border-red-600 transition">
                                <option value="free">Publik (Gratis Semua Penonton)</option>
                                <option value="pro">Eksklusif Pro & VIP Member</option>
                                <option value="vip">Khusus VIP Luxury</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Status Rilis Film *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="p-3 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center gap-2 cursor-pointer hover:border-amber-500/60 transition">
                                <input type="radio" name="status" value="ongoing" checked class="text-red-600 focus:ring-0 bg-zinc-900 border-zinc-700">
                                <div>
                                    <div class="text-xs font-bold text-white">Ongoing</div>
                                    <div class="text-[10px] text-zinc-400">Masih Berlanjut (Ada ep mingguan)</div>
                                </div>
                            </label>
                            <label class="p-3 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center gap-2 cursor-pointer hover:border-emerald-500/60 transition">
                                <input type="radio" name="status" value="completed" class="text-red-600 focus:ring-0 bg-zinc-900 border-zinc-700">
                                <div>
                                    <div class="text-xs font-bold text-white">Completed</div>
                                    <div class="text-[10px] text-zinc-400">Tamat / Selesai Sepenuhnya</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Link Cover / Sampul Film (URL Gambar)</label>
                        <input type="text" name="poster_url" placeholder="URL gambar poster (Opsional, bawaan jika kosong)" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>

                    <div class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800/80 space-y-3">
                        <div class="flex items-center gap-2 text-xs font-bold text-red-400 uppercase tracking-wider">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l5.5 4.5-5.5 4.5z"/></svg>
                            <span>Episode Perdana (Episode #1)</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Judul Episode 1</label>
                                <input type="text" name="initial_episode_title" placeholder="Ep 1: Neon Genesis" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 focus:outline-none focus:border-red-600">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-400 mb-1">Link Embed Video (URL / YouTube)</label>
                                <input type="text" name="video_url" placeholder="https://www.youtube.com/embed/..." class="w-full bg-zinc-900 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 focus:outline-none focus:border-red-600">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                        <button type="button" @click="showUploadModal = false" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
                            <span>Terbitkan Film Perdana</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FORM MODAL 2: ADD NEXT WEEK EPISODE -->
        <div x-show="showEpisodeModal" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">
            <div @click="showEpisodeModal = false" x-show="showEpisodeModal" x-transition class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="showEpisodeModal" x-transition class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full relative z-10 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div>
                        <h3 class="text-base font-black text-white tracking-tight">Tambah Episode Baru (Mingguan)</h3>
                        <p class="text-xs text-zinc-400" x-text="'Film: ' + (selectedMovie ? selectedMovie.title : '')"></p>
                    </div>

                    <button type="button" @click="showEpisodeModal = false" class="p-1 rounded-xl bg-zinc-800 text-zinc-400 hover:text-white transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                </div>

                <form method="POST" :action="'/creator/movies/' + (selectedMovie ? selectedMovie.id : 0) + '/episodes'" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Judul Episode *</label>
                        <input type="text" name="title" required :placeholder="'Contoh: Ep ' + (selectedMovie ? selectedMovie.next_ep : '') + ': Pertempuran Sengit'" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Durasi Episode</label>
                            <input type="text" name="duration" placeholder="Contoh: 48m" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                        </div>

                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1">Link Embed Video URL</label>
                            <input type="text" name="video_url" placeholder="https://www.youtube.com/embed/..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                        <button type="button" @click="showEpisodeModal = false" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            <span>Simpan Episode Baru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
