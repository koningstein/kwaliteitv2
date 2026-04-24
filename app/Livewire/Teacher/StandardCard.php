<?php

namespace App\Livewire\Teacher;

use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\CriterionScore;
use App\Models\Evaluation;
use App\Models\Standard;
use App\Models\User;
use Livewire\Component;

class StandardCard extends Component
{
    public int $standardId;
    public $periods;

    public bool $isOpen = false;
    public array $openCriteria = [];
    public array $scores = [];
    public array $explanations = [];
    public array $editingExplanation = [];

    public ?int $showAddFormFor = null;
    public string $newDescription = '';
    public ?int $newUserId = null;
    public string $newStartDate = '';
    public string $newEndDate = '';

    public ?int $editingActionPointId = null;
    public string $editDescription = '';
    public ?int $editUserId = null;
    public string $editStartDate = '';
    public string $editEndDate = '';
    public ?int $editStatusId = null;
    public string $editEvaluationText = '';

    public ?int $evaluatingId = null;
    public string $newEvaluationText = '';

    // ── Hulpfuncties voor team-scope ─────────────────────────────────

    /**
     * Geeft het eerste team van de ingelogde gebruiker terug.
     * Kwaliteitszorg heeft altijd precies 1 team.
     */
    private function userTeamId(): ?int
    {
        return auth()->user()?->teams->first()?->id;
    }

    /**
     * Geeft de users terug die tot het eigen team behoren (voor de dropdowns).
     */
    private function teamUsers()
    {
        $teamId = $this->userTeamId();

        if (!$teamId) {
            return User::orderBy('name')->get();
        }

        return User::whereHas('teams', fn ($q) => $q->where('teams.id', $teamId))
            ->orderBy('name')
            ->get();
    }

    // ── Mount ────────────────────────────────────────────────────────

    public function mount(Standard $standard, $periods): void
    {
        $this->standardId = $standard->id;
        $this->periods    = $periods;

        $teamId = $this->userTeamId();

        foreach ($standard->criteria as $criterion) {
            $this->explanations[$criterion->id] = $criterion->explanation ?? '';
            $this->scores[$criterion->id] = [];

            // Laad scores gefilterd op het eigen team
            $teamScores = $criterion->scores->when(
                $teamId,
                fn ($col) => $col->where('team_id', $teamId)
            );

            foreach ($teamScores as $score) {
                $this->scores[$criterion->id][$score->reporting_period_id] = $score->status;
            }
        }
    }

    // ── Standaard toggle ─────────────────────────────────────────────

