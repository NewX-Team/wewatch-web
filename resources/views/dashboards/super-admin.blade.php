<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="WeWatch Super Admin — System Control Console untuk mengelola pengguna, kreator, pengumuman, dan kesehatan platform.">

        <title>System Control Console — WeWatch Super Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen overflow-x-hidden"
          x-data="{
              sidebarOpen: false,
              activeTab: 'users',
              collapsed: localStorage.getItem('admin_sidebar_collapsed') === 'true',
              showCreateModal: {{ $errors->has('name') || $errors->has('email') ? 'true' : 'false' }},
              showAnnouncementModal: {{ $errors->has('title') || $errors->has('content') ? 'true' : 'false' }},
              searchQuery: '',
              filterRole: 'all',

              // Custom Themed Confirmation Modal State
              confirmModal: {
                  show: false,
                  title: '',
                  message: '',
                  confirmText: 'Ya, Lanjutkan',
                  cancelText: 'Batal',
                  type: 'danger',
                  targetFormId: null,
              },

              confirmAction({ title, message, confirmText, cancelText, type, formId }) {
                  this.confirmModal.title = title || 'Konfirmasi Tindakan';
                  this.confirmModal.message = message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                  this.confirmModal.confirmText = confirmText || 'Ya, Lanjutkan';
                  this.confirmModal.cancelText = cancelText || 'Batal';
                  this.confirmModal.type = type || 'danger';
                  this.confirmModal.targetFormId = formId;
                  this.confirmModal.show = true;
              },

              executeConfirmedAction() {
                  if (this.confirmModal.targetFormId) {
                      const form = document.getElementById(this.confirmModal.targetFormId);
                      if (form) form.submit();
                  }
                  this.confirmModal.show = false;
              },

              // Announcement Form State for Quick Templates
              annTitle: '',
              annType: 'info',
              annTarget: 'all',
              annContent: '',

              toggleCollapse() {
                  this.collapsed = !this.collapsed;
                  localStorage.setItem('admin_sidebar_collapsed', this.collapsed);
              },

              applyTemplate(type) {
                  switch(type) {
                      case 'promo':
                          this.annTitle = '🍿 Promo Spesial: Diskon 50% Langganan VIP Cinema 4K!';
                          this.annType = 'promo';
                          this.annTarget = 'all';
                          this.annContent = 'Nikmati seluruh tayangan film sinematik 4K UHD & audio Dolby Atmos tanpa gangguan iklan dengan harga hemat 50% khusus bulan ini. Gunakan kode voucher WEWATCH50 saat pembayaran!';
                          break;
                      case 'info':
                          this.annTitle = '📢 Pembaruan Sistem & Pemeliharaan Server WeWatch v2.4';
                          this.annType = 'info';
                          this.annTarget = 'all';
                          this.annContent = 'Halo Penonton & Kreator! Kami telah menyelesaikan pembaruan infrastruktur jaringan streaming. Performa pemutaran video kini 2x lebih cepat dengan dukungan Spatial Cinema Sound.';
                          break;
                      case 'event':
                          this.annTitle = '🎬 Kompetisi Film Pendek Sinematik WeWatch 2026';
                          this.annType = 'event';
                          this.annTarget = 'creator';
                          this.annContent = 'Bagi para kreator film mandiri dan studio independen, daftarkan karya sinema terbaru Anda! Menangkan hibah dana produksi total Rp 50.000.000 dan kesempatan tayang eksklusif di WeWatch Originals.';
                          break;
                      case 'warning':
                          this.annTitle = '🔒 Himbauan Keamanan: Perbarui Kata Sandi Akun Anda';
                          this.annType = 'warning';
                          this.annTarget = 'all';
                          this.annContent = 'Untuk menjaga keamanan akun Anda dari akses yang tidak sah, kami menyarankan seluruh pengguna untuk memperbarui kata sandi secara berkala dan tidak membagikan kredensial login kepada siapa pun.';
                          break;
                  }
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
                    <button @click="activeTab = 'users'"
                            :class="activeTab === 'users' ? 'bg-red-600/15 text-white border-red-600/30' : 'border-transparent text-zinc-400 hover:text-white hover:bg-zinc-900'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border text-xs font-semibold transition">
                        <svg class="w-[18px] h-[18px] fill-current text-red-500 shrink-0" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Kelola Akun Pengguna</span>
                    </button>

                    <button @click="activeTab = 'creators'"
                            :class="activeTab === 'creators' ? 'bg-red-600/15 text-white border-red-600/30' : 'border-transparent text-zinc-400 hover:text-white hover:bg-zinc-900'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border text-xs font-semibold transition">
                        <svg class="w-[18px] h-[18px] fill-current text-blue-500 shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Manajemen Kreator</span>
                        <span x-show="!collapsed && {{ $stats['verified_creators'] ?? 0 }} > 0" class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 text-[10px] font-bold">{{ $stats['verified_creators'] }} Verified</span>
                    </button>

                    <button @click="activeTab = 'announcements'"
                            :class="activeTab === 'announcements' ? 'bg-red-600/15 text-white border-red-600/30' : 'border-transparent text-zinc-400 hover:text-white hover:bg-zinc-900'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border text-xs font-semibold transition">
                        <svg class="w-[18px] h-[18px] fill-current text-amber-500 shrink-0" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Informasi &amp; Siaran</span>
                        <span x-show="!collapsed && {{ $stats['active_announcements'] }} > 0" class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 text-[10px] font-bold">{{ $stats['active_announcements'] }}</span>
                    </button>
                </div>

                <div class="space-y-1">
                    <span x-show="!collapsed" class="px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-600 block mb-2">Aksi Cepat</span>
                    <button @click="showCreateModal = true"
                            :class="collapsed ? 'lg:justify-center lg:px-0' : 'lg:px-3'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-900 transition">
                        <svg class="w-[18px] h-[18px] fill-current text-zinc-500 shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Tambah Akun Baru</span>
                    </button>

                    <button @click="showAnnouncementModal = true"
                            :class="collapsed ? 'lg:justify-center lg:px-0' : 'lg:px-3'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent text-xs font-semibold text-amber-400/90 hover:text-amber-300 hover:bg-zinc-900 transition">
                        <svg class="w-[18px] h-[18px] fill-current text-amber-500 shrink-0" viewBox="0 0 24 24"><path d="M18 11l-5-5-1.41 1.41L14.17 10H3v2h11.17l-2.58 2.59L13 16l5-5zM20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/></svg>
                        <span x-show="!collapsed" class="truncate flex-1 text-left">Buat Siaran Baru</span>
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
                <div class="text-[11px] font-mono text-amber-400">
                    Siaran Aktif: <strong class="text-white">{{ $stats['active_announcements'] }}</strong> Pengumuman
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
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-zinc-800 hover:text-white transition">
                            <svg class="w-4 h-4 fill-current text-red-500" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            Control Console Admin
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
                        <input type="text" x-model="searchQuery" placeholder="Cari nama, email, atau judul siaran..."
                               class="w-64 lg:w-96 pl-9 pr-4 py-2 rounded-xl bg-zinc-900 border border-zinc-800 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button @click="showAnnouncementModal = true" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-400 text-xs font-extrabold transition active:scale-95">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/></svg>
                        <span>Buat Siaran Baru</span>
                    </button>

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

                    <!-- Stat 3: Active Announcements -->
                    <div class="relative overflow-hidden bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-5 space-y-3 backdrop-blur-xl hover:border-zinc-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Siaran Pengumuman Aktif</span>
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center bg-purple-500/10 text-purple-400 border-purple-500/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-purple-400 font-mono tracking-tight">{{ number_format($stats['active_announcements']) }}</div>
                        <div class="text-[11px] font-bold text-purple-400">Tampil di User Popup</div>
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

                <!-- TAB 1: REAL USERS DATABASE MANAGEMENT TABLE -->
                <section x-show="activeTab === 'users'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden backdrop-blur-xl space-y-4 shadow-2xl">
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
                                                        <form id="delete-admin-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" @click="confirmAction({ title: 'Hapus Akun Admin', message: 'Apakah Anda yakin ingin MENGHAPUS PERMANEN akun Admin {{ addslashes($u->name) }}? Action ini tidak dapat dibatalkan.', formId: 'delete-admin-form-{{ $u->id }}', type: 'danger', confirmText: 'Ya, Hapus Admin' })" class="px-3 py-1.5 rounded-lg bg-red-600/15 hover:bg-red-600/30 border border-red-600/30 text-red-400 text-xs font-bold transition">
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
                                                    <form id="suspend-user-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.toggle-suspend', $u->id) }}" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($u->is_suspended)
                                                            <button type="button" @click="confirmAction({ title: 'Buka Suspensi Akun', message: 'Apakah Anda yakin ingin membuka kembali akses akun {{ addslashes($u->name) }}?', formId: 'suspend-user-form-{{ $u->id }}', type: 'warning', confirmText: 'Ya, Buka Suspensi' })" class="px-3 py-1.5 rounded-lg bg-emerald-600/15 hover:bg-emerald-600/25 border border-emerald-500/30 text-emerald-400 text-xs font-bold transition">
                                                                Buka Suspensi
                                                            </button>
                                                        @else
                                                            <button type="button" @click="confirmAction({ title: 'Suspend Akun Pengguna', message: 'Apakah Anda yakin ingin menangguhkan/suspend akun {{ addslashes($u->name) }}? Pengguna tidak akan bisa masuk ke sistem.', formId: 'suspend-user-form-{{ $u->id }}', type: 'warning', confirmText: 'Ya, Suspend Akun' })" class="px-3 py-1.5 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/30 text-amber-400 text-xs font-bold transition">
                                                                Suspend Akun
                                                            </button>
                                                        @endif
                                                    </form>

                                                    <!-- Delete Account Button -->
                                                    <form id="delete-user-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" @click="confirmAction({ title: 'Hapus Akun Pengguna', message: 'Apakah Anda yakin ingin MENGHAPUS PERMANEN akun {{ addslashes($u->name) }}? Action ini tidak dapat dibatalkan.', formId: 'delete-user-form-{{ $u->id }}', type: 'danger', confirmText: 'Ya, Hapus Akun' })" class="px-3 py-1.5 rounded-lg bg-red-600/15 hover:bg-red-600/30 border border-red-600/30 text-red-400 text-xs font-bold transition">
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

                <!-- TAB 2: ANNOUNCEMENTS & BROADCAST MANAGEMENT TABLE -->
                <section x-show="activeTab === 'announcements'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden backdrop-blur-xl space-y-4 shadow-2xl">
                    <div class="p-6 border-b border-zinc-800/80 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-extrabold text-white tracking-tight">Manajemen Informasi &amp; Siaran Pengumuman Pop-up</h2>
                            <p class="text-xs text-zinc-400 mt-0.5">Pengumuman aktif akan muncul sebagai pop-up berurutan bagi pengguna biasa saat masuk ke dashboard</p>
                        </div>

                        <button @click="showAnnouncementModal = true" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 text-xs font-black shadow-lg shadow-amber-500/20 transition active:scale-95 flex items-center gap-1.5 shrink-0">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            <span>Buat Siaran Baru</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto max-w-full">
                        <table class="w-full text-left text-xs text-zinc-300">
                            <thead class="bg-zinc-950/80 text-zinc-500 uppercase tracking-wider text-[10px] font-bold border-b border-zinc-800/80 whitespace-nowrap">
                                <tr>
                                    <th class="px-6 py-3.5">Judul &amp; Isi Siaran</th>
                                    <th class="px-6 py-3.5">Kategori / Tipe</th>
                                    <th class="px-6 py-3.5">Target Audience</th>
                                    <th class="px-6 py-3.5">Status Pop-up</th>
                                    <th class="px-6 py-3.5 text-right">Aksi Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/80">
                                @forelse ($announcements as $ann)
                                    <tr x-show="searchQuery === '' || '{{ strtolower($ann->title) }}'.includes(searchQuery.toLowerCase())" class="hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4">
                                            <div class="space-y-1 max-w-md">
                                                <div class="font-black text-white text-xs leading-snug">{{ $ann->title }}</div>
                                                <div class="text-[11px] text-zinc-400 line-clamp-2 leading-relaxed font-medium">{{ $ann->content }}</div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-md border text-[10px] font-bold {{ $ann->type_badge_color }}">
                                                {{ $ann->type_label }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-md bg-zinc-800 border border-zinc-700 text-zinc-300 font-mono text-[10px] font-bold uppercase">
                                                {{ $ann->target_role === 'all' ? 'Semua User' : ($ann->target_role === 'creator' ? 'Kreator Studio' : 'User Biasa') }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($ann->is_active)
                                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] text-emerald-400">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                    Aktif (Tampil Pop-up)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] text-zinc-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-600"></span>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Toggle Active Status Button -->
                                                <form method="POST" action="{{ route('admin.announcements.toggle', $ann->id) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($ann->is_active)
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 text-zinc-300 text-xs font-bold transition">
                                                            Nonaktifkan
                                                        </button>
                                                    @else
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600/15 hover:bg-emerald-600/25 border border-emerald-500/30 text-emerald-400 text-xs font-bold transition">
                                                            Aktifkan Siaran
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Delete Announcement Button -->
                                                <form id="delete-ann-form-{{ $ann->id }}" method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" @click="confirmAction({ title: 'Hapus Siaran Pengumuman', message: 'Apakah Anda yakin ingin MENGHAPUS siaran pengumuman \'{{ addslashes($ann->title) }}\'?', formId: 'delete-ann-form-{{ $ann->id }}', type: 'danger', confirmText: 'Ya, Hapus Siaran' })" class="px-3 py-1.5 rounded-lg bg-red-600/15 hover:bg-red-600/30 border border-red-600/30 text-red-400 text-xs font-bold transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                            Belum ada siaran pengumuman yang diterbitkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-zinc-800/80 flex items-center justify-between text-[11px] text-zinc-400">
                        <span>Menampilkan <strong>{{ count($announcements) }}</strong> siaran pengumuman di basis data</span>
                        <span class="text-zinc-600 font-mono">Sequential Broadcast System</span>
                    </div>
                </section>

                <!-- TAB 3: CREATOR MANAGEMENT & VERIFICATION TABLE -->
                <section x-show="activeTab === 'creators'" class="bg-zinc-900/90 border border-zinc-800/90 rounded-3xl overflow-hidden backdrop-blur-xl space-y-4 shadow-2xl">
                    <div class="p-6 border-b border-zinc-800/80 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded bg-blue-500/15 border border-blue-500/30 text-blue-400 font-extrabold text-[10px] uppercase tracking-wider">
                                    MANAJEMEN KREATOR OFFICIAL
                                </span>
                                <span class="text-xs font-mono text-zinc-400 font-bold">{{ $stats['verified_creators'] }} Terverifikasi</span>
                            </div>
                            <h2 class="text-xl font-extrabold text-white tracking-tight mt-1">
                                Verifikasi Channel Kreator &amp; Akses Monetisasi (Centang Biru)
                            </h2>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Berikan centang biru resmi dan buka akses fitur pendapatan/monetisasi untuk akun kreator terpercaya.
                            </p>
                        </div>
                    </div>

                    <div class="overflow-x-auto max-w-full">
                        <table class="w-full text-left text-xs text-zinc-300">
                            <thead class="bg-zinc-950/80 text-zinc-500 uppercase tracking-wider text-[10px] font-bold border-b border-zinc-800/80 whitespace-nowrap">
                                <tr>
                                    <th class="px-6 py-3.5">Channel Kreator</th>
                                    <th class="px-6 py-3.5">Email Akun</th>
                                    <th class="px-6 py-3.5">Status Verifikasi</th>
                                    <th class="px-6 py-3.5">Karya Film Rilis</th>
                                    <th class="px-6 py-3.5">Akses Revenue</th>
                                    <th class="px-6 py-3.5 text-right">Tindakan SuperAdmin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/80">
                                @forelse ($users->filter(fn($u) => $u->isCreator()) as $creatorUser)
                                    <tr x-show="searchQuery === '' || '{{ strtolower($creatorUser->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($creatorUser->email) }}'.includes(searchQuery.toLowerCase())" class="hover:bg-zinc-800/40 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-red-600 text-white font-bold text-xs flex items-center justify-center shrink-0 overflow-hidden border border-zinc-800">
                                                    @if(!empty($creatorUser->avatar_url))
                                                        <img src="{{ asset($creatorUser->avatar_url) }}" alt="{{ $creatorUser->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($creatorUser->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="font-bold text-white text-xs">{{ $creatorUser->name }}</span>
                                                        @if($creatorUser->isVerified())
                                                            <svg class="w-4 h-4 fill-blue-500 shrink-0" title="Centang Biru Official" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('creators.show', $creatorUser->handle ? ltrim($creatorUser->handle, '@') : $creatorUser->id) }}" class="text-[10px] text-zinc-400 font-mono hover:text-red-400 transition">
                                                        {{ $creatorUser->handle ?: '@'.\Illuminate\Support\Str::slug($creatorUser->name, '') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 font-mono text-[11px] text-zinc-300">
                                            {{ $creatorUser->email }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($creatorUser->isVerified())
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-600/20 border border-blue-500/40 text-blue-400 font-bold text-[10px]">
                                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                    <span>Terverifikasi (Centang Biru)</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-zinc-800 border border-zinc-700 text-zinc-400 font-bold text-[10px]">
                                                    <span>Belum Verifikasi (Basic)</span>
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 font-mono text-[11px] text-zinc-300">
                                            {{ $creatorUser->movies->count() }} Film
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($creatorUser->isVerified())
                                                <span class="text-emerald-400 font-mono text-[11px] font-bold">Terbuka (Rp 0)</span>
                                            @else
                                                <span class="text-amber-400/80 font-mono text-[10px] font-bold">Terkunci 🔒</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <form id="verify-form-{{ $creatorUser->id }}" method="POST" action="{{ route('admin.users.toggle-verification', $creatorUser->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($creatorUser->isVerified())
                                                    <button type="button"
                                                            @click="confirmAction({
                                                                title: 'Pencabutan Verifikasi Kreator',
                                                                message: 'Apakah Anda yakin ingin mencabut Verifikasi Centang Biru dan mengunci fitur revenue untuk channel {{ $creatorUser->name }}?',
                                                                confirmText: 'Ya, Cabut Verifikasi',
                                                                cancelText: 'Batal',
                                                                type: 'warning',
                                                                formId: 'verify-form-{{ $creatorUser->id }}'
                                                            })"
                                                            class="px-3 py-1.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 hover:bg-amber-500 hover:text-zinc-950 font-extrabold text-[11px] transition">
                                                        Cabut Verifikasi
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            @click="confirmAction({
                                                                title: 'Berikan Verifikasi Centang Biru',
                                                                message: 'Apakah Anda yakin ingin memberikan status Verifikasi Centang Biru dan membuka fitur pendapatan untuk {{ $creatorUser->name }}?',
                                                                confirmText: 'Ya, Verifikasi Sekarang',
                                                                cancelText: 'Batal',
                                                                type: 'info',
                                                                formId: 'verify-form-{{ $creatorUser->id }}'
                                                            })"
                                                            class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-[11px] shadow-lg shadow-blue-600/30 transition flex items-center gap-1.5 ml-auto">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                        <span>Verifikasi Kreator</span>
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-zinc-500 space-y-2">
                                            <p class="font-bold">Belum ada akun Kreator terdaftar</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>

        <!-- ============ MODAL 1: TAMBAH AKUN BARU ============ -->
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
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
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

        <!-- ============ MODAL 2: BUAT PENGUMUMAN / SIARAN BARU ============ -->
        <div x-show="showAnnouncementModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 flex items-center justify-center" style="display: none;">
            <div @click="showAnnouncementModal = false" x-show="showAnnouncementModal" x-transition.opacity class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="showAnnouncementModal" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-xl w-full relative z-10 shadow-2xl space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight">Terbitkan Siaran Pengumuman Pop-up</h3>
                            <p class="text-xs text-zinc-400">Pengumuman ini akan muncul sebagai pop-up bagi pengguna saat masuk dashboard</p>
                        </div>
                    </div>
                    <button type="button" @click="showAnnouncementModal = false" class="p-2 rounded-xl bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700 transition shrink-0" aria-label="Tutup modal">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                </div>

                <!-- QUICK TEMPLATE PRESETS BAR -->
                <div class="space-y-2 p-3.5 rounded-2xl bg-zinc-950 border border-zinc-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Gunakan Template Cepat Siap Pakai:
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button" @click="applyTemplate('promo')" class="px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[11px] font-bold transition text-left truncate">
                            🍿 Promo 50% VIP
                        </button>
                        <button type="button" @click="applyTemplate('info')" class="px-2.5 py-1.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 text-[11px] font-bold transition text-left truncate">
                            📢 Maintenance v2.4
                        </button>
                        <button type="button" @click="applyTemplate('event')" class="px-2.5 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/30 text-purple-400 text-[11px] font-bold transition text-left truncate">
                            🎬 Event Kreator
                        </button>
                        <button type="button" @click="applyTemplate('warning')" class="px-2.5 py-1.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 text-[11px] font-bold transition text-left truncate">
                            🔒 Alert Keamanan
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="ann_title_input" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Judul Pengumuman / Promosi</label>
                        <input id="ann_title_input" name="title" x-model="annTitle" type="text" required placeholder="Contoh: 🍿 Promo Diskon Langganan..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                        <x-input-error class="mt-1" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ann_type_input" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Kategori / Tipe</label>
                            <select id="ann_type_input" name="type" x-model="annType" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 font-bold focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                                <option value="info">Informasi (Informasi Umum)</option>
                                <option value="promo">Promosi (Diskon / VIP Promo)</option>
                                <option value="event">Event (Kompetisi / Tantangan)</option>
                                <option value="warning">Peringatan (Keamanan / Maintenance)</option>
                            </select>
                            <x-input-error class="mt-1" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <label for="ann_target_input" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Target Audience</label>
                            <select id="ann_target_input" name="target_role" x-model="annTarget" required class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-100 font-bold focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition">
                                <option value="all">Semua Pengguna (User &amp; Creator)</option>
                                <option value="user">Khusus User Biasa (Penonton)</option>
                                <option value="creator">Khusus Kreator Studio</option>
                            </select>
                            <x-input-error class="mt-1" :messages="$errors->get('target_role')" />
                        </div>
                    </div>

                    <div>
                        <label for="ann_content_input" class="block font-bold text-xs uppercase tracking-wider text-zinc-300 mb-1.5">Isi Pesan Pengumuman Lengkap</label>
                        <textarea id="ann_content_input" name="content" x-model="annContent" rows="4" required placeholder="Tuliskan detail pengumuman yang akan dibaca oleh pengguna..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 transition leading-relaxed"></textarea>
                        <x-input-error class="mt-1" :messages="$errors->get('content')" />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label for="is_active_checkbox" class="inline-flex items-center cursor-pointer">
                            <input id="is_active_checkbox" type="checkbox" name="is_active" value="1" checked class="rounded border-zinc-800 bg-zinc-950 text-amber-500 shadow-sm focus:ring-amber-500 focus:ring-offset-zinc-900">
                            <span class="ms-2 text-xs text-zinc-300 font-bold">Langsung Aktifkan Pop-up Publik</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                        <button type="button" @click="showAnnouncementModal = false" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                            Batal
                        </button>

                        <button type="submit" class="py-2.5 px-5 rounded-xl bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/30 transition active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            <span>Terbitkan Siaran Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============ MODAL 3: CUSTOM THEMED CONFIRMATION ACTION ============ -->
        <div x-show="confirmModal.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 flex items-center justify-center" style="display: none;">
            <div @click="confirmModal.show = false" x-show="confirmModal.show" x-transition.opacity class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="confirmModal.show" class="bg-zinc-900 border border-zinc-800/90 rounded-3xl p-6 sm:p-8 max-w-md w-full relative z-10 shadow-2xl space-y-5 text-center overflow-hidden">
                <!-- Ambient Glow -->
                <div class="absolute -top-20 -right-20 w-44 h-44 rounded-full blur-3xl pointer-events-none"
                     :class="confirmModal.type === 'danger' ? 'bg-red-600/20' : 'bg-amber-500/20'"></div>

                <!-- Icon Halo -->
                <div class="mx-auto w-14 h-14 rounded-2xl border flex items-center justify-center shadow-lg"
                     :class="confirmModal.type === 'danger' ? 'bg-red-600/15 border-red-500/30 text-red-500 shadow-red-600/20' : 'bg-amber-500/15 border-amber-500/30 text-amber-400 shadow-amber-500/20'">
                    <template x-if="confirmModal.type === 'danger'">
                        <svg class="w-7 h-7 fill-current shrink-0" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    </template>
                    <template x-if="confirmModal.type === 'warning'">
                        <svg class="w-7 h-7 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </template>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-black text-white tracking-tight" x-text="confirmModal.title"></h3>
                    <p class="text-xs text-zinc-300 leading-relaxed font-medium" x-text="confirmModal.message"></p>
                </div>

                <div class="flex items-center justify-center gap-3 pt-3 border-t border-zinc-800/80">
                    <button type="button" @click="confirmModal.show = false" class="w-1/2 py-3 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 border border-zinc-700/80 text-zinc-300 font-extrabold text-xs transition">
                        <span x-text="confirmModal.cancelText"></span>
                    </button>

                    <button type="button" @click="executeConfirmedAction()"
                            class="w-1/2 py-3 px-4 rounded-xl text-white font-black text-xs tracking-wide shadow-lg transition active:scale-95 flex items-center justify-center gap-1.5"
                            :class="confirmModal.type === 'danger' ? 'bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 shadow-red-600/30' : 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-zinc-950 shadow-amber-500/30'">
                        <span x-text="confirmModal.confirmText"></span>
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
