<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeWatch') }} — Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-zinc-100 antialiased bg-zinc-950 selection:bg-red-600 selection:text-white min-h-screen relative overflow-x-hidden">
        <!-- Floating Popup Toast Notification System -->
        <x-toast-notification />

        <!-- Ambient Backdrop Light Spotlights -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-red-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

        <div class="min-h-screen flex flex-col justify-center items-center py-10 relative z-10 px-4 sm:px-6 lg:px-8">
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-2 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-red-600 to-rose-500 flex items-center justify-center shadow-lg shadow-red-600/30 group-hover:scale-105 transition duration-300">
                        <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-wider text-white">WE<span class="text-red-500">WATCH</span></span>
                </a>
            </div>

            <div class="w-full max-w-md sm:max-w-lg flex justify-center">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
