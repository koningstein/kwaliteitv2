<?php

namespace App\Policies;

use App\Models\Indicator;
use App\Models\User;

class IndicatorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function view(User $user, Indicator $indicator): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function update(User $user, Indicator $indicator): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function delete(User $user, Indicator $indicator): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function restore(User $user, Indicator $indicator): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }

    public function forceDelete(User $user, Indicator $indicator): bool
    {
        return $user->hasPermissionTo('manage-criteria');
    }
}
