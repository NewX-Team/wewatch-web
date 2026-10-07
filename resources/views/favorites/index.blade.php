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
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between"
          x-data="{
              activeFilter: 'all',
              searchQuery: '',
              savedItems: [],
              recommendations: [
                  { id: 'cyberpunk-shadows', title: 'Cyberpunk Shadows', genre: 'Sci-Fi Series', rating: '4.9', match: '99%', year: '2026', image: '{{ asset("images/hero_banner.jpg") }}', type: 'series' },
                  { id: 'midnight-drift', title: 'Midnight Drift', genre: 'Action Thriller', rating: '4.9', match: '97%', year: '2026', image: '{{ asset("images/poster_action.jpg") }}', type: 'movie' },
                  { id: 'deep-ocean-abyss', title: 'Deep Ocean Abyss 4K', genre: 'Documentary', rating: '5.0', match: '98%', year: '2026', image: '{{ asset("images/poster_documentary.jpg") }}', type: 'doc' },
                  { id: 'realm-of-eldoria', title: 'Realm of Eldoria', genre: 'Fantasy Epic', rating: '4.8', match: '95%', year: '2026', image: '{{ asset("images/poster_fantasy.jpg") }}', type: 'series' }
              ],
              toggleFavorite(item) {
                  const idx = this.savedItems.findIndex(i => i.id === item.id);
                  if (idx > -1) {
                      this.savedItems.splice(idx, 1);
                  } else {
                      this.savedItems.push(item);
                  }
              },
              isSaved(itemId) {
                  return this.savedItems.some(i => i.id === itemId);
              },
              addAllRecommendations() {
                  this.recommendations.forEach(item => {
                      if (!this.isSaved(item.id)) {
                          this.savedItems.push(item);
                      }
                  });
              },
              removeAllFavorites() {
                  this.savedItems = [];
              },
              get filteredSaved() {
                  return this.savedItems.filter(item => {
                      const matchesFilter = this.activeFilter === 'all' || item.type === this.activeFilter;
                      const matchesSearch = item.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || item.genre.toLowerCase().includes(this.searchQuery.toLowerCase());
                      return matchesFilter && matchesSearch;
                  });
              }
          }">

        <div>
            <!-- Floating Popup Toast Notification System -->
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

                <!-- Right: Tier Badge Box & Profile -->
                <div class="flex items-center space-x-3 shrink-0">
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
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 border-b border-zinc-800/80 pb-8">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[11px] uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Daftar Sinematik Tersimpan</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight">
                            Koleksi Film Favorit
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                            Simpan film pilihan, serial premium, dan dokumenter favoritmu. Akses tontonan tersimpan kapan saja secara instant.
                        </p>
                    </div>

                    <!-- Right Quick Stats & Live Demo Actions -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Counter Badge -->
                        <div class="flex items-center gap-3 bg-zinc-900/90 border border-zinc-800 rounded-2xl px-4 py-3 shadow-inner">
                            <span class="text-xs text-zinc-400 font-medium">Tersimpan:</span>
                            <span class="text-sm font-black text-red-400 font-mono" x-text="savedItems.length + ' Film'"></span>
                        </div>

                        <!-- One-Click Demo Toggle (Simulasikan Tambah Rekomendasi) -->
                        <button type="button"
                                @click="savedItems.length === 0 ? addAllRecommendations() : removeAllFavorites()"
                                class="px-4 py-3 rounded-2xl border text-xs font-extrabold transition flex items-center gap-2 shadow-lg"
                                :class="savedItems.length === 0 ? 'bg-red-600/20 hover:bg-red-600 text-white border-red-600/50 shadow-red-600/20' : 'bg-zinc-900 hover:bg-zinc-800 text-zinc-300 border-zinc-800'">
                            <svg class="w-4 h-4 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span x-text="savedItems.length === 0 ? '+ Simpan Semua Rekomendasi' : 'Kosongkan Favorit Saya'"></span>
                        </button>
                    </div>
                </div>

                <!-- FILTER BAR & SEARCH (Shown when items exist) -->
                <div x-show="savedItems.length > 0" x-transition class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-zinc-900/60 border border-zinc-800/80 rounded-2xl p-3.5 backdrop-blur-xl">
                    <!-- Category Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto">
                        <button type="button" @click="activeFilter = 'all'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap"
                                :class="activeFilter === 'all' ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800'">
                            Semua (<span x-text="savedItems.length"></span>)
                        </button>
                        <button type="button" @click="activeFilter = 'movie'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap"
                                :class="activeFilter === 'movie' ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800'">
                            Film Movie
                        </button>
                        <button type="button" @click="activeFilter = 'series'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap"
                                :class="activeFilter === 'series' ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800'">
                            Serial TV
                        </button>
                        <button type="button" @click="activeFilter = 'doc'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap"
                                :class="activeFilter === 'doc' ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800'">
                            Dokumenter
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-64">
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="Cari judul favorit..."
                               class="w-full py-2 pl-9 pr-4 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                        <svg class="w-3.5 h-3.5 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 fill-current" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>
                </div>

                <!-- DYNAMIC GRID: Saved Favorites (When items present) -->
                <div x-show="savedItems.length > 0" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="item in filteredSaved" :key="item.id">
                            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-xl flex flex-col justify-between">
                                <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 px-2 py-0.5 rounded bg-red-600 text-white font-extrabold text-[9px] shadow">
                                        FAVORIT SAYA
                                    </div>
                                    <div class="absolute top-3 right-3 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800" x-text="item.match"></div>

                                    <!-- Quick Remove Bookmark Floating Action -->
                                    <button type="button" @click="toggleFavorite(item)" title="Hapus dari favorit" class="absolute bottom-3 right-3 w-8 h-8 rounded-xl bg-zinc-950/80 hover:bg-red-600 text-red-400 hover:text-white border border-zinc-800 flex items-center justify-center transition shadow-lg backdrop-blur-md">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </button>
                                </div>

                                <div class="p-4 space-y-3">
                                    <div>
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide" x-text="item.genre"></span>
                                        <h3 class="font-bold text-white text-sm mt-0.5 truncate group-hover:text-red-400 transition" x-text="item.title"></h3>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] text-zinc-400 border-t border-zinc-800/80 pt-3">
                                        <span x-text="item.year"></span>
                                        <div class="flex items-center gap-1 font-bold text-zinc-200">
                                            <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                            <span x-text="item.rating"></span>
                                        </div>
                                    </div>

                                    <a :href="'/movies/' + item.id" class="w-full py-2.5 px-3 rounded-xl bg-zinc-800 hover:bg-red-600 text-white font-bold text-xs transition flex items-center justify-center gap-2">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span>Tonton Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STUNNING RICH EMPTY STATE HERO CARD (When savedItems is empty) -->
                <div x-show="savedItems.length === 0" x-transition class="space-y-12">
                    <div class="bg-gradient-to-b from-zinc-900/90 via-zinc-900/80 to-zinc-950 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-2xl max-w-3xl mx-auto">
                        <!-- Subtle Background Radial Glows -->
                        <div class="absolute -top-24 -right-24 w-60 h-60 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

                        <!-- Center Double Ring Glowing Heart Halo -->
                        <div class="relative z-10 w-24 h-24 rounded-full bg-zinc-950/80 border-2 border-red-600/40 flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-red-600/20 group">
                            <div class="absolute inset-0 rounded-full bg-red-600/20 blur-md animate-pulse"></div>
                            <svg class="w-10 h-10 text-red-500 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>

                        <!-- Text Information -->
                        <div class="relative z-10 space-y-3 mb-8">
                            <span class="px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                                DAFTAR ANDA MASIH KOSONG
                            </span>
                            <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                Belum Ada Film Favorit Tersimpan
                            </h3>
                            <p class="text-xs sm:text-sm text-zinc-400 max-w-md mx-auto leading-relaxed">
                                Temukan film dan serial menarik di bawah ini, klik tombol <strong class="text-white">"❤️ Tambah Favorit"</strong> pada kartu film untuk membangun koleksi favorit pribadimu!
                            </p>
                        </div>

                        <!-- CTA Actions -->
                        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <button type="button" @click="addAllRecommendations()" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                <span>+ Tambahkan Rekomendasi Instan</span>
                            </button>

                            <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white font-bold text-xs border border-zinc-700/60 transition flex items-center justify-center gap-2">
                                <span>Jelajahi Beranda Utama</span>
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Recommended Titles to Bookmark (Always accessible for interactive bookmarking) -->
                <section class="space-y-6 pt-6 border-t border-zinc-800/80">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Rekomendasi Untuk Favoritmu</span>
                            </div>
                            <h2 class="text-xl font-extrabold text-white tracking-tight mt-0.5">
                                Film & Serial Trending Pilihan
                            </h2>
                        </div>

                        <span class="text-xs text-zinc-500 font-mono hidden sm:inline">Klik ❤️ untuk simpan/hapus</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="item in recommendations" :key="'rec-' + item.id">
                            <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl overflow-hidden group hover:border-red-600/60 hover:scale-[1.02] transition duration-300 shadow-lg flex flex-col justify-between">
                                <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    
                                    <div class="absolute top-3 right-3 px-2 py-0.5 rounded bg-zinc-950/90 text-emerald-400 font-mono text-[9px] font-bold border border-zinc-800" x-text="item.match"></div>

                                    <!-- Quick Bookmark Toggle Floating Button -->
                                    <button type="button"
                                            @click="toggleFavorite(item)"
                                            :title="isSaved(item.id) ? 'Hapus dari favorit' : 'Tambah ke favorit'"
                                            class="absolute bottom-3 right-3 px-3 py-1.5 rounded-xl border text-xs font-extrabold flex items-center gap-1.5 transition shadow-xl backdrop-blur-md"
                                            :class="isSaved(item.id) ? 'bg-red-600 text-white border-red-500' : 'bg-zinc-950/85 hover:bg-red-600 text-zinc-300 hover:text-white border-zinc-800'">
                                        <svg class="w-3.5 h-3.5 fill-current" :class="isSaved(item.id) ? 'text-white' : 'text-red-500'" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <span x-text="isSaved(item.id) ? 'Tersimpan' : 'Favoritkan'"></span>
                                    </button>
                                </div>

                                <div class="p-4 space-y-3">
                                    <div>
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide" x-text="item.genre"></span>
                                        <h3 class="font-bold text-white text-sm mt-0.5 truncate group-hover:text-red-400 transition" x-text="item.title"></h3>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] text-zinc-400 border-t border-zinc-800/80 pt-3">
                                        <span x-text="item.year"></span>
                                        <div class="flex items-center gap-1 font-bold text-zinc-200">
                                            <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                            <span x-text="item.rating"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

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
                    <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="hover:text-zinc-300 transition">Katalog</a>
                    <a href="{{ route('subscription.index') }}" class="hover:text-zinc-300 transition">Membership</a>
                    <a href="{{ route('profile.edit') }}" class="hover:text-zinc-300 transition">Profil</a>
                </div>
            </div>
        </footer>

    </body>
</html>
