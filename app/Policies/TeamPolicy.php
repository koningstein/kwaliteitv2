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
        if (! $user->hasPermissionTo('view-team-dashboard')) {
            return false;
        }

        // ok_medewerker en directie met manage-teams mogen alle teams zien
        if ($user->hasPermissionTo('manage-teams')) {
            return true;
        }

        // Onderwijsleider heeft assign-team-quality-member: ziet alleen zijn beheerde teams
        if ($user->hasPermissionTo('assign-team-quality-member')) {
            return $user->managedTeams->contains($team->id);
        }

        // Overige rollen (medewerker, kwaliteitszorg): alleen eigen teams
        return $user->teams->contains($team->id);
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
