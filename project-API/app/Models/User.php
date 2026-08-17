<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Project;
use App\Models\SystemLog;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cin',
        'email',
        'phone',
        'role',
        'image',
        'description',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*public function managedProjects(){
        return $this->hasMany(Project::class, 'manager_id'); //SHOW USERS manager ybano les projects li howa fihom manager
    }*/
    
    public function projects() {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }
    public function tasks() {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function logs() {
        return $this->hasMany(SystemLog::class);
    }
    public function managerProject() {
        return $this->hasMany(Project::class, 'manager_id');
    }
    
    /*public function logs() {
        return $this->hasMany(SystemLog::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function managerProject() {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function projects() {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }*/
}
