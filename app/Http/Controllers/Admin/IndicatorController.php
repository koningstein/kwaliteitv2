<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndicatorStoreRequest;
use App\Http\Requests\IndicatorUpdateRequest;
use App\Models\Criterion;
use App\Models\Indicator;

class IndicatorController extends Controller
{
    public function index()
    {
        return view('admin.indicators.index');
    }

    public function create()
    {
        $criteria = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();

        return view('admin.indicators.create', compact('criteria'));
    }

    public function store(IndicatorStoreRequest $request)
    {
        Indicator::create($request->validated());

        return redirect()->route('admin.indicators.index')
            ->with('success', 'Indicator succesvol aangemaakt.');
    }

    public function edit(Indicator $indicator)
    {
        $indicator->load('criterion.standard.theme');
        $criteria = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();

        return view('admin.indicators.edit', compact('indicator', 'criteria'));
    }

    public function update(IndicatorUpdateRequest $request, Indicator $indicator)
    {
        $indicator->update($request->validated());

        return redirect()->route('admin.indicators.index')
            ->with('success', 'Indicator succesvol bijgewerkt.');
    }

    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return redirect()->route('admin.indicators.index')
            ->with('success', 'Indicator succesvol verwijderd.');
    }
}
