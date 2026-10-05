<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeWatch') }} — User Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white relative overflow-x-hidden min-h-screen">
        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Sequential Announcement Broadcast Popup System -->
        <x-announcement-popup :announcements="$announcements ?? []" />

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

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
                    <a href="{{ route('user.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition">
                        Home
                    </a>
                    <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        Favorites
                    </a>
                    <a href="#" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                        <span>Subscribed</span>
                    </a>
                </div>
            </div>

            <!-- Right: Tier Badge Box, Searchbar & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Account Tier Box (Clickable to Upgrade Membership Page) -->
                <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">FREE</span>
                    <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                </a>

                <!-- Integrated Searchbar -->
                <div class="relative hidden md:block">
                    <input type="text"
                           placeholder="Search titles, creators..."
                           class="w-44 lg:w-56 py-1.5 pl-8 pr-3 text-xs rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    <svg class="w-3.5 h-3.5 text-zinc-500 absolute left-2.5 top-1/2 -translate-y-1/2 fill-current" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>

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

        <!-- Main Dashboard Catalogue -->
        <main class="pt-24 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-12"
              x-data="{
                  selectedCategory: 'all'
              }">

            <!-- SECTION 1: Most Favorite & Top Rated Titles (Seamless Infinite Auto-Scroll Carousel) -->
            <section class="space-y-4" x-data="{
                isPaused: false,
                accum: 0,
                autoScroll() {
                    if (!this.isPaused && this.$refs.favContainer && this.$refs.set1) {
                        const container = this.$refs.favContainer;
                        const setWidth = this.$refs.set1.offsetWidth;
                        if (setWidth > 0) {
                            this.accum += 0.8;
                            if (this.accum >= 1) {
                                const movePx = Math.floor(this.accum);
                                this.accum -= movePx;
                                container.scrollLeft += movePx;
                                if (container.scrollLeft >= setWidth) {
                                    container.scrollLeft -= setWidth;
                                }
                            }
                        }
                    }
                    requestAnimationFrame(() => this.autoScroll());
                },
                scrollLeft() {
                    this.isPaused = true;
                    const container = this.$refs.favContainer;
                    const setWidth = this.$refs.set1 ? this.$refs.set1.offsetWidth : 1000;
                    container.scrollBy({ left: -260, behavior: 'smooth' });
                    setTimeout(() => {
                        if (container.scrollLeft <= 0) {
                            container.scrollLeft += setWidth;
                        }
                    }, 350);
                },
                scrollRight() {
                    this.isPaused = true;
                    const container = this.$refs.favContainer;
                    const setWidth = this.$refs.set1 ? this.$refs.set1.offsetWidth : 1000;
                    container.scrollBy({ left: 260, behavior: 'smooth' });
                    setTimeout(() => {
                        if (container.scrollLeft >= setWidth) {
                            container.scrollLeft -= setWidth;
                        }
                    }, 350);
                }
            }" x-init="requestAnimationFrame(() => autoScroll())">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Top Rated Catalogue</span>
                            <span x-show="!isPaused" class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-mono flex items-center gap-1 transition">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                Auto-Scrolling
                            </span>
                            <span x-show="isPaused" class="px-2 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] font-mono transition" style="display: none;">
                                Paused on Hover
                            </span>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight mt-0.5">
                            Most Favorite & Top Rated Movies
                        </h2>
                    </div>

                    <!-- Horizontal Scroll Arrows -->
                    <div class="flex items-center gap-1.5">
                        <button @click="scrollLeft()" title="Scroll Left" class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        <button @click="scrollRight()" title="Scroll Right" class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Infinite Scroll Track with Hover Pause -->
                <div x-ref="favContainer"
                     @mouseenter="isPaused = true"
                     @mouseleave="isPaused = false"
                     class="flex overflow-x-auto custom-scrollbar pt-1.5 pb-3 px-0.5 cursor-grab active:cursor-grabbing select-none">

                    <!-- CARD SET 1 (x-ref="set1") -->
                    <div x-ref="set1" class="flex gap-4 pr-4 shrink-0">
                        <!-- Card 1 -->
                        <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[9px] shadow">
                                    #1 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    99% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Sci-Fi Series</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="{{ route('movies.show', 'midnight-drift') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #2 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    97% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Midnight Drift</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Movie</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <a href="{{ route('movies.show', 'deep-ocean-abyss') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #3 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    98% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Doc</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>5.0</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 4 -->
                        <a href="{{ route('movies.show', 'realm-of-eldoria') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #4 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    95% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Realm of Eldoria</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.8</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- CARD SET 2 (Duplicate 1 for Seamless Infinite Transition) -->
                    <div class="flex gap-4 pr-4 shrink-0">
                        <!-- Card 1 -->
                        <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[9px] shadow">
                                    #1 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    99% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Sci-Fi Series</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="{{ route('movies.show', 'midnight-drift') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #2 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    97% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Midnight Drift</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Movie</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <a href="{{ route('movies.show', 'deep-ocean-abyss') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #3 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    98% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Doc</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>5.0</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 4 -->
                        <a href="{{ route('movies.show', 'realm-of-eldoria') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #4 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    95% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Realm of Eldoria</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.8</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- CARD SET 3 (Duplicate 2 for Extra Scroll Runway on Ultra-Wide Monitors) -->
                    <div class="flex gap-4 pr-4 shrink-0">
                        <!-- Card 1 -->
                        <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[9px] shadow">
                                    #1 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    99% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Sci-Fi Series</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="{{ route('movies.show', 'midnight-drift') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #2 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    97% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Midnight Drift</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Movie</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <a href="{{ route('movies.show', 'deep-ocean-abyss') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #3 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    98% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • Doc</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>5.0</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Card 4 -->
                        <a href="{{ route('movies.show', 'realm-of-eldoria') }}" class="w-52 sm:w-60 shrink-0 bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-extrabold text-[9px] shadow">
                                    #4 FAVORITE
                                </div>
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800">
                                    95% Match
                                </div>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                                <h3 class="font-bold text-white text-xs mt-0.5 truncate group-hover:text-red-400 transition">Realm of Eldoria</h3>
                                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                    <span>2026 • S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.8</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: Newly Released & Admin Premieres (Horizontal Scroll - Compact Portrait Posters) -->
            <section class="space-y-4" x-data="{
                scrollLeft() { $refs.newContainer.scrollBy({ left: -240, behavior: 'smooth' }); },
                scrollRight() { $refs.newContainer.scrollBy({ left: 240, behavior: 'smooth' }); }
            }">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[9px] font-bold uppercase border border-emerald-500/30">Just Added</span>
                            <span class="text-[11px] font-bold text-zinc-400">Admin Uploads</span>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight mt-0.5">
                            Newly Released & Premieres
                        </h2>
                    </div>

                    <!-- Horizontal Scroll Arrows -->
                    <div class="flex items-center gap-1.5">
                        <button @click="scrollLeft()" class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        <button @click="scrollRight()" class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Scroll Track (Compact Poster Size w-36/w-40) -->
                <div x-ref="newContainer" class="flex gap-4 overflow-x-auto snap-x custom-scrollbar pt-1.5 pb-3 px-0.5">
                    <!-- New Card 1 -->
                    <a href="{{ route('movies.show', 'realm-of-eldoria') }}" class="w-36 sm:w-40 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[8px] tracking-wider shadow">
                                NEW
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <span class="text-[9px] font-bold text-amber-500 uppercase tracking-wide">Fantasy</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">Realm of Eldoria</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-1.5 border-t border-zinc-800/80 pt-1.5">
                                <span>Today</span>
                                <span class="font-mono text-zinc-300">54m</span>
                            </div>
                        </div>
                    </a>

                    <!-- New Card 2 -->
                    <a href="{{ route('movies.show', 'deep-ocean-abyss') }}" class="w-36 sm:w-40 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[8px] tracking-wider shadow">
                                NEW
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <span class="text-[9px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">Deep Ocean Abyss</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-1.5 border-t border-zinc-800/80 pt-1.5">
                                <span>2h ago</span>
                                <span class="font-mono text-zinc-300">1h 24m</span>
                            </div>
                        </div>
                    </a>

                    <!-- New Card 3 -->
                    <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="w-36 sm:w-40 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[8px] tracking-wider shadow">
                                NEW EP
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <span class="text-[9px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-1.5 border-t border-zinc-800/80 pt-1.5">
                                <span>Yesterday</span>
                                <span class="font-mono text-zinc-300">48m</span>
                            </div>
                        </div>
                    </a>

                    <!-- New Card 4 -->
                    <a href="{{ route('movies.show', 'midnight-drift') }}" class="w-36 sm:w-40 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[8px] tracking-wider shadow">
                                NEW
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <span class="text-[9px] font-bold text-red-500 uppercase tracking-wide">Action</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">Midnight Drift</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-1.5 border-t border-zinc-800/80 pt-1.5">
                                <span>2 days ago</span>
                                <span class="font-mono text-zinc-300">2h 05m</span>
                            </div>
                        </div>
                    </a>
                </div>
            </section>

            <!-- SECTION 3: Explore All Movies Catalogue (Vertical Scroll Grid - 6 Columns High Density) -->
            <section class="space-y-5">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 border-b border-zinc-800/80 pb-4">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-red-500">Explore & Discover</span>
                        <h2 class="text-xl font-extrabold text-white tracking-tight mt-0.5">
                            Explore All Movies & Shows
                        </h2>
                    </div>

                    <!-- Category Filter Chips -->
                    <div class="flex flex-wrap items-center gap-1.5">
                        <button @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3 py-1 rounded-lg text-xs font-bold border transition">
                            All
                        </button>
                        <button @click="selectedCategory = 'scifi'" :class="selectedCategory === 'scifi' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3 py-1 rounded-lg text-xs font-bold border transition">
                            Sci-Fi
                        </button>
                        <button @click="selectedCategory = 'action'" :class="selectedCategory === 'action' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3 py-1 rounded-lg text-xs font-bold border transition">
                            Action
                        </button>
                        <button @click="selectedCategory = 'doc'" :class="selectedCategory === 'doc' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3 py-1 rounded-lg text-xs font-bold border transition">
                            Documentary
                        </button>
                        <button @click="selectedCategory = 'fantasy'" :class="selectedCategory === 'fantasy' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3 py-1 rounded-lg text-xs font-bold border transition">
                            Fantasy
                        </button>
                    </div>
                </div>

                <!-- Vertical Scroll Grid Catalogue (Higher Column Density grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    <!-- Catalogue Grid Item 1 -->
                    <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" x-show="selectedCategory === 'all' || selectedCategory === 'scifi'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-red-600 text-white font-extrabold text-[8px]">
                                4K UHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Cyberpunk Shadows</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>8 Ep</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Catalogue Grid Item 2 -->
                    <a href="{{ route('movies.show', 'midnight-drift') }}" x-show="selectedCategory === 'all' || selectedCategory === 'action'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-zinc-950/90 border border-zinc-700 text-zinc-200 font-extrabold text-[8px]">
                                FHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-red-500 uppercase tracking-wide">Action</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Midnight Drift</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>1h 52m</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Catalogue Grid Item 3 -->
                    <a href="{{ route('movies.show', 'deep-ocean-abyss') }}" x-show="selectedCategory === 'all' || selectedCategory === 'doc'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-red-600 text-white font-extrabold text-[8px]">
                                4K UHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Deep Ocean Abyss</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>1h 24m</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Catalogue Grid Item 4 -->
                    <a href="{{ route('movies.show', 'realm-of-eldoria') }}" x-show="selectedCategory === 'all' || selectedCategory === 'fantasy'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-zinc-950/90 border border-zinc-700 text-zinc-200 font-extrabold text-[8px]">
                                FHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-amber-500 uppercase tracking-wide">Fantasy</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Realm of Eldoria</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>10 Ep</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.8</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Catalogue Grid Item 5 -->
                    <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" x-show="selectedCategory === 'all' || selectedCategory === 'scifi'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Neon Horizon" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-red-600 text-white font-extrabold text-[8px]">
                                4K UHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Neon Horizon 2099</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>2h 10m</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.7</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Catalogue Grid Item 6 -->
                    <a href="{{ route('movies.show', 'midnight-drift') }}" x-show="selectedCategory === 'all' || selectedCategory === 'action'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-1 shadow-sm block">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Vengeance Protocol" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-zinc-950/90 border border-zinc-700 text-zinc-200 font-extrabold text-[8px]">
                                FHD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] font-bold text-red-500 uppercase tracking-wide">Action</span>
                            <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition mt-0.5">Vengeance Protocol</h3>
                            <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2">
                                <span>1h 45m</span>
                                <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.6</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </section>
        </main>
    </body>
</html>
