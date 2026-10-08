<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Koleksi Film Favorit Saya — {{ config('app.name', 'WeWatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between"
          x-data="{
              activeFilter: 'all',
              searchQuery: '',
              sortBy: 'latest',
              favoriteMovies: {{ json_encode($favorites->map(function($m) {
                  return [
                      'id' => $m->id,
                      'slug' => $m->slug ?: $m->id,
                      'title' => $m->title,
                      'genre' => $m->genre ?: 'Sinema',
                      'genre_key' => strtolower($m->genre ?: 'sinema'),
                      'status' => $m->status_label,
                      'is_ongoing' => $m->isOngoing(),
                      'access_tier' => strtoupper($m->access_tier ?: 'FREE'),
                      'rating' => (float) ($m->rating ?: 4.9),
                      'year' => $m->release_year ?: date('Y'),
                      'poster' => asset($m->poster_url ?: 'images/hero_banner.jpg'),
                      'episodes_count' => $m->episodes->count(),
                      'creator_name' => $m->creator ? $m->creator->name : 'Kreator Studio',
                      'creator_avatar' => $m->creator ? $m->creator->avatar_url : null,
                      'creator_verified' => $m->creator ? $m->creator->isVerified() : false,
                      'creator_handle' => $m->creator ? ($m->creator->handle ?: ('@'.Str::slug($m->creator->name, ''))) : '@kreator',
                  ];
              })->values()) }},

              toggleFavorite(movieId) {
                  fetch('/movies/' + movieId + '/toggle-favorite', {
                      method: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                          'Accept': 'application/json',
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  })
                  .then(res => res.json())
                  .then(data => {
                      if (data.status === 'success') {
                          this.favoriteMovies = this.favoriteMovies.filter(m => m.id !== movieId);
                      }
                  });
              },

              filteredFavorites() {
                  let list = this.favoriteMovies.filter(item => {
                      const matchesSearch = item.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                            item.genre.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                            item.creator_name.toLowerCase().includes(this.searchQuery.toLowerCase());

                      if (!matchesSearch) return false;

                      if (this.activeFilter === 'all') return true;
                      if (this.activeFilter === 'ongoing') return item.is_ongoing;
                      if (this.activeFilter === 'completed') return !item.is_ongoing;
                      return item.genre_key.includes(this.activeFilter);
                  });

                  if (this.sortBy === 'rating') {
                      list.sort((a, b) => b.rating - a.rating);
                  } else if (this.sortBy === 'title') {
                      list.sort((a, b) => a.title.localeCompare(b.title));
                  }
                  return list;
              }
          }">

        <div>
            <!-- Toast Notifications -->
            <x-toast-notification />

            <!-- Ambient Backdrop Light Spotlights -->
            <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
            <div class="fixed top-1/3 right-1/4 w-[700px] h-[350px] bg-rose-600/5 rounded-full blur-[180px] pointer-events-none z-0"></div>

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
                    <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="flex items-center gap-2.5 group shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md transition group-hover:scale-105">
                            W
                        </div>
                        <span class="font-black text-lg tracking-tight text-white group-hover:text-red-500 transition hidden sm:inline">
                            WEWATCH
                        </span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-1 sm:space-x-2 text-xs font-bold">
                        <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                            Home
                        </a>
                        <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Favorites</span>
                        </a>
                        <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Subscribed ({{ Auth::user()->subscribedCreators()->count() }})</span>
                        </a>
                        <a href="{{ route('subscription.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Membership</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Tier Badge Box, Bell Icon & Profile -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Notification Bell Button -->
                    <a href="{{ route('user.announcements') }}" title="Pemberitahuan & Pengumuman Admin" class="p-2 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 text-zinc-300 hover:text-white transition relative group">
                        <svg class="w-4 h-4 fill-current group-hover:rotate-12 transition duration-300" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-red-600 border-2 border-zinc-950 animate-ping"></span>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-red-600 border-2 border-zinc-950"></span>
                    </a>

                    <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">{{ strtoupper(Auth::user()->getEffectiveSubscriptionTier()) }}</span>
                        <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-zinc-700 transition">
                            <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl p-2 text-xs text-zinc-300 space-y-1 z-50" style="display: none;">
                            <div class="px-3 py-2 border-b border-zinc-800">
                                <div class="font-bold text-white truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-zinc-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Pengaturan Profil</a>
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

            <!-- Main Content Container -->
            <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

                <!-- PAGE HEADER & STATS -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-zinc-800/80 pb-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3 h-3 fill-current text-red-500 animate-pulse" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                <span>KOLEKSI FAVORIT TERSIMPAN</span>
                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Koleksi Film & Serial Disukai
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                            Simpan tayangan sinematik terbaik dari seluruh studio kreator favoritmu. Tonton ulang episode kapan pun kamu mau.
                        </p>
                    </div>

                    <!-- Stat Chips -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl px-4 py-3 text-center space-y-0.5 backdrop-blur-xl shadow-lg">
                            <div class="text-2xl font-black text-red-500 font-mono tracking-tight" x-text="favoriteMovies.length"></div>
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Film Favorit</div>
                        </div>
                    </div>
                </div>

                <!-- SEARCH & CATEGORY FILTER CONTROL BAR -->
                <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 backdrop-blur-xl shadow-lg">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="Cari dalam film favoritmu..."
                               class="w-full py-2.5 pl-9 pr-4 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 fill-current" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>

                    <!-- Filter Pill Tabs & Sort -->
                    <div class="flex flex-wrap items-center gap-2">
                        <button @click="activeFilter = 'all'"
                                :class="activeFilter === 'all' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Semua ({{ count($favorites) }})
                        </button>
                        <button @click="activeFilter = 'ongoing'"
                                :class="activeFilter === 'ongoing' ? 'bg-amber-500 text-zinc-950 font-black shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Ongoing
                        </button>
                        <button @click="activeFilter = 'completed'"
                                :class="activeFilter === 'completed' ? 'bg-emerald-600 text-white font-bold shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Completed
                        </button>

                        <select x-model="sortBy" class="py-2 px-3 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-300 focus:outline-none focus:border-red-600 font-bold cursor-pointer">
                            <option value="latest">Terbaru Ditambahkan</option>
                            <option value="rating">Rating Tertinggi</option>
                            <option value="title">Judul A-Z</option>
                        </select>
                    </div>
                </div>

                <!-- FAVORITED MOVIES GRID -->
                <template x-if="filteredFavorites().length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                        <template x-for="item in filteredFavorites()" :key="item.id">
                            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-xl flex flex-col justify-between">
                                <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                    <img :src="item.poster" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                                    <!-- Status Badge -->
                                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[8px] font-extrabold uppercase shadow tracking-wider"
                                         :class="item.is_ongoing ? 'bg-amber-500 text-zinc-950' : 'bg-emerald-600 text-white'"
                                         x-text="item.status">
                                    </div>

                                    <!-- Quick Remove Favorite Button -->
                                    <button type="button"
                                            @click.prevent="toggleFavorite(item.id)"
                                            title="Hapus dari Favorit"
                                            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600 text-white shadow-lg shadow-red-600/50 flex items-center justify-center border border-red-500/50 hover:scale-110 active:scale-75 transition duration-200 z-20">
                                        <svg class="w-4 h-4 fill-current animate-pulse" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </button>

                                    <!-- Play Hover Link -->
                                    <a :href="'/movies/' + item.slug" class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                        <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                            <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </a>
                                </div>

                                <div class="p-3.5 space-y-2 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide" x-text="item.genre"></span>
                                        <h3 class="font-black text-white text-xs truncate group-hover:text-red-400 transition" x-text="item.title"></h3>
                                    </div>

                                    <div class="pt-2 border-t border-zinc-800/80 space-y-2">
                                        <div class="flex items-center gap-2 text-[11px] min-w-0">
                                            <div class="w-5 h-5 rounded-full bg-red-600 text-white font-black text-[9px] flex items-center justify-center shrink-0 overflow-hidden border border-zinc-800">
                                                <template x-if="item.creator_avatar">
                                                    <img :src="item.creator_avatar" :alt="item.creator_name" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.creator_avatar">
                                                    <span x-text="item.creator_name.charAt(0).toUpperCase()"></span>
                                                </template>
                                            </div>
                                            <span class="text-zinc-300 font-semibold truncate hover:text-red-400 transition" x-text="item.creator_name"></span>
                                            <template x-if="item.creator_verified">
                                                <svg class="w-3.5 h-3.5 fill-blue-500 shrink-0" title="Kreator Terverifikasi" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            </template>
                                        </div>

                                        <div class="flex items-center justify-between text-[10px] text-zinc-400">
                                            <span x-text="item.episodes_count + ' Episode'"></span>
                                            <div class="flex items-center gap-1 font-bold text-zinc-200">
                                                <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                <span x-text="item.rating"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- EMPTY STATE (WHEN NO FAVORITED MOVIES OR NO SEARCH MATCHES) -->
                <template x-if="filteredFavorites().length === 0">
                    <div class="max-w-3xl mx-auto my-12 bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-2xl space-y-6">
                        <div class="absolute -top-24 -right-24 w-60 h-60 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 w-24 h-24 rounded-full bg-zinc-950/80 border-2 border-red-600/40 flex items-center justify-center mx-auto shadow-2xl shadow-red-600/20 group">
                            <div class="absolute inset-0 rounded-full bg-red-600/20 blur-md animate-pulse"></div>
                            <svg class="w-10 h-10 text-red-500 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>

                        <div class="relative z-10 space-y-3">
                            <span class="px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                                BELUM ADA FILM FAVORIT
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                                Belum Ada Tayangan Disukai
                            </h2>
                            <p class="text-xs sm:text-sm text-zinc-400 max-w-lg mx-auto leading-relaxed">
                                Kamu belum menyimpan film favorit. Jelajahi katalog utama film sinematik dan tekan ikon <strong class="text-red-400 font-bold">Hati ♥</strong> pada kartu film untuk menyimpannya di sini!
                            </p>
                        </div>

                        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                            <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <span>Jelajahi Beranda Utama</span>
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                            </a>
                        </div>
                    </div>
                </template>

                <!-- RECOMMENDED REAL MOVIES SECTION -->
                @if(isset($recommendations) && $recommendations->isNotEmpty())
                    <section class="space-y-4 pt-8 border-t border-zinc-800/80">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                    <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Rekomendasi Untuk Favoritmu</span>
                                </div>
                                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-0.5">
                                    Film Pilihan Diterbitkan Kreator
                                </h2>
                            </div>
                            <a href="{{ route('user.dashboard') }}" class="text-xs text-red-400 hover:text-red-300 font-bold transition">Lihat Semua →</a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($recommendations as $rec)
                                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-xl flex flex-col justify-between"
                                     x-data="{ isFav: false }">
                                    <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                        <img src="{{ asset($rec->poster_url ?: 'images/hero_banner.jpg') }}" alt="{{ $rec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                                        <!-- Heart Save Button -->
                                        <button type="button"
                                                @click.prevent="
                                                    fetch('{{ route('movies.toggle-favorite', $rec->id) }}', {
                                                        method: 'POST',
                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                                    }).then(r => r.json()).then(d => {
                                                        if(d.status==='success') {
                                                            isFav = d.is_favorite;
                                                            window.location.reload();
                                                        }
                                                    })
                                                "
                                                title="Tambah ke Favorit"
                                                :class="isFav ? 'bg-red-600 text-white' : 'bg-zinc-950/80 text-zinc-400 hover:text-white border-zinc-800'"
                                                class="absolute top-2 right-2 w-7 h-7 rounded-full border flex items-center justify-center backdrop-blur-md transition transform active:scale-75 shadow-lg z-20">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>

                                        <a href="{{ route('movies.show', $rec->slug ?: $rec->id) }}" class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                            <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                                <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="p-3 space-y-1.5">
                                        <span class="text-[9px] font-bold text-red-500 uppercase tracking-wide">{{ $rec->genre }}</span>
                                        <h4 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $rec->title }}</h4>
                                        <div class="flex items-center justify-between text-[10px] text-zinc-400 pt-1 border-t border-zinc-800">
                                            <span>{{ $rec->episodes->count() }} Ep</span>
                                            <div class="flex items-center gap-0.5 font-bold text-zinc-200">
                                                <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                <span>{{ $rec->rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

            </main>
        </div>
    </body>
</html>
