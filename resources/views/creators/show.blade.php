<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $creator['name'] }} — Channel Kreator | WeWatch</title>

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
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[900px] h-[450px] bg-red-600/10 rounded-full blur-[160px] pointer-events-none z-0"></div>

        <!-- Floating Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-3xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-5xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Brand & Back Button -->
            <div class="flex items-center gap-4">
                <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>{{ Auth::user()->isSuperAdmin() ? 'Kembali ke Admin Console' : (Auth::user()->isCreator() ? 'Kembali ke Studio Hub' : 'Kembali ke Dashboard') }}</span>
                </a>
            </div>

            <!-- Profile Info -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono text-zinc-400 font-bold hidden sm:inline">{{ Auth::user()->name }}</span>
                <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </nav>

        <!-- Main YouTube-Style Creator Channel Container -->
        <main class="pt-20 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8"
              x-data="{
                  activeTab: 'uploads',
                  isSubscribed: {{ $creator['is_subscribed'] ? 'true' : 'false' }},
                  subscribersCount: '{{ $creator['subscribers'] }}',
                  toggleSubscribe() {
                      fetch('{{ route('creators.toggle-subscription', $creator['id']) }}', {
                          method: 'POST',
                          headers: {
                              'X-CSRF-TOKEN': '{{ csrf_token() }}',
                              'Accept': 'application/json'
                          }
                      })
                      .then(res => res.json())
                      .then(data => {
                          if (data.success) {
                              this.isSubscribed = data.subscribed;
                              this.subscribersCount = data.subscribers_formatted;
                          }
                      });
                  }
              }">

            <!-- YOUTUBE CHANNEL HEADER BANNER -->
            <div class="w-full h-44 sm:h-64 lg:h-72 rounded-3xl overflow-hidden relative bg-zinc-900 border border-zinc-800/80 shadow-2xl group">
                <img src="{{ $creator['banner'] }}" alt="{{ $creator['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-70">
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
            </div>

            <!-- YOUTUBE CHANNEL HEADER META & AVATAR BLOCK -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 -mt-16 sm:-mt-20 px-4 relative z-20">
                <!-- Avatar & Details -->
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-5">
                    <!-- Default Profile Avatar with First Letter Initials -->
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-red-600 border-4 border-zinc-950 shadow-2xl flex items-center justify-center shrink-0 relative overflow-hidden text-white text-3xl sm:text-4xl font-black">
                        {{ strtoupper(substr($creator['name'], 0, 1)) }}
                        <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-zinc-950 shadow" title="Creator Online"></div>
                    </div>

                    <!-- Channel Title, Handle & Stats -->
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                {{ $creator['name'] }}
                            </h1>
                            @if(!empty($creator['is_verified']))
                                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30" title="Akun Kreator Terverifikasi (Official Verified Channel)">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-zinc-400">
                            <span class="font-bold text-zinc-200">{{ $creator['handle'] }}</span>
                            <span>•</span>
                            <span class="text-emerald-400 font-bold" x-text="subscribersCount">{{ $creator['subscribers'] }}</span>
                            <span>•</span>
                            <span>{{ $creator['uploads_count'] }} Film Diterbitkan</span>
                        </div>

                        <p class="text-xs text-zinc-300 max-w-xl line-clamp-2 leading-relaxed pt-1">
                            {{ $creator['bio'] }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons: Subscribe & Share / Studio Control -->
                <div class="flex items-center gap-3 shrink-0">
                    @if(Auth::id() === $creator['id'])
                        <a href="{{ route('creator.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            <span>Buka Creator Studio</span>
                        </a>
                    @else
                        <form method="POST" action="{{ route('creators.toggle-subscription', $creator['id']) }}" @submit.prevent="toggleSubscribe()">
                            @csrf
                            <button type="submit"
                                    :class="isSubscribed
                                        ? 'bg-zinc-900 border-zinc-700/80 text-zinc-200 hover:border-red-600/50 hover:text-red-400 shadow-md'
                                        : 'bg-gradient-to-r from-red-600 via-rose-600 to-red-600 text-white border-red-500/30 shadow-lg shadow-red-600/30 hover:scale-105'"
                                    class="px-6 py-2.5 rounded-full border text-xs font-black tracking-wider uppercase transition-all duration-300 active:scale-95 flex items-center gap-2 relative group">
                                <template x-if="!isSubscribed">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current group-hover:rotate-12 transition duration-300" viewBox="0 0 24 24">
                                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                        </svg>
                                        <span>SUBSCRIBE</span>
                                    </div>
                                </template>
                                <template x-if="isSubscribed">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-amber-400 animate-pulse" viewBox="0 0 24 24">
                                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                        </svg>
                                        <svg class="w-3.5 h-3.5 fill-blue-500" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                        <span class="group-hover:hidden">SUBSCRIBED</span>
                                        <span class="hidden group-hover:inline text-red-400 font-extrabold">BATAL</span>
                                    </div>
                                </template>
                            </button>
                        </form>

                        <a href="{{ route('messages.index', ['type' => 'creator', 'creator_id' => $creator['id']]) }}"
                           class="py-2.5 px-4 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:border-red-600/50 transition shadow-lg flex items-center gap-1.5 font-extrabold text-xs shrink-0" title="Kirim Direct Message ke Kreator">
                            <svg class="w-4 h-4 fill-current text-red-500" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                            <span>Direct Message</span>
                        </a>
                    @endif

                    <button class="p-2.5 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white transition shadow" title="Bagikan Channel">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                    </button>
                </div>
            </div>

            <!-- YOUTUBE CHANNEL NAVIGATION TABS -->
            <div class="border-b border-zinc-800/90 pt-4">
                <div class="flex items-center space-x-2 sm:space-x-4 text-xs font-bold">
                    <button @click="activeTab = 'uploads'"
                            :class="activeTab === 'uploads' ? 'text-red-500 border-b-2 border-red-600 pb-3' : 'text-zinc-400 hover:text-white pb-3'"
                            class="px-3 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zm-7-2l5-4-5-4v8z"/></svg>
                        <span>Film & Serial Diterbitkan</span>
                    </button>

                    <button @click="activeTab = 'about'"
                            :class="activeTab === 'about' ? 'text-red-500 border-b-2 border-red-600 pb-3' : 'text-zinc-400 hover:text-white pb-3'"
                            class="px-3 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        <span>Tentang Channel</span>
                    </button>
                </div>
            </div>

            <!-- TAB 1: UPLOADS GRID -->
            <div x-show="activeTab === 'uploads'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-white tracking-tight">Koleksi Karya Film</h3>
                    <span class="text-xs text-zinc-500 font-mono">Urutkan dari Terbaru</span>
                </div>

                @if($movies->isEmpty())
                    <!-- EMPTY STATE WHEN CREATOR HAS NO POSTS -->
                    <div class="bg-zinc-900/80 border border-zinc-800/90 rounded-3xl p-10 sm:p-14 text-center max-w-xl mx-auto shadow-2xl relative overflow-hidden backdrop-blur-xl space-y-4">
                        <div class="w-16 h-16 rounded-full bg-zinc-950 border border-zinc-800 flex items-center justify-center mx-auto text-red-500">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xl font-black text-white tracking-tight">Belum Ada Postingan Film</h4>
                            <p class="text-xs text-zinc-400 leading-relaxed">
                                Channel <strong class="text-white">{{ $creator['name'] }}</strong> belum menerbitkan tayangan film sinematik atau episode serial.
                            </p>
                        </div>

                        @if(Auth::id() === $creator['id'])
                            <a href="{{ route('creator.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                <span>+ Terbitkan Film Pertama</span>
                            </a>
                        @endif
                    </div>
                @else
                    <!-- REAL PUBLISHED MOVIES GRID -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        @foreach($movies as $movie)
                            <a href="{{ route('movies.show', $movie->slug ?: $movie->id) }}" class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                                <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                    <img src="{{ asset($movie->poster_url ?: 'images/hero_banner.jpg') }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-300 font-mono text-[10px] font-bold border border-zinc-800">
                                        {{ $movie->episodes->count() }} Episode
                                    </span>
                                    <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                        <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                            <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3.5 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $movie->genre }}</span>
                                        <div class="flex items-center gap-1 font-bold text-xs text-zinc-300">
                                            <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                            <span>{{ $movie->rating }}</span>
                                        </div>
                                    </div>
                                    <h4 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $movie->title }}</h4>
                                    <div class="text-[11px] text-zinc-400 font-mono pt-1 flex items-center justify-between border-t border-zinc-800/80">
                                        <span class="text-amber-400 font-bold uppercase text-[9px]">{{ $movie->status_label }}</span>
                                        <span class="text-zinc-500">{{ $movie->release_year }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- TAB 2: ABOUT CHANNEL -->
            <div x-show="activeTab === 'about'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-6 space-y-4">
                        <h3 class="font-extrabold text-white text-base">Deskripsi Channel</h3>
                        <p class="text-xs text-zinc-300 leading-relaxed">{{ $creator['bio'] }}</p>

                        <div class="border-t border-zinc-800/80 pt-4 space-y-2 text-xs">
                            <h4 class="font-bold text-white">Detail Kontak</h4>
                            <div class="grid grid-cols-2 gap-2 text-zinc-400">
                                <div><span class="text-zinc-500">Email Kreator:</span> {{ $creator['email'] }}</div>
                                <div><span class="text-zinc-500">Platform:</span> WeWatch Cinema</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-6 space-y-4">
                        <h3 class="font-extrabold text-white text-base">Statistik Channel</h3>
                        <div class="space-y-3 text-xs text-zinc-300 border-t border-zinc-800/80 pt-3">
                            <div class="flex justify-between border-b border-zinc-800/50 pb-2">
                                <span class="text-zinc-500">Bergabung:</span>
                                <span class="font-bold">{{ $creator['joined_date'] }}</span>
                            </div>
                            <div class="flex justify-between border-b border-zinc-800/50 pb-2">
                                <span class="text-zinc-500">Film Diterbitkan:</span>
                                <span class="font-bold font-mono text-emerald-400">{{ $movies->count() }} Film</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Subscribers:</span>
                                <span class="font-bold font-mono text-red-400">{{ $creator['subscribers'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
