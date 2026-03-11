<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriterionScoreStoreRequest;
use App\Http\Requests\CriterionScoreUpdateRequest;
use App\Models\Criterion;
use App\Models\CriterionScore;
use App\Models\ReportingPeriod;

class CriterionScoreController extends Controller
{
    public function index()
    {
        return view('admin.criterion-scores.index');
    }

    public function create()
    {
        $criteria         = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();
        $reportingPeriods = ReportingPeriod::orderBy('sort_order')->orderBy('label')->get();

        return view('admin.criterion-scores.create', compact('criteria', 'reportingPeriods'));
    }

    public function store(CriterionScoreStoreRequest $request)
    {
        $data             = $request->validated();
        $data['updated_by'] = auth()->user()?->id;

        CriterionScore::create($data);

        return redirect()->route('admin.criterion-scores.index')
            ->with('success', 'Criteriumscore succesvol aangemaakt.');
    }

    public function edit(CriterionScore $criterionScore)
    {
        $criteria         = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();
        $reportingPeriods = ReportingPeriod::orderBy('sort_order')->orderBy('label')->get();

        return view('admin.criterion-scores.edit', compact('criterionScore', 'criteria', 'reportingPeriods'));
    }

    public function update(CriterionScoreUpdateRequest $request, CriterionScore $criterionScore)
    {
        $data             = $request->validated();
        $data['updated_by'] = auth()->user()?->id;

        $criterionScore->update($data);

        return redirect()->route('admin.criterion-scores.index')
            ->with('success', 'Criteriumscore succesvol bijgewerkt.');
    }

    public function destroy(CriterionScore $criterionScore)
    {
        $criterionScore->delete();

        return redirect()->route('admin.criterion-scores.index')
            ->with('success', 'Criteriumscore succesvol verwijderd.');
    }
}
