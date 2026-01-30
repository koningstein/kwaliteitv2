<x-layouts::app :title="__('Criterium aanmaken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Nieuw criterium</h1>
            <a href="{{ route('admin.criteria.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.criteria.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="standard_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Standaard</label>
                    <select
                        name="standard_id"
                        id="standard_id"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een standaard</option>
                        @foreach($standards as $standard)
                            <option value="{{ $standard->id }}" {{ old('standard_id') == $standard->id ? 'selected' : '' }}>
                                {{ $standard->code }} - {{ $standard->name }} ({{ $standard->theme->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('standard_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="number" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nummer</label>
                    <input
                        type="number"
                        name="number"
                        id="number"
                        value="{{ old('number') }}"
                        placeholder="Bijv. 1"
                        min="1"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="text" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tekst</label>
                    <textarea
                        name="text"
                        id="text"
                        rows="3"
                        placeholder="De tekst van het criterium..."
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >{{ old('text') }}</textarea>
                    @error('text')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="explanation" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Toelichting</label>
                    <textarea
                        name="explanation"
                        id="explanation"
                        rows="4"
                        placeholder="Optionele toelichting bij dit criterium..."
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                    >{{ old('explanation') }}</textarea>
                    @error('explanation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Criterium aanmaken
                    </button>
                    <a href="{{ route('admin.criteria.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
