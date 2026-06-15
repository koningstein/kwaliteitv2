<?php

namespace App\Livewire\Teacher;

use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\CriterionRemark;
use App\Models\CriterionScore;
use App\Models\Evaluation;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class StandardCard extends Component
{
    public int $standardId;

    public ?int $teamId = null;

    public $periods;

    public bool $isOpen = false;

    public array $openCriteria = [];

    public array $scores = [];

    public array $explanations = [];

    public array $editingExplanation = [];

    public array $remarks = [];

    public array $editingRemark = [];

    public ?int $showAddFormFor = null;

    public string $newDescription = '';

    public ?string $newUserId = null;

    public string $newStartDate = '';

    public string $newEndDate = '';

    public ?int $editingActionPointId = null;

    public string $editDescription = '';

    public ?string $editUserId = null;

    public string $editStartDate = '';

    public string $editEndDate = '';

    public ?string $editStatusId = null;

    public string $editEvaluationText = '';

    public ?int $evaluatingId = null;

    public string $newEvaluationText = '';

    public ?int $editingEvaluationId = null;

    public string $editingEvaluationText = '';

    // ── Hulpfuncties voor team-scope ─────────────────────────────────

    /**
     * Geeft de users terug die tot het actieve team behoren (voor de dropdowns).
     */
    private function teamUsers()
    {
        if (! $this->teamId) {
            return User::orderBy('name')->get();
        }

        return User::whereHas('teams', fn ($q) => $q->where('teams.id', $this->teamId))
            ->orderBy('name')
            ->get();
    }

    // ── Mount ────────────────────────────────────────────────────────

    public function mount(Standard $standard, $periods, ?int $teamId = null, ?int $openCriterionId = null, ?int $openStandardId = null): void
    {
        $this->standardId = $standard->id;
        $this->periods    = $periods;
        $this->teamId     = $teamId;

        // Vouw de standaard automatisch open als dit de gevraagde standaard is
        if ($openStandardId && $openStandardId === $standard->id) {
            $this->isOpen = true;

            // Vouw ook het gevraagde criterium open
            if ($openCriterionId) {
                $this->openCriteria[$openCriterionId] = true;
            }
        }

        foreach ($standard->criteria as $criterion) {
            $this->explanations[$criterion->id] = $criterion->explanation ?? '';
            $this->scores[$criterion->id] = [];

            // Laad scores gefilterd op het actieve team
            $teamScores = $criterion->scores->when(
                $this->teamId,
                fn ($col) => $col->where('team_id', $this->teamId)
            );

            foreach ($teamScores as $score) {
                $this->scores[$criterion->id][$score->reporting_period_id] = $score->status;
            }

            // Laad team-opmerking voor dit criterium
            $remark = $this->teamId
                ? CriterionRemark::where('criterion_id', $criterion->id)->where('team_id', $this->teamId)->first()
                : null;
            $this->remarks[$criterion->id] = $remark?->remark ?? '';
        }
    }

    // ── Standaard toggle ─────────────────────────────────────────────

    public function toggleStandard(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    // ── Criterium toggle ─────────────────────────────────────────────

    public function toggleCriterion(int $criterionId): void
    {
        $this->openCriteria[$criterionId] = ! ($this->openCriteria[$criterionId] ?? false);
    }

    // ── Scores ──────────────────────────────────────────────────────

    public function setScore(int $criterionId, int $periodId, string $status): void
    {
        Gate::authorize('create', CriterionScore::class);

        CriterionScore::updateOrCreate(
            [
                'criterion_id' => $criterionId,
                'reporting_period_id' => $periodId,
                'team_id' => $this->teamId,
            ],
            [
                'status' => $status,
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
        Gate::authorize('manage-criteria');
        Criterion::where('id', $criterionId)->update(['explanation' => $this->explanations[$criterionId] ?? '']);
        $this->editingExplanation[$criterionId] = false;
    }

    public function cancelExplanation(int $criterionId): void
    {
        Gate::authorize('manage-criteria');
        $this->explanations[$criterionId] = Criterion::find($criterionId)?->explanation ?? '';
        $this->editingExplanation[$criterionId] = false;
    }

    // ── Team-opmerking ──────────────────────────────────────────────

    public function startEditRemark(int $criterionId): void
    {
        $this->editingRemark[$criterionId] = true;
    }

    public function saveRemark(int $criterionId): void
    {
        Gate::authorize('create', ActionPoint::class);
        abort_unless($this->teamId, 403);

        CriterionRemark::updateOrCreate(
            ['criterion_id' => $criterionId, 'team_id' => $this->teamId],
            ['remark' => $this->remarks[$criterionId] ?? '', 'user_id' => auth()->id()]
        );

        $this->editingRemark[$criterionId] = false;
    }

    public function cancelRemark(int $criterionId): void
    {
        $remark = $this->teamId
            ? CriterionRemark::where('criterion_id', $criterionId)->where('team_id', $this->teamId)->first()
            : null;
        $this->remarks[$criterionId] = $remark?->remark ?? '';
        $this->editingRemark[$criterionId] = false;
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
        Gate::authorize('create', ActionPoint::class);

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

        $defaultStatus = ActionPointStatus::where('name', 'Niet gestart')->first();

        $ap = ActionPoint::create([
            'criterion_id'           => $this->showAddFormFor,
            'user_id'                => (int) $this->newUserId,
            'team_id'                => $this->teamId,
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
        Gate::authorize('update', $ap);
        $this->editingActionPointId = $id;
        $this->editDescription = $ap->description;
        $this->editUserId = $ap->user_id ? (string) $ap->user_id : null;
        $this->editStartDate = $ap->start_date ? \Carbon\Carbon::parse($ap->start_date)->format('Y-m-d') : '';
        $this->editEndDate = $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('Y-m-d') : '';
        $this->editStatusId = $ap->action_point_status_id ? (string) $ap->action_point_status_id : null;
        $this->editEvaluationText = '';
    }

    public function saveEditActionPoint(): void
    {
        $ap = ActionPoint::findOrFail($this->editingActionPointId);
        Gate::authorize('update', $ap);

        $this->validate([
            'editDescription' => 'required|string|max:1000',
            'editUserId' => 'required|exists:users,id',
            'editStartDate' => 'required|date',
            'editEndDate' => 'required|date|after_or_equal:editStartDate',
            'editStatusId' => 'required|exists:action_point_statuses,id',
            'editEvaluationText' => 'nullable|string|max:2000',
        ], [
            'editDescription.required' => 'Beschrijving is verplicht.',
            'editUserId.required' => 'Kies een verantwoordelijke.',
            'editStartDate.required' => 'Startdatum is verplicht.',
            'editEndDate.required' => 'Einddatum is verplicht.',
            'editEndDate.after_or_equal' => 'Einddatum moet op of na de startdatum liggen.',
            'editStatusId.required' => 'Status is verplicht.',
        ]);

        ActionPoint::where('id', $this->editingActionPointId)->update([
            'description' => $this->editDescription,
            'user_id' => $this->editUserId ? (int) $this->editUserId : null,
            'start_date' => $this->editStartDate,
            'end_date' => $this->editEndDate,
            'action_point_status_id' => $this->editStatusId,
        ]);

        if (trim($this->editEvaluationText) !== '') {
            Evaluation::create([
                'action_point_id' => $this->editingActionPointId,
                'description' => trim($this->editEvaluationText),
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
        $ap = ActionPoint::findOrFail($id);
        Gate::authorize('delete', $ap);
        $ap->delete();
    }

    // ── Evaluaties ───────────────────────────────────────────────────

    public function startEvaluation(int $id): void
    {
        $this->evaluatingId = $id;
        $this->newEvaluationText = '';
    }

    public function saveEvaluation(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);

        $this->validate([
            'newEvaluationText' => 'required|string|max:2000',
        ], [
            'newEvaluationText.required' => 'Voer een evaluatietekst in.',
        ]);

        Evaluation::create([
            'action_point_id' => $this->evaluatingId,
            'description' => $this->newEvaluationText,
        ]);

        $this->reset(['evaluatingId', 'newEvaluationText']);
    }

    public function cancelEvaluation(): void
    {
        $this->reset(['evaluatingId', 'newEvaluationText']);
    }

    public function editEvaluation(int $evalId): void
    {
        abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);
        $eval = Evaluation::findOrFail($evalId);
        $this->editingEvaluationId   = $evalId;
        $this->editingEvaluationText = $eval->description;
    }

    public function updateEvaluation(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);
        $this->validate(['editingEvaluationText' => 'required|string|max:2000'], [
            'editingEvaluationText.required' => 'Voer een evaluatietekst in.',
        ]);
        Evaluation::where('id', $this->editingEvaluationId)->update([
            'description' => $this->editingEvaluationText,
        ]);
        $this->reset(['editingEvaluationId', 'editingEvaluationText']);
    }

    public function cancelEditEvaluation(): void
    {
        $this->reset(['editingEvaluationId', 'editingEvaluationText']);
    }

    public function deleteEvaluation(int $evalId): void
    {
        abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);
        Evaluation::findOrFail($evalId)->delete();
    }

    // ── Deelnemers ───────────────────────────────────────────────────

    public function addParticipant(int $actionPointId, string $userId): void
    {
        $ap = ActionPoint::findOrFail($actionPointId);
        Gate::authorize('update', $ap);

        if ($userId === '__team__') {
            $deelnemerIds = $this->teamUsers()
                ->reject(fn ($u) => $u->id === $ap->user_id)
                ->pluck('id')->all();
            $ap->participants()->syncWithoutDetaching($deelnemerIds);
            return;
        }

        if ($ap->user_id === (int) $userId) {
            return;
        }

        $ap->participants()->syncWithoutDetaching([(int) $userId]);
    }

    public function removeParticipant(int $actionPointId, int $userId): void
    {
        $ap = ActionPoint::findOrFail($actionPointId);
        Gate::authorize('update', $ap);

        $ap->participants()->detach($userId);
    }

    // ── Render ───────────────────────────────────────────────────────

    public function render()
    {
        $standard = Standard::with([
            'theme',
            'criteria' => fn ($q) => $q->orderBy('number'),
            'criteria.indicators' => fn ($q) => $q->orderBy('sort_order'),
            // Scores gefilterd op actief team
            'criteria.scores' => fn ($q) => $this->teamId
                ? $q->where('team_id', $this->teamId)
                : $q,
            // Actiepunten gefilterd op actief team
            'criteria.actionPoints' => fn ($q) => $this->teamId
                ? $q->where('team_id', $this->teamId)
                : $q,
            'criteria.actionPoints.status',
            'criteria.actionPoints.user',
            'criteria.actionPoints.evaluations',
            'criteria.actionPoints.participants',
        ])->findOrFail($this->standardId);

        $users = $this->teamUsers();
        $statuses = ActionPointStatus::all();

        return view('livewire.teacher.standard-card', [
            'standard' => $standard,
            'users' => $users,
            'statuses' => $statuses,
        ]);
    }
}
