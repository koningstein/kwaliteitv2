<x-layouts::app :title="__('Locaties')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Locaties</h1>
            <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" wire:navigate>
                Nieuwe locatie
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <livewire:admin.location-search />
    </div>
</x-layouts::app>
