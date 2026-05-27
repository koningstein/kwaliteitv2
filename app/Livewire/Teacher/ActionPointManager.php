<?php

namespace App\Livewire\Teacher;

use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ActionPointManager extends Component
{
    public int $criterionId;

    public ?int $teamId = null;

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

    public bool $isMedewerker = false;

    private function teamUsers()
    {
        if (! $this->teamId) {
            return User::orderBy('name')->get();
        }

        return User::whereHas('teams', fn ($q) => $q->where('teams.id', $this->teamId))
            ->orderBy('name')
            ->get();
    }

    public function mount(Criterion $criterion, ?int $teamId = null): void
    {
        $this->criterionId  = $criterion->id;
        $this->teamId       = $teamId;

        $user = auth()->user();
        $this->isMedewerker = $user?->hasPermissionTo('edit-own-action-point-status')
                           || $user?->hasPermissionTo('edit-own-action-point-dates');
    }

    // ── Nieuw actiepunt ─────────────────────────────────────────────

    public function addActionPoint(): void
    {
        Gate::authorize('create', ActionPoint::class);

        $this->validate([
            'newDescription' => 'required|string|max:1000',
            'newUserId' => 'required|exists:users,id',
            'newStartDate' => 'required|date',
            'newEndDate' => 'required|date|after_or_equal:newStartDate',
        ], [
            'newDescription.required' => 'Beschrijving is verplicht.',
            'newUserId.required' => 'Kies een verantwoordelijke.',
            'newStartDate.required' => 'Startdatum is verplicht.',
            'newEndDate.required' => 'Einddatum is verplicht.',
            'newEndDate.after_or_equal' => 'Einddatum moet op of na de startdatum liggen.',
        ]);

        $defaultStatus = ActionPointStatus::where('name', 'Niet gestart')->first();

        ActionPoint::create([
            'criterion_id' => $this->criterionId,
            'user_id' => $this->newUserId,
            'team_id' => $this->teamId,
            'action_point_status_id' => $defaultStatus?->id,
            'description' => $this->newDescription,
            'start_date' => $this->newStartDate,
            'end_date' => $this->newEndDate,
        ]);

        $this->reset(['newDescription', 'newUserId', 'newStartDate', 'newEndDate', 'showAddForm']);
    }

    // ── Bewerken ────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $ap = ActionPoint::findOrFail($id);

        Gate::authorize('update', $ap);

        $this->editingId = $id;
        $this->editDescription = $ap->description;
        $this->editUserId = $ap->user_id;
        $this->editStartDate = $ap->start_date ? \Carbon\Carbon::parse($ap->start_date)->format('Y-m-d') : '';
        $this->editEndDate = $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('Y-m-d') : '';
        $this->editStatusId = $ap->action_point_status_id;
        $this->editEvaluationText = '';
    }

    public function saveEdit(): void
    {
        $ap = ActionPoint::findOrFail($this->editingId);

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

        $ap->update([
            'description' => $this->editDescription,
            'user_id' => $this->editUserId,
            'start_date' => $this->editStartDate,
            'end_date' => $this->editEndDate,
            'action_point_status_id' => $this->editStatusId,
        ]);

        if (trim($this->editEvaluationText) !== '') {
            abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);

            Evaluation::create([
                'action_point_id' => $this->editingId,
                'description' => trim($this->editEvaluationText),
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
        $ap = ActionPoint::findOrFail($id);

        Gate::authorize('delete', $ap);

        $ap->delete();
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
        abort_unless(auth()->user()->hasPermissionTo('edit-action-points'), 403);

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

    // ── Deelnemers ──────────────────────────────────────────────────

    public function addParticipant(int $actionPointId, int $userId): void
    {
        $ap = ActionPoint::findOrFail($actionPointId);

        Gate::authorize('update', $ap);

        // Verantwoordelijke kan niet ook deelnemer zijn
        if ($ap->user_id === $userId) {
            return;
        }

        $ap->participants()->syncWithoutDetaching([$userId]);
    }

    public function removeParticipant(int $actionPointId, int $userId): void
    {
        $ap = ActionPoint::findOrFail($actionPointId);

        Gate::authorize('update', $ap);

        $ap->participants()->detach($userId);
    }

    // ── Render ──────────────────────────────────────────────────────

    public function render()
    {
        $criterion = Criterion::with([
            // Actiepunten gefilterd: medewerker → eigen user_id, anderen → team_id
            'actionPoints' => function ($q) {
                if ($this->isMedewerker) {
                    $q->where('user_id', auth()->id());
                } elseif ($this->teamId) {
                    $q->where('team_id', $this->teamId);
                }
            },
            'actionPoints.status',
            'actionPoints.user',
            'actionPoints.updater',
            'actionPoints.creator',
            'actionPoints.evaluations' => fn ($q) => $q->orderBy('created_at', 'desc'),
            'actionPoints.evaluations.creator',
            'actionPoints.evaluations.updater',
            'actionPoints.participants',
        ])->findOrFail($this->criterionId);

        $users = $this->teamUsers();
        $statuses = ActionPointStatus::all();

        return view('livewire.teacher.action-point-manager', [
            'criterion' => $criterion,
            'users' => $users,
            'statuses' => $statuses,
        ]);
    }
}
