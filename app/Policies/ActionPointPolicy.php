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
        if (! $user->hasPermissionTo('view-action-points')) {
            return false;
        }

        // view-all-action-points: ziet alles (ok_medewerker, directie)
        if ($user->hasPermissionTo('view-all-action-points')) {
            return true;
        }

        // Medewerker-scope: edit-own-* permissions → alleen eigen toegewezen actiepunten
        if ($user->hasPermissionTo('edit-own-action-point-status') || $user->hasPermissionTo('edit-own-action-point-dates')) {
            return $actionPoint->user_id === $user->id;
        }

        // Iedereen met view-action-points maar zonder view-all: eigen team (kwaliteitszorg, onderwijsleider)
        return $actionPoint->team_id !== null
            && $user->teams->contains($actionPoint->team_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-action-points');
    }

    public function update(User $user, ActionPoint $actionPoint): bool
    {
        // Volledige bewerking: edit-action-points + eigen team of geleid team
        if ($user->hasPermissionTo('edit-action-points')) {
            if ($user->hasPermissionTo('view-all-action-points')) {
                return true;
            }

            return $actionPoint->team_id !== null && (
                $user->teams->contains($actionPoint->team_id) ||
                $user->managedTeams->contains($actionPoint->team_id)
            );
        }

        // Beperkte bewerking: medewerker mag status of datums van eigen actiepunt wijzigen
        if ($user->hasPermissionTo('edit-own-action-point-status') || $user->hasPermissionTo('edit-own-action-point-dates')) {
            return $actionPoint->user_id === $user->id;
        }

        return false;
    }

    /**
     * Beschrijving bewerken: vereist edit-action-points + eigen team of geleid team
     */
    public function updateDescription(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasPermissionTo('edit-action-points')) {
            if ($user->hasPermissionTo('view-all-action-points')) {
                return true;
            }

            return $actionPoint->team_id !== null && (
                $user->teams->contains($actionPoint->team_id) ||
                $user->managedTeams->contains($actionPoint->team_id)
            );
        }

        return false;
    }

    /**
     * Status bewerken: edit-action-points (eigen/geleid team) of edit-own-action-point-status (eigen actiepunt)
     */
    public function updateStatus(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasPermissionTo('edit-action-points')) {
            if ($user->hasPermissionTo('view-all-action-points')) {
                return true;
            }

            return $actionPoint->team_id !== null && (
                $user->teams->contains($actionPoint->team_id) ||
                $user->managedTeams->contains($actionPoint->team_id)
            );
        }

        if ($user->hasPermissionTo('edit-own-action-point-status')) {
            return $actionPoint->user_id === $user->id;
        }

        return false;
    }

    /**
     * Datums bewerken: edit-action-points (eigen/geleid team) of edit-own-action-point-dates (eigen actiepunt)
     */
    public function updateDates(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasPermissionTo('edit-action-points')) {
            if ($user->hasPermissionTo('view-all-action-points')) {
                return true;
            }

            return $actionPoint->team_id !== null && (
                $user->teams->contains($actionPoint->team_id) ||
                $user->managedTeams->contains($actionPoint->team_id)
            );
        }

        if ($user->hasPermissionTo('edit-own-action-point-dates')) {
            return $actionPoint->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, ActionPoint $actionPoint): bool
    {
        if ($user->hasPermissionTo('delete-action-points')) {
            // view-all-action-points: mag alles verwijderen (ok_medewerker)
            if ($user->hasPermissionTo('view-all-action-points')) {
                return true;
            }

            return $actionPoint->team_id !== null && (
                $user->teams->contains($actionPoint->team_id) ||
                $user->managedTeams->contains($actionPoint->team_id)
            );
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
