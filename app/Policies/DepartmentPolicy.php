<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('Administrator')) {
            return true;
        }
        if ($user->hasRole('Admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return mixed
     */
    public function update(User $user, Department $department)
    {
        $user_id = $user->id;
        $created_by = $department->created_by;
        // if ($user->hasRole(['Facility','FacilityAdmin'])) {
        //     $user_id = $user->hireManager->facility->id;
        //     $created_by = $department->facility_id;
        // }
        return $user_id === $created_by
                ? Response::allow()
                : Response::deny('You do not own this department.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return mixed
     */
    public function delete(User $user, Department $department)
    {
        return false;
    }
    
}
