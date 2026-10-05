@props(['announcements' => []])

@if(count($announcements) > 0)
<div x-data="{
        currentIndex: 0,
        show: true,
        announcements: {{ Illuminate\Support\Js::from($announcements) }},
        get current() {
            return this.announcements[this.currentIndex] || null;
        },
        get isLast() {
            return this.currentIndex >= this.announcements.length - 1;
        },
        dismiss() {
            this.show = false;
        },
        next() {
            if (!this.isLast) {
                this.currentIndex++;
            } else {
                this.dismiss();
            }
        },
        getBadgeColor(type) {
            switch(type) {
                case 'promo': return 'bg-amber-500/15 text-amber-400 border-amber-500/30';
                case 'warning': return 'bg-red-500/15 text-red-400 border-red-500/30';
                case 'event': return 'bg-purple-500/15 text-purple-400 border-purple-500/30';
                default: return 'bg-indigo-500/15 text-indigo-400 border-indigo-500/30';
            }
        },
        getBadgeLabel(type) {
            switch(type) {
                case 'promo': return 'PROMOSI EKSKLUSIF';
                case 'warning': return 'PERINGATAN PENTING';
                case 'event': return 'EVENT & SPESIAL';
                default: return 'PEMBERITAHUAN SISTEM';
            }
        }
    }"
    x-show="show && current"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/80 backdrop-blur-xl z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto">

    <!-- Modal Card Container -->
    <div class="max-w-lg w-full bg-zinc-900/95 border border-zinc-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-red-950/30 relative overflow-hidden text-white flex flex-col justify-between my-auto">
        
        <!-- Ambient Glowing Background Spots -->
        <div class="absolute -top-24 -right-24 w-52 h-52 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-52 h-52 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Header & Stepper Badge -->
        <div class="relative z-10 flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full border text-[10px] font-black tracking-widest uppercase"
                      :class="getBadgeColor(current.type)"
                      x-text="getBadgeLabel(current.type)">
                </span>
                
                <span class="px-2.5 py-0.5 rounded-full bg-zinc-800/80 border border-zinc-700/60 text-zinc-300 text-[10px] font-mono font-bold"
                      x-text="(currentIndex + 1) + ' dari ' + announcements.length">
                </span>
            </div>

            <!-- Close Button (Advance to Next Announcement) -->
            <button type="button" @click="next()" class="w-8 h-8 rounded-xl bg-zinc-800/80 hover:bg-zinc-700 text-zinc-400 hover:text-white border border-zinc-700/60 flex items-center justify-center transition shrink-0" aria-label="Tutup / Lanjut">
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <!-- Announcement Content Body -->
        <div class="relative z-10 space-y-3.5 my-2">
            <h3 class="text-xl sm:text-2xl font-black text-white leading-snug tracking-tight" x-text="current.title"></h3>
            
            <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 text-xs sm:text-sm text-zinc-300 leading-relaxed font-medium">
                <p x-text="current.content"></p>
            </div>
        </div>

        <!-- Step Indicator Dots -->
        <div class="relative z-10 flex items-center justify-center gap-1.5 my-4">
            <template x-for="(item, idx) in announcements" :key="idx">
                <div class="h-1.5 rounded-full transition-all duration-300"
                     :class="idx === currentIndex ? 'w-6 bg-red-600' : 'w-1.5 bg-zinc-800'"></div>
            </template>
        </div>

        <!-- Footer Action Button -->
        <div class="relative z-10 pt-2">
            <button type="button" @click="next()" class="w-full py-3.5 px-5 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                <span x-text="!isLast ? ('Pengumuman Selanjutnya (' + (currentIndex + 1) + '/' + announcements.length + ')') : 'Saya Mengerti & Akses Dashboard'"></span>
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
            </button>
        </div>
    </div>
</div>
@endif
