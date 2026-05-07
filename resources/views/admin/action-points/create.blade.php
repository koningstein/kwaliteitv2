<x-layouts::app :title="__('Actiepunt aanmaken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Nieuw actiepunt</h1>
            <a href="{{ route('admin.action-points.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                &larr; Terug naar overzicht
            </a>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900"
             x-data="{
                 selectedTeam: '{{ old('team_id') }}',
                 selectedUser: '{{ old('user_id') }}',
                 usersByTeam: {{ Js::from($usersByTeam) }},
                 get filteredUsers() {
                     if (!this.selectedTeam) return [];
                     return this.usersByTeam[this.selectedTeam] ?? [];
                 }
             }">
            <form action="{{ route('admin.action-points.store') }}" method="POST">
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
                                {{ $criterion->standard->code }}.{{ $criterion->number }} — {{ Str::limit($criterion->text, 60) }}
                            </option>
                        @endforeach
                    </select>
                    @error('criterion_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="team_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Team</label>
                    <select
                        name="team_id"
                        id="team_id"
                        x-model="selectedTeam"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een team</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="action_point_status_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</label>
                    <select
                        name="action_point_status_id"
                        id="action_point_status_id"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >
                        <option value="">Selecteer een status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('action_point_status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('action_point_status_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_id" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Verantwoordelijke</label>
                    <select
                        name="user_id"
                        id="user_id"
                        x-model="selectedUser"
                        :disabled="!selectedTeam"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                    >
                        <option value="">— Geen verantwoordelijke —</option>
                        <template x-for="user in filteredUsers" :key="user.id">
                            <option :value="user.id" :selected="user.id == selectedUser" x-text="user.name"></option>
                        </template>
                    </select>
                    <p x-show="!selectedTeam" class="mt-1 text-xs text-zinc-400">Selecteer eerst een team.</p>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Beschrijving</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Beschrijving van het actiepunt..."
                        class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Startdatum</label>
                        <input
                            type="date"
                            name="start_date"
                            id="start_date"
                            value="{{ old('start_date') }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                            required
                        />
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Einddatum</label>
                        <input
                            type="date"
                            name="end_date"
                            id="end_date"
                            value="{{ old('end_date') }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100"
                            required
                        />
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Actiepunt aanmaken
                    </button>
                    <a href="{{ route('admin.action-points.index') }}" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" wire:navigate>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
