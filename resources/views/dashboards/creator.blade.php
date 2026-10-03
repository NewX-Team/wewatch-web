<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Creator Studio Hub — WeWatch</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden"
          x-data="{
              showUploadModal: false,
              selectedTab: 'all',
              replyText: '',
              uploadProgress: 0,
              isUploading: false,
              newVideo: {
                  title: '',
                  category: 'Sci-Fi Series',
                  access: 'public'
              },
              startUpload() {
                  if (!this.newVideo.title) return;
                  this.isUploading = true;
                  this.uploadProgress = 10;
                  let interval = setInterval(() => {
                      this.uploadProgress += 20;
                      if (this.uploadProgress >= 100) {
                          clearInterval(interval);
                          setTimeout(() => {
                              this.isUploading = false;
                              this.showUploadModal = false;
                              this.uploadProgress = 0;
                              this.newVideo.title = '';
                          }, 500);
                      }
                  }, 400);
              }
          }">

        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/3 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed top-1/2 right-1/4 w-[600px] h-[300px] bg-amber-500/10 rounded-full blur-[150px] pointer-events-none z-0"></div>

        <!-- Floating Centered Morphing Navbar -->
        <nav x-data="{ isScrolled: false }"
             @scroll.window="isScrolled = (window.scrollY > 30)"
             :class="isScrolled
                 ? 'max-w-4xl rounded-full py-2.5 px-6 bg-zinc-950/90 shadow-2xl border-zinc-800 backdrop-blur-2xl scale-95'
                 : 'max-w-6xl rounded-2xl py-3.5 px-7 bg-zinc-950/85 shadow-2xl border-zinc-800/80 backdrop-blur-xl'"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 border transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) w-[92%] sm:w-full flex items-center justify-between">

            <!-- Brand Logo & Studio Tag -->
            <div class="flex items-center gap-4">
                <a href="{{ route('creator.dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center font-black text-sm text-white tracking-tighter shadow-md transition group-hover:scale-105">
                        W
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-black text-base tracking-tight text-white group-hover:text-red-500 transition block leading-none">
                            WEWATCH
                        </span>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-red-500">CREATOR STUDIO</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-2 pl-4 border-l border-zinc-800">
                    <a href="{{ route('creators.show', 'neotokyo-studios') }}" class="px-3 py-1.5 rounded-xl bg-zinc-900 border border-zinc-800 text-xs font-bold text-zinc-300 hover:text-white hover:border-zinc-700 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        <span>Lihat Channel Publik</span>
                    </a>
                </div>
            </div>

            <!-- Action Controls & User Profile Dropdown -->
            <div class="flex items-center space-x-3 shrink-0">
                <button @click="showUploadModal = true" class="py-2 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    <span>Upload Karya Baru</span>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-zinc-700 transition">
                        <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl p-2 text-xs text-zinc-300 space-y-1 z-50" style="display: none;">
                        <div class="px-3 py-2 border-b border-zinc-800">
                            <div class="font-bold text-white truncate">{{ Auth::user()->name }}</div>
                            <span class="px-1.5 py-0.2 rounded bg-amber-500/20 text-amber-400 font-mono text-[9px] font-bold border border-amber-500/30">Verified Creator</span>
                        </div>
                        <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Mode Penonton (Dashboard)</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">Pengaturan Akun</a>
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

        <!-- Main Studio Container -->
        <main class="pt-28 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

            <!-- Welcome Header Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-800/80 pb-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-amber-500/15 border border-amber-500/30 text-amber-400 font-extrabold text-[10px] uppercase tracking-wider">
                            CREATOR STUDIO HUB
                        </span>
                        <span class="text-xs font-mono text-zinc-400 font-bold">Channel ID: #NTK-8802</span>
                    </div>

                    <h1 class="text-3xl font-black text-white tracking-tight">
                        Creator Hub & Studio — {{ Auth::user()->name }} 🎬
                    </h1>
                    <p class="text-xs text-zinc-400">
                        Kelola publikasi film, analisis statistik penonton, dan pantau pendapatan karya kamu.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button @click="showUploadModal = true" class="py-3 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
                        <span>Unggah Film & Video Baru</span>
                    </button>
                </div>
            </div>

            <!-- CREATOR METRICS ANALYTICS GRID (4 CARDS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Metric 1: Total Views -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Penonton (Views)</span>
                        <div class="w-8 h-8 rounded-lg bg-red-600/10 text-red-500 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white font-mono tracking-tight">842,150</div>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-400">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5z"/></svg>
                        <span>+24.5% dari bulan lalu</span>
                    </div>
                </div>

                <!-- Metric 2: Total Watch Time -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Durasi Tonton (Watch Time)</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-600/10 text-purple-400 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white font-mono tracking-tight">124,800 <span class="text-xs text-zinc-400 font-normal">jam</span></div>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-400">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5z"/></svg>
                        <span>+18.2% retensi penonton</span>
                    </div>
                </div>

                <!-- Metric 3: Total Subscribers -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Subscribers</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-600/10 text-emerald-400 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white font-mono tracking-tight">128.5K</div>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-amber-400">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5z"/></svg>
                        <span>+1.2K pengikut baru</span>
                    </div>
                </div>

                <!-- Metric 4: Estimated Earnings -->
                <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-2 shadow-lg relative overflow-hidden backdrop-blur-xl group hover:border-zinc-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Estimasi Pendapatan</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-400 font-mono tracking-tight">Rp 14.850.000</div>
                    <div class="flex items-center justify-between text-[10px] text-zinc-400">
                        <span>Pencairan: 15 Okt 2026</span>
                        <span class="text-emerald-400 font-bold">Siap Cair</span>
                    </div>
                </div>
            </div>

            <!-- MEDIA LIBRARY TABLE & CONTENT MANAGEMENT -->
            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden shadow-2xl backdrop-blur-xl space-y-4">
                
                <!-- Table Action Bar -->
                <div class="p-6 border-b border-zinc-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-white tracking-tight">Perpustakaan Konten & Video Kamu</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Kelola status publikasi, metadata episode, dan statistik per video</p>
                    </div>

                    <!-- Category Filters -->
                    <div class="flex items-center gap-2">
                        <select x-model="selectedTab" class="px-3 py-1.5 text-xs rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 focus:outline-none focus:border-red-600 transition font-bold">
                            <option value="all">Semua Status Rilis</option>
                            <option value="published">Terpublikasi (Published)</option>
                            <option value="processing">Sedang Diproses (Processing)</option>
                            <option value="draft">Draft Simpanan</option>
                        </select>
                    </div>
                </div>

                <!-- Table View -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-zinc-300">
                        <thead class="bg-zinc-950/80 text-zinc-400 uppercase tracking-wider text-[10px] border-b border-zinc-800/80 font-bold">
                            <tr>
                                <th class="px-6 py-3.5">Judul Karya & Media</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5">Status Rilis</th>
                                <th class="px-6 py-3.5">Total Views</th>
                                <th class="px-6 py-3.5">Rating</th>
                                <th class="px-6 py-3.5">Pendapatan</th>
                                <th class="px-6 py-3.5 text-right">Aksi Studio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80">
                            <!-- Item 1 -->
                            <tr class="hover:bg-zinc-800/40 transition">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-16 h-10 rounded-lg overflow-hidden bg-zinc-800 shrink-0 border border-zinc-700 relative">
                                        <img src="{{ asset('images/hero_banner.jpg') }}" alt="Cyberpunk Shadows" class="w-full h-full object-cover">
                                        <span class="absolute bottom-0.5 right-0.5 px-1 rounded bg-red-600 text-white font-mono text-[7px] font-black">4K UHD</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="font-bold text-white hover:text-red-400 transition truncate max-w-xs block">
                                            Cyberpunk Shadows — Episode 1: Neon Genesis
                                        </a>
                                        <span class="text-[10px] text-zinc-500 font-mono">48m 20s • Multi-Subtitles</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-purple-400">Sci-Fi Series</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                        Published
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-white">450,200</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-emerald-400 font-bold">Rp 8.240.000</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('movies.show', 'cyberpunk-shadows') }}" class="text-red-400 hover:text-red-300 font-bold mr-3">Tonton</a>
                                    <button class="text-zinc-400 hover:text-white font-medium">Edit Meta</button>
                                </td>
                            </tr>

                            <!-- Item 2 -->
                            <tr class="hover:bg-zinc-800/40 transition">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-16 h-10 rounded-lg overflow-hidden bg-zinc-800 shrink-0 border border-zinc-700 relative">
                                        <img src="{{ asset('images/poster_action.jpg') }}" alt="Midnight Drift" class="w-full h-full object-cover">
                                        <span class="absolute bottom-0.5 right-0.5 px-1 rounded bg-zinc-950 text-zinc-200 font-mono text-[7px] font-black">FHD</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('movies.show', 'midnight-drift') }}" class="font-bold text-white hover:text-red-400 transition truncate max-w-xs block">
                                            Midnight Drift — Feature Cut
                                        </a>
                                        <span class="text-[10px] text-zinc-500 font-mono">1h 52m • Stereo 2.0</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-red-500">Action Thriller</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1 w-fit">
                                        Published
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-white">280,100</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 font-bold text-zinc-200">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        <span>4.9</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-emerald-400 font-bold">Rp 4.910.000</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('movies.show', 'midnight-drift') }}" class="text-red-400 hover:text-red-300 font-bold mr-3">Tonton</a>
                                    <button class="text-zinc-400 hover:text-white font-medium">Edit Meta</button>
                                </td>
                            </tr>

                            <!-- Item 3 (Processing state) -->
                            <tr class="hover:bg-zinc-800/40 transition">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-16 h-10 rounded-lg overflow-hidden bg-zinc-800 shrink-0 border border-zinc-700 flex items-center justify-center font-black text-[9px] text-sky-400 font-mono">
                                        85%
                                    </div>
                                    <div>
                                        <div class="font-bold text-white">Behind The Scenes: Cyberpunk World Building</div>
                                        <span class="text-[10px] text-zinc-500 font-mono">18m 40s • Rendering 4K HDR</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-sky-400">Documentary</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span>
                                        Diproses (85%)
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-zinc-600">-</td>
                                <td class="px-6 py-4 font-mono text-zinc-600">-</td>
                                <td class="px-6 py-4 font-mono text-zinc-600">-</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-sky-400 hover:text-sky-300 font-bold">Cek Progress</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AUDIENCE COMMENTS & DIRECT ENGAGEMENT SECTION -->
            <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl p-6 space-y-4 shadow-xl backdrop-blur-xl">
                <div class="flex items-center justify-between border-b border-zinc-800/80 pb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-white tracking-tight">Komentar Penonton Terbaru pada Karya Kamu</h3>
                        <p class="text-xs text-zinc-400">Balas langsung komentar penggemar di film kamu</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 font-mono text-xs font-bold">3 Komentar Baru</span>
                </div>

                <div class="space-y-3">
                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center">A</div>
                                <div>
                                    <span class="font-bold text-white text-xs">Alex Rivera</span>
                                    <span class="text-[10px] text-zinc-500 ml-1">pada <strong class="text-zinc-300">Cyberpunk Shadows</strong></span>
                                </div>
                            </div>
                            <span class="text-[10px] text-zinc-500 font-mono">15m lalu</span>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed pl-9">Gokil sinematografi nya dapet banget vibes cyberpunk-nya! Episode 3 paling epic pertarungannya 🔥</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center">S</div>
                                <div>
                                    <span class="font-bold text-white text-xs">Sarah Connor</span>
                                    <span class="text-[10px] text-zinc-500 ml-1">pada <strong class="text-zinc-300">Midnight Drift</strong></span>
                                </div>
                            </div>
                            <span class="text-[10px] text-zinc-500 font-mono">2j lalu</span>
                        </div>
                        <p class="text-xs text-zinc-300 leading-relaxed pl-9">Editing suara dan soundtracknya juara sih, pas banget dikombinasiin sama visual neonnya.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- INTERACTIVE UPLOAD NEW MOVIE MODAL -->
        <div x-show="showUploadModal" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">
            <!-- Backdrop -->
            <div @click="showUploadModal = false" x-show="showUploadModal" x-transition class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <!-- Modal Content Card -->
            <div x-show="showUploadModal" x-transition class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-xl w-full relative z-10 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-red-600/15 border border-red-600/30 text-red-400 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight">Unggah Film / Video Baru</h3>
                            <p class="text-xs text-zinc-400">Upload karya film 4K atau episode serial ke WeWatch Cinema</p>
                        </div>
                    </div>

                    <button @click="showUploadModal = false" class="p-1 rounded-xl bg-zinc-800 text-zinc-400 hover:text-white transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                </div>

                <!-- Form Inputs -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Judul Film / Episode</label>
                        <input type="text" x-model="newVideo.title" placeholder="Contoh: Cyberpunk Shadows — Episode 6" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Kategori Genre</label>
                            <select x-model="newVideo.category" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 focus:outline-none focus:border-red-600 transition">
                                <option value="Sci-Fi Series">Sci-Fi Series</option>
                                <option value="Action Thriller">Action Thriller</option>
                                <option value="Documentary">Documentary</option>
                                <option value="Fantasy Epic">Fantasy Epic</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Akses Penonton</label>
                            <select x-model="newVideo.access" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 focus:outline-none focus:border-red-600 transition">
                                <option value="public">Publik (Semua User)</option>
                                <option value="pro">Eksklusif Pro & VIP</option>
                                <option value="vip">Khusus VIP</option>
                            </select>
                        </div>
                    </div>

                    <!-- Drag and drop zone -->
                    <div>
                        <label class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">File Master Video (4K / Full HD)</label>
                        <div class="border-2 border-dashed border-zinc-800 hover:border-red-600/50 rounded-2xl p-6 text-center bg-zinc-950/60 transition cursor-pointer space-y-2">
                            <svg class="w-8 h-8 text-red-500 mx-auto fill-current" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                            <div class="text-xs text-zinc-300 font-bold">Klik atau geser file video .MP4 / .MKV ke sini</div>
                            <span class="text-[10px] text-zinc-500 font-mono block">Maksimal resolusi 4K Ultra HD • H.264 / HEVC</span>
                        </div>
                    </div>

                    <!-- Uploading Progress Bar -->
                    <div x-show="isUploading" class="space-y-1.5 pt-2" style="display: none;">
                        <div class="flex justify-between text-xs font-mono font-bold">
                            <span class="text-red-400">Mengunggah ke WeWatch Server...</span>
                            <span class="text-white" x-text="uploadProgress + '%'"></span>
                        </div>
                        <div class="w-full bg-zinc-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-red-600 h-full rounded-full transition-all duration-300" :style="'width: ' + uploadProgress + '%'"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                    <button @click="showUploadModal = false" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                        Batal
                    </button>

                    <button @click="startUpload()" :disabled="isUploading" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
                        <span x-text="isUploading ? 'Proses Unggah...' : 'Unggah & Publikasikan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
