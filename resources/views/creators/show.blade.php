<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $creator['name'] }} — Creator Channel | WeWatch</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden">

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
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>Back to Catalogue</span>
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
                  isSubscribed: false
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
                    <!-- WA-Style Basic Default Avatar Placeholder -->
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-zinc-800 border-4 border-zinc-950 shadow-2xl flex items-center justify-center shrink-0 relative overflow-hidden group">
                        <!-- SVG WA Kosongan Default Profile Icon -->
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-zinc-400 translate-y-2 fill-current" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-zinc-950 shadow" title="Creator Online"></div>
                    </div>

                    <!-- Channel Title, Handle & Stats -->
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                {{ $creator['name'] }}
                            </h1>
                            <!-- Verified Creator Checkmark Badge -->
                            <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center" title="Verified Creator Channel">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 text-xs font-mono text-zinc-400">
                            <span class="font-bold text-zinc-200">{{ $creator['handle'] }}</span>
                            <span>•</span>
                            <span class="text-emerald-400 font-bold">{{ $creator['subscribers'] }}</span>
                            <span>•</span>
                            <span>{{ $creator['uploads_count'] }} Videos</span>
                        </div>

                        <p class="text-xs text-zinc-300 max-w-xl line-clamp-2 leading-relaxed pt-1">
                            {{ $creator['bio'] }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons: Subscribe & Share -->
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="isSubscribed = !isSubscribed"
                            :class="isSubscribed
                                ? 'bg-zinc-800 text-zinc-200 border-zinc-700 hover:bg-zinc-700'
                                : 'bg-red-600 hover:bg-red-500 text-white border-red-500/50 shadow-lg shadow-red-600/20'"
                            class="px-6 py-2.5 rounded-xl border text-xs font-extrabold tracking-wider uppercase transition transform active:scale-95 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" :class="isSubscribed ? 'bg-emerald-400' : 'bg-white animate-pulse'"></span>
                        <span x-text="isSubscribed ? 'SUBSCRIBED' : 'SUBSCRIBE'"></span>
                    </button>

                    <button class="p-2.5 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white transition shadow" title="Share Channel">
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
                        <span>Uploads & Premieres</span>
                    </button>

                    <button @click="activeTab = 'featured'"
                            :class="activeTab === 'featured' ? 'text-red-500 border-b-2 border-red-600 pb-3' : 'text-zinc-400 hover:text-white pb-3'"
                            class="px-3 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <span>Featured Trailer</span>
                    </button>

                    <button @click="activeTab = 'about'"
                            :class="activeTab === 'about' ? 'text-red-500 border-b-2 border-red-600 pb-3' : 'text-zinc-400 hover:text-white pb-3'"
                            class="px-3 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        <span>About Channel</span>
                    </button>
                </div>
            </div>

            <!-- TAB 1: UPLOADS GRID -->
            <div x-show="activeTab === 'uploads'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-white tracking-tight">Channel Uploads</h3>
                    <span class="text-xs text-zinc-500 font-mono">Sorted by Latest</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($creator['uploads'] as $item)
                        <a href="{{ route('movies.show', $item['id']) }}" class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 shadow-md block">
                            <div class="aspect-[16/9] relative overflow-hidden bg-zinc-800">
                                <img src="{{ $item['banner'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-zinc-950/90 text-zinc-300 font-mono text-[10px] font-bold border border-zinc-800">
                                    {{ $item['duration'] }}
                                </span>
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-4 h-4 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3.5 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">{{ $item['category'] }}</span>
                                    <div class="flex items-center gap-1 font-bold text-xs text-zinc-300">
                                        <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>{{ $item['rating'] }}</span>
                                    </div>
                                </div>
                                <h4 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $item['title'] }}</h4>
                                <div class="text-[11px] text-zinc-400 font-mono pt-1 flex items-center justify-between border-t border-zinc-800/80">
                                    <span>{{ $item['views'] }}</span>
                                    <span class="text-zinc-500">2026</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- TAB 2: FEATURED TRAILER -->
            <div x-show="activeTab === 'featured'" class="space-y-4" style="display: none;">
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-6 flex flex-col md:flex-row gap-6 items-center shadow-xl">
                    <div class="w-full md:w-1/2 aspect-[16/9] rounded-2xl overflow-hidden relative bg-zinc-800 shrink-0 group">
                        <img src="{{ $creator['featured_movie']['banner'] }}" alt="{{ $creator['featured_movie']['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <a href="{{ route('movies.show', $creator['featured_movie']['id']) }}" class="absolute inset-0 bg-zinc-950/30 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-red-600 text-white flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </a>
                    </div>

                    <div class="w-full md:w-1/2 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded bg-red-600 text-white font-extrabold text-[9px] uppercase tracking-wider">FEATURED SPOTLIGHT</span>
                            <span class="text-xs font-mono text-zinc-400">{{ $creator['featured_movie']['views'] }}</span>
                        </div>

                        <h2 class="text-2xl font-black text-white tracking-tight">{{ $creator['featured_movie']['title'] }}</h2>

                        <p class="text-xs text-zinc-300 leading-relaxed">{{ $creator['featured_movie']['description'] }}</p>

                        <a href="{{ route('movies.show', $creator['featured_movie']['id']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <span>Watch Premiere Now</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ABOUT CHANNEL -->
            <div x-show="activeTab === 'about'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-6 space-y-4">
                        <h3 class="font-extrabold text-white text-base">Channel Description</h3>
                        <p class="text-xs text-zinc-300 leading-relaxed">{{ $creator['bio'] }}</p>

                        <div class="border-t border-zinc-800/80 pt-4 space-y-2 text-xs">
                            <h4 class="font-bold text-white">Details</h4>
                            <div class="grid grid-cols-2 gap-2 text-zinc-400">
                                <div><span class="text-zinc-500">Location:</span> Tokyo, Japan</div>
                                <div><span class="text-zinc-500">Business Email:</span> contact@neotokyostudios.io</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-6 space-y-4">
                        <h3 class="font-extrabold text-white text-base">Stats</h3>
                        <div class="space-y-3 text-xs text-zinc-300 border-t border-zinc-800/80 pt-3">
                            <div class="flex justify-between border-b border-zinc-800/50 pb-2">
                                <span class="text-zinc-500">Joined:</span>
                                <span class="font-bold">{{ $creator['joined_date'] }}</span>
                            </div>
                            <div class="flex justify-between border-b border-zinc-800/50 pb-2">
                                <span class="text-zinc-500">Total Views:</span>
                                <span class="font-bold font-mono text-emerald-400">2,360,490 views</span>
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
