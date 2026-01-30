<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriterionStoreRequest;
use App\Http\Requests\CriterionUpdateRequest;
use App\Models\Criterion;
use App\Models\Standard;

class CriterionController extends Controller
{
    public function index()
    {
        return view('admin.criteria.index');
    }

    public function create()
    {
        $standards = Standard::with('theme')->orderBy('code')->get();

        return view('admin.criteria.create', compact('standards'));
    }

    public function store(CriterionStoreRequest $request)
    {
        Criterion::create($request->validated());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criterium succesvol aangemaakt.');
    }

    public function edit(Criterion $criterion)
    {
        $criterion->load('standard.theme');
        $standards = Standard::with('theme')->orderBy('code')->get();

        return view('admin.criteria.edit', compact('criterion', 'standards'));
    }

    public function update(CriterionUpdateRequest $request, Criterion $criterion)
    {
        $criterion->update($request->validated());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criterium succesvol bijgewerkt.');
    }

    public function destroy(Criterion $criterion)
    {
        $criterion->delete();

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Criterium succesvol verwijderd.');
    }
}
