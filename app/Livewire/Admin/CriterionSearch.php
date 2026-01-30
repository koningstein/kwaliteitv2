<?php

namespace App\Livewire\Admin;

use App\Models\Criterion;
use Livewire\Component;
use Livewire\WithPagination;

class CriterionSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $criteria = Criterion::query()
            ->when($this->search, function ($query) {
                $query->where('text', 'like', '%' . $this->search . '%')
                    ->orWhere('number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('standard', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    });
            })
            ->with('standard.theme')
            ->orderBy('standard_id')
            ->orderBy('number')
            ->paginate(10);

        return view('livewire.admin.criterion-search', compact('criteria'));
    }
}
