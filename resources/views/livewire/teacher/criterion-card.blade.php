<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- ── Criterion header (altijd zichtbaar) ── --}}
    <button
        wire:click="toggle"
        class="w-full flex items-start justify-between gap-4 px-5 py-4 text-left hover:bg-slate-50 transition-colors"
    >
        {{-- Links: chevron + badge + tekst --}}
        <div class="flex items-start gap-3 flex-1 min-w-0">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-1 transition-transform duration-150 {{ $isOpen ? 'rotate-90' : '' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <div class="flex-1 min-w-0">
                <div class="mb-1">
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                        Criterium {{ $criterion->number }}
                    </span>
                </div>
                <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $criterion->text }}</p>
            </div>
        </div>

        {{-- Rechts: score-knoppen per periode --}}
        <div class="flex-shrink-0 flex items-center gap-4" wire:click.stop>
            @foreach($periods as $period)
                @php $current = $scores[$period->id] ?? null; @endphp
                <div class="flex flex-col items-center gap-1.5">
                    <span class="text-xs font-semibold text-slate-500">{{ $period->label }}</span>
                    <div class="flex gap-1.5">
                        <button
                            wire:click="setScore({{ $period->id }}, 'sufficient')"
                            title="Voldoende"
                            class="w-8 h-8 rounded-full transition-all bg-emerald-500 ring-emerald-400
                                {{ $current === 'sufficient' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                        ></button>
                        <button
                            wire:click="setScore({{ $period->id }}, 'attention')"
                            title="Aandacht"
                            class="w-8 h-8 rounded-full transition-all bg-amber-500 ring-amber-400
                                {{ $current === 'attention' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                        ></button>
                        <button
                            wire:click="setScore({{ $period->id }}, 'insufficient')"
                            title="Onvoldoende"
                            class="w-8 h-8 rounded-full transition-all bg-rose-500 ring-rose-400
                                {{ $current === 'insufficient' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                        ></button>
                    </div>
                </div>
            @endforeach
        </div>
    </button>

    {{-- ── Uitgeklapte inhoud — alleen als $isOpen == true ── --}}
    @if($isOpen)
        <div class="border-t border-slate-100">
            <div class="px-5 py-4 space-y-5">

                {{-- Indicatoren --}}
                @if($criterion->indicators->isNotEmpty())
                    <div>
                        <h5 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Indicatoren</h5>
                        <ul class="space-y-1.5">
                            @foreach($criterion->indicators->sortBy('sort_order') as $indicator)
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="mt-1.5 flex-shrink-0 w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    {{ $indicator->text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Toelichting --}}
                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Toelichting</h5>
                        @if(!$editingExplanation)
                            <button
                                wire:click="$set('editingExplanation', true)"
                                class="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 px-2 py-1 rounded hover:bg-blue-50 transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Bewerken
                            </button>
                        @endif
                    </div>

                    @if($editingExplanation)
                        <div class="space-y-2">
                            <textarea
                                wire:model="explanation"
                                rows="4"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none"
                                placeholder="Voeg een toelichting toe..."
                            ></textarea>
                            <div class="flex gap-2">
                                <button
                                    wire:click="saveExplanation"
                                    class="px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition-colors flex items-center gap-1"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Opslaan
                                </button>
                                <button
                                    wire:click="cancelExplanation"
                                    class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-200 transition-colors"
                                >
                                    Annuleren
                                </button>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $explanation ?: 'Nog geen toelichting toegevoegd.' }}
                        </p>
                    @endif
                </div>

                {{-- Actiepunten --}}
                <div class="pt-4 border-t border-slate-100">
                    <livewire:teacher.action-point-manager :criterion="$criterion" :key="'apm-'.$criterion->id" />
                </div>

            </div>
        </div>
    @endif
</div>
