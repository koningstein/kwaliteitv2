<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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
        $user  = auth()->user();
        $seeAll = $user?->hasPermissionTo('view-all-action-points');

        // Bepaal beschikbare teams voor deze gebruiker
        $teams = $user?->managedTeams->isNotEmpty()
            ? $user->managedTeams
            : $user?->teams;

        // Bepaal actief team via ?team= param, of eerste team als fallback
        $requestedTeamId = request()->query('team');
        $activeTeam = $requestedTeamId
            ? $teams?->firstWhere('id', $requestedTeamId)
            : null;
        $activeTeam = $activeTeam ?? $teams?->first();
        $teamId = $seeAll ? null : $activeTeam?->id;

        $periods = ReportingPeriod::orderBy('sort_order')->get();

        $theme->load([
            'standards'                        => fn ($q) => $q->orderBy('code'),
            'standards.theme',
            'standards.criteria'               => fn ($q) => $q->orderBy('number'),
            'standards.criteria.indicators'    => fn ($q) => $q->orderBy('sort_order'),
            'standards.criteria.scores'        => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.scores.reportingPeriod',
            'standards.criteria.actionPoints'  => fn ($q) => $teamId
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.actionPoints.status',
            'standards.criteria.actionPoints.user',
            'standards.criteria.actionPoints.evaluations',
        ]);

        return view('teacher.themes.show', [
            'theme'      => $theme,
            'periods'    => $periods,
            'teams'      => $teams,
            'activeTeam' => $activeTeam,
            'teamId'     => $teamId,
        ]);
    }
}
