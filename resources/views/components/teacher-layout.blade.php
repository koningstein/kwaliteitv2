@props(['title' => 'Kwaliteit in Beeld'])

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
<body class="min-h-screen bg-slate-50 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Title --}}
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 leading-tight">Kwaliteit in Beeld</div>
                        <div class="text-xs text-slate-500 leading-tight">
                            @php
                                $user = auth()->user();
                                if ($user) {
                                    $teamNames = $user->teams->pluck('name');
                                    if ($teamNames->isEmpty()) {
                                        $teamNames = $user->managedTeams->pluck('name');
                                    }
                                }
                            @endphp

                            @if(!empty($teamNames) && $teamNames->isNotEmpty())
                                {{ $teamNames->implode(', ') }}
                            @elseif($user?->hasRole('ok_medewerker'))
                                Onderwijs &amp; Kwaliteit
                            @elseif($user?->hasRole('directie'))
                                Directie
                            @else
                                Schiedamseweg 245
                            @endif
                        </div>
                    </div>
                </a>

                {{-- Navigation + User menu --}}
                <div class="flex items-center gap-1">
                    <nav class="flex items-center gap-1">
                        <a href="{{ route('teacher.dashboard') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ request()->routeIs('teacher.dashboard')
                                     ? 'bg-slate-100 text-slate-800'
                                     : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Dashboard
                        </a>
                        @can('view-themes')
                        <a href="{{ route('teacher.themes.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ request()->routeIs('teacher.themes.*')
                                     ? 'bg-slate-100 text-slate-800'
                                     : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Thema's
                        </a>
                        @endcan
                        @hasanyrole('kwaliteitszorg|onderwijsleider')
                        <a href="{{ route('teacher.team.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ request()->routeIs('teacher.team.*')
                                     ? 'bg-slate-100 text-slate-800'
                                     : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Team
                        </a>
                        @endhasanyrole
                        @role('ok_medewerker')
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                            Beheer
                        </a>
                        @endrole
                    </nav>

                    {{-- Scheidingslijn --}}
                    <div class="w-px h-6 bg-slate-200 mx-2"></div>

                    {{-- Gebruikersmenu --}}
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @keydown.escape="open = false"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                        >
                            {{-- Avatar initialen --}}
                            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold select-none">
                                {{ auth()->user()->initials() }}
                            </span>
                            <span class="hidden sm:block max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-xl border border-slate-200 shadow-lg py-1 z-50"
                            style="display: none; top: 100%;"
                        >
                            {{-- Gebruikersinformatie --}}
                            <div class="px-4 py-3 border-b border-slate-100">
                                <div class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                                @if($user && $user->roles->isNotEmpty())
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                            {{ ucfirst(str_replace('_', ' ', $user->roles->first()->name)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Uitloggen --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors text-left"
                                >
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Uitloggen
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
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
