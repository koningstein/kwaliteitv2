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
        // Medewerkers mogen alleen hun eigen actiepunten zien
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id;
        }

        return $user->hasPermissionTo('view-action-points');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-action-points');
    }

    public function update(User $user, ActionPoint $actionPoint): bool
    {
        // Medewerkers mogen eigen actiepunt status en datums wijzigen (niet beschrijving)
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && ($user->hasPermissionTo('edit-own-action-point-status')
                    || $user->hasPermissionTo('edit-own-action-point-dates'));
        }

        return $user->hasPermissionTo('edit-action-points');
    }

    public function updateDescription(User $user, ActionPoint $actionPoint): bool
    {
        // Medewerkers mogen de beschrijving NIET wijzigen
        if ($user->hasRole('medewerker')) {
            return false;
        }

        return $user->hasPermissionTo('edit-action-points');
    }

    public function updateStatus(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && $user->hasPermissionTo('edit-own-action-point-status');
        }

        return $user->hasPermissionTo('edit-action-points');
    }

    public function updateDates(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasRole('medewerker')) {
            return $actionPoint->user_id === $user->id
                && $user->hasPermissionTo('edit-own-action-point-dates');
        }

        return $user->hasPermissionTo('edit-action-points');
    }

    public function delete(User $user, ActionPoint $actionPoint): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }

    public function restore(User $user, ActionPoint $actionPoint): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }

    public function forceDelete(User $user, ActionPoint $actionPoint): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }
}
