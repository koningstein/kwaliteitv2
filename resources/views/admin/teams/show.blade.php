<x-layouts::app :title="$team->name">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $team->name }}</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.teams.edit', $team) }}" class="inline-flex items-center rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600" wire:navigate>
                    Bewerken
                </a>
                <a href="{{ route('admin.teams.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                    &larr; Terug naar overzicht
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            {{-- Teamleden --}}
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Teamleden</h2>
                @if($team->users->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($team->users as $user)
                            <li class="flex items-center gap-3 rounded-lg border border-zinc-100 px-3 py-2 dark:border-zinc-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $user->initials() }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Geen teamleden toegevoegd.</p>
                @endif
            </div>

            {{-- Teamleiders --}}
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Teamleiders</h2>
                @if($team->leaders->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach($team->leaders as $leader)
                            <li class="flex items-center gap-3 rounded-lg border border-zinc-100 px-3 py-2 dark:border-zinc-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 text-xs font-medium text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                    {{ $leader->initials() }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $leader->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $leader->email }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Geen teamleiders toegevoegd.</p>
                @endif
            </div>
        </div>

        {{-- Actiepunten --}}
        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Actiepunten</h2>
            @if($team->actionPoints->isNotEmpty())
                <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Beschrijving</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Startdatum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Einddatum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                            @foreach($team->actionPoints as $actionPoint)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ Str::limit($actionPoint->description, 80) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $actionPoint->start_date }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $actionPoint->end_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Geen actiepunten voor dit team.</p>
            @endif
        </div>
    </div>
</x-layouts::app>
