<?php

namespace App\Policies;

use App\Models\ActionPoint;
use App\Models\User;

class ActionPointPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-action-points');
    }

    public function view(User $user, ActionPoint $actionPoint): bool
    {
        if (!$user->hasPermissionTo('view-action-points')) {
            return false;
        }

        // O&K en directie mogen alles inzien
        if ($user->hasRole(['ok_medewerker', 'directie'])) {
            return true;
        }

        // Kwaliteitszorg en onderwijsleider: alleen eigen team
        if ($user->hasRole(['kwaliteitszorg', 'onderwijsleider'])) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        // Medewerker: alleen eigen toegewezen actiepunten
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Alleen kwaliteitszorg mag actiepunten aanmaken (voor zijn eigen team)
        return $user->hasRole('kwaliteitszorg')
            && $user->hasPermissionTo('create-action-points');
    }

    public function update(User $user, ActionPoint $actionPoint): bool
    {
        // Kwaliteitszorg: mag actiepunten van eigen team bewerken
        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('edit-action-points')) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        // Medewerker: mag eigen actiepunt status of datums wijzigen
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && ($user->hasPermissionTo('edit-own-action-point-status')
                    || $user->hasPermissionTo('edit-own-action-point-dates'));
        }

        return false;
    }

    /**
     * Beschrijving bewerken: alleen kwaliteitszorg van eigen team
     */
    public function updateDescription(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('edit-action-points')) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        return false;
    }

    /**
     * Status bewerken: kwaliteitszorg (eigen team) of medewerker (eigen actiepunt)
     */
    public function updateStatus(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('edit-action-points')) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && $user->hasPermissionTo('edit-own-action-point-status');
        }

        return false;
    }

    /**
     * Datums bewerken: kwaliteitszorg (eigen team) of medewerker (eigen actiepunt)
     */
    public function updateDates(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('edit-action-points')) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && $user->hasPermissionTo('edit-own-action-point-dates');
        }

        return false;
    }

    public function delete(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('kwaliteitszorg') && $user->hasPermissionTo('delete-action-points')) {
            return $actionPoint->team_id !== null
                && $user->teams->contains($actionPoint->team_id);
        }

        return false;
    }

    public function restore(User $user, ActionPoint $actionPoint): bool
    {
        return $this->delete($user, $actionPoint);
    }

    public function forceDelete(User $user, ActionPoint $actionPoint): bool
    {
        return $this->delete($user, $actionPoint);
    }
}
