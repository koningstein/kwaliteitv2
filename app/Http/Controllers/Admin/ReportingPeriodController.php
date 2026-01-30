<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportingPeriodStoreRequest;
use App\Http\Requests\ReportingPeriodUpdateRequest;
use App\Models\ReportingPeriod;

class ReportingPeriodController extends Controller
{
    public function index()
    {
        return view('admin.reporting-periods.index');
    }

    public function create()
    {
        return view('admin.reporting-periods.create');
    }

    public function store(ReportingPeriodStoreRequest $request)
    {
        ReportingPeriod::create($request->validated());

        return redirect()->route('admin.reporting-periods.index')
            ->with('success', 'Rapportage periode succesvol aangemaakt.');
    }

    public function edit(ReportingPeriod $reportingPeriod)
    {
        return view('admin.reporting-periods.edit', compact('reportingPeriod'));
    }

    public function update(ReportingPeriodUpdateRequest $request, ReportingPeriod $reportingPeriod)
    {
        $reportingPeriod->update($request->validated());

        return redirect()->route('admin.reporting-periods.index')
            ->with('success', 'Rapportage periode succesvol bijgewerkt.');
    }

    public function destroy(ReportingPeriod $reportingPeriod)
    {
        $reportingPeriod->delete();

        return redirect()->route('admin.reporting-periods.index')
            ->with('success', 'Rapportage periode succesvol verwijderd.');
    }
}
