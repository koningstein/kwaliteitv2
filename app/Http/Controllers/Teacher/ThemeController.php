<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ReportingPeriod;
use App\Models\Theme;

class ThemeController extends Controller
{
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
        $periods = ReportingPeriod::orderBy('sort_order')
            ->get();

        $theme->load([
            'standards' => fn ($q) => $q->orderBy('code'),
            'standards.theme',
            'standards.criteria' => fn ($q) => $q->orderBy('number'),
            'standards.criteria.indicators' => fn ($q) => $q->orderBy('sort_order'),
            'standards.criteria.scores.reportingPeriod',
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
