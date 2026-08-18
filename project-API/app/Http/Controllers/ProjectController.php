<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);// LARAVEL Automatiquement kaymchi l ProjectPolicy function'viewAny

        if(auth()->user()->role === 'admin'){
            $projects = Project::with(['manager','tasks','employees'])->get();
        }
        elseif(auth()->user()->role === 'manager'){
            $projects = Project::where('manager_id',auth()->id())->with(['manager','tasks','employees'])->get();
        }
        else{
            $projects = Project::whereHas('employees', function ($query) {
                $query->where('id', auth()->user()->id);
            })->with(['manager','tasks','employees'])->get();
        }
        //$projects = Project::with(['tasks', 'employees', 'manager'])->get(); //tasks/employees/manager --> Relations f Project Model
        if ($projects->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No projects found',
            ], 404);
        }else {
            return response()->json([
                'status' => 200,
                'projects' => $projects,
            ], 200);
        }

    }
    public function myProjects()
    {
        $manager = auth()->user();
        $myProjects = Project::where('manager_id', $manager->id)->with(['tasks', 'employees', 'manager'])->get();

        if ($myProjects->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No projects found',
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'projects' => $myProjects
        ]);     
    }
    public function employeeProjects()
    {
        $projects = Project::whereHas('employees', function ($query) {
            $query->where('users.id', auth()->user()->id); // => where('user.id', auth()->id())  bjoj are the same
        })->with(['tasks', 'employees', 'manager'])->get();
         if ($projects->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No projects found',
            ], 404);
        }else{
            return response()->json([
                'status' => 200,
                'myProjects' => $projects
            ]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            //'status' => 'required|in:to_do,in_progress,completed',
            'description' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'manager_id' => 'required|exists:users,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }else {
            $project = Project::create([
                'name' => $request->name,
                'status' => 'to_do', // 'status' => $request->status, kol Project ybda b to_do f status
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'manager_id' => $request->manager_id,
            ]);
            if ($request->has('employee_ids')) {
                $project->employees()->sync($request->employee_ids);
            }
            system_log(
                'created',
                'Project',
                $project->id,
                'Created project: ' . $project->name,
                [
                    'project_id' => $project->id,
                    'manager_id' => $project->manager_id,
                    'employee_ids' => $request->employee_ids ?? [],
                ]
            );
        }
        if ($project) {
            return response()->json([
                'status' => 200,
                'message' => 'Project created successfully',
                'project' => $project,
            ], 200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Project not created',
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['tasks.employee', 'employees', 'manager'])->find($id); 
        // 'tasks.employee' : Katjib tasks dyal had project w l kol task ja jib m3ah employee Relation li f Task Model 

        if ($project) {
            $this->authorize('view', $project);
            return response()->json([
                'status' => 200,
                'project' => $project,
            ], 200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Project not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::with(['tasks.employee','employees','manager'])->find($id);
        // 'tasks.employee' : Katjib tasks dyal had project w l kol task ja jib m3ah employee Relation li Task Model 

        if(!$project) {
            return response()->json([
                'status' => 404,
                'message' => 'Project not found',
            ], 404);
        }else {
            $this->authorize('update', $project);
            return response()->json([
                'status' => 200,
                'project' => $project,
            ], 200);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        if (auth()->user()->role === 'admin') {
            $this->authorize('update', $project); // $project li f policy
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'required|in:to_do,in_progress,completed',
                'description' => 'required|string|max:1000',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'manager_id' => 'required|exists:users,id',
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }else { //SYSTEM LOG    
                $oldData = $project->only([
                    'name',
                    'status',
                    'description',
                    'start_date',
                    'end_date',
                    'manager_id',
                ]);
                $oldData['employee_ids'] = $project->employees()->pluck('users.id')->toArray(); // Katjib l ids dyal employees li m3a had project

                $project->update([
                    'name' => $request->name,
                    'status' => $request->status,
                    'description' => $request->description,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'manager_id' => $request->manager_id,
                ]);
                if ($request->has('employee_ids')) {
                    $project->employees()->sync($request->employee_ids);
                }
                $newData = $project->only([
                    'name',
                    'status',
                    'description',
                    'start_date',
                    'end_date',
                    'manager_id',
                ]);
                $newData['employee_ids'] = $project->employees()->pluck('users.id')->toArray();

                system_log(
                    'updated',
                    'Project',
                    $project->id,
                    'Updated project: ' . $project->name,
                    [
                        'old' => $oldData,
                        'new' => $newData,
                    ]
                );
                return response()->json([
                    'status' => 200,
                    'message' => 'project updated successfully',
                    'project' => $project,
                ], 200);
            }
        }elseif (auth()->user()->role === 'manager') {
            $this->authorize('update', $project);
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:to_do,in_progress,completed',
                'description' => 'required|string',
                //PARITE EMPLOYEE
                'employee_ids' => 'required|array', // Exemple  : "employees": [3, 5, 8]
                'employee_ids.*' => 'required|exists:users,id', // .* : Every element in the array must be a valid user id
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }else{
                $oldData = $project->only([
                    'status',
                    'description',
                ]);
                $oldData['employee_ids'] = $project->employees()->pluck('users.id')->toArray();

                $project->update([
                    'status' => $request->status,
                    'description' => $request->description,
                ]);
                if ($request->has('employee_ids')) { //$project->employees() = REALTION F PROJECT MODEL
                    $project->employees()->sync($request->employee_ids); 
                    /*$project->employees()->sync([2,4,5]); = Function katkhli ghir l employee li 3endhom par example 
                        ID(2,4,5) lier m3a had l project / sync : katzid / katmsse7 / katkhli l users li khass 
                    */
                }
                $newData = $project->only([
                    'status',
                    'description',
                ]);
                $newData['employee_ids'] = $project->employees()->pluck('users.id')->toArray();
                
                system_log(
                    'updated',
                    'Project',
                    $project->id,
                    'Updated project: ' . $project->name,
                    [
                        'old' => $oldData,
                        'new' => $newData,
                    ]
                );
                return response()->json([
                    'status' => 200,
                    'message' => 'project updated successfully',
                    'project' => $project,
                ], 200);
            }
        }
        return response()->json([
            'status' => 403,
            'message' => 'You are not authorized to update this project',
        ], 403);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);
        if ($project) {
            $this->authorize('delete', $project);
            system_log(
                'deleted',
                'Project',
                $project->id,
                'Deleted project: ' . $project->name,
                [
                    'status' => $project->status,
                    'manager_id' => $project->manager_id,
                    'employee_ids' => $project->employees()->pluck('users.id')->toArray(),
                ]
            );
            $project->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Project deleted successfully',
            ], 200);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Project not found',
            ], 404);
        }
    }
}
