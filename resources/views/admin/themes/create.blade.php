<x-layouts::app :title="__('Thema aanmaken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Nieuw thema</h1>
            <a href="{{ route('admin.themes.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.themes.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="code" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Code</label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        value="{{ old('code') }}"
                        placeholder="Bijv. BPV"
                        maxlength="10"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 uppercase focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('code')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Naam</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Bijv. Beroepspraktijkvorming"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="color" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Kleur</label>
                    <div class="flex items-center gap-3">
                        <input
                            type="color"
                            name="color"
                            id="color"
                            value="{{ old('color', '#2563eb') }}"
                            class="h-10 w-14 cursor-pointer rounded border border-zinc-300 bg-white p-1 dark:border-zinc-600 dark:bg-zinc-700"
                        />
                        <span id="color-value" class="text-sm text-zinc-500 dark:text-zinc-400">{{ old('color', '#2563eb') }}</span>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_deletable" value="0" />
                        <input
                            type="checkbox"
                            name="is_deletable"
                            value="1"
                            {{ old('is_deletable', true) ? 'checked' : '' }}
                            class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600"
                        />
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Verwijderbaar</span>
                    </label>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Schakel dit uit als dit thema niet verwijderd mag worden.</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Thema aanmaken
                    </button>
                    <a href="{{ route('admin.themes.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('color').addEventListener('input', function() {
            document.getElementById('color-value').textContent = this.value;
        });
    </script>
</x-layouts::app>
