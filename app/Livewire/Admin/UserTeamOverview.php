<?php

namespace App\Livewire\Admin;

use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTeamOverview extends Component
{
    use WithPagination;

    public string $search = '';
    public string $teamFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTeamFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when(strlen($this->search) >= 2, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->teamFilter !== '', fn($q) =>
                $q->where(fn($q2) =>
                    $q2->whereHas('teams', fn($q3) => $q3->where('teams.id', $this->teamFilter))
                       ->orWhereHas('managedTeams', fn($q3) => $q3->where('teams.id', $this->teamFilter))
                )
            )
            ->with(['teams', 'managedTeams', 'roles'])
            ->orderBy('name')
            ->paginate(20);

        $teams = Team::orderBy('name')->get();

        return view('livewire.admin.user-team-overview', compact('users', 'teams'));
    }
}
