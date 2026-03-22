<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ChunkIQ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f4f6fa] text-gray-900">
        <x-impersonation-banner />

        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            {{-- Page heading --}}
            @isset($header)
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center gap-4">
                        <div class="w-1 self-stretch rounded-full bg-[#0f62fe] shrink-0"></div>
                        <div class="flex-1">
                            {{ $header }}
                        </div>
                    </div>
                </div>
            @endisset

            {{-- Page content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @auth
        <x-help-widget />
        @endauth
    </body>
</html>
