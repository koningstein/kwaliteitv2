<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Models\ReportingPeriod;
use App\Models\Theme;
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

    public function index()
    {
        $themes = Theme::withCount(['standards'])
            ->with('standards.criteria')
            ->get();

        return view('teacher.themes.index', [
            'themes' => $themes,
        ]);
    }

    public function show(Theme $theme)
    {
        $user         = auth()->user();
        $seeAll       = $user?->hasPermissionTo('view-all-action-points');
        $isMedewerker = $user?->hasPermissionTo('edit-own-action-point-status')
                     || $user?->hasPermissionTo('edit-own-action-point-dates');

        if ($isMedewerker) {
            // Medewerker heeft geen team-tabs; ziet eigen actiepunten via user_id
            $teams      = collect();
            $activeTeam = null;
            $teamId     = null;
        } else {
            // Bepaal beschikbare teams voor deze gebruiker
            $managed = $user?->managedTeams;
            $teams   = $managed?->isNotEmpty() ? $managed : $user?->teams;

            // Bepaal actief team via ?team= param, of eerste team als fallback
            $requestedTeamId = request()->query('team');
            $activeTeam = $requestedTeamId
                ? $teams?->firstWhere('id', $requestedTeamId)
                : null;
            $activeTeam = $activeTeam ?? $teams?->first();
            $teamId     = $seeAll ? null : $activeTeam?->id;
        }

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
            // Actiepunten: medewerker ziet alleen eigen, anderen gefilterd op team
            'standards.criteria.actionPoints'  => function ($q) use ($isMedewerker, $teamId, $user) {
                if ($isMedewerker) {
                    $q->where('user_id', $user->id);
                } elseif ($teamId) {
                    $q->where('team_id', $teamId);
                }
            },
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
