<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Job;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return bool
     */
    public function update(User $user, Job $job)
    {
        $user_id = $user->id;
        $created_by = $job->created_by;
        if ($user->hasRole(['Facility','FacilityAdmin'])) {
            $user_id = $user->facilities()->first()->id;
            $created_by = $job->facility_id;
        }
        return $user_id === $created_by
                ? Response::allow()
                : Response::deny('You do not own this job.');
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Job  $job
     * @return bool
     */
    public function delete(User $user, Job $job)
    {
        return false;
    }
}
