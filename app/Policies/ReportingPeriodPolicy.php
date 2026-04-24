<?php

namespace App\Policies;

use App\Models\ReportingPeriod;
use App\Models\User;

class ReportingPeriodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['directie', 'ok_medewerker', 'kwaliteitszorg', 'onderwijsleider', 'medewerker']);
    }

    public function view(User $user, ReportingPeriod $reportingPeriod): bool
    {
        return $user->hasAnyRole(['directie', 'ok_medewerker', 'kwaliteitszorg', 'onderwijsleider', 'medewerker']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function update(User $user, ReportingPeriod $reportingPeriod): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function delete(User $user, ReportingPeriod $reportingPeriod): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function restore(User $user, ReportingPeriod $reportingPeriod): bool
    {
        return $user->hasRole('ok_medewerker');
    }

    public function forceDelete(User $user, ReportingPeriod $reportingPeriod): bool
    {
        return $user->hasRole('ok_medewerker');
    }
}
