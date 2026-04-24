<?php

namespace App\Policies;

use App\Models\CriterionScore;
use App\Models\User;

class CriterionScorePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-criteria-scores');
    }

    public function view(User $user, CriterionScore $criterionScore): bool
    {
        return $user->hasPermissionTo('view-criteria-scores');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }

    public function update(User $user, CriterionScore $criterionScore): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }

    public function delete(User $user, CriterionScore $criterionScore): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }

    public function restore(User $user, CriterionScore $criterionScore): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }

    public function forceDelete(User $user, CriterionScore $criterionScore): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }
}
