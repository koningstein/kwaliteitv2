<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class LocationSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $locations = Location::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('abbreviation', 'like', '%' . $this->search . '%');
            })
            ->withCount(['teams', 'users'])
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.location-search', compact('locations'));
    }
}
