<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'status',
        'description',
        'start_date',
        'end_date',
        'manager_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function tasks() {
        return $this->hasMany(Task::class);
    }
    public function employees() {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /*public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employee() {
        return  $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }*/
}
