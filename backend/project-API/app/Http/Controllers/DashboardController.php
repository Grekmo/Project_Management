<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([

            'projects'=>Project::count(),

            'tasks'=>Task::count(),

            'employees'=>User::where('role','employee')->count(),

            'managers'=>User::where('role','manager')->count(),

            'completedProjects'=>Project::where('status','completed')->count(),

            'progressProjects'=>Project::where('status','in_progress')->count(),

            'todoProjects'=>Project::where('status','to_do')->count(),

            'completedTasks'=>Task::where('status','completed')->count(),

            'progressTasks'=>Task::where('status','in_progress')->count(),

            'pendingTasks'=>Task::where('status','pending')->count(),

        ]);
    }

    public function employeeDashboard() {
    
        return response()->json([
            'myProjects' => Project::whereHas('tasks', function ($query) {
                $query->where('assigned_to', auth()->id());
            })->count(),

            'completedProjects' => Project::where('status','completed')->whereHas('tasks', function ($query) {
                $query->where('assigned_to', auth()->id());
            })->count(),

            'progressProjects' => Project::where('status','in_progress')->whereHas('tasks', function ($query) {
                $query->where('assigned_to', auth()->id());
            })->count(),

            'todoProjects' => Project::where('status','to_do')->whereHas('tasks', function ($query) {
                $query->where('assigned_to', auth()->id());
            })->count(),

            'myTasks' => Task::where('assigned_to', auth()->id())->count(),
            'completedTasks' => Task::where('assigned_to', auth()->id())->where('status', 'completed')->count(),
            'progressTasks' => Task::where('assigned_to', auth()->id())->where('status', 'in_progress')->count(),
            'pendingTasks' => Task::where('assigned_to', auth()->id())->where('status', 'pending')->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
