<x-layouts::app :title="__('Database backup')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Database backup</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Download een volledige SQL-dump van de database, inclusief alle tabellen en data.
                </p>
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 rounded-full bg-blue-50 p-3 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125m16.5 2.625v5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-5.625" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">SQL-dump downloaden</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Het bestand bevat <code class="rounded bg-zinc-100 px-1 py-0.5 text-xs dark:bg-zinc-800">DROP TABLE IF EXISTS</code>,
                        <code class="rounded bg-zinc-100 px-1 py-0.5 text-xs dark:bg-zinc-800">CREATE TABLE</code> en
                        <code class="rounded bg-zinc-100 px-1 py-0.5 text-xs dark:bg-zinc-800">INSERT</code>-statements voor alle tabellen.
                        Bij grote databases kan het downloaden even duren.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.backup.download') }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Backup downloaden
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
            <div class="flex gap-3">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <p class="text-sm text-amber-800 dark:text-amber-300">
                    Bewaar de backup op een veilige locatie. Het bestand bevat alle gegevens van de applicatie, inclusief gebruikersgegevens.
                </p>
            </div>
        </div>
    </div>
</x-layouts::app>
