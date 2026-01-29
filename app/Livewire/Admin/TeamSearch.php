<?php

namespace App\Livewire\Admin;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class TeamSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $teams = Team::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount(['users', 'leaders'])
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.team-search', compact('teams'));
    }
}
