<x-layouts::app :title="__('Team bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Team bewerken: {{ $team->name }}</h1>
            <a href="{{ route('admin.teams.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.teams.update', $team) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Naam</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $team->name) }}"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Locaties</label>
                    @if($locations->isNotEmpty())
                        <div class="space-y-1 rounded-lg border border-zinc-300 p-3 dark:border-zinc-600">
                            @foreach($locations as $location)
                                <label class="flex items-center gap-2 rounded px-2 py-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                    <input
                                        type="checkbox"
                                        name="locations[]"
                                        value="{{ $location->id }}"
                                        {{ in_array($location->id, old('locations', $team->locations->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600"
                                    />
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">
                                        {{ $location->abbreviation }}
                                    </span>
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $location->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Geen locaties beschikbaar.
                            <a href="{{ route('admin.locations.create') }}" class="text-blue-600 hover:underline dark:text-blue-400" wire:navigate>Maak eerst een locatie aan.</a>
                        </p>
                    @endif
                    @error('locations')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Team bijwerken
                    </button>
                    <a href="{{ route('admin.teams.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
