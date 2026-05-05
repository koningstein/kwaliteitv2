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
        $authUser = auth()->user();
        $isGlobalViewer = $authUser?->hasPermissionTo('view-all-action-points');
        $isMedewerker = $authUser?->hasPermissionTo('edit-own-action-point-status')
                       || $authUser?->hasPermissionTo('edit-own-action-point-dates');

        $filter = $request->query('filter', 'all');
        $statuses = ActionPointStatus::all();

        // --- Teams bepalen ---
        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } elseif ($isMedewerker) {
            // Medewerker heeft geen team-tabs nodig, ziet alleen eigen actiepunten
            $teams = collect();
        } else {
            // Onderwijsleider heeft managedTeams, kwaliteitszorg/medewerker heeft teams
            $managed = $authUser?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams = $managed->isNotEmpty()
                ? $managed
                : ($authUser?->teams()->orderBy('name')->get() ?? collect());
        }

        // Geen teams en geen medewerker en geen global viewer: niets tonen
        if ($teams->isEmpty() && ! $isMedewerker) {
            return view('teacher.action-points.index', [
                'actionPoints' => collect(),
                'statuses' => $statuses,
                'filter' => $filter,
                'teams' => collect(),
                'activeTeam' => null,
                'users' => collect(),
                'selectedUser' => null,
            ]);
        }

        // --- Actief team bepalen ---
        $activeTeam = null;
        if ($teams->isNotEmpty()) {
            $teamIdParam = $request->query('team');

            // Als ?user= is opgegeven zonder ?team=, zoek het team van die user
            if (! $teamIdParam && $request->query('user')) {
                $userTeamId = User::find((int) $request->query('user'))?->teams->first()?->id;
                if ($userTeamId) {
                    $activeTeam = $teams->firstWhere('id', $userTeamId);
                }
            }

            if (! $activeTeam) {
                $activeTeam = $teamIdParam
                    ? $teams->firstWhere('id', (int) $teamIdParam)
                    : $teams->first();
            }
        }

        // --- Gebruikerslijst voor dropdown ---
        if ($isGlobalViewer && $activeTeam) {
            $users = User::whereHas('teams', fn ($q) => $q->where('teams.id', $activeTeam->id))
                ->orderBy('name')->get();
        } elseif ($isGlobalViewer) {
            $users = User::orderBy('name')->get();
        } elseif ($activeTeam) {
            $users = User::whereHas('teams', fn ($q) => $q->where('teams.id', $activeTeam->id))
                ->orderBy('name')->get();
        } else {
            $users = collect();
        }

        // --- Geselecteerde user uit ?user= (alleen als die in toegestane lijst zit) ---
        $selectedUserId = $request->query('user');
        $selectedUser = $selectedUserId
            ? $users->firstWhere('id', (int) $selectedUserId)
            : null;

        // --- Query opbouwen ---
        $query = ActionPoint::with([
            'status',
            'user',
            'criterion.standard.theme',
            'evaluations' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ])->orderBy('end_date');

        if ($isMedewerker) {
            // Medewerker ziet alleen eigen actiepunten
            $query->where('user_id', $authUser->id);
        } elseif ($activeTeam) {
            // Scope op actief team
            $query->where('team_id', $activeTeam->id);
        }

        // Extra filter: specifiek teamlid
        if ($selectedUser) {
            $query->where('user_id', $selectedUser->id);
        }

        if ($filter !== 'all') {
            $status = $statuses->firstWhere('id', $filter);
            if ($status) {
                $query->where('action_point_status_id', $status->id);
            }
        }

        $actionPoints = $query->get();

        return view('teacher.action-points.index', [
            'actionPoints' => $actionPoints,
            'statuses' => $statuses,
            'filter' => $filter,
            'teams' => $teams,
            'activeTeam' => $activeTeam,
            'users' => $users,
            'selectedUser' => $selectedUser,
        ]);
    }
}
