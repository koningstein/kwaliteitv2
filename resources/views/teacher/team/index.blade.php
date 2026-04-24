<x-teacher-layout>
    <x-slot name="title">Team — Kwaliteit in Beeld</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Team</h1>
            <p class="mt-1 text-sm text-slate-500">Overzicht van teamleden en hun voortgang</p>
        </div>

        @can('manage-team-users')
            {{-- Tabs navigatie --}}
            <div x-data="{ tab: 'voortgang' }">
                <div class="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                    <button
                        @click="tab = 'voortgang'"
                        :class="tab === 'voortgang'
                            ? 'bg-white text-slate-900 shadow-sm'
                            : 'text-slate-500 hover:text-slate-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                        Voortgang
                    </button>
                    <button
                        @click="tab = 'beheer'"
                        :class="tab === 'beheer'
                            ? 'bg-white text-slate-900 shadow-sm'
                            : 'text-slate-500 hover:text-slate-700'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
                        Teambeheer
                    </button>
                </div>

                {{-- Tab: Voortgang --}}
                <div x-show="tab === 'voortgang'" class="mt-6 space-y-4">
                    @include('teacher.team.partials.voortgang', ['users' => $users])
                </div>

                {{-- Tab: Teambeheer --}}
                <div x-show="tab === 'beheer'" class="mt-6">
                    <livewire:teacher.team-manager />
                </div>
            </div>

        @else
            {{-- Geen tabs voor andere rollen: alleen voortgang --}}
            <div class="space-y-4">
                @include('teacher.team.partials.voortgang', ['users' => $users])
            </div>
        @endcan

    </div>
</x-teacher-layout>
