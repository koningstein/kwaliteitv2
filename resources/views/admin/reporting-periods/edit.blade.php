<x-layouts::app :title="__('Periode bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Periode bewerken: {{ $reportingPeriod->label }}</h1>
            <a href="{{ route('admin.reporting-periods.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.reporting-periods.update', $reportingPeriod) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="label" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Label</label>
                    <input
                        type="text"
                        name="label"
                        id="label"
                        value="{{ old('label', $reportingPeriod->label) }}"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('label')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Slug</label>
                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        value="{{ old('slug', $reportingPeriod->slug) }}"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sort_order" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Volgorde</label>
                    <input
                        type="number"
                        name="sort_order"
                        id="sort_order"
                        value="{{ old('sort_order', $reportingPeriod->sort_order) }}"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    />
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0" />
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $reportingPeriod->is_active) ? 'checked' : '' }}
                            class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600"
                        />
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">Actief</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Periode bijwerken
                    </button>
                    <a href="{{ route('admin.reporting-periods.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
