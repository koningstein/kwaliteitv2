<x-layouts::app :title="__('Locatie: ') . $location->name">
@php
    $rolLabels = [
        'ok_medewerker'  => ['label' => 'O&K',             'color' => 'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-900/30 dark:text-purple-300 dark:ring-purple-500/30'],
        'kwaliteitszorg' => ['label' => 'Kwaliteitszorg',  'color' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30'],
        'onderwijsleider'=> ['label' => 'Onderwijsleider', 'color' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/30 dark:text-green-300 dark:ring-green-500/30'],
        'medewerker'     => ['label' => 'Medewerker',      'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-500/30'],
        'directie'       => ['label' => 'Directie',        'color' => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-900/30 dark:text-orange-300 dark:ring-orange-500/30'],
    ];
@endphp
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $location->name }}
                    <span class="ml-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">
                        {{ $location->abbreviation }}
                    </span>
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.locations.edit', $location) }}"
                   class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 dark:ring-yellow-500/30"
                   wire:navigate>
                    Bewerken
                </a>
                <a href="{{ route('admin.locations.index') }}"
                   class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                   wire:navigate>
                    &larr; Terug naar overzicht
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Gekoppelde teams --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                    Gekoppelde teams
                    <span class="ml-2 inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                        {{ $location->teams->count() }}
                    </span>
                </h2>
            </div>

            @if($location->teams->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                    Nog geen teams gekoppeld aan deze locatie.
                </div>
            @else
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($location->teams as $team)
                        <div class="flex items-start justify-between px-6 py-4">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $team->name }}</p>

                                @if($team->leaders->isNotEmpty())
                                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                        <span class="font-medium">Leider(s):</span>
                                        {{ $team->leaders->pluck('name')->join(', ') }}
                                    </p>
                                @endif

                                @if($team->users->isNotEmpty())
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($team->users as $member)
                                            @php
                                                $memberRole = $member->getRoleNames()->first();
                                                $rolInfo = $rolLabels[$memberRole] ?? ['label' => $memberRole ?? '—', 'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20'];
                                            @endphp
                                            <span class="inline-flex items-center gap-1 rounded-md bg-zinc-100 px-2 py-0.5 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                                {{ $member->name }}
                                                <span class="inline-flex items-center rounded px-1 py-0.5 text-xs font-medium ring-1 ring-inset {{ $rolInfo['color'] }}">
                                                    {{ $rolInfo['label'] }}
                                                </span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">Geen leden</p>
                                @endif
                            </div>

                            <form action="{{ route('admin.locations.teams.detach', [$location, $team]) }}" method="POST"
                                  onsubmit="return confirm('Team &quot;{{ addslashes($team->name) }}&quot; ontkoppelen van deze locatie?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30">
                                    Ontkoppelen
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Team koppelen --}}
        @if($availableTeams->isNotEmpty())
            <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Team koppelen</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.locations.teams.attach', $location) }}" method="POST"
                          x-data="{ selectedTeam: '' }">
                        @csrf
                        <div class="flex items-center gap-3">
                            <select name="team_id"
                                    x-model="selectedTeam"
                                    class="flex-1 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                                    required>
                                <option value="">— Kies een team —</option>
                                @foreach($availableTeams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    :disabled="!selectedTeam"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                Koppelen
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                            Als het team al aan een andere locatie is gekoppeld, wordt het automatisch verplaatst naar deze locatie.
                        </p>
                    </form>
                </div>
            </div>
        @endif

        {{-- Direct gekoppelde gebruikers (zonder team) --}}
        <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                    Direct gekoppelde gebruikers
                    <span class="ml-1 text-xs font-normal text-zinc-400 dark:text-zinc-500">(niet via een team)</span>
                    <span class="ml-2 inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                        {{ $directUsers->count() }}
                    </span>
                </h2>
            </div>

            @if($directUsers->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                    Geen direct gekoppelde gebruikers.
                </div>
            @else
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($directUsers as $user)
                            @php
                                $userRole = $user->getRoleNames()->first();
                                $rolInfo = $rolLabels[$userRole] ?? ['label' => $userRole ?? '—', 'color' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20'];
                            @endphp
                            <div class="flex items-center justify-between px-6 py-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $rolInfo['color'] }}">
                                            {{ $rolInfo['label'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                                </div>
                            <form action="{{ route('admin.locations.users.detach', [$location, $user]) }}" method="POST"
                                  onsubmit="return confirm('Gebruiker &quot;{{ addslashes($user->name) }}&quot; ontkoppelen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30">
                                    Ontkoppelen
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Gebruiker direct koppelen --}}
        @if($availableUsers->isNotEmpty())
            <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Gebruiker direct koppelen</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.locations.users.attach', $location) }}" method="POST"
                          x-data="{ selectedUser: '' }">
                        @csrf
                        <div class="flex items-center gap-3">
                            <select name="user_id"
                                    x-model="selectedUser"
                                    class="flex-1 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                                    required>
                                <option value="">— Kies een gebruiker —</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    :disabled="!selectedUser"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                Koppelen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</x-layouts::app>
