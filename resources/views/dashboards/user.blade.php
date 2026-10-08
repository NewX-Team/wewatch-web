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
                <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md transition group-hover:scale-105">
                        W
                    </div>
                    <span class="font-black text-lg tracking-tight text-white group-hover:text-red-500 transition hidden sm:inline">
                        WEWATCH
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-1 sm:space-x-2 text-xs font-bold">
                    <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition">
                        Home
                    </a>
                    <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        Favorites
                    </a>
                    <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                        <span>Subscribed ({{ Auth::user()->subscribedCreators()->count() }})</span>
                    </a>
                    <a href="{{ route('user.announcements') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-current text-amber-400 animate-pulse" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                        <span>Pengumuman</span>
                    </a>
                </div>
            </div>

            <!-- Right: Tier Badge Box, Bell Icon, Searchbar & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Notification Bell Button -->
                <a href="{{ route('user.announcements') }}" title="Pemberitahuan & Pengumuman Admin" class="p-2 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 text-zinc-300 hover:text-white transition relative group">
                    <svg class="w-4 h-4 fill-current group-hover:rotate-12 transition duration-300" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-red-600 border-2 border-zinc-950 animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-red-600 border-2 border-zinc-950"></span>
                </a>
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

            @if(isset($movies) && $movies->isEmpty())
                <!-- DARK SPATIAL USER CATALOGUE EMPTY STATE (When creators haven't published movies yet) -->
                <div class="max-w-3xl mx-auto my-12 bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-2xl space-y-6">
                    <!-- Ambient Spotlight Glows -->
                    <div class="absolute -top-24 -right-24 w-60 h-60 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Center Movie Reel Halo -->
                    <div class="relative z-10 w-24 h-24 rounded-full bg-zinc-950/80 border-2 border-red-600/40 flex items-center justify-center mx-auto shadow-2xl shadow-red-600/20 group">
                        <div class="absolute inset-0 rounded-full bg-red-600/20 blur-md animate-pulse"></div>
                        <svg class="w-10 h-10 text-red-500 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                        </svg>
                    </div>

                    <div class="relative z-10 space-y-3">
                        <span class="px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                            KATALOG SEMENTARA KOSONG
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                            Belum Ada Film yang Diterbitkan Kreator
                        </h2>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-lg mx-auto leading-relaxed">
                            Kreator Studio belum memasukkan atau mengunggah film sinematik di platform WeWatch. Jika Anda seorang Kreator, masuk ke Creator Studio untuk menerbitkan karya tayangan pertama Anda!
                        </p>
                    </div>

                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        @if(Auth::user()->isCreator() || Auth::user()->isSuperAdmin())
                            <a href="{{ route('creator.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                <span>Buka Creator Studio & Unggah Film</span>
                            </a>
                        @else
                            <a href="{{ route('subscription.index') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-zinc-950 font-black text-xs tracking-wide shadow-lg shadow-amber-500/20 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>Upgrade Membership & Daftar Kreator</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if(isset($movies) && $movies->isNotEmpty())
                <!-- PUBLISHED CREATOR MOVIES SECTION -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-800/80 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Karya Terbaru Kreator Studio</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-0.5">
                                Film & Serial Rilis Diterbitkan Kreator
                            </h2>
                        </div>
                        <span class="text-xs text-zinc-400 font-mono">{{ $movies->count() }} Film Rilis</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                        @foreach($movies as $movie)
                            <a href="{{ route('movies.show', $movie->slug ?: $movie->id) }}" class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-xl block">
                                <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                    <img src="{{ asset($movie->poster_url ?: 'images/hero_banner.jpg') }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[8px] font-extrabold uppercase shadow tracking-wider {{ $movie->isOngoing() ? 'bg-amber-500 text-zinc-950' : 'bg-emerald-600 text-white' }}">
                                        {{ $movie->status_label }}
                                    </div>

                                    <!-- Tier Badge & Quick Favorite Toggle -->
                                    <div class="absolute top-2 right-2 flex items-center gap-1.5 z-20" x-data="{ isFav: {{ $movie->isFavoritedBy(Auth::user()) ? 'true' : 'false' }} }">
                                        <button type="button"
                                                @click.prevent.stop="
                                                    fetch('{{ route('movies.toggle-favorite', $movie->id) }}', {
                                                        method: 'POST',
                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                                    }).then(r => r.json()).then(d => { if(d.status==='success') isFav = d.is_favorite; })
                                                "
                                                title="Tambah / Hapus Favorit"
                                                :class="isFav ? 'bg-red-600 text-white shadow-red-600/50 scale-105' : 'bg-zinc-950/80 text-zinc-400 hover:text-white border-zinc-800'"
                                                class="w-7 h-7 rounded-full border flex items-center justify-center backdrop-blur-md transition transform active:scale-75 shadow-lg">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>

                                        <div class="px-2 py-0.5 rounded bg-zinc-950/90 text-red-400 font-mono text-[9px] font-bold border border-zinc-800 uppercase">
                                            {{ $movie->access_tier }}
                                        </div>
                                    </div>

                                    <!-- Play Hover Overlay -->
                                    <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                        <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                            <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3.5 space-y-2 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $movie->genre }}</span>
                                        <h3 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $movie->title }}</h3>
                                    </div>

                                    <div class="pt-2 border-t border-zinc-800/80 space-y-2">
                                        @if($movie->creator)
                                            <div class="flex items-center gap-2 text-[11px] min-w-0">
                                                <div class="w-5 h-5 rounded-full bg-red-600 text-white font-black text-[9px] flex items-center justify-center shrink-0 overflow-hidden border border-zinc-800">
                                                    @if(!empty($movie->creator->avatar_url))
                                                        <img src="{{ asset($movie->creator->avatar_url) }}" alt="{{ $movie->creator->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($movie->creator->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <span class="text-zinc-300 font-semibold truncate hover:text-red-400 transition">
                                                    {{ $movie->creator->name }}
                                                </span>
                                                @if($movie->creator->isVerified())
                                                    <svg class="w-3.5 h-3.5 fill-blue-500 shrink-0" title="Kreator Terverifikasi" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between text-[10px] text-zinc-400">
                                            <span>{{ $movie->episodes->count() }} Episode</span>
                                            <div class="flex items-center gap-1 font-bold text-zinc-200">
                                                <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                <span>{{ $movie->rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif


        </main>
    </body>
</html>
