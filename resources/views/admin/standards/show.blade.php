<x-layouts::app :title="$standard->code . ' - ' . $standard->name">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-block h-3 w-3 rounded-full shrink-0" style="background-color: {{ $standard->theme->color }}"></span>
                <span class="text-sm text-zinc-500">{{ $standard->theme->name }}</span>
                <span class="text-zinc-300">/</span>
                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                    {{ $standard->code }}
                </span>
                <h1 class="text-2xl font-bold text-zinc-900">{{ $standard->name }}</h1>
            </div>
            <div class="flex items-center gap-3">
                @can('manage-standards')
                    <a href="{{ route('admin.standards.edit', $standard) }}" class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100" wire:navigate>
                        Standaard bewerken
                    </a>
                @endcan
                <a href="{{ route('admin.themes.show', $standard->theme) }}" class="text-sm text-zinc-600 hover:text-zinc-900" wire:navigate>
                    &larr; Terug naar {{ $standard->theme->name }}
                </a>
            </div>
        </div>

        @if($standard->description)
            <p class="text-sm text-zinc-600">{{ $standard->description }}</p>
        @endif

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Bestaande criteria --}}
        <div class="rounded-lg border border-zinc-200 bg-white overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-200 bg-zinc-50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-zinc-700">Criteria</h2>
                <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                    {{ $standard->criteria->count() }}
                </span>
            </div>

            @if($standard->criteria->isNotEmpty())
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 w-16">Nr.</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500">Tekst</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500">Toelichting</th>
                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach($standard->criteria as $criterion)
                            <tr class="hover:bg-zinc-50">
                                <td class="px-5 py-4 text-sm font-medium text-zinc-900 align-top">
                                    {{ $standard->code }}.{{ $criterion->number }}
                                </td>
                                <td class="px-5 py-4 text-sm text-zinc-700 align-top max-w-xs">
                                    {{ $criterion->text }}
                                </td>
                                <td class="px-5 py-4 text-sm text-zinc-500 align-top max-w-xs">
                                    {{ $criterion->explanation ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-right align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('manage-criteria')
                                            <a href="{{ route('admin.criteria.edit', $criterion) }}" class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100" wire:navigate>
                                                Bewerken
                                            </a>
                                            <form action="{{ route('admin.criteria.destroy', $criterion) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je criterium {{ $standard->code }}.{{ $criterion->number }} wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-5 py-8 text-center">
                    <p class="text-sm text-zinc-500">Nog geen criteria voor deze standaard.</p>
                </div>
            @endif
        </div>

        {{-- Inline criterium toevoegen --}}
        @can('manage-criteria')
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <h2 class="mb-4 text-sm font-semibold text-zinc-700">Criterium toevoegen</h2>

                <form action="{{ route('admin.criteria.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="standard_id" value="{{ $standard->id }}">

                    <div class="mb-4">
                        <label for="number" class="mb-1 block text-sm font-medium text-zinc-700">Nummer</label>
                        <input
                            type="number"
                            name="number"
                            id="number"
                            value="{{ old('number') }}"
                            placeholder="Bijv. 1"
                            min="1"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required
                        />
                        @error('number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="text" class="mb-1 block text-sm font-medium text-zinc-700">Tekst</label>
                        <textarea
                            name="text"
                            id="text"
                            rows="3"
                            placeholder="De tekst van het criterium..."
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            required
                        >{{ old('text') }}</textarea>
                        @error('text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="explanation" class="mb-1 block text-sm font-medium text-zinc-700">
                            Toelichting <span class="font-normal text-zinc-400">(optioneel)</span>
                        </label>
                        <textarea
                            name="explanation"
                            id="explanation"
                            rows="3"
                            placeholder="Optionele toelichting bij dit criterium..."
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Criterium toevoegen
                        </button>
                    </div>
                </form>
            </div>
        @endcan
    </div>
</x-layouts::app>
