<x-layouts::app :title="__('Teams')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">Teams</flux:heading>

            <flux:button
                href="{{ route('admin.teams.create') }}"
                variant="primary"
                icon="plus"
                wire:navigate
            >
                Nieuw team
            </flux:button>
        </div>

        @if(session('success'))
            <flux:card class="bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                <p class="text-sm text-green-800 dark:text-green-300">
                    {{ session('success') }}
                </p>
            </flux:card>
        @endif

        <livewire:admin.team-search />
    </div>
</x-layouts::app>
