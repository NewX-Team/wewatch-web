<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Koleksi Favorit Saya — {{ config('app.name', 'WeWatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between">
        
        <div>
            <!-- Floating Popup Toast Notification System -->
            <x-toast-notification />

            <!-- Ambient Backdrop Light Spotlights -->
            <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
            <div class="fixed top-1/3 right-1/4 w-[600px] h-[300px] bg-rose-600/5 rounded-full blur-[160px] pointer-events-none z-0"></div>

            <!-- Floating Centered Morphing Navbar -->
            <nav x-data="{ isScrolled: false }"
                 @scroll.window="isScrolled = (window.scrollY > 30)"
                 :class="isScrolled
                     ? 'max-w-3xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                     : 'max-w-5xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
                 class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

                <!-- Left: Brand Logo & Links -->
                <div class="flex items-center gap-6">
                    <!-- Brand -->
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md transition group-hover:scale-105">
                            W
                        </div>
                        <span class="font-black text-lg tracking-tight text-white group-hover:text-red-500 transition hidden sm:inline">
                            WEWATCH
                        </span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-1 sm:space-x-2 text-xs font-bold">
                        <a href="{{ route('user.dashboard') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                            Home
                        </a>
                        <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Favorites</span>
                        </a>
                        <a href="{{ route('subscription.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Membership</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Tier Badge Box, Searchbar & Profile -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Account Tier Box -->
                    <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">FREE</span>
                        <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-zinc-700 transition">
                            <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl p-2 text-xs text-zinc-300 space-y-1 z-50" style="display: none;">
                            <div class="px-3 py-2 border-b border-zinc-800">
                                <div class="font-bold text-white truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-zinc-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Profile Settings</a>
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

            <!-- Main Page Content -->
            <main class="pt-28 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">

                <!-- HERO SECTION HEADER -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-zinc-800/80 pb-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[11px] uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>DAFTAR FAVORIT SAYA</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Koleksi Film Favorit
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                            Kumpulan film, serial sinematik, dan dokumenter favorit yang telah kamu simpan untuk ditonton kembali kapan saja.
                        </p>
                    </div>

                    <!-- Items Counter Badge -->
                    <div class="shrink-0 flex items-center gap-3 bg-zinc-900/80 border border-zinc-800 rounded-2xl px-4 py-2.5">
                        <span class="text-xs text-zinc-400 font-medium">Total Tersimpan:</span>
                        <span class="text-sm font-black text-red-400 font-mono">{{ count($favorites) }} Film</span>
                    </div>
                </div>

                <!-- CONTENT AREA: Empty State vs Favorites Grid -->
                @if($favorites->isEmpty())
                    <!-- STUNNING DARK SPATIAL EMPTY STATE CARD -->
                    <div class="max-w-2xl mx-auto my-8 bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-xl">
                        <!-- Ambient Spotlight Glows Inside Card -->
                        <div class="absolute -top-24 -right-24 w-56 h-56 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-56 h-56 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

                        <!-- Center Glowing Heart Icon Halo -->
                        <div class="relative z-10 w-24 h-24 rounded-full bg-red-600/10 border-2 border-red-600/30 flex items-center justify-center mx-auto mb-6 shadow-inner shadow-red-600/20 group">
                            <div class="absolute inset-0 rounded-full bg-red-600/20 blur-md animate-pulse"></div>
                            <svg class="w-10 h-10 text-red-500 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>

                        <!-- Text & Explanation -->
                        <div class="relative z-10 space-y-3 mb-8">
                            <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                Belum Ada Film Favorit
                            </h3>
                            <p class="text-xs sm:text-sm text-zinc-400 max-w-md mx-auto leading-relaxed">
                                Kamu belum menambahkan film atau serial ke daftar favoritmu. Jelajahi katalog WeWatch dan tekan tombol <strong class="text-zinc-200">"Add to Favorites"</strong> (❤️) pada film yang kamu sukai!
                            </p>
                        </div>

                        <!-- Action CTA Buttons -->
                        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <span>Jelajahi Katalog Film</span>
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                            </a>

                            <a href="{{ route('subscription.index') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-zinc-800/80 hover:bg-zinc-700 text-zinc-300 hover:text-white font-bold text-xs border border-zinc-700/60 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 fill-amber-400 shrink-0" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>Upgrade Membership</span>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- FAVORITES MOVIES GRID (Prepared for populated database items) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                        @foreach($favorites as $movie)
                            <a href="{{ route('movies.show', $movie->slug ?? $movie->id) }}" class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                                <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                    <img src="{{ asset($movie->poster_path ?? 'images/poster_action.jpg') }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-red-400 font-bold text-[10px] border border-zinc-800 flex items-center gap-1">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <span>Favorit</span>
                                    </div>
                                </div>
                                <div class="p-3 space-y-1">
                                    <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $movie->title }}</h3>
                                    <p class="text-[10px] text-zinc-400">{{ $movie->genre ?? 'Movie' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

            </main>
        </div>

        <!-- Sleek Footer -->
        <footer class="border-t border-zinc-900 bg-zinc-950/80 py-6 text-center text-xs text-zinc-500 relative z-10">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-red-600 text-white font-black text-[10px] flex items-center justify-center">W</span>
                    <span class="font-bold text-zinc-300">WEWATCH</span>
                    <span>&copy; {{ date('Y') }} All rights reserved.</span>
                </div>
                <div class="flex items-center gap-4 text-[11px]">
                    <a href="{{ route('user.dashboard') }}" class="hover:text-zinc-300 transition">Katalog</a>
                    <a href="{{ route('subscription.index') }}" class="hover:text-zinc-300 transition">Membership</a>
                    <a href="{{ route('profile.edit') }}" class="hover:text-zinc-300 transition">Profil</a>
                </div>
            </div>
        </footer>

    </body>
</html>
