<?php

namespace App\Policies;

use App\Models\ActionPointStatus;
use App\Models\User;

class ActionPointStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['directie', 'ok_medewerker', 'kwaliteitszorg', 'onderwijsleider', 'medewerker']);
    }

    public function view(User $user, ActionPointStatus $actionPointStatus): bool
    {
        return $user->hasAnyRole(['directie', 'ok_medewerker', 'kwaliteitszorg', 'onderwijsleider', 'medewerker']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function update(User $user, ActionPointStatus $actionPointStatus): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function delete(User $user, ActionPointStatus $actionPointStatus): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function restore(User $user, ActionPointStatus $actionPointStatus): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function forceDelete(User $user, ActionPointStatus $actionPointStatus): bool
    {
        return $user->hasRole('ok_medewerker');
    }
}
