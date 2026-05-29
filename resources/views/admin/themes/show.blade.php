<x-layouts::app :title="$theme->name">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-block h-4 w-4 rounded" style="background-color: {{ $theme->color }}"></span>
                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                    {{ $theme->code }}
                </span>
                <h1 class="text-2xl font-bold text-zinc-900">{{ $theme->name }}</h1>
            </div>
            <div class="flex items-center gap-3">
                @can('manage-themes')
                    <a href="{{ route('admin.themes.edit', $theme) }}" class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100" wire:navigate>
                        Thema bewerken
                    </a>
                @endcan
                <a href="{{ route('admin.themes.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900" wire:navigate>
                    &larr; Terug naar thema's
                </a>
            </div>
        </div>

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

        <div class="flex items-center justify-between">
            <p class="text-sm text-zinc-500">{{ $theme->standards->count() }} {{ $theme->standards->count() === 1 ? 'standaard' : 'standaarden' }}</p>
            @can('manage-standards')
                <a href="{{ route('admin.standards.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" wire:navigate>
                    Nieuwe standaard
                </a>
            @endcan
        </div>

        @forelse($theme->standards as $standard)
            <div class="rounded-lg border border-zinc-200 bg-white p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 shrink-0">
                            {{ $standard->code }}
                        </span>
                        <div class="min-w-0">
                            <a href="{{ route('admin.standards.show', $standard) }}" class="font-medium text-zinc-900 hover:text-blue-600 hover:underline truncate block" wire:navigate>{{ $standard->name }}</a>
                            @if($standard->description)
                                <p class="mt-0.5 text-sm text-zinc-500 truncate">{{ $standard->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                            {{ $standard->criteria->count() }} {{ $standard->criteria->count() === 1 ? 'criterium' : 'criteria' }}
                        </span>
                        <a href="{{ route('admin.standards.show', $standard) }}" class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 hover:bg-blue-100" wire:navigate>
                            Criteria beheren &rarr;
                        </a>
                        @can('manage-standards')
                            <a href="{{ route('admin.standards.edit', $standard) }}" class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 hover:bg-yellow-100" wire:navigate>
                                Bewerken
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-zinc-200 bg-white p-8 text-center">
                <p class="text-sm text-zinc-500">Nog geen standaarden voor dit thema.</p>
                @can('manage-standards')
                    <a href="{{ route('admin.standards.create') }}" class="mt-3 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" wire:navigate>
                        Eerste standaard aanmaken
                    </a>
                @endcan
            </div>
        @endforelse
    </div>
</x-layouts::app>
