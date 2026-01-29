<x-layouts::app :title="__('Leden beheren') . ' - ' . $team->name">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Leden beheren</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Team: {{ $team->name }}
                    @if($team->locations->isNotEmpty())
                        &mdash;
                    @foreach($team->locations as $location)
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">{{ $location->abbreviation }}</span>
                    @endforeach
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.teams.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        {{-- Hier de melding toevoegen --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200 border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        <livewire:admin.team-member-manager :team="$team" />
    </div>
</x-layouts::app>
