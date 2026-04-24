@if($users->isEmpty())
    <div class="bg-white border-2 border-slate-200 rounded-xl p-12 text-center">
        <p class="text-slate-400 text-sm">Geen teamleden gevonden.</p>
    </div>
@else
    {{-- Verlopende deadlines waarschuwing --}}
    @php
        $hasAnyOverdue = false;
        foreach ($users as $u) {
            foreach ($u->actionPoints as $ap) {
                if ($ap->end_date && \Carbon\Carbon::parse($ap->end_date)->isPast() && $ap->status?->name !== 'Afgerond') {
                    $hasAnyOverdue = true;
                    break 2;
                }
            }
        }
    @endphp
    @if($hasAnyOverdue)
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3 text-sm text-red-700 font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Verlopende deadlines! Sommige actiepunten zijn verlopen en nog niet afgerond.
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($users as $u)
            @php
                $aps   = $u->actionPoints;
                $total = $aps->count();
                if ($total === 0) continue;

                $statusCounts = $aps->groupBy(fn ($ap) => $ap->status?->name ?? 'Onbekend')
                    ->map->count();

                $hasOverdue = $aps->filter(fn ($ap) => $ap->end_date
                    && \Carbon\Carbon::parse($ap->end_date)->isPast()
                    && $ap->status?->name !== 'Afgerond'
                )->isNotEmpty();
            @endphp
            <div class="bg-white border-2 rounded-xl p-5 hover:shadow-md transition-all {{ $hasOverdue ? 'border-red-200' : 'border-slate-200 hover:border-slate-300' }}">
                {{-- Avatar + naam --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ $u->initials() }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $u->name }}</h3>
                        <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
                    </div>
                    @if($hasOverdue)
                        <div class="flex-shrink-0 ml-auto">
                            <span class="inline-flex items-center gap-1 text-xs text-red-600 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Verlopen
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Status counts --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Totaal actiepunten</span>
                        <span class="font-semibold text-slate-800">{{ $total }}</span>
                    </div>
                    @foreach($statusCounts as $statusName => $count)
                        @php
                            $colorMap = [
                                'Niet gestart' => 'text-slate-500',
                                'Op schema'    => 'text-emerald-600',
                                'Loopt achter' => 'text-amber-600',
                                'Uitgesteld'   => 'text-orange-600',
                                'Afgerond'     => 'text-blue-600',
                            ];
                            $color = $colorMap[$statusName] ?? 'text-slate-500';
                        @endphp
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">{{ $statusName }}</span>
                            <span class="font-medium {{ $color }}">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Link naar actiepunten --}}
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('teacher.action-points.index') }}"
                       class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                        Alle actiepunten bekijken →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
