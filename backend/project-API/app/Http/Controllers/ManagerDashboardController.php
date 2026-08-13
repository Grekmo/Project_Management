<?php

namespace App\Http\Controllers;

//use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manager = auth()->user();

        $tasks = Task::whereHas('project', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        });

        $employees = User::whereHas('projects', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        });


        $dashboard = [

            'myProjects' => $manager->managerProject->count() ,

            'team' => $employees->distinct()->count(),
            
            'tasks' => $tasks->count(),

            'completedProjects' => $manager->managerProject->where('status','completed')->count(),

            'progressProjects' => $manager->managerProject->where('status','in_progress')->count(),

            'todoProjects' => $manager->managerProject->where('status','to_do')->count(),

            'completedTasks' => (clone $tasks)->where('status','completed')->count(), //clone crate a copy of the query builder li hiya $tasks

            'progressTasks' => (clone $tasks)->where('status','in_progress')->count(),

            'pendingTasks' => (clone $tasks)->where('status','pending')->count(),
        ];
            
        return response()->json([
            'status' => 200 ,
            'dashboard' => $dashboard
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
