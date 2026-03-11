<?php

namespace App\Livewire\Admin;

use App\Models\Evaluation;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $evaluations = Evaluation::query()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('actionPoint', function ($q) {
                        $q->whereHas('criterion', fn ($q2) => $q2->where('text', 'like', '%' . $this->search . '%'))
                            ->orWhereHas('team', fn ($q2) => $q2->where('name', 'like', '%' . $this->search . '%'));
                    });
            })
            ->with(['actionPoint.criterion.standard', 'actionPoint.team'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.evaluation-search', compact('evaluations'));
    }
}
