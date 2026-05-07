<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\CriterionScore;
use App\Models\ReportingPeriod;
use App\Models\Team;
use App\Models\Theme;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-dashboard'),
        ];
    }

    public function index(Request $request)
    {
        $user           = auth()->user();
        $isGlobalViewer = $user?->hasRole(['ok_medewerker', 'directie']);
        $isMedewerker   = $user?->hasPermissionTo('edit-own-action-point-status')
                       || $user?->hasPermissionTo('edit-own-action-point-dates');

        // Beschikbare teams ophalen — zelfde logica als TeamController
        if ($isGlobalViewer) {
            $teams = Team::orderBy('name')->get();
        } else {
            $managed = $user?->managedTeams()->orderBy('name')->get() ?? collect();
            $teams   = $managed->isNotEmpty()
                ? $managed
                : ($user?->teams()->orderBy('name')->get() ?? collect());
        }

        // Actief team bepalen via ?team= queryparameter
        $teamIdParam = $request->query('team');
        $activeTeam  = $teamIdParam
            ? $teams->firstWhere('id', (int) $teamIdParam) ?? $teams->first()
            : $teams->first();

        $teamId = $activeTeam?->id;

        // Actiepunten statistieken
        $statuses = ActionPointStatus::withCount([
            'actionPoints' => fn ($q) => (!$isGlobalViewer && $teamId)
                ? $q->where('team_id', $teamId)
                : $q,
        ])->get();

        $totalActionPoints = ($isGlobalViewer && !$teamId)
            ? ActionPoint::count()
            : ActionPoint::where('team_id', $teamId)->count();

        // Rapportageperiodes
        $periods = ReportingPeriod::orderBy('sort_order')->get();

        // Statistieken per rapportageperiode
        $periodStats = $periods->map(function ($period) use ($teamId, $isGlobalViewer) {
            $query = CriterionScore::where('reporting_period_id', $period->id);

            if (!$isGlobalViewer && $teamId) {
                $query->where('team_id', $teamId);
            } elseif ($teamId) {
                $query->where('team_id', $teamId);
            }

            $scores = $query->get();
            $total  = $scores->count();

            if ($total === 0) {
                return [
                    'period'           => $period,
                    'total'            => 0,
                    'sufficient'       => 0,
                    'attention'        => 0,
                    'insufficient'     => 0,
                    'pct_sufficient'   => 0,
                    'pct_attention'    => 0,
                    'pct_insufficient' => 0,
                ];
            }

            $sufficient   = $scores->where('status', 'sufficient')->count();
            $attention    = $scores->where('status', 'attention')->count();
            $insufficient = $scores->where('status', 'insufficient')->count();

            return [
                'period'           => $period,
                'total'            => $total,
                'sufficient'       => $sufficient,
                'attention'        => $attention,
                'insufficient'     => $insufficient,
                'pct_sufficient'   => round($sufficient / $total * 100),
                'pct_attention'    => round($attention / $total * 100),
                'pct_insufficient' => round($insufficient / $total * 100),
            ];
        });

        // Statistieken per thema
        $activePeriodIds = $periods->pluck('id');

        $themes = Theme::with(['standards.criteria.scores' => function ($q) use ($activePeriodIds, $teamId) {
            $q->whereIn('reporting_period_id', $activePeriodIds);

            if ($teamId) {
                $q->where('team_id', $teamId);
            }
        }])->get();

        $themeStats = $themes->map(function ($theme) {
            $scores = $theme->standards->flatMap(
                fn ($s) => $s->criteria->flatMap(fn ($c) => $c->scores)
            );
            $total = $scores->count();

            if ($total === 0) {
                return [
                    'theme'            => $theme,
                    'total'            => 0,
                    'sufficient'       => 0,
                    'attention'        => 0,
                    'insufficient'     => 0,
                    'pct_sufficient'   => 0,
                    'pct_attention'    => 0,
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
        })->filter(fn ($stat) => $stat['total'] > 0)->values();

        // Persoonlijke actiepunten voor medewerkers
        $myActionPoints = $isMedewerker
            ? ActionPoint::with(['status', 'criterion.standard.theme'])
                ->where('user_id', $user->id)
                ->orderByRaw('end_date IS NULL, end_date ASC')
                ->get()
            : collect();

        return view('teacher.dashboard', [
            'teams'             => $teams,
            'activeTeam'        => $activeTeam,
            'statuses'          => $statuses,
            'totalActionPoints' => $totalActionPoints,
            'periodStats'       => $periodStats,
            'themeStats'        => $themeStats,
            'isMedewerker'      => $isMedewerker,
            'myActionPoints'    => $myActionPoints,
        ]);
    }

    public function exportPdf(Request $request)
    {
        abort_unless(auth()->user()?->hasPermissionTo('export-reports'), 403);

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

        $teamIdParam = $request->query('team');
        $activeTeam  = $teamIdParam
            ? $teams->firstWhere('id', (int) $teamIdParam) ?? $teams->first()
            : $teams->first();

        if (! $activeTeam) {
            abort(404);
        }

        $teamId = $activeTeam->id;

        // Teamleden, teamleiders, locaties en kwaliteitszorgmedewerkers
        $activeTeam->load(['users.roles', 'leaders', 'locations']);

        $teamMembers           = $activeTeam->users;
        $teamLeaders           = $activeTeam->leaders;
        $kwaliteitsMedewerkers = $activeTeam->users->filter(fn ($u) => $u->hasRole('kwaliteitszorg'));

        // Rapportageperiodes
        $periods = ReportingPeriod::orderBy('sort_order')->get();

        // Volledige thema-hiërarchie met scores en actiepunten voor dit team
        $themes = Theme::with([
            'standards'                                      => fn ($q) => $q->orderBy('code'),
            'standards.criteria'                             => fn ($q) => $q->orderBy('number'),
            'standards.criteria.indicators'                  => fn ($q) => $q->orderBy('sort_order'),
            'standards.criteria.scores'                      => fn ($q) => $q->where('team_id', $teamId),
            'standards.criteria.actionPoints'                => fn ($q) => $q->where('team_id', $teamId)
                ->with([
                    'status',
                    'user',
                    'evaluations' => fn ($eq) => $eq->with('creator')->orderBy('created_at'),
                ])
                ->orderBy('created_at'),
        ])->orderBy('code')->get();

        $pdf = Pdf::loadView('teacher.exports.dashboard-pdf', [
            'activeTeam'            => $activeTeam,
            'teamMembers'           => $teamMembers,
            'teamLeaders'           => $teamLeaders,
            'kwaliteitsMedewerkers' => $kwaliteitsMedewerkers,
            'locations'             => $activeTeam->locations,
            'periods'               => $periods,
            'themes'                => $themes,
            'exportDate'            => now()->format('d-m-Y'),
        ])->setPaper('a4', 'portrait');

        $filename = 'kwaliteit-in-beeld-' . str($activeTeam->name)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
