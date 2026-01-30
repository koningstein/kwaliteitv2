<?php

namespace App\Livewire\Admin;

use App\Models\Indicator;
use Livewire\Component;
use Livewire\WithPagination;

class IndicatorSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $indicators = Indicator::query()
            ->when($this->search, function ($query) {
                $query->where('text', 'like', '%' . $this->search . '%')
                    ->orWhereHas('criterion', function ($q) {
                        $q->where('number', 'like', '%' . $this->search . '%')
                            ->orWhereHas('standard', function ($sq) {
                                $sq->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('code', 'like', '%' . $this->search . '%');
                            });
                    });
            })
            ->with('criterion.standard.theme')
            ->orderBy('criterion_id')
            ->orderBy('sort_order')
            ->paginate(10);

        return view('livewire.admin.indicator-search', compact('indicators'));
    }
}
