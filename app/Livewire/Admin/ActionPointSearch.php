<?php

namespace App\Livewire\Admin;

use App\Models\ActionPoint;
use Livewire\Component;
use Livewire\WithPagination;

class ActionPointSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $actionPoints = ActionPoint::query()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('team', fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('criterion', fn ($q) => $q->where('text', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('status', fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            })
            ->with(['criterion.standard', 'team', 'status', 'user'])
            ->orderBy('team_id')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.action-point-search', compact('actionPoints'));
    }
}
