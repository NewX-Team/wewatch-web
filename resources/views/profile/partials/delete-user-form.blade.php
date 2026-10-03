<section class="space-y-6">
    <header class="space-y-1">
        <h2 class="text-lg font-black text-red-500 tracking-tight flex items-center gap-2">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
            <span>Zona Bahaya — Hapus Akun</span>
        </h2>

        <p class="text-xs text-zinc-400">
            Setelah akun kamu dihapus, seluruh data watch history, favorit, dan pengaturan akan dihapus secara permanen.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="py-2.5 px-4 rounded-xl bg-red-600/15 hover:bg-red-600 border border-red-600/30 text-red-400 hover:text-white font-bold text-xs transition shadow active:scale-95 flex items-center gap-2"
    >
        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
        <span>Hapus Akun Permanen</span>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <div class="space-y-1">
                <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <span>Konfirmasi Penghapusan Akun</span>
                </h3>

                <p class="text-xs text-zinc-400 leading-relaxed">
                    Apakah kamu yakin ingin menghapus akun ini secara permanen? Silakan masukkan kata sandi kamu untuk memverifikasi tindakan ini.
                </p>
            </div>

            <div class="pt-2">
                <x-input-label for="password" value="Kata Sandi Verifikasi" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Masukkan kata sandi akun kamu..."
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-zinc-800">
                <button type="button" x-on:click="$dispatch('close')" class="py-2.5 px-4 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold text-xs transition">
                    Batal
                </button>

                <button type="submit" class="py-2.5 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    <span>Ya, Hapus Akun</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
