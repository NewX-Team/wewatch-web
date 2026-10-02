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

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false, profileOpen: false }"
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
                    <a href="#" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition">
                        Home
                    </a>
                    <a href="#" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        Favorites
                    </a>
                    <a href="#" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                        <span>Subscribed</span>
                    </a>
                </div>
            </div>

            <!-- Right: Tier Badge Box, Searchbar & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Account Tier Box (Free Version Template) -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200">FREE</span>
                </div>

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
        <main class="pt-28 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16"
              x-data="{
                  selectedCategory: 'all'
              }">

            <!-- SECTION 1: Most Favorite & Top Rated Titles (Horizontal Scroll) -->
            <section class="space-y-5" x-data="{
                scrollLeft() { $refs.favContainer.scrollBy({ left: -340, behavior: 'smooth' }); },
                scrollRight() { $refs.favContainer.scrollBy({ left: 340, behavior: 'smooth' }); }
            }">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                            <span class="text-xs font-bold text-red-500 uppercase tracking-wider">Top Rated Catalogue</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight mt-0.5">
                            Most Favorite & Top Rated Movies
                        </h2>
                    </div>

                    <!-- Horizontal Scroll Arrows -->
                    <div class="flex items-center gap-2">
                        <button @click="scrollLeft()" class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        <button @click="scrollRight()" class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Scroll Track -->
                <div x-ref="favContainer" class="flex gap-6 overflow-x-auto snap-x scrollbar-none py-2 px-1">
                    <!-- Fav Card 1 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-md bg-red-600 text-white font-black text-xs shadow-md">
                                #1 FAVORITE
                            </div>
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[11px] font-bold border border-zinc-800">
                                99% Match
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Sci-Fi Series</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>2026 • Season 1</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fav Card 2 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-md bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-black text-xs shadow-md">
                                #2 FAVORITE
                            </div>
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[11px] font-bold border border-zinc-800">
                                97% Match
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Midnight Drift</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>2026 • Feature Movie</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fav Card 3 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-md bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-black text-xs shadow-md">
                                #3 FAVORITE
                            </div>
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[11px] font-bold border border-zinc-800">
                                98% Match
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>2026 • Feature Doc</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fav Card 4 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-md bg-zinc-950/90 text-zinc-200 border border-zinc-700 font-black text-xs shadow-md">
                                #4 FAVORITE
                            </div>
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[11px] font-bold border border-zinc-800">
                                95% Match
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Realm of Eldoria</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>2026 • Season 1</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.8</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: Newly Released & Admin Premieres (Horizontal Scroll) -->
            <section class="space-y-5" x-data="{
                scrollLeft() { $refs.newContainer.scrollBy({ left: -340, behavior: 'smooth' }); },
                scrollRight() { $refs.newContainer.scrollBy({ left: 340, behavior: 'smooth' }); }
            }">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase border border-emerald-500/30">Just Added</span>
                            <span class="text-xs font-bold text-zinc-400">Admin Uploads</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight mt-0.5">
                            Newly Released & Premieres
                        </h2>
                    </div>

                    <!-- Horizontal Scroll Arrows -->
                    <div class="flex items-center gap-2">
                        <button @click="scrollLeft()" class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        <button @click="scrollRight()" class="w-9 h-9 rounded-xl bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition shadow-md">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Scroll Track -->
                <div x-ref="newContainer" class="flex gap-6 overflow-x-auto snap-x scrollbar-none py-2 px-1">
                    <!-- New Card 1 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-emerald-600 text-white font-extrabold text-[10px] tracking-wider shadow">
                                JUST RELEASED
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Realm of Eldoria — Ep. 1</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>Uploaded Today</span>
                                <span class="font-mono text-zinc-300">54m</span>
                            </div>
                        </div>
                    </div>

                    <!-- New Card 2 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-emerald-600 text-white font-extrabold text-[10px] tracking-wider shadow">
                                JUST RELEASED
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>Uploaded 2h ago</span>
                                <span class="font-mono text-zinc-300">1h 24m</span>
                            </div>
                        </div>
                    </div>

                    <!-- New Card 3 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-red-600 text-white font-extrabold text-[10px] tracking-wider shadow">
                                NEW SEASON
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi Series</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Cyberpunk Shadows — Ep. 8</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>Uploaded Yesterday</span>
                                <span class="font-mono text-zinc-300">48m</span>
                            </div>
                        </div>
                    </div>

                    <!-- New Card 4 -->
                    <div class="w-64 sm:w-72 shrink-0 snap-start bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-emerald-600 text-white font-extrabold text-[10px] tracking-wider shadow">
                                JUST RELEASED
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                            <h3 class="font-bold text-white text-base mt-0.5 truncate group-hover:text-red-400 transition">Midnight Drift: Directors Cut</h3>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-2 border-t border-zinc-800/80 pt-2.5">
                                <span>Uploaded 2 days ago</span>
                                <span class="font-mono text-zinc-300">2h 05m</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 3: Explore All Movies Catalogue (Vertical Scroll Grid) -->
            <section class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-zinc-800/80 pb-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-red-500">Explore & Discover</span>
                        <h2 class="text-2xl font-black text-white tracking-tight mt-0.5">
                            Explore All Movies & Shows
                        </h2>
                    </div>

                    <!-- Category Filter Chips -->
                    <div class="flex flex-wrap items-center gap-2">
                        <button @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition">
                            All Genres
                        </button>
                        <button @click="selectedCategory = 'scifi'" :class="selectedCategory === 'scifi' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition">
                            Sci-Fi
                        </button>
                        <button @click="selectedCategory = 'action'" :class="selectedCategory === 'action' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition">
                            Action
                        </button>
                        <button @click="selectedCategory = 'doc'" :class="selectedCategory === 'doc' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition">
                            Documentary
                        </button>
                        <button @click="selectedCategory = 'fantasy'" :class="selectedCategory === 'fantasy' ? 'bg-red-600 text-white border-red-600' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition">
                            Fantasy
                        </button>
                    </div>
                </div>

                <!-- Vertical Scroll Grid Catalogue -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Catalogue Grid Item 1 -->
                    <div x-show="selectedCategory === 'all' || selectedCategory === 'scifi'" class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2 py-0.5 rounded bg-red-600 text-white font-bold text-[10px]">
                                4K ULTRA HD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi Series</span>
                            <h3 class="font-bold text-white text-base mt-1 group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">In a neon-soaked dystopian future, three operatives battle a mega-corporation.</p>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-4 border-t border-zinc-800/80 pt-3">
                                <span>8 Episodes</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catalogue Grid Item 2 -->
                    <div x-show="selectedCategory === 'all' || selectedCategory === 'action'" class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2 py-0.5 rounded bg-zinc-950/90 border border-zinc-700 text-zinc-200 font-bold text-[10px]">
                                FULL HD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                            <h3 class="font-bold text-white text-base mt-1 group-hover:text-red-400 transition">Midnight Drift</h3>
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">High-stakes underground racing through futuristic neon Tokyo streets.</p>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-4 border-t border-zinc-800/80 pt-3">
                                <span>1h 52m</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catalogue Grid Item 3 -->
                    <div x-show="selectedCategory === 'all' || selectedCategory === 'doc'" class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2 py-0.5 rounded bg-red-600 text-white font-bold text-[10px]">
                                4K ULTRA HD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                            <h3 class="font-bold text-white text-base mt-1 group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">Journey into the deepest marine trenches with bioluminescent creatures.</p>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-4 border-t border-zinc-800/80 pt-3">
                                <span>1h 24m</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catalogue Grid Item 4 -->
                    <div x-show="selectedCategory === 'all' || selectedCategory === 'fantasy'" class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card">
                        <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                            <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 px-2 py-0.5 rounded bg-zinc-950/90 border border-zinc-700 text-zinc-200 font-bold text-[10px]">
                                FULL HD
                            </div>
                            <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                    <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                            <h3 class="font-bold text-white text-base mt-1 group-hover:text-red-400 transition">Realm of Eldoria</h3>
                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">A dormant war reawakens as a young knight claims the ancient glowing citadel.</p>
                            <div class="flex items-center justify-between text-xs text-zinc-400 mt-4 border-t border-zinc-800/80 pt-3">
                                <span>10 Episodes</span>
                                <div class="flex items-center gap-1 font-bold text-zinc-200">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>4.8</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
