<?php

namespace App\Livewire\Teacher;

use App\Models\Criterion;
use App\Models\CriterionScore;
use Livewire\Component;

class CriterionCard extends Component
{
    public int $criterionId;
    public $periods = [];

    // Scores indexed by reporting_period_id => status
    public array $scores = [];

    // Open/dicht state
    public bool $isOpen = false;

    // Toelichting (explanation)
    public string $explanation = '';
    public bool $editingExplanation = false;

    public function mount(Criterion $criterion, $periods): void
    {
        $this->criterionId = $criterion->id;
        $this->periods     = $periods;
        $this->explanation = $criterion->explanation ?? '';

        // Scores initialiseren vanuit de al eager-loaded relatie
        foreach ($criterion->scores as $score) {
            $this->scores[$score->reporting_period_id] = $score->status;
        }
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function setScore(int $periodId, string $status): void
    {
        CriterionScore::updateOrCreate(
            [
                'criterion_id'        => $this->criterionId,
                'reporting_period_id' => $periodId,
            ],
            [
                'status'     => $status,
                'updated_by' => optional(auth()->user())->id,
            ]
        );

        $this->scores[$periodId] = $status;
    }

    public function saveExplanation(): void
    {
        Criterion::where('id', $this->criterionId)->update(['explanation' => $this->explanation]);
        $this->editingExplanation = false;
    }

    public function cancelExplanation(): void
    {
        $this->explanation = Criterion::find($this->criterionId)?->explanation ?? '';
        $this->editingExplanation = false;
    }

    public function render()
    {
        $criterion = Criterion::with([
            'indicators' => fn ($q) => $q->orderBy('sort_order'),
            'actionPoints.status',
            'actionPoints.user',
            'actionPoints.evaluations',
        ])->findOrFail($this->criterionId);

        return view('livewire.teacher.criterion-card', [
            'criterion' => $criterion,
        ]);
    }
}
