<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kwaliteit in Beeld</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Title --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 leading-tight">Kwaliteit in Beeld</div>
                        <div class="text-xs text-slate-500 leading-tight">ICT &amp; Programmeren &bull; Schiedamseweg 245</div>
                    </div>
                </a>

                {{-- Navigatie --}}
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('teacher.dashboard') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                            Inloggen
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Kwaliteitszorg platform
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 leading-tight mb-6">
                    Kwaliteit in Beeld
                </h1>
                <p class="text-xl text-slate-500 leading-relaxed mb-10">
                    Het platform voor kwaliteitszorg binnen ICT &amp; Programmeren. Volg de voortgang van criteria, beheer actiepunten en houd de kwaliteit van onderwijs inzichtelijk.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    @auth
                        <a href="{{ route('teacher.dashboard') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Naar het dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Inloggen
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Kenmerken --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-8">Wat biedt het platform</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Thema's & Criteria --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Thema's &amp; Criteria</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Alle kwaliteitscriteria gestructureerd per thema en standaard. Beoordeel per rapportageperiode en houd de voortgang bij.
                </p>
            </div>

            {{-- Actiepunten --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Actiepunten</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Koppel verbeteracties direct aan criteria. Wijs verantwoordelijken toe, stel deadlines in en volg de status per actiepunt.
                </p>
            </div>

            {{-- Voortgang --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Voortgang &amp; Rapportage</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Inzicht in de kwaliteitsstatus per jaar, per thema en per team. Overzichtelijke voortgangsbalken per rapportageperiode.
                </p>
            </div>

            {{-- Teams --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Teams</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Medewerkers zijn ingedeeld in teams met een teamleider. Actiepunten en verantwoordelijkheden zijn per team inzichtelijk.
                </p>
            </div>

            {{-- Rollen --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Rollen &amp; Rechten</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Gedifferentieerde toegang voor directie, O&amp;K, kwaliteitszorgmedewerkers, onderwijsleiders en medewerkers.
                </p>
            </div>

            {{-- Periodes --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Rapportageperiodes</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Scores en actiepunten worden bijgehouden per schooljaar. Vergelijk de voortgang tussen meerdere jaren.
                </p>
            </div>

        </div>
    </section>

    {{-- Statuslegenda --}}
    <section class="border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-6">Statusoverzicht</h2>
            <div class="flex flex-wrap gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-500"></div>
                    <span class="text-sm text-slate-700 font-medium">Voldoende</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-amber-500"></div>
                    <span class="text-sm text-slate-700 font-medium">Aandacht</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-rose-500"></div>
                    <span class="text-sm text-slate-700 font-medium">Onvoldoende</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-slate-300"></div>
                    <span class="text-sm text-slate-700 font-medium">Niet beoordeeld</span>
                </div>
                <div class="mx-3 w-px bg-slate-200 self-stretch"></div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-300">Op schema</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-300">Afgerond</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-300">Loopt achter</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 border border-orange-300">Uitgesteld</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-300">Niet gestart</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-md bg-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Kwaliteit in Beeld</span>
                </div>
                <p class="text-xs text-slate-400">ICT &amp; Programmeren &bull; Schiedamseweg 245</p>
            </div>
        </div>
    </footer>

</body>
</html>
