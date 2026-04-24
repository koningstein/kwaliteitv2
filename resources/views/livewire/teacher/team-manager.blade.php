<div class="space-y-8">

    {{-- Flash berichten --}}
    @if($successMessage)
        <div class="flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3 text-sm text-emerald-700 font-medium">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $successMessage }}
            </div>
            <button wire:click="clearMessages" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errorMessage)
        <div class="flex items-center justify-between gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3 text-sm text-red-700 font-medium">
            <span>{{ $errorMessage }}</span>
            <button wire:click="clearMessages" class="text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- Bevestigingsdialoog verwijderen --}}
    @if($confirmRemoveId)
        @php $removeUser = $this->members->firstWhere('id', $confirmRemoveId); @endphp
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900">Teamlid verwijderen</h3>
                @if($removeUser)
                    @if($removeUser->actionPoints->count() > 0)
                        <p class="text-sm text-slate-600">
                            <span class="font-semibold">{{ $removeUser->name }}</span> heeft
                            <span class="font-semibold text-amber-600">{{ $removeUser->actionPoints->count() }} actiepunt(en)</span>.
                            De gebruiker wordt <strong>gedeactiveerd</strong> — actiepunten blijven behouden en de gebruiker kan niet meer inloggen.
                        </p>
                    @else
                        <p class="text-sm text-slate-600">
                            Weet je zeker dat je <span class="font-semibold">{{ $removeUser->name }}</span> uit het team wilt verwijderen?
                            De gebruiker heeft geen actiepunten en wordt volledig ontkoppeld.
                        </p>
                    @endif
                @endif
                <div class="flex gap-3 pt-2">
                    <button wire:click="removeMember"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                        {{ $removeUser && $removeUser->actionPoints->count() > 0 ? 'Deactiveren' : 'Verwijderen' }}
                    </button>
                    <button wire:click="cancelRemove"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                        Annuleren
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Huidig overzicht met acties per lid --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
            <p class="text-sm font-semibold text-slate-700">Acties per teamlid</p>
        </div>
        @if($this->members->isEmpty())
            <div class="px-5 py-8 text-center text-sm text-slate-400">Nog geen teamleden gekoppeld.</div>
        @else
            <table class="w-full text-sm">
                <thead class="border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Naam</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden sm:table-cell">E-mail</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Actiepunten</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($this->members as $member)
                        @php $isInactive = $member->deleted_at !== null; @endphp
                        <tr class="{{ $isInactive ? 'bg-slate-50 opacity-60' : 'hover:bg-slate-50' }} transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full {{ $isInactive ? 'bg-slate-200 text-slate-500' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ $member->initials() }}
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $member->name }}</span>
                                    @if($isInactive)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-slate-200 text-slate-600">Inactief</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 hidden sm:table-cell">{{ $member->email }}</td>
                            <td class="px-5 py-3.5 text-center text-slate-700 font-medium">
                                {{ $member->actionPoints->count() }}
                            </td>
                            <td class="px-5 py-3.5">
                                @unless($isInactive)
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="resetPassword({{ $member->id }})"
                                                title="Wachtwoord-resetlink sturen"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmRemove({{ $member->id }})"
                                                title="Verwijderen uit team"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Bestaande gebruiker koppelen --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Bestaande gebruiker koppelen</h3>
                <p class="text-xs text-slate-500 mt-0.5">Zoek een bestaand account en voeg toe aan dit team</p>
            </div>
            <button wire:click="$toggle('showAttachSearch')"
                    class="text-sm font-medium px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                {{ $showAttachSearch ? 'Inklappen' : 'Uitklappen' }}
            </button>
        </div>

        @if($showAttachSearch)
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        wire:model.live.debounce.300ms="searchExisting"
                        type="text"
                        placeholder="Zoek op naam of e-mail..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                @if(strlen($searchExisting) >= 2)
                    @if($this->candidates->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">Geen gebruikers gevonden voor "{{ $searchExisting }}".</p>
                    @else
                        <div class="divide-y divide-slate-100 border border-slate-100 rounded-lg overflow-hidden">
                            @foreach($this->candidates as $candidate)
                                <div class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ $candidate->initials() }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $candidate->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $candidate->email }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="attachMember({{ $candidate->id }})"
                                            class="text-sm font-medium px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                        Koppelen
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="text-xs text-slate-400 text-center py-2">Typ minimaal 2 tekens om te zoeken.</p>
                @endif
            </div>
        @endif
    </div>

    {{-- Nieuw teamlid aanmaken --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Nieuw teamlid aanmaken</h3>
                <p class="text-xs text-slate-500 mt-0.5">Maak een nieuw account aan — rol wordt automatisch <span class="font-medium text-slate-600">medewerker</span></p>
            </div>
            <button wire:click="$toggle('showCreateForm')"
                    class="text-sm font-medium px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                {{ $showCreateForm ? 'Annuleren' : '+ Nieuw lid' }}
            </button>
        </div>

        @if($showCreateForm)
            <form wire:submit="createMember" class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Naam</label>
                        <input
                            wire:model="name"
                            type="text"
                            placeholder="Volledige naam"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                        />
                        @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">E-mailadres</label>
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="naam@school.nl"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                        />
                        @error('email') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Tijdelijk wachtwoord</label>
                        <input
                            wire:model="password"
                            type="text"
                            placeholder="Minimaal 8 tekens"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                        />
                        @error('password') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                        <span wire:loading.remove wire:target="createMember">Teamlid aanmaken</span>
                        <span wire:loading wire:target="createMember">Aanmaken...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>

</div>
