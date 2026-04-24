<?php

namespace App\Policies;

use App\Models\Criterion;
use App\Models\User;

class CriterionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function view(User $user, Criterion $criterion): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function update(User $user, Criterion $criterion): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function delete(User $user, Criterion $criterion): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function restore(User $user, Criterion $criterion): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function forceDelete(User $user, Criterion $criterion): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }
}
