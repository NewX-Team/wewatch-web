<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $movie['title'] }} — WeWatch Cinema</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-950 text-zinc-100 selection:bg-red-600 selection:text-white min-h-screen overflow-x-hidden"
          x-data="{
              isPlaying: false,
              isFavorited: false,
              isSubscribed: {{ $movie['creator']['is_subscribed'] ? 'true' : 'false' }},
              creatorSubscribers: '{{ $movie['creator']['subscribers'] }}',
              userTier: '{{ Auth::user()->getEffectiveSubscriptionTier() }}',
              showLockModal: false,
              lockedEpTier: 'pro',
              activeEpisode: {{ json_encode($movie['episodes'][0] ?? ['number' => 1, 'title' => $movie['title'], 'duration' => 'Auto', 'access_tier' => 'free', 'thumb' => $movie['banner']]) }},
              progress: 24,
              newCommentText: '',
              comments: {{ json_encode($initialComments ?? []) }},
              toggleSubscribe() {
                  fetch('{{ route('creators.toggle-subscription', $movie['creator']['id']) }}', {
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
                          this.creatorSubscribers = data.subscribers_formatted;
                      }
                  });
              },
              canAccess(epTier) {
                  if (this.userTier === 'vip') return true;
                  if (this.userTier === 'pro') return epTier === 'free' || epTier === 'pro';
                  return epTier === 'free';
              },
              selectEpisode(ep) {
                  if (this.canAccess(ep.access_tier || 'free')) {
                      this.activeEpisode = ep;
                      this.isPlaying = true;
                      this.showLockModal = false;
                  } else {
                      this.lockedEpTier = ep.access_tier || 'pro';
                      this.showLockModal = true;
                  }
              },
              addComment() {
                  if (this.newCommentText.trim() === '') return;
                  this.comments.unshift({
                      id: Date.now(),
                      name: '{{ Auth::user()->name }}',
                      initial: '{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}',
                      color: 'bg-red-600',
                      time: 'Just now',
                      content: this.newCommentText.trim(),
                      likes: 0,
                      liked: false
                  });
                  this.newCommentText = '';
              }
          }">

        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Split Screen Cinema Layout: Left Video Player (Top-to-Bottom) + Right Movie Info -->
        <div class="flex flex-col lg:flex-row min-h-screen">

            <!-- LEFT COLUMN: Video Player Screen (Top-to-Bottom Viewport Height) -->
            <div class="w-full lg:w-[58%] xl:w-[62%] bg-black relative flex flex-col justify-between border-r border-zinc-900 lg:h-screen lg:sticky lg:top-0 overflow-hidden group">

                <!-- Ambient Glow behind video player -->
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-zinc-950/60 pointer-events-none z-10"></div>

                <!-- Video Screen / Poster Canvas -->
                <div class="absolute inset-0 z-0">
                    <img :src="activeEpisode.thumb || '{{ $movie['banner'] }}'" :alt="activeEpisode.title" class="w-full h-full object-cover opacity-80 group-hover:scale-102 transition duration-700">
                    <!-- Overlay Dark Gradient Mesh -->
                    <div class="absolute inset-0 bg-zinc-950/30 backdrop-brightness-95"></div>
                </div>

                <!-- Top Navigation & Badges Bar (Over Video) -->
                <div class="relative z-20 p-5 sm:p-7 flex items-center justify-between">
                    <!-- Back Button -->
                    <a href="{{ Auth::user()->isSuperAdmin() ? route('admin.dashboard') : (Auth::user()->isCreator() ? route('creator.dashboard') : route('user.dashboard')) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-zinc-900/80 hover:bg-zinc-800 text-zinc-200 hover:text-white border border-zinc-800/80 backdrop-blur-md text-xs font-bold transition shadow-lg">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                        <span>{{ Auth::user()->isSuperAdmin() ? 'Kembali ke Admin Console' : (Auth::user()->isCreator() ? 'Kembali ke Studio Hub' : 'Back to Catalogue') }}</span>
                    </a>

                    <!-- Video Quality & Atmos Badge -->
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md bg-red-600/90 text-white font-black text-[10px] tracking-wider uppercase shadow-md border border-red-500/30">
                            {{ $movie['quality'] }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-zinc-900/80 text-zinc-300 font-mono font-bold text-[10px] border border-zinc-800 backdrop-blur-md hidden sm:inline">
                            DOLBY ATMOS
                        </span>
                    </div>
                </div>

                <!-- Center Play Pulse Button Overlay -->
                <div class="relative z-20 flex-1 flex flex-col items-center justify-center p-6 text-center">
                    <button @click="isPlaying = !isPlaying" class="w-20 h-20 rounded-full bg-red-600 text-white flex items-center justify-center shadow-2xl hover:scale-110 hover:bg-red-500 transition duration-300 border-2 border-white/20 group/btn">
                        <svg x-show="!isPlaying" class="w-9 h-9 fill-current translate-x-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <svg x-show="isPlaying" class="w-9 h-9 fill-current" viewBox="0 0 24 24" style="display: none;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </button>

                    <div class="mt-4 space-y-1">
                        <span class="text-xs font-bold text-red-500 uppercase tracking-widest" x-text="isPlaying ? 'Now Playing Stream' : 'Click to Play Stream'"></span>
                        <h3 class="text-lg font-black text-white truncate max-w-md" x-text="activeEpisode.title"></h3>
                    </div>
                </div>

                <!-- Bottom Custom Video Player Control Bar -->
                <div class="relative z-20 p-5 sm:p-7 bg-gradient-to-t from-zinc-950 via-zinc-950/90 to-transparent space-y-3">
                    <!-- Progress Bar Scrubber -->
                    <div class="relative w-full h-1.5 bg-zinc-800/90 rounded-full overflow-hidden cursor-pointer group/track" @click="progress = Math.floor(Math.random() * 80) + 10">
                        <div class="h-full bg-red-600 rounded-full transition-all duration-300 relative" :style="'width: ' + progress + '%'">
                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow opacity-0 group-hover/track:opacity-100 transition"></span>
                        </div>
                    </div>

                    <!-- Player HUD Controls -->
                    <div class="flex items-center justify-between text-xs text-zinc-300">
                        <div class="flex items-center gap-4">
                            <button @click="isPlaying = !isPlaying" class="hover:text-white transition">
                                <svg x-show="!isPlaying" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                <svg x-show="isPlaying" class="w-5 h-5 fill-current" viewBox="0 0 24 24" style="display: none;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            </button>
                            <span class="font-mono text-[11px] text-zinc-400">14:20 / <span x-text="activeEpisode.duration"></span></span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="hover:text-white transition hidden sm:inline" title="Audio / Subtitles">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2v2H6zm0 4h8v2H6zm10 0h2v2h-2zm-6-4h8v2h-8z"/></svg>
                            </button>
                            <button class="hover:text-white transition" title="Full Screen">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Detailed Movie Metadata, Episodes & Comments -->
            <div class="w-full lg:w-[42%] xl:w-[38%] p-6 sm:p-8 lg:p-10 space-y-8 overflow-y-auto max-h-screen custom-scrollbar">

                <!-- Movie Title & Primary Action Badges -->
                <div class="space-y-4 border-b border-zinc-800/80 pb-6">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[11px] uppercase tracking-wider">
                            {{ $movie['category'] }}
                        </span>
                        <span class="text-xs font-mono text-emerald-400 font-bold px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800">
                            {{ $movie['match'] }} Match
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        {{ $movie['title'] }}
                    </h1>

                    <!-- Rating, Duration & Studio Bar -->
                    <div class="flex flex-wrap items-center gap-4 text-xs text-zinc-400">
                        <div class="flex items-center gap-1 font-bold text-zinc-200">
                            <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <span>{{ $movie['rating'] }}</span>
                        </div>
                        <span>•</span>
                        <span>{{ $movie['year'] }}</span>
                        <span>•</span>
                        <span>{{ $movie['duration'] }}</span>
                        <span>•</span>
                        <span class="text-zinc-300 font-bold">{{ $movie['studio'] }}</span>
                    </div>

                    <!-- Creator Profile Card (Clickable to Creator Channel Page) -->
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-zinc-900/90 border border-zinc-800/90 hover:border-red-600/50 transition duration-300 shadow-xl group">
                        <a href="{{ route('creators.show', $movie['creator']['slug'] ?? $movie['creator']['id']) }}" class="flex items-center gap-3.5 flex-1 min-w-0">
                            <!-- Avatar Image or Letter Initial -->
                            <div class="w-11 h-11 rounded-full bg-red-600 border-2 border-zinc-950 shadow-lg flex items-center justify-center shrink-0 overflow-hidden text-white font-black text-lg group-hover:scale-105 transition">
                                @if(!empty($movie['creator']['avatar']))
                                    <img src="{{ asset($movie['creator']['avatar']) }}" alt="{{ $movie['creator']['name'] }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($movie['creator']['name'] ?? 'K', 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="font-black text-white text-xs truncate group-hover:text-red-400 transition">{{ $movie['creator']['name'] }}</h4>
                                    @if(!empty($movie['creator']['is_verified']))
                                        <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm" title="Verified Creator Channel">
                                            <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-[10px] text-zinc-400 font-mono truncate mt-0.5">
                                    <span class="text-zinc-300 font-bold">{{ $movie['creator']['handle'] }}</span>
                                    <span>•</span>
                                    <span class="text-emerald-400 font-bold" x-text="creatorSubscribers">{{ $movie['creator']['subscribers'] }}</span>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('creators.show', $movie['creator']['slug'] ?? $movie['creator']['id']) }}" class="px-3.5 py-2 rounded-xl bg-red-600/10 border border-red-600/30 hover:bg-red-600 hover:text-white text-red-400 text-xs font-extrabold transition shrink-0 flex items-center gap-1.5">
                            <span>Buka Channel</span>
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </a>
                    </div>

                    <!-- Action Buttons: Favorites & Subscribe -->
                    <div class="flex items-center gap-3 pt-2">
                        <button @click="isFavorited = !isFavorited"
                                :class="isFavorited ? 'bg-red-600/20 text-red-400 border-red-600/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:text-white'"
                                class="flex-1 py-3 px-4 rounded-xl border text-xs font-bold transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span x-text="isFavorited ? 'In Favorites' : 'Add to Favorites'"></span>
                        </button>

                        <form method="POST" action="{{ route('creators.toggle-subscription', $movie['creator']['id']) }}" @submit.prevent="toggleSubscribe()" class="flex-1">
                            @csrf
                            <button type="submit"
                                    :class="isSubscribed ? 'bg-emerald-600/20 text-emerald-400 border-emerald-600/40' : 'bg-zinc-900 text-zinc-300 border-zinc-800 hover:text-white'"
                                    class="w-full py-3 px-4 rounded-xl border text-xs font-bold transition shadow-sm flex items-center justify-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="isSubscribed ? 'bg-emerald-400' : 'bg-zinc-500'"></span>
                                <span x-text="isSubscribed ? 'Subscribed to Creator' : 'Subscribe to Creator'"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- SECTION: Episodes List (Horizontal Scroll - Text/Number Cards, No Photos) -->
                <div class="space-y-3" x-data="{
                    scrollLeft() { $refs.epContainer.scrollBy({ left: -200, behavior: 'smooth' }); },
                    scrollRight() { $refs.epContainer.scrollBy({ left: 200, behavior: 'smooth' }); }
                }">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Select Episode</span>
                            <h2 class="text-lg font-extrabold text-white tracking-tight">Episodes & Content</h2>
                        </div>

                        <!-- Scroll Trigger Arrows -->
                        <div class="flex items-center gap-1.5">
                            <button @click="scrollLeft()" class="w-7 h-7 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                            </button>
                            <button @click="scrollRight()" class="w-7 h-7 rounded-lg bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-300 hover:text-white flex items-center justify-center transition">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Horizontal Scroll Carousel for Episodes (No Photos, Sleek Number & Text Cards) -->
                    <div x-ref="epContainer" class="flex gap-3 overflow-x-auto custom-scrollbar pt-1 pb-3 px-0.5">
                        @foreach($movie['episodes'] as $ep)
                            <div @click="selectEpisode({{ json_encode($ep) }})"
                                 :class="activeEpisode.title === '{{ $ep['title'] }}'
                                     ? 'border-red-600/80 bg-red-600/10 shadow-lg shadow-red-600/10'
                                     : 'border-zinc-800 bg-zinc-900/80 hover:border-zinc-700 hover:bg-zinc-900'"
                                 class="w-52 shrink-0 rounded-xl border p-3 cursor-pointer group transition duration-300 shadow-sm flex items-center gap-3">
                                <div :class="activeEpisode.title === '{{ $ep['title'] }}' ? 'bg-red-600 text-white shadow-md' : 'bg-zinc-800 text-zinc-400 group-hover:bg-zinc-700 group-hover:text-white'"
                                     class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-xs shrink-0 transition">
                                    {{ $ep['number'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-white text-xs truncate group-hover:text-red-400 transition">{{ $ep['title'] }}</h4>
                                    <div class="flex items-center justify-between text-[10px] text-zinc-400 mt-1">
                                        @if(($ep['access_tier'] ?? 'free') === 'vip')
                                            <span class="px-1.5 py-0.2 rounded bg-amber-500/20 text-amber-300 font-bold border border-amber-500/40 text-[9px]">VIP</span>
                                        @elseif(($ep['access_tier'] ?? 'free') === 'pro')
                                            <span class="px-1.5 py-0.2 rounded bg-red-600/20 text-red-400 font-bold border border-red-600/40 text-[9px]">PRO</span>
                                        @else
                                            <span class="px-1.5 py-0.2 rounded bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/40 text-[9px]">FREE</span>
                                        @endif

                                        <template x-if="!canAccess('{{ $ep['access_tier'] ?? 'free' }}')">
                                            <span class="text-amber-400 font-bold flex items-center gap-0.5" title="Terkunci">🔒</span>
                                        </template>
                                        <template x-if="canAccess('{{ $ep['access_tier'] ?? 'free' }}') && activeEpisode.title === '{{ $ep['title'] }}'">
                                            <span class="text-[9px] font-bold text-red-400 uppercase tracking-wider">Playing</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SECTION: Film Synopsis & Description -->
                <div class="space-y-3 border-t border-zinc-800/80 pt-6">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Overview & Synopsis</span>
                    <p class="text-xs text-zinc-300 leading-relaxed font-normal">
                        {{ $movie['description'] }}
                    </p>

                    <!-- Directors & Cast Details -->
                    <div class="grid grid-cols-2 gap-4 text-xs pt-2">
                        <div class="space-y-0.5">
                            <span class="text-[10px] text-zinc-500 font-bold uppercase">Director</span>
                            <p class="text-zinc-200 font-semibold">{{ $movie['director'] }}</p>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[10px] text-zinc-500 font-bold uppercase">Main Cast</span>
                            <p class="text-zinc-200 font-semibold truncate">{{ $movie['cast'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Genre Box Chips -->
                <div class="space-y-3 border-t border-zinc-800/80 pt-6">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Genres & Categories</span>
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($movie['genres'] as $genre)
                            <div class="px-3.5 py-2 rounded-xl bg-zinc-900 border border-zinc-800/90 text-xs font-bold text-zinc-200 hover:border-red-600/50 hover:text-red-400 transition shadow-sm cursor-pointer">
                                {{ $genre }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SECTION: User Comments & Discussion (Below Genres) -->
                <div class="space-y-5 border-t border-zinc-800/80 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-red-500 uppercase tracking-wider">Discussion</span>
                            <span class="px-2 py-0.5 rounded-full bg-zinc-900 border border-zinc-800 text-[10px] font-mono font-bold text-zinc-300" x-text="comments.length + ' Comments'"></span>
                        </div>
                    </div>

                    <!-- Add Comment Input Form -->
                    <div class="bg-zinc-900/90 border border-zinc-800/90 rounded-2xl p-3.5 space-y-3 shadow-md">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 space-y-2">
                                <textarea x-model="newCommentText"
                                          @keydown.enter.prevent="addComment()"
                                          rows="2"
                                          placeholder="Tulis komentar kamu tentang film ini..."
                                          class="w-full bg-zinc-950/80 border border-zinc-800 rounded-xl p-2.5 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-red-600 transition resize-none"></textarea>
                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-[10px] text-zinc-500">Tekan Enter untuk mengirim</span>
                                    <button @click="addComment()"
                                            class="px-4 py-1.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow transition active:scale-95">
                                        Kirim Komentar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-3.5">
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-2xl p-4 space-y-2 transition hover:border-zinc-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div :class="comment.color" class="w-7 h-7 rounded-lg text-white font-bold text-xs flex items-center justify-center shrink-0" x-text="comment.initial"></div>
                                        <div>
                                            <h5 class="font-bold text-white text-xs" x-text="comment.name"></h5>
                                            <span class="text-[10px] text-zinc-500 font-mono" x-text="comment.time"></span>
                                        </div>
                                    </div>
                                    <button @click="comment.liked = !comment.liked; comment.liked ? comment.likes++ : comment.likes--"
                                            :class="comment.liked ? 'text-red-500 bg-red-600/10 border-red-600/20' : 'text-zinc-400 bg-zinc-900 border-zinc-800 hover:text-white'"
                                            class="px-2.5 py-1 rounded-lg border text-[11px] font-bold flex items-center gap-1.5 transition">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <span x-text="comment.likes"></span>
                                    </button>
                                </div>
                                <p class="text-xs text-zinc-300 leading-relaxed pl-9" x-text="comment.content"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        <!-- LOCKED EPISODE ACCESS MODAL OVERLAY -->
        <div x-show="showLockModal" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">
            <div @click="showLockModal = false" x-show="showLockModal" x-transition class="fixed inset-0 bg-zinc-950/85 backdrop-blur-md"></div>

            <div x-show="showLockModal" x-transition class="bg-zinc-900 border border-amber-500/50 rounded-3xl p-6 sm:p-8 max-w-md w-full relative z-10 shadow-2xl space-y-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center mx-auto shadow-lg">
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                </div>

                <div class="space-y-2">
                    <span class="px-3 py-1 rounded-full bg-amber-500/15 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-widest border border-amber-500/30">
                        AKSES TERKUNCI Tier <span x-text="lockedEpTier.toUpperCase()"></span>
                    </span>
                    <h3 class="text-xl font-black text-white tracking-tight">Episode Membutuhkan Keanggotaan Special</h3>
                    <p class="text-xs text-zinc-400 leading-relaxed">
                        Kreator membatasi episode ini khusus untuk penonton paket <strong class="text-white uppercase" x-text="lockedEpTier"></strong>. Upgrade paket keanggotaan kamu untuk langsung membuka akses menonton!
                    </p>
                </div>

                <div class="space-y-3 pt-2">
                    <a href="{{ route('subscription.index') }}" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-zinc-950 font-black text-xs shadow-lg shadow-amber-500/20 transition transform active:scale-95 flex items-center justify-center gap-2">
                        <span>Upgrade Membership Sekarang</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    </a>
                    <button type="button" @click="showLockModal = false" class="w-full py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
