<x-teacher-layout>
    <x-slot name="title">Thema's — Kwaliteit in Beeld</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Thema's</h1>
            <p class="mt-1 text-sm text-slate-500">Selecteer een thema om de standaarden en criteria te bekijken</p>
        </div>

        @if($themes->isEmpty())
            <div class="bg-white border-2 border-slate-200 rounded-xl p-12 text-center">
                <p class="text-slate-400 text-sm">Nog geen thema's aangemaakt.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($themes as $theme)
                    @php
                        $criteriaCount = $theme->standards->sum(fn ($s) => $s->criteria->count());
                    @endphp
                    <a href="{{ route('teacher.themes.show', $theme) }}"
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
