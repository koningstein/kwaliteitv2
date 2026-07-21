<x-layouts::app :title="__('Gebruikers & Teams')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Gebruikers & Teams</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht per gebruiker bij welke teams ze horen en met welke rol.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
               wire:navigate>
                ← Terug naar gebruikers
            </a>
        </div>

        <livewire:admin.user-team-overview />
    </div>
</x-layouts::app>
