<div>
    {{-- ── Header: titel + knop nieuw actiepunt ── --}}
    <div class="flex items-center justify-between mb-3">
        <h5 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Actiepunten</h5>
        @if(!$showAddForm)
            <button
                wire:click="$set('showAddForm', true)"
                class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-slate-900 px-2 py-1 rounded hover:bg-slate-100 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Actiepunt toevoegen
            </button>
        @endif
    </div>

    {{-- ── Formulier: nieuw actiepunt ── --}}
    @if($showAddForm)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 space-y-3">
            <h6 class="text-sm font-semibold text-slate-800">Nieuw actiepunt</h6>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Beschrijving <span class="text-red-500">*</span></label>
                <textarea
                    wire:model="newDescription"
                    rows="3"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                    placeholder="Beschrijf het actiepunt..."
                ></textarea>
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
                <button
                    wire:click="addActionPoint"
                    class="px-4 py-2 bg-slate-800 text-white text-xs font-semibold rounded-lg hover:bg-slate-900 transition-colors"
                >
                    Opslaan
                </button>
                <button
                    wire:click="$set('showAddForm', false)"
                    class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-50 transition-colors"
                >
                    Annuleren
                </button>
            </div>
        </div>
    @endif

    {{-- ── Lijst van actiepunten ── --}}
    @forelse($criterion->actionPoints as $ap)
        @php
            $badgeMap = [
                'Niet gestart'  => 'bg-slate-100 text-slate-700 border border-slate-300',
                'Op schema'     => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                'Loopt achter'  => 'bg-amber-100 text-amber-700 border border-amber-300',
                'Uitgesteld'    => 'bg-orange-100 text-orange-700 border border-orange-300',
                'Afgerond'      => 'bg-blue-100 text-blue-700 border border-blue-300',
            ];
            $badge = $badgeMap[$ap->status?->name] ?? $badgeMap['Niet gestart'];
        @endphp

        <div class="border border-slate-200 rounded-xl mb-3 bg-white overflow-hidden">

            @if($editingId === $ap->id)
                {{-- ── Edit-modus ── --}}
                <div class="p-4 space-y-3 bg-amber-50 border-b border-amber-200">
                    <h6 class="text-xs font-semibold text-slate-700 uppercase tracking-wide">Actiepunt bewerken</h6>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Beschrijving</label>
                        <textarea wire:model="editDescription" rows="3"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"
                        ></textarea>
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

                    {{-- Evaluatie / toelichting (optioneel) --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Evaluatie / toelichting
                            <span class="text-slate-400 font-normal">(optioneel — wordt als evaluatie opgeslagen)</span>
                        </label>
                        <textarea wire:model="editEvaluationText" rows="3"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"
                            placeholder="Beschrijf de voortgang of bevindingen bij deze wijziging..."
                        ></textarea>
                        @error('editEvaluationText') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button wire:click="saveEdit"
                            class="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-lg hover:bg-slate-900 transition-colors">
                            Opslaan
                        </button>
                        <button wire:click="cancelEdit"
                            class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-lg hover:bg-slate-50 transition-colors">
                            Annuleren
                        </button>
                    </div>
                </div>

            @else
                {{-- ── Weergave-modus ── --}}
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
                                        →
                                        {{ $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('d-m-Y') : '—' }}
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
                            <button wire:click="startEdit({{ $ap->id }})"
                                class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors" title="Bewerken">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button wire:click="deleteActionPoint({{ $ap->id }})"
                                wire:confirm="Weet je zeker dat je dit actiepunt wilt verwijderen?"
                                class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Verwijderen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Evaluaties --}}
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <button
                                wire:click="toggleEvaluations({{ $ap->id }})"
                                class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition-colors"
                            >
                                <svg class="w-3.5 h-3.5 transition-transform {{ in_array($ap->id, $expandedEvaluations) ? 'rotate-180' : '' }}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                {{ $ap->evaluations->count() }} evaluatie(s)
                            </button>
                            @if($evaluatingId !== $ap->id)
                                <button
                                    wire:click="startEvaluation({{ $ap->id }})"
                                    class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-900 px-2 py-1 rounded hover:bg-slate-100 transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Evaluatie toevoegen
                                </button>
                            @endif
                        </div>

                        {{-- Evaluatie-formulier --}}
                        @if($evaluatingId === $ap->id)
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-2 space-y-2">
                                <label class="block text-xs font-medium text-slate-700">Nieuwe evaluatie</label>
                                <textarea
                                    wire:model="newEvaluationText"
                                    rows="3"
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"
                                    placeholder="Beschrijf de voortgang of bevindingen..."
                                ></textarea>
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

                        {{-- Evaluatie-lijst --}}
                        @if(in_array($ap->id, $expandedEvaluations))
                            @forelse($ap->evaluations->sortByDesc('created_at') as $eval)
                                <div class="bg-slate-50 rounded-lg px-3 py-2 mb-2">
                                    <p class="text-xs text-slate-400 mb-1">{{ $eval->created_at?->format('d-m-Y H:i') }}</p>
                                    <p class="text-sm text-slate-700 leading-snug">{{ $eval->description }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Nog geen evaluaties.</p>
                            @endforelse
                        @endif
                    </div>
                </div>
            @endif

        </div>
    @empty
        <p class="text-sm text-slate-400 italic py-1">Geen actiepunten voor dit criterium.</p>
    @endforelse
</div>
