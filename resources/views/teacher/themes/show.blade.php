<x-teacher-layout>
    <x-slot name="title">{{ $theme->code }} — {{ $theme->name }}</x-slot>

    <div class="space-y-6">
        {{-- Terug + header --}}
        <div>
            <a href="{{ route('teacher.themes.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Terug naar thema's
            </a>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-base flex-shrink-0"
                     style="background-color: {{ $theme->color }}">
                    {{ $theme->code }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $theme->name }}</h1>
                    @if($periods->isNotEmpty())
                        <p class="text-sm text-slate-500 mt-0.5">
                            Periodes:
                            @foreach($periods as $p)
                                {{ $p->label }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Score legenda --}}
        @if($periods->isNotEmpty())
            <div class="flex flex-wrap items-center gap-6 text-xs text-slate-500 bg-white border border-slate-200 rounded-xl px-5 py-3">
                <span class="font-semibold text-slate-700">Scores:</span>
                <span class="flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-emerald-500 inline-block"></span> Voldoende
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-amber-500 inline-block"></span> Aandacht
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-rose-500 inline-block"></span> Onvoldoende
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-slate-200 inline-block"></span> Niet beoordeeld
                </span>
            </div>
        @endif

        {{-- Standaarden — beginnen ingeklapt --}}
        @forelse($theme->standards as $standard)
            <livewire:teacher.standard-card
                :standard="$standard"
                :periods="$periods"
                :key="'sc-'.$standard->id"
            />
        @empty
            <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
                <p class="text-slate-400 text-sm">Geen standaarden gevonden voor dit thema.</p>
            </div>
        @endforelse
    </div>
</x-teacher-layout>
