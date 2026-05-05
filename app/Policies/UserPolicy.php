<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Gebruikerslijst inzien: iedereen met manage-team-users
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage-team-users');
    }

    /**
     * Specifieke gebruiker inzien: manage-team-users + team-scope
     * ok_medewerker heeft manage-team-users én manage-teams → ziet iedereen
     */
    public function view(User $user, User $model): bool
    {
        if (! $user->hasPermissionTo('manage-team-users')) {
            return false;
        }

        // manage-teams = globale beheerdersrol (ok_medewerker): mag iedereen zien
        if ($user->hasPermissionTo('manage-teams')) {
            return true;
        }

        // Kwaliteitszorg: alleen gebruikers van eigen team
        $userTeamIds = $user->teams->pluck('id');
        $modelTeamIds = $model->teams->pluck('id');

        return $userTeamIds->intersect($modelTeamIds)->isNotEmpty();
    }

    /**
     * Gebruiker aanmaken: manage-team-users
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-team-users');
    }

    /**
     * Gebruiker bewerken: manage-team-users + team-scope
     */
    public function update(User $user, User $model): bool
    {
        if (! $user->hasPermissionTo('manage-team-users')) {
            return false;
        }

        // manage-teams = globale beheerdersrol (ok_medewerker): mag iedereen bewerken
        if ($user->hasPermissionTo('manage-teams')) {
            return true;
        }

        // Kwaliteitszorg: alleen gebruikers van eigen team
        $userTeamIds = $user->teams->pluck('id');
        $modelTeamIds = $model->teams->pluck('id');

        return $userTeamIds->intersect($modelTeamIds)->isNotEmpty();
    }

    /**
     * Gebruiker verwijderen: manage-team-users + team-scope + niet zichzelf
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $this->update($user, $model);
    }

    public function restore(User $user, User $model): bool
    {
        return $this->update($user, $model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    /**
     * Kwaliteitsmedewerker toewijzen aan team: assign-team-quality-member
     */
    public function assignQualityMember(User $user): bool
    {
        return $user->hasPermissionTo('assign-team-quality-member');
    }
}
