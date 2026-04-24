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
        if (!$user->hasPermissionTo('view-criteria-scores')) {
            return false;
        }

        // O&K en directie mogen alles inzien
        if ($user->hasRole(['ok_medewerker', 'directie'])) {
            return true;
        }

        // Anderen mogen alleen scores van hun eigen team inzien
        return $criterionScore->team_id !== null
            && $user->teams->contains($criterionScore->team_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-criteria-scores');
    }

    public function update(User $user, CriterionScore $criterionScore): bool
    {
        if (!$user->hasPermissionTo('edit-criteria-scores')) {
            return false;
        }

        // Alleen kwaliteitszorg mag scores bewerken, en alleen van zijn eigen team
        if ($user->hasRole('kwaliteitszorg')) {
            return $criterionScore->team_id !== null
                && $user->teams->contains($criterionScore->team_id);
        }

        return false;
    }

    public function delete(User $user, CriterionScore $criterionScore): bool
    {
        return $this->update($user, $criterionScore);
    }

    public function restore(User $user, CriterionScore $criterionScore): bool
    {
        return $this->update($user, $criterionScore);
    }

    public function forceDelete(User $user, CriterionScore $criterionScore): bool
    {
        return $this->update($user, $criterionScore);
    }
}
