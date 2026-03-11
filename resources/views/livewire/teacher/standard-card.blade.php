<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">

    {{-- ── Standaard-header ── --}}
    <button
        wire:click="toggleStandard"
        class="w-full flex items-center gap-4 px-5 py-4 text-left hover:bg-slate-50 transition-colors border-l-4"
        style="border-left-color: {{ $standard->theme->color ?? '#6366f1' }}"
    >
        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-200 {{ $isOpen ? 'rotate-90' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-xs font-bold text-slate-400 font-mono tracking-wide">{{ $standard->code }}</span>
                <h2 class="text-base font-semibold text-slate-900">{{ $standard->name }}</h2>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                    {{ $standard->criteria->count() }} {{ $standard->criteria->count() === 1 ? 'criterium' : 'criteria' }}
                </span>
            </div>
            @if($standard->description)
                <p class="text-sm text-slate-500 mt-0.5">{{ $standard->description }}</p>
            @endif
        </div>
    </button>

    {{-- ── Criteria-lijst ── --}}
    @if($isOpen)
        <div class="border-t border-slate-100 bg-slate-50 px-4 py-4 space-y-3">
            @forelse($standard->criteria as $criterion)
                @php
                    $critOpen   = $openCriteria[$criterion->id] ?? false;
                    $critScores = $scores[$criterion->id] ?? [];
                @endphp

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                    {{-- Criterium-header: div ipv button, want buttons mogen geen buttons bevatten --}}
                    <div class="flex items-start justify-between gap-4 px-5 py-4 hover:bg-slate-50 transition-colors cursor-pointer">
                        {{-- Klikbaar gedeelte: chevron + badge + tekst --}}
                        <div
                            wire:click="toggleCriterion({{ $criterion->id }})"
                            class="flex items-start gap-3 flex-1 min-w-0"
                        >
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-1 transition-transform duration-150 {{ $critOpen ? 'rotate-90' : '' }}"
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

                        {{-- Score-knoppen per periode — aparte div, niet inside button --}}
                        <div class="flex-shrink-0 flex items-center gap-6">
                            @foreach($periods as $period)
                                @php $current = $critScores[$period->id] ?? null; @endphp
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="text-xs font-semibold text-slate-500">{{ $period->label }}</span>
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="setScore({{ $criterion->id }}, {{ $period->id }}, 'sufficient')"
                                            title="Voldoende"
                                            class="w-8 h-8 rounded-full transition-all bg-emerald-500 ring-emerald-400 {{ $current === 'sufficient' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                                        ></button>
                                        <button
                                            wire:click="setScore({{ $criterion->id }}, {{ $period->id }}, 'attention')"
                                            title="Aandacht"
                                            class="w-8 h-8 rounded-full transition-all bg-amber-500 ring-amber-400 {{ $current === 'attention' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                                        ></button>
                                        <button
                                            wire:click="setScore({{ $criterion->id }}, {{ $period->id }}, 'insufficient')"
                                            title="Onvoldoende"
                                            class="w-8 h-8 rounded-full transition-all bg-rose-500 ring-rose-400 {{ $current === 'insufficient' ? 'ring-4 scale-110' : 'opacity-30 hover:opacity-70' }}"
                                        ></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Criterium uitgeklapt --}}
                    @if($critOpen)
                        <div class="border-t border-slate-100 px-5 py-4 space-y-5">

                            {{-- Indicatoren --}}
                            @if($criterion->indicators->isNotEmpty())
                                <div>
                                    <h5 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Indicatoren</h5>
                                    <ul class="space-y-1.5">
                                        @foreach($criterion->indicators as $indicator)
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
                                    @if(!($editingExplanation[$criterion->id] ?? false))
                                        <button
                                            wire:click="startEditExplanation({{ $criterion->id }})"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Bewerken
                                        </button>
                                    @endif
                                </div>
                                @if($editingExplanation[$criterion->id] ?? false)
                                    <div class="space-y-2">
                                        <textarea
                                            wire:model="explanations.{{ $criterion->id }}"
                                            rows="4"
                                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                                            placeholder="Voeg een toelichting toe..."
                                        ></textarea>
                                        <div class="flex gap-2">
                                            <button wire:click="saveExplanation({{ $criterion->id }})"
                                                class="px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition-colors">
                                                Opslaan
                                            </button>
                                            <button wire:click="cancelExplanation({{ $criterion->id }})"
                                                class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-200 transition-colors">
                                                Annuleren
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        {{ $explanations[$criterion->id] ?? 'Nog geen toelichting toegevoegd.' }}
                                    </p>
                                @endif
                            </div>

                            {{-- Actiepunten --}}
                            <div class="pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Actiepunten</h5>
                                    @if($showAddFormFor !== $criterion->id)
                                        <button
                                            wire:click="showAddForm({{ $criterion->id }})"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Actiepunt toevoegen
                                        </button>
                                    @endif
                                </div>

                                {{-- Nieuw actiepunt formulier --}}
                                @if($showAddFormFor === $criterion->id)
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 space-y-3">
                                        <h6 class="text-sm font-semibold text-slate-800">Nieuw actiepunt</h6>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Beschrijving <span class="text-red-500">*</span></label>
                                            <textarea wire:model="newDescription" rows="3"
                                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                                                placeholder="Beschrijf het actiepunt..."></textarea>
                                            @error('newDescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Verantwoordelijke <span class="text-red-500">*</span></label>
                                            <select wire:model="newUserId" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                <option value="">— Kies persoon —</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('newUserId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">Startdatum <span class="text-red-500">*</span></label>
                                                <input type="date" wire:model="newStartDate"
                                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                                @error('newStartDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">Einddatum <span class="text-red-500">*</span></label>
                                                <input type="date" wire:model="newEndDate"
                                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                                @error('newEndDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="flex gap-2 pt-1">
                                            <button wire:click="addActionPoint"
                                                class="px-4 py-2 bg-slate-800 text-white text-xs font-semibold rounded-lg hover:bg-slate-900 transition-colors">
                                                Opslaan
                                            </button>
                                            <button wire:click="cancelAddForm"
                                                class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-50 transition-colors">
                                                Annuleren
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                {{-- Actiepunten lijst --}}
                                @forelse($criterion->actionPoints as $ap)
                                    @php
                                        $badgeMap = [
                                            'Niet gestart' => 'bg-slate-100 text-slate-700 border border-slate-300',
                                            'Op schema'    => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                                            'Loopt achter' => 'bg-amber-100 text-amber-700 border border-amber-300',
                                            'Uitgesteld'   => 'bg-orange-100 text-orange-700 border border-orange-300',
                                            'Afgerond'     => 'bg-blue-100 text-blue-700 border border-blue-300',
                                        ];
                                        $badge = $badgeMap[$ap->status?->name] ?? $badgeMap['Niet gestart'];
                                    @endphp

                                    <div class="border border-slate-400 rounded-xl mb-3 bg-white overflow-hidden">
                                        @if($editingActionPointId === $ap->id)
                                            {{-- Edit modus --}}
                                            <div class="p-4 space-y-3 bg-amber-50">
                                                <h6 class="text-xs font-semibold text-slate-700 uppercase tracking-wide">Actiepunt bewerken</h6>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">Beschrijving</label>
                                                    <textarea wire:model="editDescription" rows="3"
                                                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
                                                    @error('editDescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Verantwoordelijke</label>
                                                        <select wire:model="editUserId" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400">
                                                            <option value="">— Kies persoon —</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('editUserId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Status</label>
                                                        <select wire:model="editStatusId" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400">
                                                            @foreach($statuses as $status)
                                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('editStatusId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Startdatum</label>
                                                        <input type="date" wire:model="editStartDate"
                                                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400" />
                                                        @error('editStartDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Einddatum</label>
                                                        <input type="date" wire:model="editEndDate"
                                                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400" />
                                                        @error('editEndDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                                        Evaluatie / toelichting
                                                        <span class="text-slate-400 font-normal">(optioneel)</span>
                                                    </label>
                                                    <textarea wire:model="editEvaluationText" rows="3"
                                                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"
                                                        placeholder="Beschrijf de voortgang of bevindingen..."></textarea>
                                                    @error('editEvaluationText') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="flex gap-2 pt-1">
                                                    <button wire:click="saveEditActionPoint"
                                                        class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-lg hover:bg-slate-900 transition-colors">
                                                        Opslaan
                                                    </button>
                                                    <button wire:click="cancelEditActionPoint"
                                                        class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-50 transition-colors">
                                                        Annuleren
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Weergave modus --}}
                                            <div class="p-4">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-slate-800 leading-snug mb-2">{{ $ap->description }}</p>
                                                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                            @if($ap->user)
                                                                <span class="flex items-center gap-1">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                    </svg>
                                                                    {{ $ap->user->name }}
                                                                </span>
                                                            @endif
                                                            @if($ap->start_date)
                                                                <span>
                                                                    {{ \Carbon\Carbon::parse($ap->start_date)->format('d-m-Y') }}
                                                                    → {{ $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('d-m-Y') : '—' }}
                                                                </span>
                                                            @endif
                                                            @if($ap->status)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                                                    {{ $ap->status->name }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1 flex-shrink-0">
                                                        <button wire:click="startEditActionPoint({{ $ap->id }})"
                                                            class="p-1.5 text-slate-600 hover:text-blue-700 rounded-lg hover:bg-blue-50 transition-colors" title="Bewerken">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                            </svg>
                                                        </button>
                                                        <button wire:click="deleteActionPoint({{ $ap->id }})"
                                                            wire:confirm="Weet je zeker dat je dit actiepunt wilt verwijderen?"
                                                            class="p-1.5 text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Verwijderen">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Evaluaties --}}
                                                <div class="mt-3 pt-3 border-t border-slate-100">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                                            Evaluaties ({{ $ap->evaluations->count() }})
                                                        </span>
                                                        @if($evaluatingId !== $ap->id)
                                                            <button wire:click="startEvaluation({{ $ap->id }})"
                                                                class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                </svg>
                                                                Evaluatie toevoegen
                                                            </button>
                                                        @endif
                                                    </div>

                                                    @if($evaluatingId === $ap->id)
                                                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-2 space-y-2">
                                                            <label class="block text-xs font-medium text-slate-700">Nieuwe evaluatie</label>
                                                            <textarea wire:model="newEvaluationText" rows="3"
                                                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"
                                                                placeholder="Beschrijf de voortgang of bevindingen..."></textarea>
                                                            @error('newEvaluationText') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                                            <div class="flex gap-2">
                                                                <button wire:click="saveEvaluation"
                                                                    class="px-3 py-1.5 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 transition-colors">
                                                                    Opslaan
                                                                </button>
                                                                <button wire:click="cancelEvaluation"
                                                                    class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-50 transition-colors">
                                                                    Annuleren
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @forelse($ap->evaluations->sortByDesc('created_at') as $eval)
                                                        <div class="bg-slate-100 rounded-lg px-3 py-2 mb-2">
                                                            <p class="text-xs text-slate-400 mb-1">{{ $eval->created_at?->format('d-m-Y H:i') }}</p>
                                                            <p class="text-sm text-slate-700 leading-snug">{{ $eval->description }}</p>
                                                        </div>
                                                    @empty
                                                        <p class="text-xs text-slate-400 italic">Nog geen evaluaties.</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400 italic py-1">Geen actiepunten voor dit criterium.</p>
                                @endforelse
                            </div>

                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-400 italic py-2">Geen criteria voor deze standaard.</p>
            @endforelse
        </div>
    @endif

</div>
