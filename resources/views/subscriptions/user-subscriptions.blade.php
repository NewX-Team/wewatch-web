<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Channel Di-Subscribe — {{ config('app.name', 'WeWatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden"
          x-data="{
              searchQuery: '',
              sortBy: 'newest',
              creators: {{ json_encode($subscribedCreators->map(function($c) {
                  return [
                      'id' => $c->id,
                      'name' => $c->name,
                      'handle' => $c->handle ?: ('@'.Str::slug($c->name, '')),
                      'slug' => $c->handle ? ltrim($c->handle, '@') : $c->id,
                      'avatar' => $c->avatar_url,
                      'banner' => $c->banner_url ?: asset('images/hero_banner.jpg'),
                      'is_verified' => $c->isVerified(),
                      'subscribers' => $c->subscribersCountFormatted(),
                      'subscribers_count' => $c->subscribers_count ?? 0,
                      'movies_count' => $c->movies_count ?? 0,
                      'bio' => $c->bio ?: ('Channel Resmi Kreator '.$c->name.' di WeWatch Cinema. Menyajikan tayangan sinematik berkualitas tinggi.'),
                  ];
              })) }},
              filteredCreators() {
                  let list = this.creators.filter(c => {
                      let q = this.searchQuery.toLowerCase().trim();
                      return c.name.toLowerCase().includes(q) || c.handle.toLowerCase().includes(q) || c.bio.toLowerCase().includes(q);
                  });

                  if (this.sortBy === 'name') {
                      return list.sort((a, b) => a.name.localeCompare(b.name));
                  } else if (this.sortBy === 'subscribers') {
                      return list.sort((a, b) => b.subscribers_count - a.subscribers_count);
                  } else if (this.sortBy === 'movies') {
                      return list.sort((a, b) => b.movies_count - a.movies_count);
                  }

                  return list;
              }
          }">

        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/4 w-[800px] h-[400px] bg-emerald-600/10 rounded-full blur-[160px] pointer-events-none z-0"></div>
        <div class="fixed top-1/3 right-1/4 w-[700px] h-[350px] bg-red-600/10 rounded-full blur-[160px] pointer-events-none z-0"></div>

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-4xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-6xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Left: Brand Logo & Navigation Links -->
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
                    <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        Favorites
                    </a>
                    <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span>Subscribed</span>
                    </a>
                </div>
            </div>

            <!-- Right: Tier Badge Box & Profile -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Account Tier Box -->
                <a href="{{ route('subscription.index') }}" title="Upgrade Membership Plan" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-red-600/60 transition shadow-inner group cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-black tracking-wider uppercase text-zinc-200 group-hover:text-red-400 transition">{{ strtoupper(Auth::user()->getEffectiveSubscriptionTier()) }}</span>
                    <span class="text-[9px] font-bold text-red-500 bg-red-600/15 border border-red-600/30 px-1.5 py-0.5 rounded group-hover:bg-red-600 group-hover:text-white transition">UPGRADE</span>
                </a>

                <!-- Integrated Searchbar -->
                <div class="relative hidden md:block">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Cari saluran kreator..."
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

        <!-- Main Container -->
        <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

            <!-- PAGE HEADER & SUMMARY STATS -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-zinc-800/80 pb-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-extrabold text-[10px] uppercase tracking-wider flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>SALURAN KREATOR DI-SUBSCRIBE</span>
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        Koleksi Channel Kreator Favorit Kamu
                    </h1>
                    <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                        Kelola seluruh saluran studio kreator yang kamu ikuti. Dapatkan pemberitahuan rilis episode terbaru dan jelajahi karya sinematik pilihan mereka.
                    </p>
                </div>

                <!-- Summary Stat Chips -->
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl px-4 py-3 text-center space-y-0.5 backdrop-blur-xl shadow-lg">
                        <div class="text-2xl font-black text-white font-mono tracking-tight">{{ $subscribedCreators->count() }}</div>
                        <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Channel Di-subscribe</div>
                    </div>
                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl px-4 py-3 text-center space-y-0.5 backdrop-blur-xl shadow-lg">
                        <div class="text-2xl font-black text-amber-400 font-mono tracking-tight">{{ $subscribedCreators->sum('movies_count') }}</div>
                        <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Film Rilis</div>
                    </div>
                </div>
            </div>

            <!-- SEARCH & SORT CONTROLS BAR -->
            <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 backdrop-blur-xl shadow-lg">
                <!-- Search Input Mobile / Tablet -->
                <div class="relative flex-1">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Filter berdasarkan nama kreator, handle @, atau deskripsi studio..."
                           class="w-full py-2.5 pl-9 pr-4 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 fill-current" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs font-bold text-zinc-400">Urutkan:</span>
                    <select x-model="sortBy" class="bg-zinc-950 border border-zinc-800 rounded-xl py-2 px-3 text-xs text-zinc-200 font-bold focus:outline-none focus:border-red-600 transition">
                        <option value="newest">Terbaru Di-subscribe</option>
                        <option value="name">Nama Kreator (A-Z)</option>
                        <option value="subscribers">Subscriber Terbanyak</option>
                        <option value="movies">Film Diterbitkan Terbanyak</option>
                    </select>
                </div>
            </div>

            <!-- SUBSCRIBED CREATORS GRID -->
            <template x-if="filteredCreators().length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <template x-for="c in filteredCreators()" :key="'creator-' + c.id">
                        <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden shadow-2xl backdrop-blur-xl group hover:border-red-600/50 hover:scale-[1.02] transition duration-300 flex flex-col justify-between relative">

                            <!-- Top Banner Image Header -->
                            <div class="h-28 relative overflow-hidden bg-zinc-950">
                                <img :src="c.banner" :alt="c.name" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/40 to-transparent"></div>
                                
                                <div class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono text-[9px] font-bold border border-emerald-500/40 backdrop-blur-md">
                                    Subscribed
                                </div>
                            </div>

                            <!-- Creator Info Body -->
                            <div class="p-5 pt-0 space-y-4 relative z-10 flex-1 flex flex-col justify-between -mt-10">
                                <div class="space-y-3">
                                    <!-- Avatar & Title -->
                                    <div class="flex items-end gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-red-600 border-4 border-zinc-900 shadow-2xl flex items-center justify-center shrink-0 overflow-hidden text-white font-black text-xl">
                                            <template x-if="c.avatar">
                                                <img :src="c.avatar" :alt="c.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!c.avatar">
                                                <span x-text="c.name.charAt(0).toUpperCase()"></span>
                                            </template>
                                        </div>

                                        <div class="min-w-0 flex-1 pb-1">
                                            <div class="flex items-center gap-1.5">
                                                <h3 class="font-black text-white text-base truncate group-hover:text-red-400 transition" x-text="c.name"></h3>
                                                <template x-if="c.is_verified">
                                                    <svg class="w-4 h-4 fill-blue-500 shrink-0" title="Terverifikasi" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                </template>
                                            </div>
                                            <div class="text-xs text-zinc-400 font-mono truncate font-bold" x-text="c.handle"></div>
                                        </div>
                                    </div>

                                    <p class="text-xs text-zinc-300 line-clamp-2 leading-relaxed font-normal" x-text="c.bio"></p>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <!-- Stats Strip -->
                                    <div class="flex items-center justify-between text-[11px] text-zinc-400 bg-zinc-950/90 px-3 py-2 rounded-xl border border-zinc-800/80 font-mono font-bold">
                                        <span class="text-emerald-400" x-text="c.subscribers"></span>
                                        <span class="text-zinc-300" x-text="c.movies_count + ' Film'"></span>
                                    </div>

                                    <!-- Action Buttons: Open Channel & Unsubscribe Form -->
                                    <div class="flex items-center gap-2 pt-1">
                                        <a :href="'/creators/' + c.slug" class="flex-1 py-2.5 px-3 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs shadow-md shadow-red-600/20 transition text-center flex items-center justify-center gap-1.5">
                                            <span>Buka Channel</span>
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                        </a>

                                        <form method="POST" :action="'/creators/' + c.id + '/toggle-subscription'" class="inline" onsubmit="return confirm('Apakah kamu yakin ingin membatalkan subscribe channel ini?')">
                                            @csrf
                                            <button type="submit" class="py-2.5 px-3.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-zinc-400 hover:text-red-400 font-bold text-xs border border-zinc-800 transition" title="Batal Subscribe">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- EMPTY STATE (WHEN NO CREATORS SUBSCRIBED OR NO MATCHES) -->
            <template x-if="filteredCreators().length === 0">
                <div class="max-w-3xl mx-auto my-12 bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-2xl space-y-6">
                    <div class="absolute -top-24 -right-24 w-60 h-60 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 w-24 h-24 rounded-full bg-zinc-950/80 border-2 border-emerald-500/40 flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/20 group">
                        <div class="absolute inset-0 rounded-full bg-emerald-500/20 blur-md animate-pulse"></div>
                        <svg class="w-10 h-10 text-emerald-400 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                    </div>

                    <div class="relative z-10 space-y-3">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                            BELUM ADA CHANNEL DI-SUBSCRIBE
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                            Belum Ada Saluran Kreator Tersimpan
                        </h2>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-lg mx-auto leading-relaxed">
                            Kamu belum mensubscribe saluran kreator mana pun. Jelajahi katalog utama film sinematik dan klik tombol <strong class="text-white">"Subscribe"</strong> untuk mengikuti update rilis karya kreator favoritmu!
                        </p>
                    </div>

                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                            <span>Jelajahi Katalog Film Utama</span>
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                        </a>
                    </div>
                </div>
            </template>

        </main>
    </body>
</html>
