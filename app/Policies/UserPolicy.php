<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Gebruikerslijst inzien: alleen kwaliteitszorg en O&K
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['kwaliteitszorg', 'ok_medewerker']);
    }

    /**
     * Specifieke gebruiker inzien: kwaliteitszorg (eigen team) of O&K
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('ok_medewerker')) {
            return true;
        }

        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('manage-team-users')) {
            // Mag alleen gebruikers van zijn eigen team inzien
            $userTeamIds = $user->teams->pluck('id');
            $modelTeamIds = $model->teams->pluck('id');
            return $userTeamIds->intersect($modelTeamIds)->isNotEmpty();
        }

        return false;
    }

    /**
     * Gebruiker aanmaken: kwaliteitszorg (voor eigen team) of O&K
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['kwaliteitszorg', 'ok_medewerker'])
            && $user->hasPermissionTo('manage-team-users');
    }

    /**
     * Gebruiker bewerken: kwaliteitszorg (eigen team) of O&K
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('ok_medewerker')) {
            return true;
        }

        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('manage-team-users')) {
            $userTeamIds = $user->teams->pluck('id');
            $modelTeamIds = $model->teams->pluck('id');
            return $userTeamIds->intersect($modelTeamIds)->isNotEmpty();
        }

        return false;
    }

    /**
     * Gebruiker verwijderen: kwaliteitszorg (eigen team) of O&K
     */
    public function delete(User $user, User $model): bool
    {
        // Niemand mag zichzelf verwijderen
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
     * Kwaliteitsmedewerker toewijzen aan team: O&K of onderwijsleider
     */
    public function assignQualityMember(User $user): bool
    {
        return $user->hasPermissionTo('assign-team-quality-member');
    }
}
