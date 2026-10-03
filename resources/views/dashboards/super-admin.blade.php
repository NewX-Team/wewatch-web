<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="WeWatch Super Admin — System Control Console untuk mengelola pengguna, kreator, konten, dan kesehatan platform.">

        <title>System Control Console — WeWatch Super Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen overflow-x-hidden"
          x-data="{
              sidebarOpen: false,
              collapsed: localStorage.getItem('admin_sidebar_collapsed') === 'true',
              showCreateModal: {{ $errors->any() ? 'true' : 'false' }},
              searchQuery: '',
              filterRole: 'all',
              toggleCollapse() {
                  this.collapsed = !this.collapsed;
                  localStorage.setItem('admin_sidebar_collapsed', this.collapsed);
              }
          }">

        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Lights -->
        <div class="fixed top-0 left-1/3 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
        <div class="fixed bottom-0 right-0 w-[600px] h-[300px] bg-rose-500/5 rounded-full blur-[150px] pointer-events-none z-0"></div>

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
             class="fixed inset-0 bg-zinc-950/80 backdrop-blur-md z-40 lg:hidden" style="display: none;"></div>

        <!-- ============ LEFT SIDEBAR ============ -->
        <aside id="admin-sidebar"
               :class="[
                   sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                   collapsed ? 'lg:w-20' : 'lg:w-72'
               ]"
               class="fixed top-0 left-0 h-screen w-72 z-50 bg-zinc-950/95 border-r border-zinc-800/80 backdrop-blur-2xl flex flex-col transition-all duration-300 ease-out">

            <!-- Brand -->
            <div :class="collapsed ? 'lg:justify-center lg:px-2' : 'lg:justify-between lg:px-5'"
                 class="h-20 flex items-center justify-between px-5 border-b border-zinc-800/80 shrink-0 transition-all">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-600 to-rose-700 flex items-center justify-center font-black text-white shadow-lg shadow-red-600/30 shrink-0 group-hover:scale-105 transition">
                        W
                    </div>
                    <div x-show="!collapsed" x-transition.opacity class="min-w-0">
                        <span class="font-black text-base tracking-tight text-white block leading-none">WEWATCH</span>
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-red-500">Super Admin</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition" aria-label="Tutup sidebar">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6">
                <div class="space-y-1">
                    <span x-show="!collapsed" class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-600 block mb-2">Utama</span>
                    <a href="{{ route('admin.dashboard') }}"
                       :class="collapsed ? 'lg:justify-center lg:px-0' : 'lg:px-3'"
                       class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border text-xs font-semibold transition bg-red-600/15 text-white border-red-600/30">
                        <svg class="w-[18px] h-[18px] fill-current text-red-500 shrink-0" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">System Control</span>
                    </a>
                </div>

                <div class="space-y-1">
                    <span x-show="!collapsed" class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-600 block mb-2">Manajemen</span>
                    <button @click="showCreateModal = true"
                            :class="collapsed ? 'lg:justify-center lg:px-0' : 'lg:px-3'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        <svg class="w-[18px] h-[18px] fill-current text-zinc-500 shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Tambah Akun Baru</span>
                    </button>
                </div>
            </nav>

            <!-- Realtime System Status Card -->
            <div x-show="!collapsed" class="mx-3 mb-3 p-4 rounded-2xl bg-gradient-to-br from-zinc-900 to-zinc-950 border border-zinc-800/80 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Database Realtime</span>
                    <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online
                    </span>
                </div>
                <div class="text-xs font-mono text-zinc-300">
                    Total: <strong class="text-white">{{ $stats['total_users'] }}</strong> Akun
                </div>
            </div>

            <!-- Profile & Logout Dropdown -->
            <div class="border-t border-zinc-800/80 p-3 shrink-0" x-data="{ menu: false }">
                <div class="relative">
                    <button @click="menu = !menu"
                            :class="collapsed ? 'lg:justify-center lg:p-1.5' : 'lg:p-2'"
                            class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-zinc-900 transition">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-600 to-rose-700 text-white font-black text-sm flex items-center justify-center shrink-0 border border-red-500/30">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div x-show="!collapsed" class="min-w-0 flex-1 text-left">
                            <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-red-400 font-semibold">Super Administrator</div>
                        </div>
                    </button>

                    <div x-show="menu" @click.away="menu = false" x-transition
                         class="absolute bottom-full left-0 mb-2 w-60 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl p-2 text-xs text-zinc-300 space-y-1 z-50" style="display: none;">
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">
                            <svg class="w-4 h-4 fill-current text-zinc-500" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            Lihat Mode Penonton
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">
                            <svg class="w-4 h-4 fill-current text-zinc-500" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Pengaturan Akun
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-red-600/20 text-red-400 font-semibold transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Collapse Sidebar Button (Desktop) -->
                <button @click="toggleCollapse()" class="hidden lg:flex w-full items-center justify-center gap-2 mt-2 py-2 rounded-xl text-[11px] font-semibold text-zinc-500 hover:text-white hover:bg-zinc-900 transition">
                    <svg :class="collapsed ? 'rotate-180' : ''" class="w-4 h-4 fill-current transition" viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
                    <span x-show="!collapsed">Ciutkan Sidebar</span>
                </button>
            </div>
        </aside>

        <!-- ============ MAIN AREA ============ -->
        <div :class="collapsed ? 'lg:pl-20' : 'lg:pl-72'" class="relative z-10 min-h-screen transition-all duration-300">

            <!-- Top Header Bar -->
            <header class="sticky top-0 z-30 h-20 flex items-center justify-between gap-4 px-4 sm:px-8 bg-zinc-950/80 backdrop-blur-xl border-b border-zinc-800/80">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white transition">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                    </button>
                    <div class="relative hidden sm:block">
                        <svg class="w-4 h-4 text-zinc-500 fill-current absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama atau email akun..."
                               class="w-64 lg:w-96 pl-9 pr-4 py-2 rounded-xl bg-zinc-900 border border-zinc-800 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button @click="showCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-extrabold shadow-lg shadow-red-600/30 transition active:scale-95">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        <span>Tambah Akun Baru</span>
                    </button>
                </div>
            </header>

            <main class="px-4 sm:px-8 py-8 space-y-8 max-w-[1600px]">

                <!-- OVERVIEW HEADER -->
                <section class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[10px] uppercase tracking-wider">Super Admin Access</span>
                        <span class="text-xs font-mono text-zinc-500">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-white tracking-tight">System Control Console</h1>
                    <p class="text-xs text-zinc-400">Ringkasan data realtime platform WeWatch langsung dari basis data.</p>
                </section>

                <!-- REALTIME DATABASE STAT CARDS (4 CARDS) -->
                <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                    <!-- Stat 1: Total Registered Users -->
                    <div class="relative overflow-hidden bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-3 backdrop-blur-xl hover:border-zinc-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Akun Terdaftar</span>
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center bg-red-600/10 text-red-500 border-red-600/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white font-mono tracking-tight">{{ number_format($stats['total_users']) }}</div>
                        <div class="text-[11px] font-bold text-zinc-400">Database Realtime Sync</div>
                    </div>

                    <!-- Stat 2: Active Creators -->
                    <div class="relative overflow-hidden bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-3 backdrop-blur-xl hover:border-zinc-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Kreator Studio</span>
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center bg-amber-500/10 text-amber-400 border-amber-500/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H9l2 4H8L6 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-amber-400 font-mono tracking-tight">{{ number_format($stats['creators']) }}</div>
                        <div class="text-[11px] font-bold text-amber-400">Role: Creator Studio</div>
                    </div>

                    <!-- Stat 3: Super Admins -->
                    <div class="relative overflow-hidden bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-3 backdrop-blur-xl hover:border-zinc-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Super Administrator</span>
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center bg-rose-500/10 text-rose-400 border-rose-500/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-rose-400 font-mono tracking-tight">{{ number_format($stats['super_admins']) }}</div>
                        <div class="text-[11px] font-bold text-rose-400">Role: Super Admin</div>
                    </div>

                    <!-- Stat 4: Suspended Users -->
                    <div class="relative overflow-hidden bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-3 backdrop-blur-xl hover:border-zinc-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Akun Di-suspend</span>
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center bg-red-600/10 text-red-400 border-red-600/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 17.31C15.55 18.37 13.85 19 12 19zm4.31-2.1L5.69 6.29C7.05 5.23 8.75 4.6 10.6 4.6c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.90z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-red-500 font-mono tracking-tight">{{ number_format($stats['suspended_users']) }}</div>
                        <div class="text-[11px] font-bold text-zinc-400">Status: Suspended</div>
                    </div>
                </section>

                <!-- REAL USERS DATABASE MANAGEMENT TABLE -->
                <section class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden backdrop-blur-xl space-y-4 shadow-2xl">
                    <div class="p-6 border-b border-zinc-800/80 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-extrabold text-white tracking-tight">Daftar Akun Pengguna (Database Realtime)</h2>
                            <p class="text-xs text-zinc-400 mt-0.5">Kelola pembuatan akun baru, buka/tutup suspensi, serta hapus akun pengguna di bawah Super Admin</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-1 p-1 rounded-xl bg-zinc-950 border border-zinc-800 text-[11px] font-bold">
                                <button @click="filterRole = 'all'" :class="filterRole === 'all' ? 'bg-zinc-800 text-white' : 'text-zinc-500 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">Semua</button>
                                <button @click="filterRole = 'super_admin'" :class="filterRole === 'super_admin' ? 'bg-zinc-800 text-white' : 'text-zinc-500 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">Admin</button>
                                <button @click="filterRole = 'creator'" :class="filterRole === 'creator' ? 'bg-zinc-800 text-white' : 'text-zinc-500 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">Kreator</button>
                                <button @click="filterRole = 'user'" :class="filterRole === 'user' ? 'bg-zinc-800 text-white' : 'text-zinc-500 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">User biasa</button>
                            </div>

                            <button @click="showCreateModal = true" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-extrabold shadow-lg shadow-red-600/20 transition active:scale-95 flex items-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                <span>Tambah Akun</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full">
                        <table class="w-full text-left text-xs text-zinc-300">
                            <thead class="bg-zinc-950/80 text-zinc-500 uppercase tracking-wider text-[10px] font-bold border-b border-zinc-800/80 whitespace-nowrap">
                                <tr>
                                    <th class="px-6 py-3.5">Akun Pengguna</th>
                                    <th class="px-6 py-3.5">Role</th>
                                    <th class="px-6 py-3.5">Status Account</th>
                                    <th class="px-6 py-3.5">Tgl Bergabung</th>
                                    <th class="px-6 py-3.5 text-right">Aksi Super Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/80">
                                @forelse ($users as $u)
                                    @php
                                        $userRoleVal = $u->role instanceof \App\Enums\UserRole ? $u->role->value : (string) $u->role;
                                        if ($u->isRootAdmin()) {
                                            $roleBadge = ['Root Super Admin', 'bg-amber-500/20 text-amber-300 border-amber-500/40 shadow-sm shadow-amber-500/20', 'from-amber-500 via-amber-600 to-amber-700'];
                                        } else {
                                            $roleBadge = match ($userRoleVal) {
                                                'super_admin' => ['Admin Console', 'bg-rose-500/10 text-rose-400 border-rose-500/20', 'from-rose-600 to-rose-800'],
                                                'creator' => ['Creator Studio', 'bg-purple-500/10 text-purple-400 border-purple-500/20', 'from-purple-600 to-purple-800'],
                                                default => ['Standard User', 'bg-zinc-800 text-zinc-300 border-zinc-700', 'from-zinc-700 to-zinc-900'],
                                            };
                                        }
                                    @endphp
                                    <tr x-show="(filterRole === 'all' || filterRole === '{{ $userRoleVal }}') && (searchQuery === '' || '{{ strtolower($u->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($u->email) }}'.includes(searchQuery.toLowerCase()))"
                                        class="hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $roleBadge[2] }} text-white font-black text-xs flex items-center justify-center shrink-0 border border-zinc-700">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-bold text-white flex items-center gap-1.5 min-w-0">
                                                        <span class="truncate max-w-[160px] sm:max-w-[240px]">{{ $u->name }}</span>
                                                        @if($u->id === Auth::user()->id)
                                                            <span class="px-1.5 py-0.2 rounded bg-red-600/20 text-red-400 text-[9px] font-mono font-bold shrink-0">Anda</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[11px] text-zinc-500 font-mono truncate max-w-[180px] sm:max-w-[260px]">{{ $u->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-md border text-[10px] font-bold {{ $roleBadge[1] }}">
                                                {{ $roleBadge[0] }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($u->is_suspended)
                                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] text-red-400">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                    Suspended
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] text-emerald-400">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-zinc-400 font-mono text-[11px] whitespace-nowrap">
                                            {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($u->isRootAdmin())
                                                    <span class="px-2.5 py-1 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 font-extrabold text-[10px] tracking-wide flex items-center gap-1">
                                                        <span>👑 Root Admin Mutlak</span>
                                                    </span>
                                                @elseif($u->isSuperAdmin())
                                                    @if(Auth::user()->isRootAdmin())
                                                        <!-- Root Admin Can Delete Sub-Admin Accounts -->
                                                        <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGHAPUS PERMANEN akun Admin {{ addslashes($u->name) }}? Action ini tidak bisa dibatalkan.')" class="px-3 py-1.5 rounded-lg bg-red-600/15 hover:bg-red-600/30 border border-red-600/30 text-red-400 text-xs font-bold transition">
                                                                Hapus Admin
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="px-2.5 py-1 rounded-xl bg-zinc-900 border border-zinc-800 text-zinc-500 font-mono text-[10px]">
                                                            🔒 Akses Khusus Root
                                                        </span>
                                                    @endif
                                                @else
                                                    <!-- Toggle Suspend Button -->
                                                    <form method="POST" action="{{ route('admin.users.toggle-suspend', $u->id) }}" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($u->is_suspended)
                                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600/15 hover:bg-emerald-600/25 border border-emerald-500/30 text-emerald-400 text-xs font-bold transition">
                                                                Buka Suspensi
                                                            </button>
                                                        @else
                                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mematikan/suspend akun {{ addslashes($u->name) }}?')" class="px-3 py-1.5 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-400 text-xs font-bold transition">
                                                                Suspend Akun
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Delete Account Button -->
                                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENGHAPUS PERMANEN akun {{ addslashes($u->name) }}? Action ini tidak bisa dibatalkan.')" class="px-3 py-1.5 rounded-lg bg-red-600/15 hover:bg-red-600/30 border border-red-600/30 text-red-400 text-xs font-bold transition">
                                                            Hapus Akun
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                            Belum ada data akun terdaftar di basis data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-zinc-800/80 flex items-center justify-between text-[11px] text-zinc-400">
                        <span>Menampilkan <strong>{{ count($users) }}</strong> dari <strong>{{ $stats['total_users'] }}</strong> akun terdaftar di basis data</span>
                        <span class="text-zinc-600 font-mono">WeWatch Control Console</span>
                    </div>
                </section>
            </main>
        </div>

        <!-- ============ MODAL: TAMBAH AKUN BARU ============ -->
        <div x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 flex items-center justify-center" style="display: none;">
            <div @click="showCreateModal = false" x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="showCreateModal" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full relative z-10 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-red-600/15 border border-red-600/30 text-red-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight">Buat Akun Pengguna Baru</h3>
                            <p class="text-xs text-zinc-400">Tambahkan akun User biasa, Creator Studio, atau Super Admin baru</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="p-2 rounded-xl bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700 transition shrink-0" aria-label="Tutup modal">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 13.41 12z"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="modal_name" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Nama Lengkap / Nama Studio</label>
                        <input id="modal_name" name="name" type="text" value="{{ old('name') }}" required placeholder="Contoh: Alexander Cinema / Studio Neo" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600/30 transition">
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <label for="modal_email" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Alamat Email Resmi</label>
                        <input id="modal_email" name="email" type="email" value="{{ old('email') }}" required placeholder="email@domain.com" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 font-mono placeholder-zinc-500 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600/30 transition">
                        <x-input-error class="mt-1" :messages="$errors->get('email')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_role" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Pilih Role Akun</label>
                            <select id="modal_role" name="role" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 font-bold focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600/30 transition">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User Biasa (Standard)</option>
                                <option value="creator" {{ old('role') === 'creator' ? 'selected' : '' }}>Creator Studio (Kreator)</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Console)</option>
                            </select>
                            <x-input-error class="mt-1" :messages="$errors->get('role')" />
                        </div>

                        <div>
                            <label for="modal_password" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Kata Sandi Akun</label>
                            <input id="modal_password" name="password" type="password" required placeholder="Min 8 karakter..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600/30 transition">
                            <x-input-error class="mt-1" :messages="$errors->get('password')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                        <button type="button" @click="showCreateModal = false" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                            Batal
                        </button>

                        <button type="submit" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            <span>Buat Akun Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
