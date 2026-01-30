<?php

namespace App\Livewire\Admin;

use App\Models\Standard;
use Livewire\Component;
use Livewire\WithPagination;

class StandardSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $standards = Standard::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('theme', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->with('theme')
            ->withCount('criteria')
            ->orderBy('code')
            ->paginate(10);

        return view('livewire.admin.standard-search', compact('standards'));
    }
}
