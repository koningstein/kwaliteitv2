<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        return view('admin.teams.index');
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.teams.create', compact('users'));
    }

    public function store(TeamStoreRequest $request)
    {
        $team = Team::create($request->validated());

        if ($request->has('users')) {
            $team->users()->sync($request->input('users', []));
        }

        if ($request->has('leaders')) {
            $team->leaders()->sync($request->input('leaders', []));
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol aangemaakt.');
    }

    public function show(Team $team)
    {
        $team->load(['users', 'leaders', 'actionPoints']);

        return view('admin.teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $team->load(['users', 'leaders']);
        $users = User::orderBy('name')->get();

        return view('admin.teams.edit', compact('team', 'users'));
    }

    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->update($request->validated());

        if ($request->has('users')) {
            $team->users()->sync($request->input('users', []));
        }

        if ($request->has('leaders')) {
            $team->leaders()->sync($request->input('leaders', []));
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol bijgewerkt.');
    }

    public function destroy(Team $team)
    {
        $team->users()->detach();
        $team->leaders()->detach();
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team succesvol verwijderd.');
    }
}
