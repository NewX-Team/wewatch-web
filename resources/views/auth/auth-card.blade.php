@props(['initialMode' => 'login'])

<div x-data="{
        mode: '{{ old('name') || $errors->has('name') || $errors->has('password_confirmation') ? 'register' : (request()->routeIs('register') ? 'register' : $initialMode) }}',
        switchMode(target) {
            if (this.mode === target) return;
            this.mode = target;
            if (window.history && window.history.pushState) {
                const targetUrl = target === 'login' ? '{{ route('login') }}' : '{{ route('register') }}';
                window.history.pushState({}, '', targetUrl);
            }
        }
    }"
    class="w-full bg-zinc-900/95 border border-zinc-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-red-950/20 backdrop-blur-2xl relative overflow-hidden">

    <!-- Ambient Red Spotlights Inside Card -->
    <div class="absolute -top-24 -right-24 w-52 h-52 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-52 h-52 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Mode Switcher Pill Bar -->
    <div class="relative bg-zinc-950/90 p-1.5 rounded-2xl border border-zinc-800/80 flex items-center mb-6 z-20 shadow-inner">
        <!-- Sliding Pill Background -->
        <div class="absolute top-1.5 bottom-1.5 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 shadow-lg shadow-red-600/40 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]"
             :style="mode === 'login' ? 'left: 6px; width: calc(50% - 6px);' : 'left: 50%; width: calc(50% - 6px);'"></div>
        
        <!-- Login Mode Tab Button -->
        <button type="button" @click="switchMode('login')"
                class="relative z-10 w-1/2 py-2.5 text-center text-xs font-black tracking-wider transition-colors duration-300 uppercase flex items-center justify-center gap-1.5"
                :class="mode === 'login' ? 'text-white' : 'text-zinc-400 hover:text-zinc-200'">
            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
            <span>Masuk</span>
        </button>

        <!-- Register Mode Tab Button -->
        <button type="button" @click="switchMode('register')"
                class="relative z-10 w-1/2 py-2.5 text-center text-xs font-black tracking-wider transition-colors duration-300 uppercase flex items-center justify-center gap-1.5"
                :class="mode === 'register' ? 'text-white' : 'text-zinc-400 hover:text-zinc-200'">
            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <span>Daftar Akun</span>
        </button>
    </div>

    <!-- Suspended / Error Session Alert Banner -->
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 p-3.5 rounded-2xl bg-red-600/15 border border-red-600/40 text-red-400 relative overflow-hidden shadow-xl z-20">
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-xl bg-red-600/20 border border-red-500/30 text-red-400 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h4 class="font-extrabold text-[11px] text-white uppercase tracking-wider">Pemberitahuan Sistem</h4>
                    <p class="text-[11px] text-zinc-300 mt-0.5 leading-relaxed font-medium">
                        {{ session('error') }}
                    </p>
                </div>
                <button type="button" @click="show = false" class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800/60 transition shrink-0" aria-label="Tutup alert">
                    <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 z-20" :status="session('status')" />

    <!-- SLIDER VIEWPORT CONTAINER (Zero ghosting, zero overlap traces, pure 200% width track slide) -->
    <div class="w-full overflow-hidden relative z-10">
        <div class="flex w-[200%] transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] items-start"
             :class="mode === 'login' ? 'translate-x-0' : '-translate-x-1/2'">

            <!-- ==================== SLIDE 1: LOGIN FORM ==================== -->
            <div class="w-1/2 pr-3 sm:pr-4 space-y-4 shrink-0"
                 :class="mode === 'login' ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none transition-opacity duration-300'">

                <div class="mb-2">
                    <h2 class="text-2xl font-black text-white tracking-tight">Masuk ke WeWatch</h2>
                    <p class="text-xs text-zinc-400 mt-1">Akses ribuan film, serial &amp; studio sinematik.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="login_email" :value="__('Email Address')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                        <x-text-input id="login_email" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-3 px-3.5 transition duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="login_password" :value="__('Kata Sandi')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-zinc-400 hover:text-red-400 transition" href="{{ route('password.request') }}">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        <x-text-input id="login_password" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-3 px-3.5 transition duration-200"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        placeholder="••••••••" />

                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-400" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-zinc-800 bg-zinc-950 text-red-600 shadow-sm focus:ring-red-500 focus:ring-offset-zinc-900" name="remember">
                            <span class="ms-2 text-xs text-zinc-400 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Masuk Sekarang</span>
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-zinc-800/80 text-center">
                    <p class="text-xs text-zinc-400">
                        Belum memiliki akun?
                        <button type="button" @click="switchMode('register')" class="font-bold text-red-500 hover:text-red-400 underline transition ml-1">Daftar Akun Baru</button>
                    </p>
                </div>
            </div>

            <!-- ==================== SLIDE 2: REGISTER FORM ==================== -->
            <div class="w-1/2 pl-3 sm:pl-4 space-y-3.5 shrink-0"
                 :class="mode === 'register' ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none transition-opacity duration-300'">

                <div class="mb-2">
                    <h2 class="text-2xl font-black text-white tracking-tight">Daftar Akun Baru</h2>
                    <p class="text-xs text-zinc-400 mt-1">Nikmati tayangan film 4K UHD &amp; fitur studio sinematik.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="register_name" :value="__('Nama Lengkap')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                        <x-text-input id="register_name" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-2.5 px-3.5 transition duration-200" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Nama Anda atau Nama Studio" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="register_email" :value="__('Email Address')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                        <x-text-input id="register_email" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-2.5 px-3.5 transition duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="register_password" :value="__('Kata Sandi')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                        <x-text-input id="register_password" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-2.5 px-3.5 transition duration-200"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password"
                                        placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="register_password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-xs font-bold text-zinc-300 uppercase tracking-wider" />
                        <x-text-input id="register_password_confirmation" class="block mt-1.5 w-full bg-zinc-950/90 border-zinc-800 text-white placeholder-zinc-500 rounded-xl focus:border-red-600 focus:ring-red-600 text-xs py-2.5 px-3.5 transition duration-200"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Ulangi kata sandi" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <div class="pt-1.5">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 text-white font-extrabold text-xs tracking-wide shadow-lg shadow-red-600/30 transition hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Buat Akun Sekarang</span>
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                        </button>
                    </div>
                </form>

                <div class="pt-3 border-t border-zinc-800/80 text-center">
                    <p class="text-xs text-zinc-400">
                        Sudah memiliki akun?
                        <button type="button" @click="switchMode('login')" class="font-bold text-red-500 hover:text-red-400 underline transition ml-1">Masuk ke Akun</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
