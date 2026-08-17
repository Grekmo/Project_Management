<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(auth()->user());
        $this->authorize('viewAny', Task::class);  
        $tasks = Task::with(['project', 'employee'])->get();
        if ($tasks) {
            return response()->json([
                'status' => 200,
                'tasks' => $tasks,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No tasks found',
            ], 404);
        }
    }

    public function managerTasks()
    {
        $manager = auth()->user();
        $managerTasks = Task::whereHas('project', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        })->with(['project', 'employee'])->get();

        if ($managerTasks->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'NO Tasks Found',
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'managerTasks' => $managerTasks,
        ], 200);
    }


    public function myTasks() 
    {
        $myTasks = Task::where('assigned_to', auth()->id())->with(['project','employee'])->get();
        
        if ($myTasks->isEmpty()) {  
            return response()->json([
                'status' => 404,
                'message' => 'No tasks found'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'myTasks' => $myTasks
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Task::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        $projectValidator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);
        if ($projectValidator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $projectValidator->errors(),
            ], 422);
        }
        $project = Project::findOrFail($request->project_id);
        if ($project->status === 'completed') {
            return response()->json([
                'status' => 403,
                'message' => 'Cannot add tasks to a completed project.'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed',
            'end_date' => ['required','date',
                'after:'. $project->start_date,
                'before:'. $project->end_date,
            ],
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }else{
            $task = Task::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'end_date' => $request->end_date,
                'project_id' => $request->project_id,
                'assigned_to' => $request->assigned_to,
            ]);
            system_log(
                'created',
                'Task',
                $task->id,
                'Created task: ' . $task->name,
                [
                    'project_id' => $task->project_id,
                    'assigned_to' => $task->assigned_to,
                    'status' => $task->status,
                ]
            );

            $project = Project::find($request->project_id);
            $project->employees()->syncWithoutDetaching($request->assigned_to); // syncWithoutDetaching (Ida makan l employee zido wila kan khlih)
            /* Had l *$project*, sir l relation daylo *employees()*, w zid ila makanch merbot OR khlih ida kan had 
            l employee li 3endo fl id $request->assigned_to*/
            if ($task) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Task created successfully',
                    'tasks' => $task,
                ], 200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Task not created',
                ], 400);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with(['project', 'employee'])->find($id); // findOrFail($id)
        if ($task) {
            $this->authorize('view', $task);
            return response()->json([
                'status' => 200,
                'task' => $task,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::with(['project', 'employee'])->find($id);
        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }
        if (auth()->user()->role === 'manager') {
            $this->authorize('update', $task);
            return response()->json([
                'status' => 200,
                'task' => $task,
            ], 200);

        }else if (auth()->user()->role === 'employee') {
            $this->authorize('updateStatus', $task);
            return response()->json([
                'status' => 200,
                'task' => $task,
                /*'task' => [ 
                    'project_id' => $task->project_id,
                    'name' => $task->name, 
                    'id' => $task->id, 
                    'status' => $task->status, 
                    'end_date' => $task->end_date, 
                ],*/
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $project = Project::findOrFail($request->project_id);

        if (auth()->user()->role === 'employee') {
            $this->authorize('updateStatus', $task);
            $oldStatus = $task->status;
            $validator = Validator::make($request->all(),[
                'status' => 'required|in:pending,in_progress,completed',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }else{
                $task->update(['status' => $request->status,]);
                system_log(
                    'status_updated',
                    'Task',
                    $task->id,
                    'Updated task status: ' . $task->name,
                    [
                        'old_status' => $oldStatus,
                        'new_status' => $task->status,
                    ]
                );
            }
            return response()->json([
                'status' => 200,
                'message' => 'Task Status updated successfully',
                'task' => $task->status,
            ], 200);
        }else //if (auth()->user()->role === 'admin') {
        {
            $this->authorize('update', $task);
            $oldData = $task->only([
                'name',
                'description',
                'status',
                'end_date',
                'project_id',
                'assigned_to',
            ]);
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'status' => 'required|in:pending,in_progress,completed',
                'end_date' => ['required','date',
                    'after:'. $project->start_date,
                    'before:'. $project->end_date
                ],
                'project_id' => 'required|exists:projects,id',
                'assigned_to' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }else{
                $task->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'status' => $request->status,
                    'end_date' => $request->end_date,
                    'project_id' => $request->project_id,
                    'assigned_to' => $request->assigned_to,
                ]);
                system_log(
                    'updated',
                    'Task',
                    $task->id,
                    'Updated task: ' . $task->name,
                    [
                        'old' => $oldData,
                        'new' => $task->only([
                            'name',
                            'description',
                            'status',
                            'end_date',
                            'project_id',
                            'assigned_to',
                        ]),
                    ]
                );
                return response()->json([
                    'status' => 200,
                    'message' => 'Task updated successfully',
                    'task' => $task,
                ], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if ($task) {
            $this->authorize('delete', $task);
            system_log(
                'deleted',
                'Task',
                $task->id,
                'Deleted task: ' . $task->name,
                [
                    'project_id' => $task->project_id,
                    'assigned_to' => $task->assigned_to,
                    'status' => $task->status,
                ]
            );
            $task->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Task deleted successfully',
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }
    }
}
