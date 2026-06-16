<x-layouts::app :title="__('Beheer')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl" level="1">Beheer</flux:heading>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Kies een onderdeel om te beheren.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Gebruikers --}}
            <a href="{{ route('admin.users.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m6-7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Gebruikers</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer accounts en wijs rollen toe aan medewerkers.</p>
                </div>
            </a>

            {{-- Teams --}}
            <a href="{{ route('admin.teams.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Teams</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Maak teams (opleidingen) aan en koppel leden en teamleiders.</p>
                </div>
            </a>

            {{-- Locaties --}}
            <a href="{{ route('admin.locations.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Locaties</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer vestigingen en koppel ze aan teams en gebruikers.</p>
                </div>
            </a>

            {{-- Thema's --}}
            <a href="{{ route('admin.themes.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Thema's</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer de kwaliteitsthema's met bijbehorende kleur en code.</p>
                </div>
            </a>

            {{-- Standaarden --}}
            <a href="{{ route('admin.standards.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Standaarden</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer standaarden binnen de thema's van het kwaliteitskader.</p>
                </div>
            </a>

            {{-- Criteria --}}
            <a href="{{ route('admin.criteria.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Criteria</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer criteria per standaard inclusief toelichting en uitleg.</p>
                </div>
            </a>

            {{-- Indicatoren --}}
            <a href="{{ route('admin.indicators.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Indicatoren</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer de indicatoren per criterium die het bewijsmateriaal beschrijven.</p>
                </div>
            </a>

            {{-- Actiepunt statussen --}}
            <a href="{{ route('admin.action-point-statuses.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Actiepunt statussen</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Stel de statuslabels in die gebruikt worden voor actiepunten.</p>
                </div>
            </a>

            {{-- Rapportage periodes --}}
            <a href="{{ route('admin.reporting-periods.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-blue-500">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">Rapportage periodes</p>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Beheer de rapportageperiodes waarop stoplichten worden bijgehouden.</p>
                </div>
            </a>

        </div>
    </div>
</x-layouts::app>
