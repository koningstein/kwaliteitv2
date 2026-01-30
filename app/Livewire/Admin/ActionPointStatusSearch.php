<?php

namespace App\Livewire\Admin;

use App\Models\ActionPointStatus;
use Livewire\Component;
use Livewire\WithPagination;

class ActionPointStatusSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $statuses = ActionPointStatus::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.action-point-status-search', compact('statuses'));
    }
}
