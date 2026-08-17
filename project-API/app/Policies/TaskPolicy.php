<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
//use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function before(User $user)
    {
        //dd($user->role);
        if ($user->role === 'admin') {
            return true;
        }
    }


    public function viewAny(User $user): bool
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->role === 'manager') {
            return $task->project->manager_id === $user->id; // ychouf task li kaynin fl project li howa manager dyalo 
        }
        if ($user->role === 'employee') {
            return $task->assigned_to === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->role === 'manager' && $task->project->manager_id === $user->id; //Manager : Update l task kamel
    }
    public function updateStatus(User $user, Task $task)
    {
        return $user->role ==='employee' && $task->assigned_to === $user->id; //Employee : Update l status li kayn fl task
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'manager' && $task->project->manager_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
