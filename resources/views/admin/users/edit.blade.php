<x-layouts::app :title="__('Gebruiker bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Gebruiker bewerken: {{ $user->name }}</h1>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
               wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Naam</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                           required autofocus />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">E-mailadres</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                           required />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Rol</label>
                    <select name="role" id="role"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                            required>
                        <option value="">— Kies een rol —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                @selected(old('role', $user->roles->first()?->name) === $role->name)>
                                {{ match($role->name) {
                                    'admin'           => 'Beheerder',
                                    'ok_medewerker'   => 'O&K medewerker',
                                    'kwaliteitszorg'  => 'Kwaliteitszorg',
                                    'onderwijsleider' => 'Onderwijsleider',
                                    'medewerker'      => 'Medewerker',
                                    'directie'        => 'Directie',
                                    default           => $role->name,
                                } }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Nieuw wachtwoord
                        <span class="ml-1 font-normal text-zinc-400 dark:text-zinc-500">(leeg laten om niet te wijzigen)</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Wachtwoord bevestigen</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100" />
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Gebruiker bijwerken
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                       wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
