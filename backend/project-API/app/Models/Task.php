<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'end_date',
        'project_id',
        'assigned_to',
    ];
    protected $casts = [
        'end_date' => 'datetime',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function employee() {
        return $this->belongsTo(User::class, 'assigned_to');
    }



    /*public function project() {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo() {
        return $this->belongsTo(User::class, 'user_id');
    }*/
}