    public function toggleStandard(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    // ── Criterium toggle ─────────────────────────────────────────────

    public function toggleCriterion(int $criterionId): void
    {
        $this->openCriteria[$criterionId] = !($this->openCriteria[$criterionId] ?? false);
    }

    // ── Scores ──────────────────────────────────────────────────────

    public function setScore(int $criterionId, int $periodId, string $status): void
    {
        $teamId = $this->userTeamId();

        CriterionScore::updateOrCreate(
            [
                'criterion_id'        => $criterionId,
                'reporting_period_id' => $periodId,
                'team_id'             => $teamId,
            ],
            [
                'status'     => $status,
                'updated_by' => optional(auth()->user())->id,
            ]
        );

        $this->scores[$criterionId][$periodId] = $status;
    }

    // ── Toelichting ─────────────────────────────────────────────────

    public function startEditExplanation(int $criterionId): void
    {
        $this->editingExplanation[$criterionId] = true;
    }

    public function saveExplanation(int $criterionId): void
    {
        Criterion::where('id', $criterionId)->update(['explanation' => $this->explanations[$criterionId] ?? '']);
        $this->editingExplanation[$criterionId] = false;
    }

    public function cancelExplanation(int $criterionId): void
    {
        $this->explanations[$criterionId] = Criterion::find($criterionId)?->explanation ?? '';
        $this->editingExplanation[$criterionId] = false;
    }

    // ── Actiepunten: nieuw ───────────────────────────────────────────

    public function showAddForm(int $criterionId): void
    {
        $this->showAddFormFor = $criterionId;
        $this->reset(['newDescription', 'newUserId', 'newStartDate', 'newEndDate']);
    }

    public function cancelAddForm(): void
    {
        $this->showAddFormFor = null;
        $this->reset(['newDescription', 'newUserId', 'newStartDate', 'newEndDate']);
    }

    public function addActionPoint(): void
    {
        $this->validate([
            'newDescription' => 'required|string|max:1000',
            'newUserId'      => 'required|exists:users,id',
            'newStartDate'   => 'required|date',
            'newEndDate'     => 'required|date|after_or_equal:newStartDate',
        ], [
            'newDescription.required'   => 'Beschrijving is verplicht.',
            'newUserId.required'        => 'Kies een verantwoordelijke.',
            'newStartDate.required'     => 'Startdatum is verplicht.',
            'newEndDate.required'       => 'Einddatum is verplicht.',
            'newEndDate.after_or_equal' => 'Einddatum moet op of na de startdatum liggen.',
        ]);

        $defaultStatus = ActionPointStatus::first();

        ActionPoint::create([
            'criterion_id'           => $this->showAddFormFor,
            'user_id'                => $this->newUserId,
            'team_id'                => $this->userTeamId(),
            'action_point_status_id' => $defaultStatus?->id,
            'description'            => $this->newDescription,
            'start_date'             => $this->newStartDate,
            'end_date'               => $this->newEndDate,
        ]);

        $this->showAddFormFor = null;
        $this->reset(['newDescription', 'newUserId', 'newStartDate', 'newEndDate']);
    }

    // ── Actiepunten: bewerken ────────────────────────────────────────

    public function startEditActionPoint(int $id): void
    {
        $ap = ActionPoint::findOrFail($id);
        $this->editingActionPointId = $id;
        $this->editDescription      = $ap->description;
        $this->editUserId           = $ap->user_id;
        $this->editStartDate        = $ap->start_date ? \Carbon\Carbon::parse($ap->start_date)->format('Y-m-d') : '';
        $this->editEndDate          = $ap->end_date   ? \Carbon\Carbon::parse($ap->end_date)->format('Y-m-d')   : '';
        $this->editStatusId         = $ap->action_point_status_id;
        $this->editEvaluationText   = '';
    }

    public function saveEditActionPoint(): void
    {
        $this->validate([
            'editDescription'    => 'required|string|max:1000',
            'editUserId'         => 'required|exists:users,id',
            'editStartDate'      => 'required|date',
            'editEndDate'        => 'required|date|after_or_equal:editStartDate',
            'editStatusId'       => 'required|exists:action_point_statuses,id',
            'editEvaluationText' => 'nullable|string|max:2000',
        ], [
            'editDescription.required'   => 'Beschrijving is verplicht.',
            'editUserId.required'        => 'Kies een verantwoordelijke.',
            'editStartDate.required'     => 'Startdatum is verplicht.',
            'editEndDate.required'       => 'Einddatum is verplicht.',
            'editEndDate.after_or_equal' => 'Einddatum moet op of na de startdatum liggen.',
            'editStatusId.required'      => 'Status is verplicht.',
        ]);

        ActionPoint::where('id', $this->editingActionPointId)->update([
            'description'            => $this->editDescription,
            'user_id'                => $this->editUserId,
            'start_date'             => $this->editStartDate,
            'end_date'               => $this->editEndDate,
            'action_point_status_id' => $this->editStatusId,
        ]);

        if (trim($this->editEvaluationText) !== '') {
            Evaluation::create([
                'action_point_id' => $this->editingActionPointId,
                'description'     => trim($this->editEvaluationText),
            ]);
        }

        $this->reset(['editingActionPointId', 'editDescription', 'editUserId', 'editStartDate', 'editEndDate', 'editStatusId', 'editEvaluationText']);
    }

    public function cancelEditActionPoint(): void
    {
        $this->reset(['editingActionPointId', 'editDescription', 'editUserId', 'editStartDate', 'editEndDate', 'editStatusId', 'editEvaluationText']);
    }

    public function deleteActionPoint(int $id): void
    {
        ActionPoint::findOrFail($id)->delete();
    }

    // ── Evaluaties ───────────────────────────────────────────────────

    public function startEvaluation(int $id): void
    {
        $this->evaluatingId      = $id;
        $this->newEvaluationText = '';
    }

    public function saveEvaluation(): void
    {
        $this->validate([
            'newEvaluationText' => 'required|string|max:2000',
        ], [
            'newEvaluationText.required' => 'Voer een evaluatietekst in.',
        ]);

        Evaluation::create([
            'action_point_id' => $this->evaluatingId,
            'description'     => $this->newEvaluationText,
        ]);

        $this->reset(['evaluatingId', 'newEvaluationText']);
    }

    public function cancelEvaluation(): void
    {
        $this->reset(['evaluatingId', 'newEvaluationText']);
    }

    // ── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $teamId = $this->userTeamId();

        $standard = Standard::with([
            'theme',
            'criteria'            => fn ($q) => $q->orderBy('number'),
            'criteria.indicators' => fn ($q) => $q->orderBy('sort_order'),
            // Scores gefilterd op eigen team
            'criteria.scores'     => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            // Actiepunten gefilterd op eigen team
            'criteria.actionPoints' => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'criteria.actionPoints.status',
            'criteria.actionPoints.user',
            'criteria.actionPoints.evaluations',
        ])->findOrFail($this->standardId);

        $users    = $this->teamUsers();
        $statuses = ActionPointStatus::all();

        return view('livewire.teacher.standard-card', [
            'standard' => $standard,
            'users'    => $users,
            'statuses' => $statuses,
        ]);
    }
}
