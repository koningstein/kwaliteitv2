<x-teacher-layout>
    <x-slot name="title">Dashboard — Kwaliteit in Beeld</x-slot>

    <div class="space-y-8">
        {{-- Export buttons --}}
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="14 2 14 8 20 8"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="16" y1="13" x2="8" y2="13"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Exporteer naar PDF
            </button>
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="14 2 14 8 20 8"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="3" y1="10" x2="21" y2="10"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="3" y1="14" x2="21" y2="14"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="10" y1="2" x2="10" y2="22"/>
                    <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="14" y1="2" x2="14" y2="22"/>
                </svg>
                Exporteer naar Excel
            </button>
        </div>

        {{-- KPI-kaarten actiepunten --}}
        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Actiepunten</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('teacher.action-points.index') }}"
                   class="bg-white border-2 border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-slate-300 transition-all text-center">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3v18h18M18 17V9M13 17V5M8 17v-3"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalActionPoints }}</div>
                     <div class="text-xs text-slate-500 mt-1 font-medium">Totaal Actiepunten</div>
                    <div class="text-xs text-slate-400 mt-2">↗ Bekijk details</div>
                </a>

                @foreach($statuses as $status)
                    @php
                        $colorMap = [
                            'Niet gestart' => [
                                'text'   => 'text-slate-600',
                                'bg'     => 'bg-white border-slate-300',
                                'hover'  => 'hover:border-slate-400',
                                'icon'   => 'text-slate-400',
                                'detail' => 'text-slate-400',
                            ],
                            'Op schema' => [
                                'text'   => 'text-emerald-700',
                                'bg'     => 'bg-white border-emerald-300',
                                'hover'  => 'hover:border-emerald-400',
                                'icon'   => 'text-emerald-400',
                                'detail' => 'text-emerald-400',
                            ],
                            'Loopt achter' => [
                                'text'   => 'text-amber-700',
                                'bg'     => 'bg-white border-amber-300',
                                'hover'  => 'hover:border-amber-400',
                                'icon'   => 'text-amber-400',
                                'detail' => 'text-amber-400',
                            ],
                            'Uitgesteld' => [
                                'text'   => 'text-orange-700',
                                'bg'     => 'bg-white border-orange-300',
                                'hover'  => 'hover:border-orange-400',
                                'icon'   => 'text-orange-400',
                                'detail' => 'text-orange-400',
                            ],
                            'Afgerond' => [
                                'text'   => 'text-blue-700',
                                'bg'     => 'bg-white border-blue-300',
                                'hover'  => 'hover:border-blue-400',
                                'icon'   => 'text-blue-400',
                                'detail' => 'text-blue-400',
                            ],
                        ];
                        $colors = $colorMap[$status->name] ?? $colorMap['Niet gestart'];
                    @endphp
                    <a href="{{ route('teacher.action-points.index', ['filter' => $status->id]) }}"
                       class="border-2 rounded-xl p-5 hover:shadow-md transition-all text-center {{ $colors['bg'] }} {{ $colors['hover'] }}">
                        <div class="flex items-center justify-center mb-2">
                            @if($status->name === 'Op schema' || $status->name === 'Afgerond')
                                <svg class="w-5 h-5 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                    <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            @endif
                        </div>
                        <div class="text-3xl font-bold {{ $colors['text'] }}">{{ $status->action_points_count }}</div>
                        <div class="text-xs text-slate-500 mt-1 font-medium">{{ $status->name }}</div>
                        <div class="text-xs {{ $colors['detail'] }} mt-2">↗ Bekijk details</div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Voortgang per rapportageperiode --}}
        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Voortgang per periode</h2>
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Voortgang per Jaar</h3>
                <div class="space-y-6">
                    @foreach($periodStats as $stat)
                        <div>
                            <h4 class="text-lg font-semibold text-slate-800 mb-3">{{ $stat['period']->label }}</h4>
                            @if($stat['total'] > 0)
                                <div class="space-y-2">
                                    <div class="flex gap-1 h-8 rounded-lg overflow-hidden">
                                        @if($stat['sufficient'] > 0)
                                            <div class="bg-emerald-500 flex items-center justify-center text-white text-xs font-medium"
                                                 style="width: {{ $stat['pct_sufficient'] }}%">
                                                {{ $stat['sufficient'] }}
                                            </div>
                                        @endif
                                        @if($stat['attention'] > 0)
                                            <div class="bg-amber-500 flex items-center justify-center text-white text-xs font-medium"
                                                 style="width: {{ $stat['pct_attention'] }}%">
                                                {{ $stat['attention'] }}
                                            </div>
                                        @endif
                                        @if($stat['insufficient'] > 0)
                                            <div class="bg-rose-500 flex items-center justify-center text-white text-xs font-medium"
                                                 style="width: {{ $stat['pct_insufficient'] }}%">
                                                {{ $stat['insufficient'] }}
                                            </div>
                                        @endif
                                        @php $pctNone = max(0, 100 - $stat['pct_sufficient'] - $stat['pct_attention'] - $stat['pct_insufficient']); @endphp
                                        @if($pctNone > 0)
                                            <div class="bg-slate-200 h-full flex-1"></div>
                                        @endif
                                    </div>
                                    <div class="flex justify-between text-xs text-slate-600">
                                        <span>🟢 {{ $stat['sufficient'] }} Voldoende ({{ $stat['pct_sufficient'] }}%)</span>
                                        <span>🟠 {{ $stat['attention'] }} Aandacht ({{ $stat['pct_attention'] }}%)</span>
                                        <span>🔴 {{ $stat['insufficient'] }} Onvoldoende ({{ $stat['pct_insufficient'] }}%)</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-sm text-slate-400">Geen data</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Voortgang per thema --}}
        @if($themeStats->isNotEmpty())
            <div>
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Voortgang per Thema</h3>
                    <div class="space-y-6">
                        @foreach($themeStats as $stat)
                            <a href="{{ route('teacher.themes.show', $stat['theme']) }}" class="block group">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-1 h-6 rounded flex-shrink-0" style="background-color: {{ $stat['theme']->color }}"></div>
                                    <h4 class="text-lg font-semibold text-slate-800 group-hover:text-slate-900">
                                        {{ $stat['theme']->code }}. {{ $stat['theme']->name }}
                                    </h4>
                                </div>
                                @if($stat['total'] > 0)
                                    <div class="space-y-2">
                                        <div class="flex gap-1 h-8 rounded-lg overflow-hidden">
                                            @if($stat['sufficient'] > 0)
                                                <div class="bg-emerald-500 flex items-center justify-center text-white text-xs font-medium"
                                                     style="width: {{ $stat['pct_sufficient'] }}%">
                                                    {{ $stat['sufficient'] }}
                                                </div>
                                            @endif
                                            @if($stat['attention'] > 0)
                                                <div class="bg-amber-500 flex items-center justify-center text-white text-xs font-medium"
                                                     style="width: {{ $stat['pct_attention'] }}%">
                                                    {{ $stat['attention'] }}
                                                </div>
                                            @endif
                                            @if($stat['insufficient'] > 0)
                                                <div class="bg-rose-500 flex items-center justify-center text-white text-xs font-medium"
                                                     style="width: {{ $stat['pct_insufficient'] }}%">
                                                    {{ $stat['insufficient'] }}
                                                </div>
                                            @endif
                                            @php $pctNone = max(0, 100 - $stat['pct_sufficient'] - $stat['pct_attention'] - $stat['pct_insufficient']); @endphp
                                            @if($pctNone > 0)
                                                <div class="bg-slate-200 h-full flex-1"></div>
                                            @endif
                                        </div>
                                        <div class="flex justify-between text-xs text-slate-600">
                                            <span>🟢 {{ $stat['sufficient'] }} Voldoende ({{ $stat['pct_sufficient'] }}%)</span>
                                            <span>🟠 {{ $stat['attention'] }} Aandacht ({{ $stat['pct_attention'] }}%)</span>
                                            <span>🔴 {{ $stat['insufficient'] }} Onvoldoende ({{ $stat['pct_insufficient'] }}%)</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-slate-400">Geen data</div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-teacher-layout>
