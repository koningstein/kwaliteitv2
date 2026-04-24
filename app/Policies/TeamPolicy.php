<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-team-dashboard');
    }

    public function view(User $user, Team $team): bool
    {
        // Onderwijsleider en medewerkers mogen alleen hun eigen teams zien
        if ($user->hasRole('onderwijsleider')) {
            return $user->managedTeams->contains($team->id);
        }

        if ($user->hasRole('medewerker') || $user->hasRole('kwaliteitszorg')) {
            return $user->teams->contains($team->id);
        }

        return $user->hasPermissionTo('view-team-dashboard');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-teams');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage-teams');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage-teams');
    }

    public function restore(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage-teams');
    }

    public function forceDelete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage-teams');
    }
}
