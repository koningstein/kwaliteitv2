<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActionPointController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-action-points'),
        ];
    }

    public function index(Request $request)
    {
        $authUser       = auth()->user();
        $isGlobalViewer = $authUser?->hasPermissionTo('view-all-action-points');
        $isMedewerker   = $authUser?->hasPermissionTo('edit-own-action-point-status')
                       || $authUser?->hasPermissionTo('edit-own-action-point-dates');

        $filter   = $request->query('filter', 'all');
        $mineOnly = $isMedewerker && $request->boolean('mine');
        $statuses = ActionPointStatus::all();

        // --- Teams bepalen ---
        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } else {
            // Medewerker, onderwijsleider, kwaliteitszorg: eigen teams
            $managed = $authUser?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams   = $managed->isNotEmpty()
                ? $managed
                : ($authUser?->teams()->orderBy('name')->get() ?? collect());
        }

        // Geen teams: lege state tonen
        if ($teams->isEmpty()) {
            return view('teacher.action-points.index', [
                'actionPoints'  => collect(),
                'statuses'      => $statuses,
                'filter'        => $filter,
                'teams'         => collect(),
                'activeTeam'    => null,
                'users'         => collect(),
                'selectedUser'  => null,
                'isMedewerker'  => $isMedewerker,
                'mineOnly'      => false,
            ]);
        }

        // --- Actief team bepalen ---
        $teamIdParam = $request->query('team');

        $activeTeam = null;
        if (! $teamIdParam && $request->query('user')) {
            $userTeamId = User::find((int) $request->query('user'))?->teams->first()?->id;
            if ($userTeamId) {
                $activeTeam = $teams->firstWhere('id', $userTeamId);
            }
        }
        if (! $activeTeam) {
            $activeTeam = $teamIdParam
                ? $teams->firstWhere('id', (int) $teamIdParam) ?? $teams->first()
                : $teams->first();
        }

        // --- Gebruikerslijst voor dropdown ---
        if ($activeTeam) {
            $users = User::whereHas('teams', fn ($q) => $q->where('teams.id', $activeTeam->id))
                ->orderBy('name')->get();
        } else {
            $users = collect();
        }

        // --- Geselecteerde user uit ?user= ---
        $selectedUserId = $request->query('user');
        $selectedUser   = $selectedUserId
            ? $users->firstWhere('id', (int) $selectedUserId)
            : null;

        // --- Query opbouwen ---
        $query = ActionPoint::with([
            'status',
            'user',
            'criterion.standard.theme',
            'evaluations' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ])->orderByRaw('end_date IS NULL, end_date ASC');

        if ($mineOnly) {
            // Medewerker filtert op eigen actiepunten binnen het team
            $query->where('team_id', $activeTeam->id)
                  ->where('user_id', $authUser->id);
        } elseif ($activeTeam) {
            $query->where('team_id', $activeTeam->id);
        }

        // Extra filter: specifiek teamlid (via dropdown)
        if ($selectedUser) {
            $query->where('user_id', $selectedUser->id);
        }

        if ($filter !== 'all') {
            $status = $statuses->firstWhere('id', (int) $filter);
            if ($status) {
                $query->where('action_point_status_id', $status->id);
            }
        }

        $actionPoints = $query->get();

        return view('teacher.action-points.index', [
            'actionPoints'  => $actionPoints,
            'statuses'      => $statuses,
            'filter'        => $filter,
            'teams'         => $teams,
            'activeTeam'    => $activeTeam,
            'users'         => $users,
            'selectedUser'  => $selectedUser,
            'isMedewerker'  => $isMedewerker,
            'mineOnly'      => $mineOnly,
        ]);
    }
}
