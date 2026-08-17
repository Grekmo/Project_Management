<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
//use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function before(User $user)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //$user->role : KATJIB ROLE DYAL L CURRENT USER
        //return $user->role === 'manager';
        return in_array($user->role, ['manager', 'employee']);
        // return if ($user->role === 'manager' || $user->role === 'employee')   both are true
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool //function view = katchof projet wa7de
    {
        if ($user->role === 'manager') {
            return $project->manager_id === $user->id;
        }
        if ($user->role === 'employee') {
            return $project->employees()->where('users.id', $user->id)->exists();            
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project)
    {
        return $user->role === 'manager' && $project->manager_id === $user->id; // f Policy laravel man7tajoch condition if
        //$user->role = manager ? and Had l user l ID dyalo = ID dyal manager dyal $project ? then TRUE
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }
}