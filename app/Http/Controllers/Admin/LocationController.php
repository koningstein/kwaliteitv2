<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        return view('admin.locations.index');
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(LocationStoreRequest $request)
    {
        Location::create($request->validated());

        return redirect()->route('admin.locations.index')
            ->with('success', 'Locatie succesvol aangemaakt.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(LocationUpdateRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()->route('admin.locations.index')
            ->with('success', 'Locatie succesvol bijgewerkt.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Locatie succesvol verwijderd.');
    }
}
