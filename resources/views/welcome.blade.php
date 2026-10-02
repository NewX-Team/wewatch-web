<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeWatch') }} — Stream Movies, Series & Originals in 4K</title>
        <meta name="description" content="Stream thousands of 4K Ultra HD movies, original series, and documentaries on WeWatch. Start watching today on any device.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white relative overflow-x-hidden"
          x-data="{
              trailerOpen: false,
              activeTab: 'quality',
              activeDevice: 'tv',
              faqOpen: null,
              scrollProgress: 0,
              hdrSlider: 50,
              scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); }
          }"
          @scroll.window="scrollProgress = Math.min(100, Math.max(0, Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100)))">

        <!-- Ambient Spatial Backdrop Glow -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-red-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

        <!-- Floating Desktop Navbar with Top & Side Gaps -->
        <header class="sticky top-4 z-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300">
            <div class="bg-zinc-950/85 backdrop-blur-xl border border-zinc-800/90 rounded-2xl shadow-2xl h-16 sm:h-20 flex items-center justify-between px-6">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center font-black text-xl text-white tracking-tighter shadow-lg shadow-red-950/50 transition group-hover:scale-105 duration-300">
                        W
                    </div>
                    <span class="font-black text-2xl tracking-tight text-white group-hover:text-red-500 transition">
                        WEWATCH
                    </span>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-zinc-300">
                    <a href="#trending" class="hover:text-white transition">Trending</a>
                    <a href="#features" class="hover:text-white transition">Features</a>
                    <a href="#pricing" class="hover:text-white transition">Plans</a>
                    <a href="#faq" class="hover:text-white transition">FAQ</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-zinc-800 text-zinc-100 hover:bg-zinc-700 text-sm font-semibold border border-zinc-700 transition shadow-md">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-zinc-300 hover:text-white transition">
                                Sign In
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-500 text-sm font-bold shadow-lg shadow-red-950/50 transition transform active:scale-95">
                                    Start Free Trial
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 pt-4">
            <!-- Hero Section: Immersive Atmospheric Spatial Showcase -->
            <section class="relative min-h-[85vh] flex items-center justify-center bg-zinc-950 border-b border-zinc-900 overflow-hidden py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative w-full">

                    <!-- Hero Card Frame with Inner Hairline Border & Ambient Depth -->
                    <div class="relative rounded-3xl border border-zinc-800/90 overflow-hidden shadow-2xl bg-zinc-900 group">
                        <!-- Hero Background Poster Layer -->
                        <div class="relative h-[620px] w-full overflow-hidden">
                            <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows WeWatch Original" class="w-full h-full object-cover opacity-50 transition duration-1000 group-hover:scale-105">
                            <!-- Solid Dark Masking Layer for Text Legibility -->
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/70 to-zinc-950/30"></div>
                            <div class="absolute inset-0 bg-zinc-950/40"></div>
                        </div>

                        <!-- Hero Content Showcase Layer -->
                        <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-14 text-left">
                            <div class="max-w-3xl">
                                <!-- Badge Line -->
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-3 py-1 rounded-full bg-red-600 text-white text-xs font-black uppercase tracking-wider shadow-md">
                                        WEWATCH ORIGINAL
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-zinc-900/90 text-zinc-300 text-xs font-bold border border-zinc-700/80 backdrop-blur-md">
                                        SEASON 1 NOW STREAMING
                                    </span>
                                </div>

                                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white uppercase leading-none drop-shadow-xl">
                                    CYBERPUNK SHADOWS
                                </h1>

                                <p class="mt-5 text-base sm:text-xl text-zinc-300 font-normal leading-relaxed max-w-2xl drop-shadow">
                                    In a neon-soaked dystopian future, three rogue operatives battle a mega-corporation to expose the ultimate neural truth. Stream season one in 4K Ultra HD with spatial sound.
                                </p>

                                <!-- Title Metadata -->
                                <div class="mt-5 flex flex-wrap items-center gap-3 text-xs font-semibold text-zinc-300">
                                    <span class="px-2.5 py-1 rounded bg-zinc-900/90 border border-zinc-700 text-zinc-200">2026</span>
                                    <span class="px-2.5 py-1 rounded bg-zinc-900/90 border border-zinc-700 text-zinc-200">18+</span>
                                    <span class="px-2.5 py-1 rounded bg-red-600 text-white font-bold">4K ULTRA HD</span>
                                    <span>8 Episodes • Sci-Fi Thriller</span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-8 flex flex-col sm:flex-row items-center gap-4">
                                    <button @click="trailerOpen = true" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-white text-zinc-950 hover:bg-zinc-200 font-bold text-base shadow-xl transition flex items-center justify-center gap-3 transform active:scale-95">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        Watch Teaser Trailer
                                    </button>

                                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-red-600 text-white hover:bg-red-500 font-bold text-base shadow-xl shadow-red-950/60 transition flex items-center justify-center gap-2 transform active:scale-95">
                                        Join Free for 30 Days &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Trending Section: Refined Spatial Poster Grid -->
            <section id="trending" class="py-24 bg-zinc-950 border-b border-zinc-900 scroll-rise">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-red-500">Popular Streaming Now</span>
                            <h2 class="text-3xl font-extrabold text-white tracking-tight mt-1">
                                Trending Across WeWatch
                            </h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-zinc-400">Handpicked premieres updated daily</span>
                        </div>
                    </div>

                    <!-- Poster Card Grid with Tactile Elevation Hover -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Poster Card 1 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card hover:shadow-depth-hover cursor-pointer">
                            <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-zinc-950/90 text-white text-[10px] font-extrabold tracking-wider border border-zinc-800">
                                    TOP 1 TODAY
                                </div>
                                <!-- Hover Play Overlay -->
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Action Thriller</span>
                                <h3 class="font-bold text-white text-lg mt-1 group-hover:text-red-400 transition">Midnight Drift</h3>
                                <div class="flex items-center justify-between text-xs text-zinc-400 mt-3 border-t border-zinc-800/80 pt-3">
                                    <span>2026 • Movie</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Poster Card 2 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card hover:shadow-depth-hover cursor-pointer">
                            <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_fantasy.jpg') }}" alt="Realm of Eldoria" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-zinc-950/90 text-white text-[10px] font-extrabold tracking-wider border border-zinc-800">
                                    NEW RELEASE
                                </div>
                                <!-- Hover Play Overlay -->
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wide">Fantasy Epic</span>
                                <h3 class="font-bold text-white text-lg mt-1 group-hover:text-red-400 transition">Realm of Eldoria</h3>
                                <div class="flex items-center justify-between text-xs text-zinc-400 mt-3 border-t border-zinc-800/80 pt-3">
                                    <span>2026 • Series S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.8</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Poster Card 3 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card hover:shadow-depth-hover cursor-pointer">
                            <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/poster_documentary.jpg') }}" alt="Deep Ocean Abyss 4K" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-zinc-950/90 text-white text-[10px] font-extrabold tracking-wider border border-zinc-800">
                                    ORIGINAL
                                </div>
                                <!-- Hover Play Overlay -->
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold text-sky-500 uppercase tracking-wide">Documentary</span>
                                <h3 class="font-bold text-white text-lg mt-1 group-hover:text-red-400 transition">Deep Ocean Abyss 4K</h3>
                                <div class="flex items-center justify-between text-xs text-zinc-400 mt-3 border-t border-zinc-800/80 pt-3">
                                    <span>2026 • Feature</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>5.0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Poster Card 4 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden group hover:border-red-600/60 transition duration-300 hover:-translate-y-2 shadow-depth-card hover:shadow-depth-hover cursor-pointer">
                            <div class="aspect-[2/3] relative overflow-hidden bg-zinc-800">
                                <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded bg-red-600 text-white text-[10px] font-extrabold tracking-wider">
                                    MUST WATCH
                                </div>
                                <!-- Hover Play Overlay -->
                                <div class="absolute inset-0 bg-zinc-950/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-5 h-5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold text-purple-500 uppercase tracking-wide">Sci-Fi Series</span>
                                <h3 class="font-bold text-white text-lg mt-1 group-hover:text-red-400 transition">Cyberpunk Shadows</h3>
                                <div class="flex items-center justify-between text-xs text-zinc-400 mt-3 border-t border-zinc-800/80 pt-3">
                                    <span>2026 • Series S1</span>
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section: Interactive Multi-Device & Screen Simulators -->
            <section id="features" class="py-24 bg-zinc-950 border-b border-zinc-900 scroll-rise">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-14">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-500">Built for Modern Viewers</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mt-2">
                            Why Entertainment Lovers Choose WeWatch
                        </h2>
                        <p class="text-zinc-400 text-base mt-4">
                            Experience cinematic clarity with zero compromises. Stream everywhere from smart TVs to mobile devices.
                        </p>
                    </div>

                    <!-- Tab Switch Buttons -->
                    <div class="flex flex-wrap justify-center gap-3 mb-10">
                        <button @click="activeTab = 'quality'" :class="activeTab === 'quality' ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-950/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:bg-zinc-800'" class="px-6 py-3 rounded-xl font-bold text-sm border transition">
                            4K Ultra HD & Dolby Atmos
                        </button>
                        <button @click="activeTab = 'devices'" :class="activeTab === 'devices' ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-950/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:bg-zinc-800'" class="px-6 py-3 rounded-xl font-bold text-sm border transition">
                            Watch Everywhere (Device Simulator)
                        </button>
                        <button @click="activeTab = 'offline'" :class="activeTab === 'offline' ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-950/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:bg-zinc-800'" class="px-6 py-3 rounded-xl font-bold text-sm border transition">
                            Offline Downloads
                        </button>
                        <button @click="activeTab = 'creator'" :class="activeTab === 'creator' ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-950/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:bg-zinc-800'" class="px-6 py-3 rounded-xl font-bold text-sm border transition">
                            Creator Studio Hub
                        </button>
                    </div>

                    <!-- Tab Content Panel -->
                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden">
                        <!-- Quality Pane with Interactive 4K HDR Cinema Screen Simulator -->
                        <div x-show="activeTab === 'quality'" class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                            <div class="lg:col-span-5">
                                <span class="px-3 py-1 rounded bg-zinc-800 text-red-500 font-mono text-xs font-bold">INTERACTIVE SIMULATION</span>
                                <h3 class="text-3xl font-bold text-white mt-4">Feel the Difference: SDR vs 4K Ultra HDR</h3>
                                <p class="text-zinc-400 text-sm leading-relaxed mt-4">
                                    Drag the comparison slider on the screen preview to feel the dramatic contrast, 10-bit color spectrum, and crystal-clear 3840x2160 detail delivered by WeWatch Ultra.
                                </p>
                                <div class="mt-6 p-4 bg-zinc-950 border border-zinc-800 rounded-2xl space-y-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-400">Comparison Position:</span>
                                        <span class="font-mono text-red-500 font-bold" x-text="hdrSlider + '% 4K HDR Coverage'"></span>
                                    </div>
                                    <input type="range" min="0" max="100" x-model="hdrSlider" class="w-full accent-red-600 cursor-pointer">
                                </div>
                            </div>

                            <!-- 4K Cinema Screen Frame Simulation Widget -->
                            <div class="lg:col-span-7">
                                <div class="relative bg-zinc-950 border-4 border-zinc-800 rounded-2xl p-2 shadow-2xl overflow-hidden group">
                                    <!-- LED Backlight Glow -->
                                    <div class="absolute -inset-1 bg-red-600/10 rounded-2xl blur-lg pointer-events-none"></div>

                                    <!-- Screen Container -->
                                    <div class="relative aspect-video rounded-lg overflow-hidden bg-black select-none">
                                        <!-- Right Base Layer: Full 4K HDR Image -->
                                        <img src="{{ asset('images/hero_banner.jpg') }}" alt="4K HDR Stream Master" class="w-full h-full object-cover">
                                        <div class="absolute top-4 right-4 px-3 py-1 rounded-md bg-red-600 text-white text-xs font-black tracking-wider shadow-lg">
                                            4K ULTRA HD + HDR10+
                                        </div>

                                        <!-- Left Clipped Layer: SDR 1080p Image (Desaturated & Slightly Blurred) -->
                                        <div class="absolute inset-0 overflow-hidden border-r-2 border-white/80 shadow-2xl"
                                             :style="'width: ' + hdrSlider + '%'">
                                            <div class="w-[700px] sm:w-[900px] h-full relative">
                                                <img src="{{ asset('images/hero_banner.jpg') }}" alt="SDR 1080p Stream" class="w-full h-full object-cover filter blur-[1px] saturate-[0.6] contrast-[0.85]">
                                                <div class="absolute top-4 left-4 px-3 py-1 rounded-md bg-zinc-900/90 text-zinc-300 text-xs font-bold border border-zinc-700">
                                                    SDR 1080p Standard
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Drag Handle Indicator SVG -->
                                        <div class="absolute top-0 bottom-0 pointer-events-none flex items-center justify-center"
                                             :style="'left: calc(' + hdrSlider + '% - 12px)'">
                                            <div class="w-6 h-6 rounded-full bg-white text-zinc-950 flex items-center justify-center shadow-2xl border-2 border-red-600">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8.5 17l-5-5 5-5v10zm7 0l5-5-5-5v10z"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TV Frame Bezel Footer -->
                                    <div class="mt-2 flex items-center justify-between px-3 text-[10px] text-zinc-500 font-mono">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>WEWATCH SMART TV STREAM ENGINE</span>
                                        </div>
                                        <span>DOLBY VISION • ATMOS SPATIAL AUDIO</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Devices Pane: Interactive Realistic Multi-Device Screen Simulator -->
                        <div x-show="activeTab === 'devices'" class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center" style="display: none;">
                            <!-- Left Device Controls & Selectors -->
                            <div class="lg:col-span-5 space-y-4">
                                <div>
                                    <span class="px-3 py-1 rounded bg-zinc-800 text-red-500 font-mono text-xs font-bold">INTERACTIVE DEVICE SIMULATOR</span>
                                    <h3 class="text-3xl font-bold text-white mt-4">Select a Device to Test the Stream</h3>
                                    <p class="text-zinc-400 text-sm leading-relaxed mt-2">
                                        Click any registered device below to view how *Cyberpunk Shadows* seamlessly adapts its layout, interface, and 4K playback sync in real-time.
                                    </p>
                                </div>

                                <!-- Device Selection Buttons with Minimal SVG Icons -->
                                <div class="space-y-3 pt-2">
                                    <!-- Device 1: Samsung TV -->
                                    <button @click="activeDevice = 'tv'"
                                            :class="activeDevice === 'tv' ? 'bg-zinc-950 border-red-600 ring-1 ring-red-600/50 shadow-xl' : 'bg-zinc-950/60 border-zinc-800 hover:bg-zinc-950'"
                                            class="w-full p-4 rounded-2xl border text-left flex items-center justify-between transition group">
                                        <div class="flex items-center gap-3.5">
                                            <div :class="activeDevice === 'tv' ? 'bg-red-600 text-white' : 'bg-zinc-800 text-zinc-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M21 3H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h5v2h8v-2h5c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 14H3V5h18v12z"/></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm">Samsung 75" QLED 4K TV</div>
                                                <div class="text-xs text-zinc-400">Living Room Home Theater</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span x-show="activeDevice === 'tv'" class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                            <span :class="activeDevice === 'tv' ? 'text-red-500 font-bold' : 'text-zinc-600'" class="font-mono text-xs">
                                                Active
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Device 2: iPhone 17 Pro -->
                                    <button @click="activeDevice = 'phone'"
                                            :class="activeDevice === 'phone' ? 'bg-zinc-950 border-red-600 ring-1 ring-red-600/50 shadow-xl' : 'bg-zinc-950/60 border-zinc-800 hover:bg-zinc-950'"
                                            class="w-full p-4 rounded-2xl border text-left flex items-center justify-between transition group">
                                        <div class="flex items-center gap-3.5">
                                            <div :class="activeDevice === 'phone' ? 'bg-red-600 text-white' : 'bg-zinc-800 text-zinc-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm">iPhone 17 Pro Max</div>
                                                <div class="text-xs text-zinc-400">Mobile On-The-Go Stream</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span x-show="activeDevice === 'phone'" class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                            <span :class="activeDevice === 'phone' ? 'text-red-500 font-bold' : 'text-zinc-600'" class="font-mono text-xs">
                                                Synced
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Device 3: MacBook Pro -->
                                    <button @click="activeDevice = 'macbook'"
                                            :class="activeDevice === 'macbook' ? 'bg-zinc-950 border-red-600 ring-1 ring-red-600/50 shadow-xl' : 'bg-zinc-950/60 border-zinc-800 hover:bg-zinc-950'"
                                            class="w-full p-4 rounded-2xl border text-left flex items-center justify-between transition group">
                                        <div class="flex items-center gap-3.5">
                                            <div :class="activeDevice === 'macbook' ? 'bg-red-600 text-white' : 'bg-zinc-800 text-zinc-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 18V3H2v15H0v2h24v-2h-2zm-8 0h-4v-1h4v1zm6-3H4V5h16v10z"/></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm">MacBook Pro 16" M3 Max</div>
                                                <div class="text-xs text-zinc-400">Workspace Web Studio Player</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span x-show="activeDevice === 'macbook'" class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                            <span :class="activeDevice === 'macbook' ? 'text-red-500 font-bold' : 'text-zinc-600'" class="font-mono text-xs">
                                                Ready
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Right Mockup Screen Viewers with Smooth Physics -->
                            <div class="lg:col-span-7 flex justify-center items-center relative min-h-[360px]">

                                <!-- 1. Samsung 4K TV Mockup -->
                                <div x-show="activeDevice === 'tv'"
                                     x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500 transform"
                                     x-transition:enter-start="opacity-0 scale-95 translateY(12px)"
                                     x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                                     x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200 transform"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="w-full bg-zinc-950 border-8 border-zinc-900 rounded-3xl p-3 shadow-2xl relative group overflow-hidden">
                                    <!-- Wall Backlight Ambient Glow -->
                                    <div class="absolute -inset-4 bg-red-600/15 rounded-full blur-2xl pointer-events-none"></div>

                                    <div class="relative aspect-video rounded-xl overflow-hidden bg-black border border-zinc-800">
                                        <img src="{{ asset('images/hero_banner.jpg') }}" alt="Streaming on Samsung TV" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40"></div>

                                        <!-- TV Player Top Bar -->
                                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between text-xs text-white">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded bg-red-600 font-bold text-[10px]">SAMSUNG TIZEN OS</span>
                                                <span class="font-bold">Living Room TV</span>
                                            </div>
                                            <span class="px-2 py-0.5 rounded bg-zinc-900/90 text-red-400 font-mono text-[10px] border border-zinc-700">4K HDR • DOLBY ATMOS</span>
                                        </div>

                                        <!-- TV Player Bottom Controls -->
                                        <div class="absolute bottom-4 left-4 right-4 text-white space-y-2">
                                            <div class="flex items-center justify-between text-xs font-bold">
                                                <span>Cyberpunk Shadows — Ep. 4: Neural Awakening</span>
                                                <span>45m 12s / 58m 00s</span>
                                            </div>
                                            <div class="h-1.5 bg-zinc-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-red-600 w-[78%] rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2 text-[10px] font-mono text-zinc-500">SAMSUNG 75" QLED CINEMA STREAM SIMULATOR</div>
                                </div>

                                <!-- 2. iPhone 17 Pro Mockup -->
                                <div x-show="activeDevice === 'phone'"
                                     x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500 transform"
                                     x-transition:enter-start="opacity-0 scale-95 translateY(12px)"
                                     x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                                     x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200 transform"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="w-[320px] bg-zinc-950 border-8 border-zinc-800 rounded-[40px] p-3 shadow-2xl relative group overflow-hidden"
                                     style="display: none;">
                                    <!-- Phone Screen Frame -->
                                    <div class="relative aspect-[9/18] rounded-[28px] overflow-hidden bg-black border border-zinc-800">
                                        <!-- Dynamic Island Notch -->
                                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-24 h-5 rounded-full bg-black z-30 flex items-center justify-center gap-2 border border-zinc-900">
                                            <span class="w-2 h-2 rounded-full bg-zinc-800"></span>
                                            <span class="w-2 h-2 rounded-full bg-blue-900/40"></span>
                                        </div>

                                        <img src="{{ asset('images/hero_banner.jpg') }}" alt="Streaming on iPhone" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-black/50"></div>

                                        <!-- Mobile Status Bar -->
                                        <div class="absolute top-9 inset-x-4 flex justify-between items-center text-[10px] text-white font-semibold">
                                            <span>9:41 AM</span>
                                            <div class="flex items-center gap-1.5">
                                                <span>5G</span>
                                                <span class="w-3.5 h-2 rounded-sm border border-white bg-white"></span>
                                            </div>
                                        </div>

                                        <!-- Mobile Stream Player UI Overlay -->
                                        <div class="absolute bottom-6 inset-x-4 text-white space-y-3">
                                            <div class="bg-zinc-900/90 border border-zinc-800 p-3 rounded-2xl backdrop-blur-md space-y-2">
                                                <div class="flex items-center justify-between text-[11px] font-bold">
                                                    <span class="truncate">Cyberpunk Shadows</span>
                                                    <span class="px-1.5 py-0.5 rounded bg-red-600 text-[9px]">MOBILE 1080p</span>
                                                </div>
                                                <div class="flex items-center justify-between text-[10px] text-zinc-400">
                                                    <span>S1 : E4</span>
                                                    <span>Synced with TV</span>
                                                </div>
                                                <div class="h-1 bg-zinc-800 rounded-full overflow-hidden">
                                                    <div class="h-full bg-red-600 w-[78%]"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2 text-[10px] font-mono text-zinc-500">IPHONE 17 PRO STREAM SIMULATOR</div>
                                </div>

                                <!-- 3. MacBook Pro 16" Mockup -->
                                <div x-show="activeDevice === 'macbook'"
                                     x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500 transform"
                                     x-transition:enter-start="opacity-0 scale-95 translateY(12px)"
                                     x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                                     x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200 transform"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="w-full bg-zinc-950 border-4 border-zinc-800 rounded-2xl p-2 shadow-2xl relative group overflow-hidden"
                                     style="display: none;">
                                    <div class="relative aspect-[16/10] rounded-lg overflow-hidden bg-black border border-zinc-800">
                                        <!-- macOS Window Header Bar -->
                                        <div class="h-7 bg-zinc-900 border-b border-zinc-800 flex items-center justify-between px-3 text-[10px] text-zinc-400">
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                            </div>
                                            <span class="font-mono text-zinc-300">wewatch.com/watch/cyberpunk-shadows</span>
                                            <span class="font-mono text-red-500">SAFARI 4K</span>
                                        </div>

                                        <img src="{{ asset('images/hero_banner.jpg') }}" alt="Streaming on MacBook" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>

                                        <!-- Web Player Overlay Controls -->
                                        <div class="absolute bottom-3 inset-x-4 flex items-center justify-between text-xs text-white bg-zinc-900/80 p-3 rounded-xl border border-zinc-800 backdrop-blur-md">
                                            <div class="flex items-center gap-3">
                                                <button class="w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-xs shadow-md">
                                                    <svg class="w-3.5 h-3.5 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </button>
                                                <div>
                                                    <div class="font-bold text-xs">Cyberpunk Shadows - S1:E4</div>
                                                    <div class="text-[10px] text-zinc-400">45m 12s / 58m 00s</div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300">PIP</span>
                                                <span class="px-2 py-0.5 rounded bg-red-600 text-white font-bold">4K HDR</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Laptop Base Hinge Bar -->
                                    <div class="h-2 bg-zinc-800 rounded-b-xl mt-1 mx-auto w-32"></div>
                                    <div class="text-center mt-2 text-[10px] font-mono text-zinc-500">MACBOOK PRO 16" WEB PLAYER SIMULATOR</div>
                                </div>

                            </div>
                        </div>

                        <!-- Offline Downloads Pane -->
                        <div x-show="activeTab === 'offline'" class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center" style="display: none;">
                            <div>
                                <span class="px-3 py-1 rounded bg-zinc-800 text-red-500 font-mono text-xs font-bold">TRAVEL READY</span>
                                <h3 class="text-3xl font-bold text-white mt-4">Download & Watch Anywhere Offline</h3>
                                <p class="text-zinc-400 text-sm leading-relaxed mt-4">
                                    Going on a long flight or heading out of coverage? Save your favorite movies and full series seasons to your mobile or tablet with one click.
                                </p>
                            </div>
                            <div class="bg-zinc-950 border border-zinc-800 rounded-2xl p-6 shadow-xl space-y-3 text-xs">
                                <div class="flex justify-between font-bold text-white pb-2 border-b border-zinc-800">
                                    <span>Offline Downloads Storage</span>
                                    <span class="text-red-500">14.2 GB Used</span>
                                </div>
                                <div class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl flex items-center justify-between">
                                    <span>Cyberpunk Shadows S1 (8 Eps)</span>
                                    <span class="text-emerald-400 font-mono">Downloaded</span>
                                </div>
                                <div class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl flex items-center justify-between">
                                    <span>Midnight Drift (4K)</span>
                                    <span class="text-emerald-400 font-mono">Downloaded</span>
                                </div>
                            </div>
                        </div>

                        <!-- Creator Studio Pane -->
                        <div x-show="activeTab === 'creator'" class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center" style="display: none;">
                            <div>
                                <span class="px-3 py-1 rounded bg-zinc-800 text-amber-500 font-mono text-xs font-bold">INDEPENDENT CREATORS</span>
                                <h3 class="text-3xl font-bold text-white mt-4">Publish & Monetize Your Own Films</h3>
                                <p class="text-zinc-400 text-sm leading-relaxed mt-4">
                                    WeWatch isn't just for viewers. Filmmakers and independent creators get dedicated Creator Studio tools to upload content, track real-time analytics, and earn fair revenue share.
                                </p>
                            </div>
                            <div class="bg-zinc-950 border border-zinc-800 rounded-2xl p-6 shadow-xl space-y-3">
                                <div class="text-xs font-bold text-white pb-2 border-b border-zinc-800">Creator Analytics Portal</div>
                                <div class="grid grid-cols-2 gap-3 text-center text-xs">
                                    <div class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl">
                                        <div class="text-lg font-black text-amber-400">$3,840.50</div>
                                        <div class="text-[10px] text-zinc-500 mt-0.5">Est. Monthly Earnings</div>
                                    </div>
                                    <div class="p-3 bg-zinc-900 border border-zinc-800 rounded-xl">
                                        <div class="text-lg font-black text-white">452.1K</div>
                                        <div class="text-[10px] text-zinc-500 mt-0.5">Total Views</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section with Highlighted Elevation Cards -->
            <section id="pricing" class="py-24 bg-zinc-950 border-b border-zinc-900 scroll-rise">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-500">Transparent Pricing</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mt-2">
                            Choose the Plan That Fits Your Vision
                        </h2>
                        <p class="text-zinc-400 text-base mt-3">
                            No hidden fees. Switch or cancel your subscription anytime with one click.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Plan 1: Basic -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl p-8 flex flex-col justify-between hover:border-zinc-700 transition duration-300 hover:-translate-y-1 shadow-depth-card">
                            <div>
                                <h3 class="text-xl font-bold text-white">Basic HD</h3>
                                <p class="text-xs text-zinc-400 mt-1">Great for mobile & tablet viewers</p>
                                <div class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">$6.99</span>
                                    <span class="text-xs text-zinc-400">/ month</span>
                                </div>

                                <ul class="mt-8 space-y-4 text-xs text-zinc-300">
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 720p HD Quality
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 1 Screen at a time
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Watch on Phone & Tablet
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ route('register') }}" class="mt-8 w-full py-3 rounded-xl bg-zinc-800 text-white hover:bg-zinc-700 text-xs font-bold text-center border border-zinc-700 transition">
                                Choose Basic
                            </a>
                        </div>

                        <!-- Plan 2: Pro (Featured Plan with Glowing Rim) -->
                        <div class="bg-zinc-900 border-2 border-red-600 rounded-2xl p-8 flex flex-col justify-between relative shadow-2xl shadow-red-950/30 scale-105">
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-red-600 text-white text-[10px] font-black tracking-widest uppercase shadow-md">
                                MOST POPULAR
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-white">Standard 1080p</h3>
                                <p class="text-xs text-zinc-400 mt-1">Full HD for home televisions</p>
                                <div class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">$12.99</span>
                                    <span class="text-xs text-zinc-400">/ month</span>
                                </div>

                                <ul class="mt-8 space-y-4 text-xs text-zinc-200 font-medium">
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 1080p Full HD Resolution
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 2 Screens simultaneously
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Smart TV & Console App
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 30 Offline Downloads
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ route('register') }}" class="mt-8 w-full py-3.5 rounded-xl bg-red-600 text-white hover:bg-red-500 text-xs font-bold text-center shadow-lg shadow-red-950/60 transition">
                                Start 30-Day Free Trial
                            </a>
                        </div>

                        <!-- Plan 3: Premium Ultra HD -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl p-8 flex flex-col justify-between hover:border-zinc-700 transition duration-300 hover:-translate-y-1 shadow-depth-card">
                            <div>
                                <h3 class="text-xl font-bold text-white">Ultra 4K HDR</h3>
                                <p class="text-xs text-zinc-400 mt-1">Ultimate home theater experience</p>
                                <div class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold text-white">$18.99</span>
                                    <span class="text-xs text-zinc-400">/ month</span>
                                </div>

                                <ul class="mt-8 space-y-4 text-xs text-zinc-300">
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 4K Ultra HD + HDR10+
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Dolby Atmos Spatial Audio
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> 4 Screens simultaneously
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Unlimited Offline Downloads
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ route('register') }}" class="mt-8 w-full py-3 rounded-xl bg-zinc-800 text-white hover:bg-zinc-700 text-xs font-bold text-center border border-zinc-700 transition">
                                Choose Ultra 4K
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section with Smooth Height Accordion -->
            <section id="faq" class="py-24 bg-zinc-950 border-b border-zinc-900 scroll-rise">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-500">Got Questions?</span>
                        <h2 class="text-3xl font-black text-white tracking-tight mt-2">
                            Frequently Asked Questions
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <!-- FAQ Item 1 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden shadow-md">
                            <button @click="faqOpen = (faqOpen === 1 ? null : 1)" class="w-full p-6 text-left font-bold text-white flex items-center justify-between hover:bg-zinc-800/80 transition group">
                                <span>What is WeWatch?</span>
                                <svg class="w-5 h-5 text-zinc-400 transition-transform duration-300 group-hover:text-white" :class="faqOpen === 1 ? 'rotate-180 text-red-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="faqOpen === 1" x-collapse class="px-6 pb-6 text-sm text-zinc-400 leading-relaxed border-t border-zinc-800/80 pt-4" style="display: none;">
                                WeWatch is a premium global streaming platform featuring blockbuster movies, exclusive original series, documentaries, and an independent Creator Studio hub for filmmakers worldwide.
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden shadow-md">
                            <button @click="faqOpen = (faqOpen === 2 ? null : 2)" class="w-full p-6 text-left font-bold text-white flex items-center justify-between hover:bg-zinc-800/80 transition group">
                                <span>How much does WeWatch cost?</span>
                                <svg class="w-5 h-5 text-zinc-400 transition-transform duration-300 group-hover:text-white" :class="faqOpen === 2 ? 'rotate-180 text-red-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="faqOpen === 2" x-collapse class="px-6 pb-6 text-sm text-zinc-400 leading-relaxed border-t border-zinc-800/80 pt-4" style="display: none;">
                                Plans start from $6.99 per month for Basic HD up to $18.99 per month for Ultra 4K. There are no long-term contracts or hidden cancellation fees.
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden shadow-md">
                            <button @click="faqOpen = (faqOpen === 3 ? null : 3)" class="w-full p-6 text-left font-bold text-white flex items-center justify-between hover:bg-zinc-800/80 transition group">
                                <span>Can I cancel my subscription anytime?</span>
                                <svg class="w-5 h-5 text-zinc-400 transition-transform duration-300 group-hover:text-white" :class="faqOpen === 3 ? 'rotate-180 text-red-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="faqOpen === 3" x-collapse class="px-6 pb-6 text-sm text-zinc-400 leading-relaxed border-t border-zinc-800/80 pt-4" style="display: none;">
                                Yes! You can cancel your subscription online at any time in two clicks from your profile settings. You will retain access until the end of your billing cycle.
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="bg-zinc-900/90 border border-zinc-800 rounded-2xl overflow-hidden shadow-md">
                            <button @click="faqOpen = (faqOpen === 4 ? null : 4)" class="w-full p-6 text-left font-bold text-white flex items-center justify-between hover:bg-zinc-800/80 transition group">
                                <span>How does the Creator Studio work?</span>
                                <svg class="w-5 h-5 text-zinc-400 transition-transform duration-300 group-hover:text-white" :class="faqOpen === 4 ? 'rotate-180 text-red-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="faqOpen === 4" x-collapse class="px-6 pb-6 text-sm text-zinc-400 leading-relaxed border-t border-zinc-800/80 pt-4" style="display: none;">
                                Registered Creator accounts can upload their independent films, documentaries, or series straight to WeWatch, set monetization preferences, and view audience analytics directly from their studio portal.
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Floating Glass Scroll Progress & Back-To-Top Control -->
        <div x-show="scrollProgress > 10" class="fixed bottom-6 right-6 z-50 transition-all duration-300 transform" x-transition>
            <button @click="scrollToTop()" class="px-4 py-2.5 rounded-full bg-zinc-900/90 hover:bg-red-600 text-white font-bold text-xs border border-zinc-700/80 shadow-2xl backdrop-blur-md flex items-center gap-2 transition group">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4l-8 8h5v8h6v-8h5z"/></svg>
                <span>Top</span>
                <span class="px-1.5 py-0.5 rounded bg-zinc-800 text-[10px] text-zinc-300 font-mono group-hover:bg-red-700 group-hover:text-white" x-text="scrollProgress + '%'"></span>
            </button>
        </div>

        <!-- Teaser Trailer Modal -->
        <div x-show="trailerOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/90 backdrop-blur-md" style="display: none;">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl max-w-3xl w-full p-6 relative shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-zinc-800 pb-3">
                    <h3 class="font-bold text-white text-lg">Cyberpunk Shadows — Official Teaser Trailer</h3>
                    <button @click="trailerOpen = false" class="text-zinc-400 hover:text-white font-bold text-xl">
                        &times;
                    </button>
                </div>
                <div class="aspect-video bg-zinc-950 rounded-xl flex items-center justify-center border border-zinc-800">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 rounded-full bg-red-600 text-white flex items-center justify-center mx-auto mb-4 font-bold shadow-lg shadow-red-950/50">
                            <svg class="w-6 h-6 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <p class="text-zinc-200 font-bold text-base">Streaming Teaser Trailer (4K Master)</p>
                        <p class="text-zinc-500 text-xs mt-1">Season 1 Premieres Exclusively on WeWatch</p>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button @click="trailerOpen = false" class="px-5 py-2 rounded-lg bg-zinc-800 text-zinc-200 hover:bg-zinc-700 text-xs font-semibold">
                        Close Preview
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-zinc-950 text-zinc-400 py-16 text-xs border-t border-zinc-900 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">WeWatch Global</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition">Press Releases</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">Help & Support</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Supported Devices</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">Creator Studio</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition">Submit Your Film</a></li>
                        <li><a href="#" class="hover:text-white transition">Creator Guidelines</a></li>
                        <li><a href="#" class="hover:text-white transition">Monetization Terms</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">Legal & Privacy</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Cookie Preferences</a></li>
                    </ul>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-zinc-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} WeWatch Inc. All rights reserved.</p>
                <p class="text-zinc-600">Mastered in 4K Ultra HD • Native Digital Streaming</p>
            </div>
        </footer>

        <!-- Smooth Hardware-Accelerated Bidirectional Scroll Observer -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: "0px 0px -40px 0px"
                };

                const riseObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        } else {
                            // Enables bidirectional smooth re-animation when scrolling both up and down
                            entry.target.classList.remove('is-visible');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.scroll-rise').forEach(el => riseObserver.observe(el));
            });
        </script>
    </body>
</html>
