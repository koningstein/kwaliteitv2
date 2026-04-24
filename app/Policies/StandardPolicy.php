<?php

namespace App\Policies;

use App\Models\Standard;
use App\Models\User;

class StandardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function view(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('view-themes');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-standards');
    }

    public function update(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('manage-standards');
    }

    public function delete(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('manage-standards');
    }

    public function restore(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('manage-standards');
    }

    public function forceDelete(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('manage-standards');
    }
}
