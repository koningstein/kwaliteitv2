<?php

namespace App\Livewire\Teacher;

use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\User;
use Livewire\Component;

class ActionPointManager extends Component
{
    public int $criterionId;

    public bool $showAddForm = false;
    public string $newDescription = '';
    public ?int $newUserId = null;
    public string $newStartDate = '';
    public string $newEndDate = '';

    public ?int $editingId = null;
    public string $editDescription = '';
    public ?int $editUserId = null;
    public string $editStartDate = '';
    public string $editEndDate = '';
    public ?int $editStatusId = null;
    public string $editEvaluationText = '';

    public ?int $evaluatingId = null;
    public string $newEvaluationText = '';
    public array $expandedEvaluations = [];

    private function userTeamId(): ?int
    {
        return auth()->user()?->teams->first()?->id;
    }

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

    public function mount(Criterion $criterion): void
    {
        $this->criterionId = $criterion->id;
    }

    // ── Nieuw actiepunt ─────────────────────────────────────────────

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
            'criterion_id'           => $this->criterionId,
            'user_id'                => $this->newUserId,
            'team_id'                => $this->userTeamId(),
            'action_point_status_id' => $defaultStatus?->id,
            'description'            => $this->newDescription,
            'start_date'             => $this->newStartDate,
            'end_date'               => $this->newEndDate,
        ]);

        $this->reset(['newDescription', 'newUserId', 'newStartDate', 'newEndDate', 'showAddForm']);
    }

    // ── Bewerken ────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $ap = ActionPoint::findOrFail($id);
        $this->editingId          = $id;
        $this->editDescription    = $ap->description;
        $this->editUserId         = $ap->user_id;
        $this->editStartDate      = $ap->start_date ? \Carbon\Carbon::parse($ap->start_date)->format('Y-m-d') : '';
        $this->editEndDate        = $ap->end_date   ? \Carbon\Carbon::parse($ap->end_date)->format('Y-m-d')   : '';
        $this->editStatusId       = $ap->action_point_status_id;
        $this->editEvaluationText = '';
    }

    public function saveEdit(): void
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

        ActionPoint::where('id', $this->editingId)->update([
            'description'            => $this->editDescription,
            'user_id'                => $this->editUserId,
            'start_date'             => $this->editStartDate,
            'end_date'               => $this->editEndDate,
            'action_point_status_id' => $this->editStatusId,
        ]);

        if (trim($this->editEvaluationText) !== '') {
            Evaluation::create([
                'action_point_id' => $this->editingId,
                'description'     => trim($this->editEvaluationText),
            ]);
        }

        $this->reset(['editingId', 'editDescription', 'editUserId', 'editStartDate', 'editEndDate', 'editStatusId', 'editEvaluationText']);
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'editDescription', 'editUserId', 'editStartDate', 'editEndDate', 'editStatusId', 'editEvaluationText']);
    }

    // ── Verwijderen ─────────────────────────────────────────────────

    public function deleteActionPoint(int $id): void
    {
        ActionPoint::findOrFail($id)->delete();
    }

    // ── Evaluaties ──────────────────────────────────────────────────

    public function toggleEvaluations(int $id): void
    {
        if (in_array($id, $this->expandedEvaluations)) {
            $this->expandedEvaluations = array_values(array_filter($this->expandedEvaluations, fn ($v) => $v !== $id));
        } else {
            $this->expandedEvaluations[] = $id;
        }
    }

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

    // ── Render ──────────────────────────────────────────────────────

    public function render()
    {
        $teamId = $this->userTeamId();

        $criterion = Criterion::with([
            // Actiepunten gefilterd op eigen team
            'actionPoints' => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'actionPoints.status',
            'actionPoints.user',
            'actionPoints.evaluations',
        ])->findOrFail($this->criterionId);

        $users    = $this->teamUsers();
        $statuses = ActionPointStatus::all();

        return view('livewire.teacher.action-point-manager', [
            'criterion' => $criterion,
            'users'     => $users,
            'statuses'  => $statuses,
        ]);
    }
}
