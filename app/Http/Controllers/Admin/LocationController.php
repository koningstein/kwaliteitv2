<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class LocationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('manage-teams'), only: [
                'index', 'show', 'create', 'store', 'edit', 'update', 'destroy',
                'attachTeam', 'detachTeam', 'attachUser', 'detachUser',
            ]),
        ];
    }

    public function index()
    {
        return view('admin.locations.index');
    }

    public function show(Location $location)
    {
        $location->load([
            'teams.users.roles',
            'teams.leaders',
            'users.roles',
        ]);

        $attachedTeamIds = $location->teams->pluck('id');

        $availableTeams = Team::whereNotIn('id', $attachedTeamIds)
            ->orderBy('name')
            ->get();

        // Users die al via een gekoppeld team lid zijn van deze locatie
        $teamUserIds = $location->teams->flatMap(fn($t) => $t->users->pluck('id'));
        // Users die direct gekoppeld zijn
        $directUserIds = $location->users->pluck('id');
        // Alle users al op deze locatie (team of direct)
        $allLocationUserIds = $teamUserIds->merge($directUserIds)->unique();

        $availableUsers = User::whereNotIn('id', $allLocationUserIds)
            ->orderBy('name')
            ->get();

        // Direct gekoppelde users = location_user maar NIET via een team
        $directUsers = $location->users->whereNotIn('id', $teamUserIds->all());

        return view('admin.locations.show', compact(
            'location',
            'availableTeams',
            'availableUsers',
            'directUsers',
        ));
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

    public function attachTeam(Request $request, Location $location)
    {
        $request->validate(['team_id' => 'required|exists:teams,id']);
        $team = Team::findOrFail($request->team_id);
        // Enforce businessregel: team heeft maar 1 locatie
        // Ontkoppel van eventuele andere locaties eerst
        $team->locations()->detach();

        // Koppel team aan deze locatie
        $location->teams()->attach($team->id);

        // Teamleden automatisch ook aan location_user koppelen (union, niet overschrijven)
        $existingUserIds = $location->users()->pluck('users.id')->toArray();
        $teamUserIds = $team->users()->pluck('users.id')->toArray();
        $newUserIds = array_diff($teamUserIds, $existingUserIds);

        if (!empty($newUserIds)) {
            $location->users()->attach($newUserIds);
        }

        return redirect()->route('admin.locations.show', $location)
            ->with('success', "Team \"{$team->name}\" gekoppeld aan locatie \"{$location->name}\".");
    }

    public function detachTeam(Location $location, Team $team)
    {
        // Ontkoppel het team
        $location->teams()->detach($team->id);

        // Verwijder teamleden uit location_user als ze niet via een ander team nog gekoppeld zijn
        // en niet direct gekoppeld waren
        $remainingTeamUserIds = $location->teams()
            ->with('users')
            ->get()
            ->flatMap(fn($t) => $t->users->pluck('id'))
            ->unique()
            ->toArray();

        $teamUserIds = $team->users()->pluck('users.id')->toArray();

        // Users die alleen via dit team waren gekoppeld (niet via ander team)
        $toRemove = array_diff($teamUserIds, $remainingTeamUserIds);

        if (!empty($toRemove)) {
            $location->users()->detach($toRemove);
        }

        return redirect()->route('admin.locations.show', $location)
            ->with('success', "Team \"{$team->name}\" ontkoppeld van locatie \"{$location->name}\".");
    }

    public function attachUser(Request $request, Location $location)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->user_id);
        if (!$location->users()->where('users.id', $user->id)->exists()) {
            $location->users()->attach($user->id);
        }

        return redirect()->route('admin.locations.show', $location)
            ->with('success', "\"{$user->name}\" gekoppeld aan locatie \"{$location->name}\".");
    }

    public function detachUser(Location $location, User $user)
    {
        $location->users()->detach($user->id);

        return redirect()->route('admin.locations.show', $location)
            ->with('success', "\"{$user->name}\" ontkoppeld van locatie \"{$location->name}\".");
    }
}
