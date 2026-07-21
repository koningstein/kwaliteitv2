<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Models\ReportingPeriod;
use App\Models\Team;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ThemeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-themes'),
        ];
    }

    public function index(Request $request)
    {
        $user           = auth()->user();
        $isGlobalViewer = $user?->hasRole(['ok_medewerker', 'directie']);

        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } else {
            $managed = $user?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams   = $managed->isNotEmpty()
                ? $managed
                : ($user?->teams()->orderBy('name')->get() ?? collect());
        }

        $teams->load('locations');

        $teamIdParam = $request->query('team');
        if ($teamIdParam) {
            $activeTeam = $teams->firstWhere('id', (int) $teamIdParam) ?? $teams->first();
            session(['active_team_id' => $activeTeam?->id]);
        } else {
            $storedId   = session('active_team_id');
            $activeTeam = ($storedId ? $teams->firstWhere('id', $storedId) : null) ?? $teams->first();
        }

        $themes = Theme::withCount(['standards'])
            ->with('standards.criteria')
            ->get();

        return view('teacher.themes.index', [
            'themes'     => $themes,
            'teams'      => $teams,
            'activeTeam' => $activeTeam,
        ]);
    }

    public function show(Theme $theme, Request $request)
    {
        $user         = auth()->user();
        $isGlobalViewer = $user?->hasRole(['ok_medewerker', 'directie']);
        $isMedewerker = $user?->hasPermissionTo('edit-own-action-point-status')
                     || $user?->hasPermissionTo('edit-own-action-point-dates');

        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } else {
            $managed = $user?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams   = $managed->isNotEmpty()
                ? $managed
                : ($user?->teams()->orderBy('name')->get() ?? collect());
        }

        $teams->load('locations');

        $requestedTeamId = $request->query('team');
        if ($requestedTeamId) {
            $activeTeam = $teams->firstWhere('id', (int) $requestedTeamId) ?? $teams->first();
            session(['active_team_id' => $activeTeam?->id]);
        } else {
            $storedId   = session('active_team_id');
            $activeTeam = ($storedId ? $teams->firstWhere('id', $storedId) : null) ?? $teams->first();
        }
        $teamId = $activeTeam?->id;

        $periods = ReportingPeriod::orderBy('sort_order')->get();

        $theme->load([
            'standards'                        => fn ($q) => $q->orderBy('code'),
            'standards.theme',
            'standards.criteria'               => fn ($q) => $q->orderBy('number'),
            'standards.criteria.indicators'    => fn ($q) => $q->orderBy('sort_order'),
            // Scores zijn team-gebonden — altijd tonen, gefilterd op team indien van toepassing
            'standards.criteria.scores'        => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.scores.reportingPeriod',
            // Actiepunten gefilterd op actief team
            'standards.criteria.actionPoints'  => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.actionPoints.status',
            'standards.criteria.actionPoints.user',
            'standards.criteria.actionPoints.evaluations',
        ]);

        // open_criterion: automatisch de juiste standaard + criterium openvouwen
        $openCriterionId = (int) request()->query('open_criterion') ?: null;
        $openStandardId  = $openCriterionId
            ? Criterion::find($openCriterionId)?->standard_id
            : null;

        return view('teacher.themes.show', [
            'theme'           => $theme,
            'periods'         => $periods,
            'teams'           => $teams,
            'activeTeam'      => $activeTeam,
            'teamId'          => $teamId,
            'isMedewerker'    => $isMedewerker,
            'openCriterionId' => $openCriterionId,
            'openStandardId'  => $openStandardId,
        ]);
    }
}
