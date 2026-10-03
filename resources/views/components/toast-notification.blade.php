<!-- FLOATING POPUP TOAST NOTIFICATION STACK -->
<div class="fixed top-6 right-6 z-[100] max-w-md w-full pointer-events-none space-y-3 px-4 sm:px-0">
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="pointer-events-auto bg-zinc-900/95 border border-emerald-500/40 rounded-2xl p-4 shadow-2xl backdrop-blur-2xl relative overflow-hidden flex items-start gap-3.5">

            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-emerald-500/20 blur-2xl pointer-events-none"></div>

            <div class="w-9 h-9 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>

            <div class="flex-1 min-w-0 pr-2">
                <h4 class="font-extrabold text-xs text-white flex items-center gap-1.5">
                    <span>Berhasil Disimpan!</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </h4>
                <p class="text-xs text-zinc-300 mt-0.5 leading-relaxed font-medium">
                    {{ session('success') }}
                </p>
            </div>

            <button @click="show = false" class="text-zinc-500 hover:text-white p-1 rounded-lg hover:bg-zinc-800 transition" aria-label="Tutup pemberitahuan">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 6000)"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="pointer-events-auto bg-zinc-900/95 border border-red-600/40 rounded-2xl p-4 shadow-2xl backdrop-blur-2xl relative overflow-hidden flex items-start gap-3.5">

            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-red-600/20 blur-2xl pointer-events-none"></div>

            <div class="w-9 h-9 rounded-xl bg-red-600/20 border border-red-600/30 text-red-400 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>

            <div class="flex-1 min-w-0 pr-2">
                <h4 class="font-extrabold text-xs text-white flex items-center gap-1.5">
                    <span>Pemberitahuan Peringatan / Ditolak</span>
                </h4>
                <p class="text-xs text-zinc-300 mt-0.5 leading-relaxed font-medium">
                    {{ session('error') }}
                </p>
            </div>

            <button @click="show = false" class="text-zinc-500 hover:text-white p-1 rounded-lg hover:bg-zinc-800 transition" aria-label="Tutup pemberitahuan">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="pointer-events-auto bg-zinc-900/95 border border-amber-500/40 rounded-2xl p-4 shadow-2xl backdrop-blur-2xl relative overflow-hidden flex items-start gap-3.5">

            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-amber-500/20 blur-2xl pointer-events-none"></div>

            <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>

            <div class="flex-1 min-w-0 pr-2">
                <h4 class="font-extrabold text-xs text-white flex items-center gap-1.5">
                    <span>Pemberitahuan Status</span>
                </h4>
                <p class="text-xs text-zinc-300 mt-0.5 leading-relaxed font-medium">
                    {{ session('status') === 'profile-updated' ? 'Profil dan identitas berhasil diperbarui ke database!' : session('status') }}
                </p>
            </div>

            <button @click="show = false" class="text-zinc-500 hover:text-white p-1 rounded-lg hover:bg-zinc-800 transition" aria-label="Tutup pemberitahuan">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    @endif
</div>
