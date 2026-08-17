<?php

namespace App\Policies;

use App\Models\User;
//use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function getManagers(User $user) 
    {
        return $user->role === 'admin';
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function managerViewTeams(User $user)
    {
        return $user->role === 'manager';
    }
    
    public function managerViewEmployee(User $user, User $model)
    {
        if ($user->role !== 'manager') {
            return false;
        }
        return $model->projects()->where('manager_id', $user->id)->exists();
    }

    public function employeeList(User $user)
    {
        return in_array($user->role, ['manager', 'employee']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->role === 'manager') {
            return $model->managerProject()->where('manager_id', $user->id)->exists();
        }else if ($user->role === 'employee') {
            return $model->projects()->whereHas('employees', function ($query) use ($user) { 
                // whereHas('employees'.. Relation employees f Project Model
                $query->where('users.id', $user->id);
            })->exists();
        }
        return false;
    }

    public function employeeTeamMembers(User $user)
    {
        return $user->role === 'employee';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user):bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model):bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
