<x-teacher-layout>
    <x-slot name="title">Actiepunten — Kwaliteit in Beeld</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Actiepunten</h1>
            <p class="mt-1 text-sm text-slate-500">Overzicht van alle actiepunten</p>
        </div>

        {{-- Filter tabs --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('teacher.action-points.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ $filter === 'all' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Alle
            </a>
            @foreach($statuses as $status)
                @php
                    $colorMap = [
                        'Niet gestart'  => ['active' => 'bg-slate-600 text-white', 'idle' => 'border-slate-200 text-slate-600 hover:bg-slate-50'],
                        'Op schema'     => ['active' => 'bg-emerald-600 text-white', 'idle' => 'border-emerald-200 text-emerald-700 hover:bg-emerald-50'],
                        'Loopt achter'  => ['active' => 'bg-amber-600 text-white', 'idle' => 'border-amber-200 text-amber-700 hover:bg-amber-50'],
                        'Uitgesteld'    => ['active' => 'bg-orange-500 text-white', 'idle' => 'border-orange-200 text-orange-700 hover:bg-orange-50'],
                        'Afgerond'      => ['active' => 'bg-blue-600 text-white', 'idle' => 'border-blue-200 text-blue-700 hover:bg-blue-50'],
                    ];
                    $c = $colorMap[$status->name] ?? $colorMap['Niet gestart'];
                    $isActive = (string)$filter === (string)$status->id;
                @endphp
                <a href="{{ route('teacher.action-points.index', ['filter' => $status->id]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors
                          {{ $isActive ? $c['active'] : 'bg-white '.$c['idle'] }}">
                    {{ $status->name }}
                </a>
            @endforeach
        </div>

        {{-- Actiepunten lijst --}}
        @forelse($actionPoints as $ap)
            @php
                $statusName = $ap->status?->name ?? 'Onbekend';
                $badgeMap = [
                    'Niet gestart'  => 'bg-slate-100 text-slate-700 border border-slate-300',
                    'Op schema'     => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                    'Loopt achter'  => 'bg-amber-100 text-amber-700 border border-amber-300',
                    'Uitgesteld'    => 'bg-orange-100 text-orange-700 border border-orange-300',
                    'Afgerond'      => 'bg-blue-100 text-blue-700 border border-blue-300',
                ];
                $badge = $badgeMap[$statusName] ?? $badgeMap['Niet gestart'];
                $isOverdue = $ap->end_date
                    && \Carbon\Carbon::parse($ap->end_date)->isPast()
                    && $statusName !== 'Afgerond';
            @endphp
            <div class="bg-white border-2 rounded-xl p-5 {{ $isOverdue ? 'border-red-200' : 'border-slate-200' }}">
                {{-- Gekleurde linkerbalk per thema --}}
                @if($ap->criterion?->standard?->theme)
                    <div class="flex gap-4">
                        <div class="w-1 rounded-full flex-shrink-0 self-stretch" style="background-color: {{ $ap->criterion->standard->theme->color }}"></div>
                        <div class="flex-1 min-w-0">
                @else
                    <div class="flex-1 min-w-0">
                @endif

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-3">
                    @if($ap->criterion?->standard?->theme)
                        <span class="font-semibold" style="color: {{ $ap->criterion->standard->theme->color }}">
                            {{ $ap->criterion->standard->theme->code }}
                        </span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span>{{ $ap->criterion->standard->name }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span>Criterium {{ $ap->criterion->number }}</span>
                        <span class="ml-1">
                            <a href="{{ route('teacher.themes.show', $ap->criterion->standard->theme) }}"
                               class="text-blue-500 hover:text-blue-700 underline">Bekijken</a>
                        </span>
                    @endif
                </div>

                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-800 leading-snug mb-3">{{ $ap->description }}</p>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                            @if($ap->user)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $ap->user->name }}
                                </span>
                            @endif
                            @if($ap->start_date)
                                <span class="{{ $isOverdue ? 'text-red-600 font-medium' : '' }}">
                                    {{ \Carbon\Carbon::parse($ap->start_date)->format('d-m-Y') }}
                                    →
                                    {{ $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('d-m-Y') : '—' }}
                                    @if($isOverdue) (verlopen) @endif
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $badge }}">
                                {{ $statusName }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Evaluaties --}}
                @if($ap->evaluations->isNotEmpty())
                    <div class="mt-4 pt-3 border-t border-slate-100" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            {{ $ap->evaluations->count() }} evaluatie(s)
                        </button>
                        <div x-show="open" x-transition class="mt-2 space-y-2">
                            @foreach($ap->evaluations as $eval)
                                <div class="bg-slate-50 rounded-lg px-3 py-2">
                                    <p class="text-xs text-slate-400 mb-1">{{ $eval->created_at?->format('d-m-Y H:i') }}</p>
                                    <p class="text-sm text-slate-700">{{ $eval->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($ap->criterion?->standard?->theme)
                        </div>{{-- /flex-1 --}}
                    </div>{{-- /flex gap-4 --}}
                @endif
            </div>
        @empty
            <div class="bg-white border-2 border-slate-200 rounded-xl p-12 text-center">
                <p class="text-slate-400 text-sm">Geen actiepunten gevonden{{ $filter !== 'all' ? ' voor dit filter' : '' }}.</p>
            </div>
        @endforelse
    </div>
</x-teacher-layout>
