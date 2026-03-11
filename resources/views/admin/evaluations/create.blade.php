<x-layouts::app :title="__('Evaluatie aanmaken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Nieuwe evaluatie</h1>
            <a href="{{ route('admin.evaluations.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.evaluations.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="action_point_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Actiepunt</label>
                    <select
                        name="action_point_id"
                        id="action_point_id"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een actiepunt</option>
                        @foreach($actionPoints as $actionPoint)
                            <option value="{{ $actionPoint->id }}" {{ old('action_point_id') == $actionPoint->id ? 'selected' : '' }}>
                                {{ $actionPoint->criterion->standard->code }}.{{ $actionPoint->criterion->number }} — {{ $actionPoint->team->name }} — {{ Str::limit($actionPoint->description, 50) }}
                            </option>
                        @endforeach
                    </select>
                    @error('action_point_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Beschrijving</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        placeholder="Beschrijving van de evaluatie..."
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Evaluatie aanmaken
                    </button>
                    <a href="{{ route('admin.evaluations.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
