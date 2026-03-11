<x-layouts::app :title="__('Criteriumscore aanmaken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Nieuwe criteriumscore</h1>
            <a href="{{ route('admin.criterion-scores.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.criterion-scores.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="criterion_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Criterium</label>
                    <select
                        name="criterion_id"
                        id="criterion_id"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een criterium</option>
                        @foreach($criteria as $criterion)
                            <option value="{{ $criterion->id }}" {{ old('criterion_id') == $criterion->id ? 'selected' : '' }}>
                                {{ $criterion->standard->code }}.{{ $criterion->number }} — {{ Str::limit($criterion->text, 60) }} ({{ $criterion->standard->theme->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('criterion_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="reporting_period_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Rapportageperiode</label>
                    <select
                        name="reporting_period_id"
                        id="reporting_period_id"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een rapportageperiode</option>
                        @foreach($reportingPeriods as $period)
                            <option value="{{ $period->id }}" {{ old('reporting_period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('reporting_period_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Score</label>
                    <select
                        name="status"
                        id="status"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een score</option>
                        <option value="sufficient"   {{ old('status') === 'sufficient'   ? 'selected' : '' }}>Voldoende</option>
                        <option value="attention"    {{ old('status') === 'attention'    ? 'selected' : '' }}>Aandacht</option>
                        <option value="insufficient" {{ old('status') === 'insufficient' ? 'selected' : '' }}>Onvoldoende</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Score aanmaken
                    </button>
                    <a href="{{ route('admin.criterion-scores.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
