<x-teacher-layout>
    <x-slot name="title">Team — Kwaliteit in Beeld</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Team</h1>
            <p class="mt-1 text-sm text-slate-500">Overzicht van teamleden en hun voortgang</p>
        </div>

        @if($teams->isEmpty())
            {{-- Geen teams toegewezen --}}
            <div class="bg-white border-2 border-slate-200 rounded-xl p-12 text-center">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m6-7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-slate-500 font-medium">Er zijn geen teams aan u toegewezen.</p>
                <p class="text-slate-400 text-sm mt-1">Neem contact op met de beheerder om teams toe te wijzen.</p>
            </div>

        @else

            {{-- Team-tabs (alleen tonen bij meerdere teams) --}}
            @if($teams->count() > 1)
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                    @foreach($teams as $team)
                        <a href="{{ route('teacher.team.index', ['team' => $team->id]) }}"
                           class="px-5 py-2 rounded-lg text-sm font-medium transition-all
                                  {{ $activeTeam?->id === $team->id
                                      ? 'bg-white text-slate-900 shadow-sm'
                                      : 'text-slate-500 hover:text-slate-700' }}">
                            {{ $team->name }}
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-sm font-semibold text-slate-700">
                    {{ $activeTeam?->name }}
                </div>
            @endif

            @can('manage-team-users')
                {{-- Voortgang + Teambeheer tabs --}}
                <div x-data="{ tab: 'voortgang' }">
                    <div class="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                        <button
                            @click="tab = 'voortgang'"
                            :class="tab === 'voortgang' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                            Voortgang
                        </button>
                        <button
                            @click="tab = 'beheer'"
                            :class="tab === 'beheer' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                            Teambeheer
                        </button>
                    </div>

                    <div x-show="tab === 'voortgang'" class="mt-6 space-y-4">
                        @include('teacher.team.partials.voortgang', ['users' => $users])
                    </div>

                    <div x-show="tab === 'beheer'" class="mt-6">
                        <livewire:teacher.team-manager />
                    </div>
                </div>

            @else
                <div class="space-y-4">
                    @include('teacher.team.partials.voortgang', ['users' => $users])
                </div>
            @endcan

        @endif
    </div>
</x-teacher-layout>
