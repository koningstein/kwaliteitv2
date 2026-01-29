<div>
    <div class="mb-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Zoek teams op naam..."
            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 dark:placeholder-zinc-400"
        />
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Naam</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Leden</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Teamleiders</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Acties</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
            @forelse($teams as $team)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                        {{ $team->name }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $team->users_count }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $team->leaders_count }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 px-4">
                            {{-- Leden knop - Blauw --}}
                            <a href="{{ route('admin.teams.members', $team) }}"
                               class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30 dark:hover:bg-blue-900/50"
                               wire:navigate>
                                Leden
                            </a>

                            {{-- Bewerken knop - Geel --}}
                            <a href="{{ route('admin.teams.edit', $team) }}"
                               class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 dark:ring-yellow-500/30 dark:hover:bg-yellow-900/50"
                               wire:navigate>
                                Bewerken
                            </a>

                            {{-- Verwijderen knop - Rood --}}
                            <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit team wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30 dark:hover:bg-red-900/50">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        Geen teams gevonden.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($teams->hasPages())
        <div class="mt-4">
            {{ $teams->links() }}
        </div>
    @endif
</div>
