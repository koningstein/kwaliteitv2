<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\CriterionScore;
use App\Models\ReportingPeriod;
use App\Models\Theme;

class DashboardController extends Controller
{
    public function index()
    {
        // Action point stats per status
        $statuses = ActionPointStatus::withCount('actionPoints')->get();

        $totalActionPoints = ActionPoint::count();

        // Voortgang per rapportageperiode (altijd alle periodes, ook zonder is_active)
        $periods = ReportingPeriod::orderBy('sort_order')
            ->get();

        $periodStats = $periods->map(function ($period) {
            $scores = CriterionScore::where('reporting_period_id', $period->id)->get();
            $total = $scores->count();

            if ($total === 0) {
                return [
                    'period'      => $period,
                    'total'       => 0,
                    'sufficient'  => 0,
                    'attention'   => 0,
                    'insufficient' => 0,
                    'pct_sufficient'  => 0,
                    'pct_attention'   => 0,
                    'pct_insufficient' => 0,
                ];
            }

            $sufficient  = $scores->where('status', 'sufficient')->count();
            $attention   = $scores->where('status', 'attention')->count();
            $insufficient = $scores->where('status', 'insufficient')->count();

            return [
                'period'          => $period,
                'total'           => $total,
                'sufficient'      => $sufficient,
                'attention'       => $attention,
                'insufficient'    => $insufficient,
                'pct_sufficient'  => round($sufficient / $total * 100),
                'pct_attention'   => round($attention / $total * 100),
                'pct_insufficient' => round($insufficient / $total * 100),
            ];
        });

        // Voortgang per thema (actieve periodes samen)
        $activePeriodIds = $periods->pluck('id');

        $themes = Theme::with(['standards.criteria.scores' => function ($q) use ($activePeriodIds) {
            $q->whereIn('reporting_period_id', $activePeriodIds);
        }])->get();

        $themeStats = $themes->map(function ($theme) {
            $scores = $theme->standards->flatMap(
                fn ($s) => $s->criteria->flatMap(fn ($c) => $c->scores)
            );
            $total = $scores->count();

            if ($total === 0) {
                return [
                    'theme'           => $theme,
                    'total'           => 0,
                    'pct_sufficient'  => 0,
                    'pct_attention'   => 0,
                    'pct_insufficient' => 0,
                ];
            }

            return [
                'theme'            => $theme,
                'total'            => $total,
                'sufficient'       => $scores->where('status', 'sufficient')->count(),
                'attention'        => $scores->where('status', 'attention')->count(),
                'insufficient'     => $scores->where('status', 'insufficient')->count(),
                'pct_sufficient'   => round($scores->where('status', 'sufficient')->count() / $total * 100),
                'pct_attention'    => round($scores->where('status', 'attention')->count() / $total * 100),
                'pct_insufficient' => round($scores->where('status', 'insufficient')->count() / $total * 100),
            ];
        });

        return view('teacher.dashboard', [
            'statuses'         => $statuses,
            'totalActionPoints' => $totalActionPoints,
            'periodStats'      => $periodStats,
            'themeStats'       => $themeStats,
        ]);
    }
}
