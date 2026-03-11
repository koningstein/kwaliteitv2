<?php

namespace App\Livewire\Admin;

use App\Models\CriterionScore;
use Livewire\Component;
use Livewire\WithPagination;

class CriterionScoreSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $scores = CriterionScore::query()
            ->when($this->search, function ($query) {
                $query->whereHas('criterion', fn ($q) => $q->where('text', 'like', '%' . $this->search . '%')
                        ->orWhere('number', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('reportingPeriod', fn ($q) => $q->where('label', 'like', '%' . $this->search . '%'));
            })
            ->with(['criterion.standard.theme', 'reportingPeriod', 'updater'])
            ->orderBy('reporting_period_id', 'desc')
            ->orderBy('criterion_id')
            ->paginate(10);

        return view('livewire.admin.criterion-score-search', compact('scores'));
    }
}
