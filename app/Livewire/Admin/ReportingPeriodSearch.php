<?php

namespace App\Livewire\Admin;

use App\Models\ReportingPeriod;
use Livewire\Component;
use Livewire\WithPagination;

class ReportingPeriodSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $periods = ReportingPeriod::query()
            ->when($this->search, function ($query) {
                $query->where('label', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->paginate(10);

        return view('livewire.admin.reporting-period-search', compact('periods'));
    }
}
