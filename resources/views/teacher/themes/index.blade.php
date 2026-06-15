<x-teacher-layout>
    <x-slot name="title">Thema's — Kwaliteit in Beeld</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Thema's</h1>
            <p class="mt-1 text-sm text-slate-500">Selecteer een thema om de standaarden en criteria te bekijken</p>
        </div>

        {{-- Team-selector: tabs bij ≤3 teams, dropdown bij meer --}}
        @if($teams->count() > 1)
            @if($teams->count() <= 3)
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                    @foreach($teams as $team)
                        <a href="{{ route('teacher.themes.index', ['team' => $team->id]) }}"
                           class="px-5 py-2 rounded-lg text-sm font-medium transition-all
                                  {{ $activeTeam?->id === $team->id
                                      ? 'bg-white text-slate-900 shadow-sm'
                                      : 'text-slate-500 hover:text-slate-700' }}">
                            {{ $team->name }}
                            @if($team->locations->isNotEmpty())
                                <span class="text-xs font-normal opacity-60">({{ $team->locations->first()->abbreviation }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex items-center gap-3">
                    <label for="team-select" class="text-sm font-medium text-slate-600 whitespace-nowrap">Opleiding:</label>
                    <select id="team-select"
                            onchange="window.location='{{ route('teacher.themes.index') }}?team='+this.value"
                            class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-48">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $activeTeam?->id === $team->id ? 'selected' : '' }}>
                                {{ $team->name }}{{ $team->locations->isNotEmpty() ? ' ('.$team->locations->first()->abbreviation.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endif

        @if($themes->isEmpty())
            <div class="bg-white border-2 border-slate-200 rounded-xl p-12 text-center">
                <p class="text-slate-400 text-sm">Nog geen thema's aangemaakt.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($themes as $theme)
                    @php
                        $criteriaCount = $theme->standards->sum(fn ($s) => $s->criteria->count());
                        $themeUrl = route('teacher.themes.show', array_filter([
                            'theme' => $theme->id,
                            'team'  => $activeTeam?->id,
                        ]));
                    @endphp
                    <a href="{{ $themeUrl }}"
                       class="group bg-white border-2 border-slate-200 rounded-xl p-6 hover:shadow-md hover:border-slate-300 transition-all overflow-hidden relative">
                        {{-- Colored left accent --}}
                        <div class="absolute top-0 left-0 w-1.5 h-full rounded-l-xl" style="background-color: {{ $theme->color }}"></div>

                        <div class="pl-2">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background-color: {{ $theme->color }}">
                                        {{ $theme->code }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 group-hover:text-slate-700 transition-colors leading-tight">
                                            {{ $theme->name }}
                                        </h3>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span>{{ $theme->standards_count }} standaard{{ $theme->standards_count !== 1 ? 'en' : '' }}</span>
                                <span>{{ $criteriaCount }} {{ $criteriaCount === 1 ? 'criterium' : 'criteria' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-teacher-layout>
