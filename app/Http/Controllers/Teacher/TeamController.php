<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-team-dashboard'),
        ];
    }

    public function index(Request $request)
    {
        $authUser = auth()->user();
        $isGlobalViewer = $authUser?->hasPermissionTo('view-all-action-points');

        // Bepaal de beschikbare teams
        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } else {
            // Onderwijsleider heeft managedTeams, kwaliteitszorg/medewerker heeft teams
            $managed = $authUser?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams = $managed->isNotEmpty()
                ? $managed
                : ($authUser?->teams()->orderBy('name')->get() ?? collect());
        }

        // Geen teams: toon melding
        if ($teams->isEmpty()) {
            return view('teacher.team.index', [
                'teams' => $teams,
                'activeTeam' => null,
                'users' => collect(),
            ]);
        }

        $teams->load('locations');

        // Actief team bepalen: URL-param heeft prioriteit, daarna sessie, dan eerste team
        $teamIdParam = $request->query('team');
        if ($teamIdParam) {
            $activeTeam = $teams->firstWhere('id', (int) $teamIdParam) ?? $teams->first();
            session(['active_team_id' => $activeTeam->id]);
        } else {
            $storedId   = session('active_team_id');
            $activeTeam = ($storedId ? $teams->firstWhere('id', $storedId) : null) ?? $teams->first();
        }

        // Teamleden ophalen voor het actieve team
        $users = User::with([
            'actionPoints.status',
            'actionPoints.criterion.standard.theme',
            'teams',
        ])
            ->withoutTrashed()
            ->whereHas('teams', fn ($q) => $q->where('teams.id', $activeTeam->id))
            ->orderBy('name')
            ->get();

        return view('teacher.team.index', [
            'teams' => $teams,
            'activeTeam' => $activeTeam,
            'users' => $users,
        ]);
    }
}
