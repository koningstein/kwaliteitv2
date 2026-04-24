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

    private function userTeamId(): ?int
    {
        return auth()->user()?->teams->first()?->id;
    }

    public function mount(Criterion $criterion, $periods): void
    {
        $this->criterionId = $criterion->id;
        $this->periods     = $periods;
        $this->explanation = $criterion->explanation ?? '';

        $teamId = $this->userTeamId();

        // Laad scores gefilterd op het eigen team
        $teamScores = $criterion->scores->when(
            $teamId,
            fn ($col) => $col->where('team_id', $teamId)
        );

        foreach ($teamScores as $score) {
            $this->scores[$score->reporting_period_id] = $score->status;
        }
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function setScore(int $periodId, string $status): void
    {
        $teamId = $this->userTeamId();

        CriterionScore::updateOrCreate(
            [
                'criterion_id'        => $this->criterionId,
                'reporting_period_id' => $periodId,
                'team_id'             => $teamId,
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
        $teamId = $this->userTeamId();

        $criterion = Criterion::with([
            'indicators' => fn ($q) => $q->orderBy('sort_order'),
            // Actiepunten gefilterd op eigen team
            'actionPoints' => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'actionPoints.status',
            'actionPoints.user',
            'actionPoints.evaluations',
        ])->findOrFail($this->criterionId);

        return view('livewire.teacher.criterion-card', ['criterion' => $criterion]);
    }
}
