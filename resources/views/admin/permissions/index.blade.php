<x-layouts::app :title="__('Permissiebeheer')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">Permissiebeheer</flux:heading>
        </div>

        @if(session('success'))
            <flux:card class="bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                <p class="text-sm text-green-800 dark:text-green-300">
                    {{ session('success') }}
                </p>
            </flux:card>
        @endif

        <livewire:admin.permission-manager />
    </div>
</x-layouts::app>
