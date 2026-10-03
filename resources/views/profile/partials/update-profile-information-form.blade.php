<section class="space-y-6">
    <header class="space-y-1">
        <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <span>Informasi Profil & Persona</span>
        </h2>
        <p class="text-xs text-zinc-400">
            Perbarui nama lengkap, alamat email akun, dan identitas pengguna streaming kamu.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Persona Avatar Preview Selector -->
        <div class="p-4 rounded-2xl bg-zinc-950/80 border border-zinc-800/80 space-y-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block">Avatar Persona Cinema</span>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-600 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-red-600/20 border-2 border-red-500/50 shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold text-white text-xs">{{ $user->name }}</h4>
                    <span class="text-[10px] text-zinc-400 font-mono">{{ $user->email }}</span>
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-mono font-bold text-[9px]">Akun Aktif</span>
                        <span class="px-2 py-0.5 rounded bg-red-600/15 border border-red-600/30 text-red-400 font-extrabold text-[9px]">Member WeWatch</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" value="Nama Lengkap / Username" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap kamu..." />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email Akun" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" placeholder="email@domain.com" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs text-amber-400 font-medium">
                        Alamat email kamu belum terverifikasi.

                        <button form="send-verification" class="underline text-xs text-zinc-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-1">
                            Klik di sini untuk mengirim ulang link verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-400">
                            Link verifikasi baru telah dikirimkan ke email kamu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="py-2.5 px-5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/20 transition active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                <span>Simpan Perubahan Profil</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-400 flex items-center gap-1"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <span>Berhasil diperbarui!</span>
                </p>
            @endif
        </div>
    </form>
</section>
