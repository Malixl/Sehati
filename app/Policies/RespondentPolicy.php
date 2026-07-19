<?php

namespace App\Policies;

use App\Models\Respondent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RespondentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if (in_array($user->role, ['owner', 'super_admin'])) {
            return true; // Global Access
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Respondent $respondent): bool
    {
        if (in_array($user->role, ['operator_puskesmas', 'admin_puskesmas'])) {
            return $respondent->village->district_id === $user->healthPost->district_id;
        }

        if (in_array($user->role, ['operator_posyandu', 'admin_posyandu'])) {
            return $respondent->village_id === $user->healthPost->village_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Respondent $respondent): bool
    {
        return $this->view($user, $respondent);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Respondent $respondent): bool
    {
        return $this->view($user, $respondent);
    }
}
