<div class="flex flex-col gap-4">
@php
    $rolLabels = [
        'admin'           => ['label' => 'Beheerder',        'color' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30'],
        'ok_medewerker'   => ['label' => 'O&K',             'color' => 'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-900/30 dark:text-purple-300 dark:ring-purple-500/30'],
        'kwaliteitszorg'  => ['label' => 'Kwaliteitszorg',  'color' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30'],
        'onderwijsleider' => ['label' => 'Onderwijsleider', 'color' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/30 dark:text-green-300 dark:ring-green-500/30'],
        'medewerker'      => ['label' => 'Medewerker',      'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-500/30'],
        'directie'        => ['label' => 'Directie',        'color' => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-900/30 dark:text-orange-300 dark:ring-orange-500/30'],
    ];
@endphp
    {{-- Succesmelding binnen de Livewire component --}}
    @if (session()->has('message'))
        <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200 border border-green-200 dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        {{-- Linker kolom: Huidige leden --}}
        <div class="space-y-4">
            {{-- Docenten --}}
            <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Docenten</h2>
                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">{{ $members->count() }}</span>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($members as $member)
                            @php
                                $rolKey  = $member->roles->first()?->name;
                                $rolInfo = $rolLabels[$rolKey] ?? ['label' => $rolKey ?? '—', 'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20'];
                            @endphp
                            <li class="flex items-center justify-between rounded-lg border border-zinc-100 px-3 py-2 dark:border-zinc-700">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $member->name }}</p>
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset {{ $rolInfo['color'] }}">{{ $rolInfo['label'] }}</span>
                                    @foreach($member->locations as $loc)
                                        <span class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">{{ $loc->abbreviation }}</span>
                                    @endforeach
                                </div>
                                <button wire:click="removeMember({{ $member->id }})" class="w-32 inline-flex items-center justify-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30">
                                    Verwijderen
                                </button>
                            </li>
                        @empty
                            <p class="text-center text-sm text-zinc-500">Geen docenten.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Teamleiders --}}
            <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm">
                <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Teamleiders</h2>
                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-700 dark:text-zinc-300">{{ $leaders->count() }}</span>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($leaders as $leader)
                            @php
                                $rolKey  = $leader->roles->first()?->name;
                                $rolInfo = $rolLabels[$rolKey] ?? ['label' => $rolKey ?? '—', 'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20'];
                            @endphp
                            <li class="flex items-center justify-between rounded-lg border border-zinc-100 px-3 py-2 dark:border-zinc-700">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $leader->name }}</p>
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset {{ $rolInfo['color'] }}">{{ $rolInfo['label'] }}</span>
                                    @foreach($leader->locations as $loc)
                                        <span class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">{{ $loc->abbreviation }}</span>
                                    @endforeach
                                </div>
                                <button wire:click="removeLeader({{ $leader->id }})" class="w-32 inline-flex items-center justify-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30">
                                    Verwijderen
                                </button>
                            </li>
                        @empty
                            <p class="text-center text-sm text-zinc-500">Geen teamleiders.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Rechter kolom: Zoeken --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm">
            <div class="border-b border-zinc-200 px-5 py-3 dark:border-zinc-700">
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Personen zoeken</h2>
            </div>
            <div class="p-4">
                <div class="mb-4 flex gap-3">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Naam..." class="flex-1 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100" />
                    <select wire:model.live="locationFilter" class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                        <option value="">Alle locaties</option>
                        @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->abbreviation }}</option> @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    @forelse($searchResults as $user)
                        @php
                            $rolKey  = $user->roles->first()?->name;
                            $rolInfo = $rolLabels[$rolKey] ?? ['label' => $rolKey ?? '—', 'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20'];
                        @endphp
                        <div class="flex items-center justify-between rounded-lg border border-zinc-100 px-3 py-2 dark:border-zinc-700">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset {{ $rolInfo['color'] }}">
                                    {{ $rolInfo['label'] }}
                                </span>
                                @foreach($user->locations as $loc)
                                    <span class="inline-flex items-center rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">{{ $loc->abbreviation }}</span>
                                @endforeach
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="addMember({{ $user->id }})" class="w-32 inline-flex items-center justify-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">
                                    + Docent
                                </button>
                                <button wire:click="addLeader({{ $user->id }})" class="w-32 inline-flex items-center justify-center rounded-md bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 dark:ring-yellow-500/30">
                                    + Teamleider
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">Geen personen gevonden.</p>
                    @endforelse
                </div>

                @if($searchResults->hasPages())
                    <div class="mt-4">
                        {{ $searchResults->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
