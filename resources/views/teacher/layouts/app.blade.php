<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Kwaliteit in Beeld' }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo + Title --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900 leading-tight">Kwaliteit in Beeld</div>
                            <div class="text-xs text-gray-500 leading-tight">Kwaliteitsbeheer</div>
                        </div>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex items-center gap-1">
                    <a href="{{ route('teacher.dashboard') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('teacher.dashboard')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('teacher.themes.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('teacher.themes.*')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        Thema's
                    </a>
                    <a href="{{ route('teacher.team.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('teacher.team.*')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        Team
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
