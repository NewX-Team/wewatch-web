<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pengumuman & Notifikasi Admin — {{ config('app.name', 'WeWatch') }}</title>

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
              announcementsList: {{ json_encode($announcements->map(function($a) {
                  return [
                      'id' => $a->id,
                      'title' => $a->title,
                      'content' => $a->content,
                      'type' => $a->type ?: 'info',
                      'type_label' => $a->type_label,
                      'type_badge_color' => $a->type_badge_color,
                      'target_role' => strtoupper($a->target_role ?: 'ALL'),
                      'created_at_human' => $a->created_at ? $a->created_at->diffForHumans() : 'Baru saja',
                      'created_at_formatted' => $a->created_at ? $a->created_at->format('d M Y, H:i') : '',
                      'is_read' => false,
                  ];
              })->values()) }},

              toggleRead(id) {
                  const item = this.announcementsList.find(a => a.id === id);
                  if (item) {
                      item.is_read = !item.is_read;
                  }
              },

              filteredAnnouncements() {
                  return this.announcementsList.filter(item => {
                      const matchesSearch = item.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                            item.content.toLowerCase().includes(this.searchQuery.toLowerCase());
                      if (!matchesSearch) return false;

                      if (this.activeFilter === 'all') return true;
                      return item.type === this.activeFilter;
                  });
              }
          }">

        <div>
            <!-- Toast Notification Popup System -->
            <x-toast-notification />

            <!-- Ambient Backdrop Light Spotlights -->
            <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
            <div class="fixed top-1/3 right-1/4 w-[700px] h-[350px] bg-amber-500/5 rounded-full blur-[180px] pointer-events-none z-0"></div>

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
                        <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Favorites</span>
                        </a>
                        <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Subscribed</span>
                        </a>
                        <a href="{{ route('user.announcements') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500 animate-pulse" viewBox="0 0 24 24">
                                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                            </svg>
                            <span>Notifications</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Tier Badge & Profile -->
                <div class="flex items-center space-x-3 shrink-0">
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
            <main class="pt-28 pb-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

                <!-- PAGE HEADER & SUMMARY STATS -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-zinc-800/80 pb-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[10px] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                <span>OFFICIAL ADMIN BROADCAST FEED</span>
                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Pusat Pemberitahuan & Pengumuman Admin
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-400 max-w-2xl leading-relaxed">
                            Informasi resmi, promosi membership, jadwal maintenance, serta pengumuman event terbaru dari SuperAdmin platform WeWatch.
                        </p>
                    </div>

                    <!-- Stat Chips -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl px-4 py-3 text-center space-y-0.5 backdrop-blur-xl shadow-lg">
                            <div class="text-2xl font-black text-red-500 font-mono tracking-tight">{{ $announcements->count() }}</div>
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Pengumuman</div>
                        </div>
                    </div>
                </div>

                <!-- SEARCH & CATEGORY FILTER CONTROL BAR -->
                <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 backdrop-blur-xl shadow-lg">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="Cari dalam notifikasi admin..."
                               class="w-full py-2.5 pl-9 pr-4 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 fill-current" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>

                    <!-- Filter Pill Tabs -->
                    <div class="flex flex-wrap items-center gap-2">
                        <button @click="activeFilter = 'all'"
                                :class="activeFilter === 'all' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Semua
                        </button>
                        <button @click="activeFilter = 'info'"
                                :class="activeFilter === 'info' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Informasi
                        </button>
                        <button @click="activeFilter = 'promo'"
                                :class="activeFilter === 'promo' ? 'bg-amber-500 text-zinc-950 font-black shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Promosi
                        </button>
                        <button @click="activeFilter = 'warning'"
                                :class="activeFilter === 'warning' ? 'bg-rose-600 text-white font-bold shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Peringatan
                        </button>
                        <button @click="activeFilter = 'event'"
                                :class="activeFilter === 'event' ? 'bg-purple-600 text-white font-bold shadow-lg' : 'bg-zinc-950 text-zinc-400 hover:text-white border-zinc-800'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold border transition">
                            Event
                        </button>
                    </div>
                </div>

                <!-- ANNOUNCEMENTS FEED LIST -->
                <template x-if="filteredAnnouncements().length > 0">
                    <div class="space-y-4">
                        <template x-for="item in filteredAnnouncements()" :key="item.id">
                            <div :class="item.is_read ? 'opacity-75 bg-zinc-900/60 border-zinc-800/60' : 'bg-zinc-900/90 border-zinc-800/90 hover:border-red-600/50 shadow-xl'"
                                 class="border rounded-2xl p-6 transition duration-300 space-y-4 relative overflow-hidden backdrop-blur-xl group">

                                <!-- Top Header Row: Admin Signature & Badges -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-zinc-800/80 pb-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Official Admin Avatar -->
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 to-rose-600 text-white font-black text-sm flex items-center justify-center shadow-lg border border-red-500/30 shrink-0">
                                            W
                                        </div>

                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <h4 class="font-extrabold text-white text-xs">WEWATCH OFFICIAL ADMIN</h4>
                                                <svg class="w-4 h-4 fill-blue-500 shrink-0" title="Terverifikasi Offical Admin" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            </div>
                                            <div class="text-[10px] text-zinc-400 font-mono" x-text="item.created_at_human + ' • ' + item.created_at_formatted"></div>
                                        </div>
                                    </div>

                                    <!-- Category Badge & Target Role -->
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-wider border"
                                              :class="item.type_badge_color"
                                              x-text="item.type_label">
                                        </span>
                                        <span class="px-2 py-0.5 rounded bg-zinc-950 text-zinc-400 font-mono text-[9px] border border-zinc-800 font-bold"
                                              x-text="'TARGET: ' + item.target_role">
                                        </span>
                                    </div>
                                </div>

                                <!-- Announcement Content Body -->
                                <div class="space-y-2">
                                    <h3 class="text-lg font-black text-white group-hover:text-red-400 transition" x-text="item.title"></h3>
                                    <p class="text-xs sm:text-sm text-zinc-300 whitespace-pre-line leading-relaxed font-normal" x-text="item.content"></p>
                                </div>

                                <!-- Card Bottom Controls -->
                                <div class="flex items-center justify-between pt-2 text-xs border-t border-zinc-800/80">
                                    <button @click="toggleRead(item.id)"
                                            class="text-[11px] font-bold text-zinc-400 hover:text-white transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4 fill-current" :class="item.is_read ? 'text-emerald-400' : 'text-zinc-500'" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
                                        <span x-text="item.is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca'"></span>
                                    </button>

                                    <span class="text-[10px] font-mono text-zinc-500">Official Broadcast #<span x-text="item.id"></span></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- EMPTY STATE (WHEN NO ANNOUNCEMENTS) -->
                <template x-if="filteredAnnouncements().length === 0">
                    <div class="max-w-3xl mx-auto my-12 bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-8 sm:p-14 text-center shadow-2xl relative overflow-hidden backdrop-blur-2xl space-y-6">
                        <div class="absolute -top-24 -right-24 w-60 h-60 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 w-24 h-24 rounded-full bg-zinc-950/80 border-2 border-red-600/40 flex items-center justify-center mx-auto shadow-2xl shadow-red-600/20 group">
                            <div class="absolute inset-0 rounded-full bg-red-600/20 blur-md animate-pulse"></div>
                            <svg class="w-10 h-10 text-red-500 fill-current relative z-10 transform group-hover:scale-110 transition duration-300" viewBox="0 0 24 24">
                                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                            </svg>
                        </div>

                        <div class="relative z-10 space-y-3">
                            <span class="px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                                TIDAK ADA NOTIFIKASI
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                                Belum Ada Pengumuman Baru
                            </h2>
                            <p class="text-xs sm:text-sm text-zinc-400 max-w-lg mx-auto leading-relaxed">
                                Saat ini belum ada pemberitahuan atau pengumuman baru dari SuperAdmin. Silakan periksa kembali halaman ini secara berkala untuk update informasi terbaru!
                            </p>
                        </div>

                        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                            <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <span>Kembali ke Beranda Utama</span>
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                            </a>
                        </div>
                    </div>
                </template>

            </main>
        </div>
    </body>
</html>
