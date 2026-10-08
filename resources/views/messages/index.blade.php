<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pesan & Support Direct Message — {{ config('app.name', 'WeWatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden flex flex-col justify-between"
          x-data="{
              messageText: '',
              messagesList: {{ json_encode($activeMessages->map(function($m) {
                  return [
                      'id' => $m->id,
                      'sender_id' => $m->sender_id,
                      'receiver_id' => $m->receiver_id,
                      'message' => $m->message,
                      'is_admin_chat' => $m->is_admin_chat,
                      'is_self' => $m->sender_id === Auth::id(),
                      'sender_name' => $m->is_admin_chat ? ($m->sender_id === Auth::id() ? Auth::user()->name : 'WeWatch Official Support Admin') : ($m->sender ? $m->sender->name : 'User'),
                      'time' => $m->created_at->format('H:i'),
                  ];
              })->values()) }},

              sendMessage(receiverId, isAdminChat) {
                  if (this.messageText.trim() === '') return;

                  const text = this.messageText.trim();
                  this.messageText = '';

                  fetch('{{ route('messages.store') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                          'Accept': 'application/json'
                      },
                      body: JSON.stringify({
                          message: text,
                          receiver_id: receiverId,
                          is_admin_chat: isAdminChat
                      })
                  })
                  .then(res => res.json())
                  .then(data => {
                      if (data.status === 'success') {
                          this.messagesList.push({
                              id: data.message_data.id,
                              sender_id: data.message_data.sender_id,
                              receiver_id: data.message_data.receiver_id,
                              message: data.message_data.message,
                              is_admin_chat: data.message_data.is_admin_chat,
                              is_self: true,
                              sender_name: '{{ Auth::user()->name }}',
                              time: data.message_data.time
                          });
                          this.$nextTick(() => {
                              const container = document.getElementById('chat-stream');
                              if (container) container.scrollTop = container.scrollHeight;
                          });
                      } else {
                          alert(data.message || 'Gagal mengirim pesan.');
                      }
                  })
                  .catch(err => console.error(err));
              }
          }">

        <div>
            <!-- Toast Notification System -->
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
                        <a href="{{ route('favorites.index') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Favorites</span>
                        </a>
                        <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Subscribed</span>
                        </a>
                        <a href="{{ route('user.announcements') }}" class="px-3 py-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-900 transition flex items-center gap-1.5">
                            <span>Notifications</span>
                        </a>
                        <a href="{{ route('messages.index') }}" class="px-3 py-1.5 rounded-lg bg-red-600/15 text-red-400 border border-red-600/30 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-current text-red-500" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                            <span>Messages</span>
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
            <main class="pt-28 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

                <div class="bg-zinc-900/80 border border-zinc-800/90 rounded-3xl shadow-2xl backdrop-blur-2xl grid grid-cols-1 lg:grid-cols-12 overflow-hidden h-[78vh] min-h-[550px]">

                    <!-- LEFT SIDEBAR: CONVERSATIONS LIST -->
                    <div class="lg:col-span-4 border-r border-zinc-800/80 flex flex-col justify-between bg-zinc-950/50">
                        <div class="p-4 border-b border-zinc-800/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-black text-white tracking-tight flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                    <span>Direct Messages</span>
                                </h2>
                                <span class="px-2 py-0.5 rounded bg-red-600/15 text-red-400 text-[10px] font-mono font-bold uppercase border border-red-600/30">
                                    {{ Auth::user()->getEffectiveSubscriptionTier() }}
                                </span>
                            </div>
                        </div>

                        <!-- Sidebar Conversations Feed -->
                        <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">

                            <!-- OFFICIAL ADMIN SUPPORT TEAM THREAD -->
                            <a href="{{ route('messages.index', ['type' => 'admin']) }}"
                               :class="('{{ $activeType }}' === 'admin' && !'{{ $activeUserId }}') ? 'bg-red-600/15 border-red-600/50 text-white shadow-lg' : 'bg-zinc-900/60 border-zinc-800/80 text-zinc-300 hover:bg-zinc-900 hover:border-zinc-700'"
                               class="p-3.5 rounded-2xl border block transition group relative overflow-hidden">

                                <div class="flex items-center gap-3">
                                    <!-- Official Admin Avatar Emblem -->
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-red-600 via-rose-600 to-red-600 text-white font-black text-base flex items-center justify-center shrink-0 shadow-lg border border-red-500/40 relative">
                                        W
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center border border-zinc-950 text-[8px]" title="Official Verified Admin">
                                            ✓
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-extrabold text-white text-xs truncate group-hover:text-red-400 transition flex items-center gap-1">
                                                <span>Official Support Admin</span>
                                            </h4>
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-500/15 border border-amber-500/30 text-amber-400 uppercase font-mono">
                                                VIP ADMIN
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-zinc-400 truncate mt-0.5">
                                            @if(!$hasAdminChat)
                                                <span class="text-amber-400 font-bold">Khusus Anggota VIP (Locked)</span>
                                            @else
                                                <span>Layanan Bantuan & Support Prioritas 24/7</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <!-- SUPERADMIN VIEW: VIP USER SUPPORT TICKETS LIST -->
                            @if(Auth::user()->isSuperAdmin() && isset($adminThreads) && $adminThreads->isNotEmpty())
                                <div class="pt-3 pb-1 border-t border-zinc-800/80">
                                    <span class="text-[10px] font-mono font-bold text-red-400 uppercase tracking-wider px-2">Tiket Support User VIP (Admin View)</span>
                                </div>

                                @foreach($adminThreads as $uThread)
                                    <a href="{{ route('messages.index', ['type' => 'admin', 'user_id' => $uThread->id]) }}"
                                       class="p-3.5 rounded-2xl border block transition group {{ ($activeUserId == $uThread->id) ? 'bg-red-600/15 border-red-600/50 text-white' : 'bg-zinc-900/60 border-zinc-800/80 text-zinc-300 hover:bg-zinc-900' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-zinc-800 text-white font-bold text-xs flex items-center justify-center shrink-0 border border-zinc-700">
                                                {{ strtoupper(substr($uThread->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-white text-xs truncate">{{ $uThread->name }}</h4>
                                                <span class="text-[10px] text-zinc-400 font-mono">{{ $uThread->email }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif

                            <!-- CREATORS CONVERSATIONS LIST -->
                            <div class="pt-3 pb-1 border-t border-zinc-800/80 flex items-center justify-between px-2">
                                <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider">Saluran Studio Kreator</span>
                                <span class="text-[10px] font-mono text-zinc-500">{{ $creatorsList->count() }} Channel</span>
                            </div>

                            @if($creatorsList->isNotEmpty())
                                @foreach($creatorsList as $cItem)
                                    <a href="{{ route('messages.index', ['type' => 'creator', 'creator_id' => $cItem->id]) }}"
                                       :class="('{{ $activeCreator ? $activeCreator->id : 0 }}' == '{{ $cItem->id }}') ? 'bg-red-600/15 border-red-600/50 text-white shadow-lg' : 'bg-zinc-900/60 border-zinc-800/80 text-zinc-300 hover:bg-zinc-900 hover:border-zinc-700'"
                                       class="p-3.5 rounded-2xl border block transition group">

                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-red-600 border-2 border-zinc-950 shadow flex items-center justify-center shrink-0 overflow-hidden text-white font-black text-sm">
                                                @if(!empty($cItem->avatar_url))
                                                    <img src="{{ asset($cItem->avatar_url) }}" alt="{{ $cItem->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($cItem->name, 0, 1)) }}
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="font-extrabold text-white text-xs truncate group-hover:text-red-400 transition flex items-center gap-1">
                                                        <span>{{ $cItem->name }}</span>
                                                        @if($cItem->isVerified())
                                                            <svg class="w-3.5 h-3.5 fill-blue-500 shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                        @endif
                                                    </h4>
                                                    <span class="text-[8px] font-bold px-1.5 py-0.5 rounded bg-zinc-950 border border-zinc-800 text-zinc-400 uppercase font-mono">
                                                        DM: {{ strtoupper($cItem->dm_access_tier ?: 'PRO') }}
                                                    </span>
                                                </div>
                                                <p class="text-[10px] text-zinc-400 font-mono truncate mt-0.5">
                                                    {{ $cItem->handle ?: ('@'.Str::slug($cItem->name, '')) }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="p-4 text-center text-xs text-zinc-500">
                                    Belum ada channel kreator di-subscribe. Jelajahi halaman kreator untuk memulai obrolan!
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- RIGHT CHAT STREAM & INPUT AREA -->
                    <div class="lg:col-span-8 flex flex-col justify-between bg-zinc-950/80">

                        @if($activeType === 'admin' || $activeCreator || ($activeUser && Auth::user()->isSuperAdmin()))
                            <!-- CHAT HEADER -->
                            <div class="p-4 border-b border-zinc-800/80 bg-zinc-950/90 flex items-center justify-between backdrop-blur-xl">
                                <div class="flex items-center gap-3">
                                    @if($activeType === 'admin')
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 via-rose-600 to-red-600 text-white font-black text-sm flex items-center justify-center shadow-lg border border-red-500/40 relative">
                                            W
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <h3 class="font-black text-white text-sm">
                                                    @if(Auth::user()->isSuperAdmin() && $activeUser)
                                                        <span>Dukungan Chat Admin — User: {{ $activeUser->name }}</span>
                                                    @else
                                                        <span>WeWatch Official Support Admin</span>
                                                    @endif
                                                </h3>
                                                <svg class="w-4 h-4 fill-blue-500 shrink-0" title="Official Verified Admin" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            </div>
                                            <p class="text-[10px] text-zinc-400 font-mono">Layanan Bantuan & Layanan Pelanggan VIP WeWatch</p>
                                        </div>
                                    @elseif($activeCreator)
                                        <div class="w-10 h-10 rounded-full bg-red-600 border-2 border-zinc-950 shadow flex items-center justify-center shrink-0 overflow-hidden text-white font-black text-sm">
                                            @if(!empty($activeCreator->avatar_url))
                                                <img src="{{ asset($activeCreator->avatar_url) }}" alt="{{ $activeCreator->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($activeCreator->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <h3 class="font-black text-white text-sm">{{ $activeCreator->name }}</h3>
                                                @if($activeCreator->isVerified())
                                                    <svg class="w-4 h-4 fill-blue-500 shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-zinc-400 font-mono">{{ $activeCreator->handle }} • {{ $activeCreator->subscribersCountFormatted() }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($activeCreator)
                                    <a href="{{ route('creators.show', $activeCreator->handle ? ltrim($activeCreator->handle, '@') : $activeCreator->id) }}" class="px-3 py-1.5 rounded-xl bg-red-600/15 border border-red-600/30 text-red-400 hover:bg-red-600 hover:text-white text-xs font-bold transition">
                                        Buka Channel →
                                    </a>
                                @endif
                            </div>

                            <!-- CHAT MESSAGE STREAM -->
                            <div id="chat-stream" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 custom-scrollbar">
                                <template x-for="m in messagesList" :key="m.id">
                                    <div :class="m.is_self ? 'flex justify-end' : 'flex justify-start'" class="space-y-1">
                                        <div :class="m.is_self
                                                ? 'bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-2xl rounded-tr-none shadow-lg'
                                                : 'bg-zinc-900 border border-zinc-800 text-zinc-100 rounded-2xl rounded-tl-none shadow-md'"
                                             class="max-w-md p-3.5 space-y-1">

                                            <div class="flex items-center justify-between gap-3 text-[10px] opacity-80 pb-1 border-b border-white/10 font-mono">
                                                <span class="font-extrabold" x-text="m.sender_name"></span>
                                                <span x-text="m.time"></span>
                                            </div>

                                            <p class="text-xs leading-relaxed whitespace-pre-line font-normal" x-text="m.message"></p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="messagesList.length === 0">
                                    <div class="text-center py-12 text-zinc-500 text-xs space-y-2">
                                        <div class="w-12 h-12 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-400 flex items-center justify-center mx-auto">
                                            💬
                                        </div>
                                        <div>Belum ada pesan di percakapan ini. Ketik pesan Anda di bawah untuk memulai obrolan!</div>
                                    </div>
                                </template>
                            </div>

                            <!-- INPUT BOX OR LOCK BANNER -->
                            @if($canSendMessage)
                                <div class="p-4 border-t border-zinc-800/80 bg-zinc-950/90">
                                    <form @submit.prevent="sendMessage({{ $activeCreator ? $activeCreator->id : ($activeUser ? $activeUser->id : 'null') }}, {{ $activeType === 'admin' ? 'true' : 'false' }})"
                                          class="flex items-center gap-3">
                                        <input type="text"
                                               x-model="messageText"
                                               placeholder="Tulis pesan Anda di sini..."
                                               class="flex-1 py-3 px-4 text-xs rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition">
                                        <button type="submit"
                                                class="px-6 py-3 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition transform active:scale-95 flex items-center gap-2 shrink-0">
                                            <span>Kirim</span>
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="p-6 bg-zinc-900/90 border-t border-zinc-800/90 text-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center mx-auto text-xl">
                                        🔒
                                    </div>
                                    <h4 class="font-black text-white text-sm">Direct Message Terkunci</h4>
                                    <p class="text-xs text-zinc-400 max-w-md mx-auto leading-relaxed">
                                        {{ $lockReason }}
                                    </p>
                                    <div class="pt-1">
                                        <a href="{{ route('subscription.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 hover:scale-105 transition inline-block">
                                            Upgrade Membership Sekarang ★
                                        </a>
                                    </div>
                                </div>
                            @endif

                        @else
                            <!-- SELECT CONVERSATION PROMPT -->
                            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-400 flex items-center justify-center shadow-xl">
                                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-lg font-black text-white tracking-tight">Pilih Obrolan Pesan</h3>
                                    <p class="text-xs text-zinc-400 max-w-sm leading-relaxed">
                                        Pilih **Official Support Admin** di sebelah kiri atau salah satu **Saluran Kreator** untuk memulai obrolan langsung!
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </main>
        </div>
    </body>
</html>
