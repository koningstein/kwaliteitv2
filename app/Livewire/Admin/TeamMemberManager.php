<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;

class TeamMemberManager extends Component
{
    public Team $team;
    public string $search = '';
    public string $locationFilter = '';

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function addMember(int $userId): void
    {
        $user = User::findOrFail($userId);
        if (!$this->team->users()->where('user_id', $userId)->exists()) {
            $this->team->users()->attach($userId);
            session()->flash('message', "{$user->name} is toegevoegd als docent.");
        }
        $this->team->load(['users.locations', 'leaders.locations']);
    }

    public function removeMember(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->team->users()->detach($userId);
        session()->flash('message', "{$user->name} is verwijderd uit het team.");
        $this->team->load(['users.locations', 'leaders.locations']);
    }

    public function addLeader(int $userId): void
    {
        $user = User::findOrFail($userId);
        if (!$this->team->leaders()->where('user_id', $userId)->exists()) {
            $this->team->leaders()->attach($userId);
            session()->flash('message', "{$user->name} is toegevoegd als teamleider.");
        }
        $this->team->load(['users.locations', 'leaders.locations']);
    }

    public function removeLeader(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->team->leaders()->detach($userId);
        session()->flash('message', "{$user->name} is verwijderd als teamleider.");
        $this->team->load(['users.locations', 'leaders.locations']);
    }

    public function render()
    {
        $existingUserIds = $this->team->users->pluck('id')
            ->merge($this->team->leaders->pluck('id'))
            ->unique();

        $searchResults = collect();
        if (strlen($this->search) >= 2 || $this->locationFilter !== '') {
            $searchResults = User::query()
                ->whereNotIn('id', $existingUserIds) // Verberg reeds gekoppelde personen
                ->when(strlen($this->search) >= 2, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->when($this->locationFilter !== '', function ($query) {
                    $query->whereHas('locations', function ($q) {
                        $q->where('locations.id', $this->locationFilter);
                    });
                })
                ->with('locations')
                ->orderBy('name')
                ->limit(20)
                ->get();
        }

        return view('livewire.admin.team-member-manager', [
            'searchResults' => $searchResults,
            'locations' => Location::orderBy('name')->get(),
            'members' => $this->team->users,
            'leaders' => $this->team->leaders,
        ]);
    }
}
