<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-action-points');
    }

    public function view(User $user, Evaluation $evaluation): bool
    {
        if (! $user->hasPermissionTo('view-action-points')) {
            return false;
        }

        // Gebruikers zonder edit-rechten (medewerker) mogen alleen evaluaties zien
        // van actiepunten die aan hen zijn toegewezen.
        if (! $user->hasPermissionTo('edit-action-points') && ! $user->hasPermissionTo('view-all-action-points')) {
            return $evaluation->actionPoint->user_id === $user->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-action-points');
    }

    public function update(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('edit-action-points');
    }

    public function delete(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }

    public function restore(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }

    public function forceDelete(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('delete-action-points');
    }
}
