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
        $user   = auth()->user();
        $teamId = $user?->teams->first()?->id;

        // O&K en directie zien alle periodes; anderen zien ook alle periodes
        // maar de scores/actiepunten worden per team gefilterd
        $periods = ReportingPeriod::orderBy('sort_order')->get();

        $theme->load([
            'standards'                        => fn ($q) => $q->orderBy('code'),
            'standards.theme',
            'standards.criteria'               => fn ($q) => $q->orderBy('number'),
            'standards.criteria.indicators'    => fn ($q) => $q->orderBy('sort_order'),
            // Scores gefilterd op eigen team (O&K/directie ziet alles)
            'standards.criteria.scores'        => fn ($q) => ($teamId && !$user->hasRole(['ok_medewerker', 'directie']))
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.scores.reportingPeriod',
            // Actiepunten gefilterd op eigen team
            'standards.criteria.actionPoints'  => fn ($q) => ($teamId && !$user->hasRole(['ok_medewerker', 'directie']))
                ? $q->where('team_id', $teamId)
                : $q,
            'standards.criteria.actionPoints.status',
            'standards.criteria.actionPoints.user',
            'standards.criteria.actionPoints.evaluations',
        ]);

        return view('teacher.themes.show', [
            'theme'   => $theme,
            'periods' => $periods,
        ]);
    }
}
