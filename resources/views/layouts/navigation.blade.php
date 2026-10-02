<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-slate-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-xl tracking-tight text-white">
                        <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">WeWatch</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:flex">
                    @if(Auth::user()->isSuperAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-slate-300 hover:text-white">
                            {{ __('System Control') }}
                        </x-nav-link>
                    @elseif(Auth::user()->isCreator())
                        <x-nav-link :href="route('creator.dashboard')" :active="request()->routeIs('creator.dashboard')" class="text-slate-300 hover:text-white">
                            {{ __('Creator Studio') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="text-slate-300 hover:text-white">
                            {{ __('Watch Home') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown & Role Badge -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-4">
                <!-- Active Role Pill -->
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ Auth::user()->role->badgeColor() }}">
                    {{ Auth::user()->role->label() }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium rounded-lg text-slate-200 bg-slate-800 hover:bg-slate-700 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-700 text-xs text-slate-400">
                            Logged in as <span class="font-medium text-slate-200">{{ Auth::user()->email }}</span>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->isSuperAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('System Control') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isCreator())
                <x-responsive-nav-link :href="route('creator.dashboard')" :active="request()->routeIs('creator.dashboard')">
                    {{ __('Creator Studio') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    {{ __('Watch Home') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-800">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-slate-100">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold border {{ Auth::user()->role->badgeColor() }}">
                    {{ Auth::user()->role->label() }}
                </span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
