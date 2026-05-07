<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Location;
use App\Models\Team;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class TeamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('manage-teams'), only: [
                'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
            ]),
            new Middleware(PermissionMiddleware::using('manage-team-users'), only: [
                'members',
            ]),
        ];
    }

    public function index()
    {
        return view('admin.teams.index');
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get();

        return view('admin.teams.create', compact('locations'));
    }

    public function store(TeamStoreRequest $request)
    {
        $team = Team::create($request->validated());

        if ($request->has('locations')) {
            $team->locations()->sync($request->input('locations', []));
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol aangemaakt.');
    }

    public function show(Team $team)
    {
        $team->load(['users', 'leaders', 'locations', 'actionPoints']);

        return view('admin.teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $team->load('locations');
        $locations = Location::orderBy('name')->get();

        return view('admin.teams.edit', compact('team', 'locations'));
    }

    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->update($request->validated());

        if ($request->has('locations')) {
            $team->locations()->sync($request->input('locations', []));
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol bijgewerkt.');
    }

    public function destroy(Team $team)
    {
        $team->users()->detach();
        $team->leaders()->detach();
        $team->locations()->detach();
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol verwijderd.');
    }

    public function members(Team $team)
    {
        $team->load(['users.locations', 'leaders.locations', 'locations']);

        return view('admin.teams.members', compact('team'));
    }
}
