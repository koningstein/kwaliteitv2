<div class="flex flex-col gap-6">

    {{-- Nieuwe permissie aanmaken --}}
    <flux:card class="p-4">
        <flux:heading size="lg" class="mb-4">Nieuwe permissie aanmaken</flux:heading>

        @if($errors->has('name'))
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
                {{ $errors->first('name') }}
            </div>
        @endif

        <form action="{{ route('admin.permissions.store') }}" method="POST" class="flex items-start gap-3">
            @csrf
            <div class="flex-1">
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="bijv. view-rapportages"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 dark:placeholder-zinc-400"
                />
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">
                    Alleen kleine letters, cijfers en koppeltekens (bijv. <code>edit-action-points</code>)
                </p>
            </div>
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                Aanmaken
            </button>
        </form>
    </flux:card>

    {{-- Permissie × Rol matrix --}}
    <flux:card class="p-0">
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 min-w-[220px]">
                            Permissie
                        </th>
                        @foreach($roles as $role)
                            <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                @php
                                    $rolLabel = match($role->name) {
                                        'admin'          => 'Beheerder',
                                        'ok_medewerker'  => 'O&K',
                                        'kwaliteitszorg' => 'Kwaliteit',
                                        'onderwijsleider'=> 'Onderwijs­leider',
                                        'directie'       => 'Directie',
                                        'medewerker'     => 'Medewerker',
                                        default          => $role->name,
                                    };
                                @endphp
                                {{ $rolLabel }}
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            Acties
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" x-data="{ confirming: false }">

                            {{-- Permissienaam --}}
                            <td class="px-6 py-4 text-sm font-mono font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $permission->name }}
                            </td>

                            {{-- Checkbox per rol --}}
                            @foreach($roles as $role)
                                <td class="px-4 py-4 text-center">
                                    <input
                                        type="checkbox"
                                        wire:click="togglePermission({{ $permission->id }}, {{ $role->id }})"
                                        @checked(in_array($role->id, $permission->roleIds))
                                        class="h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 cursor-pointer"
                                    />
                                </td>
                            @endforeach

                            {{-- Acties --}}
                            <td class="px-6 py-4 text-right">
                                <div x-show="!confirming" x-cloak>
                                    <button
                                        type="button"
                                        @click="confirming = true"
                                        class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30 dark:hover:bg-red-900/50"
                                    >
                                        Verwijder
                                    </button>
                                </div>

                                <div x-show="confirming" x-cloak class="text-left">
                                    <div class="mb-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800 dark:border-amber-700 dark:bg-amber-900/20 dark:text-amber-300">
                                        <p class="font-semibold mb-1">⚠️ Let op!</p>
                                        @if(count($permission->roleNames) > 0)
                                            <p>
                                                <strong>{{ $permission->name }}</strong> is gekoppeld aan:
                                                <strong>{{ implode(', ', $permission->roleNames) }}</strong>.
                                            </p>
                                        @else
                                            <p><strong>{{ $permission->name }}</strong> is aan geen enkele rol gekoppeld.</p>
                                        @endif
                                        <p class="mt-1">
                                            Na verwijdering wordt toegang op alle plekken waar deze permissie gecontroleerd wordt stilzwijgend geweigerd.
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                                Ja, verwijder definitief
                                            </button>
                                        </form>
                                        <button
                                            type="button"
                                            @click="confirming = false"
                                            class="inline-flex items-center rounded-md bg-zinc-100 px-3 py-1.5 text-xs font-medium text-zinc-700 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:ring-zinc-600 dark:hover:bg-zinc-600"
                                        >
                                            Annuleer
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $roles->count() + 2 }}" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                Geen permissies gevonden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
