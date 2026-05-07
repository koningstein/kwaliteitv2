<?php

namespace App\Livewire\Teacher;

use App\Models\Criterion;
use App\Models\CriterionScore;
use Livewire\Component;

class CriterionCard extends Component
{
    public int $criterionId;

    public $periods = [];

    public array $scores = [];

    public bool $isOpen = false;

    public string $explanation = '';

    public bool $editingExplanation = false;

    public ?int $teamId = null;

    public function mount(Criterion $criterion, $periods, ?int $teamId = null): void
    {
        $this->criterionId = $criterion->id;
        $this->periods = $periods;
        $this->teamId = $teamId;
        $this->explanation = $criterion->explanation ?? '';

        // Laad scores gefilterd op het actieve team
        $teamScores = $criterion->scores->when(
            $this->teamId,
            fn ($col) => $col->where('team_id', $this->teamId)
        );

        foreach ($teamScores as $score) {
            $this->scores[$score->reporting_period_id] = $score->status;
        }
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function setScore(int $periodId, string $status): void
    {
        Gate::authorize('create', CriterionScore::class);

        CriterionScore::updateOrCreate(
            [
                'criterion_id' => $this->criterionId,
                'reporting_period_id' => $periodId,
                'team_id' => $this->teamId,
            ],
            [
                'status' => $status,
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
            // Actiepunten gefilterd op actief team
            'actionPoints' => fn ($q) => $this->teamId
                ? $q->where('team_id', $this->teamId)
                : $q,
            'actionPoints.status',
            'actionPoints.user',
            'actionPoints.evaluations',
        ])->findOrFail($this->criterionId);

        return view('livewire.teacher.criterion-card', [
            'criterion' => $criterion,
            'teamId'    => $this->teamId,
        ]);
    }
}
