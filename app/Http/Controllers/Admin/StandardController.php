<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StandardStoreRequest;
use App\Http\Requests\StandardUpdateRequest;
use App\Models\Standard;
use App\Models\Theme;

class StandardController extends Controller
{
    public function index()
    {
        return view('admin.standards.index');
    }

    public function create()
    {
        $themes = Theme::orderBy('name')->get();

        return view('admin.standards.create', compact('themes'));
    }

    public function store(StandardStoreRequest $request)
    {
        Standard::create($request->validated());

        return redirect()->route('admin.standards.index')
            ->with('success', 'Standaard succesvol aangemaakt.');
    }

    public function edit(Standard $standard)
    {
        $standard->load('theme');
        $themes = Theme::orderBy('name')->get();

        return view('admin.standards.edit', compact('standard', 'themes'));
    }

    public function update(StandardUpdateRequest $request, Standard $standard)
    {
        $standard->update($request->validated());

        return redirect()->route('admin.standards.index')
            ->with('success', 'Standaard succesvol bijgewerkt.');
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return redirect()->route('admin.standards.index')
            ->with('success', 'Standaard succesvol verwijderd.');
    }
}
