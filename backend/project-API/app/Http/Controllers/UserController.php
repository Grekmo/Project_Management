<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['projects', 'tasks'])->get();
        if ($users) {
            return response()->json([
                'status' => 200,
                'users' => $users,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No users found',
            ], 404);
        }
    }

    public function managers() {

        $managers = User::where('role', 'manager')->select('id', 'name')->get(); //select('id', 'name') katjib ghir had l columns
        if ($managers->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Manager found',
            ], 404);
        }else{
            return response()->json([
                'status' => 200,
                'managers' => $managers,
            ], 200);
        }
    }

    public function teams() {

        $manager = auth()->user();
        $teams = User::where('role', 'employee')->whereHas('projects', function ($query) use ($manager) {
            //User::whereHas('projects', function ($query) use ($manager) Laravel kat3tik imkaniyaa t7ded Chert 3la l projects li f whereHas
            $query->where('manager_id', $manager->id);
        })->distinct()->get();
        if ($teams->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No User Found'
            ], 404);
        }return response()->json([
            'status' => 200,
            'users' => $teams,
        ], 200);
    }

    public function teamMembers() {

        $employee = auth()->user();
        //dd($employee->id);
        //3tini l Users li 3endhom relation projects katwafe9 l condition li ghadi n3tik f function li hiya ($query).
        $teamMembers = User::whereHas('projects', function ($query) use ($employee) {
            $query->whereIn('projects.id', $employee->projects->pluck('id'));  // pluck() katjib column mo3yan 
            // $employee->projects->pluck('id')); jib l'id dyal projects li kaynin f had employee w b3d katdir lihom whereIn()
            // whereIn('projects.id',[2,5,8])
        })->where('role','employee')
        ->where('users.id', '!=', $employee->id)
        ->with(['projects','tasks'])
        ->distinct()
        ->get();

        $completed = $employee->tasks->where('status', 'completed')->count();
        $total = $employee->tasks->count();
        $employee->progress = $total ? round(($completed / $total) * 100) : 0; //round() kat9reb l re9m li mn be3d,  EX : round(59.4) 59
        foreach ($teamMembers as $member) {

            $completed = $member->tasks->where('status', 'completed')->count();

            $total = $member->tasks->count();
            $member->progress = $total > 0 ? round(($completed / $total) * 100) : 0;
        }

        if ($teamMembers->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Team Members Found'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'teamMembers' => $teamMembers,
            'employee' => $employee,
        ], 200);    
    }

    public function managerShow(string $id)
    {
        $user = User::findOrFail($id);

        // Projects li had manager howa manager fihom
        $projects = $user->projects()->where('manager_id', auth()->id())->with('tasks')->get();

        // Tasks li kaynin ghir f had projects
        // pluck() katjib column mo3yan
        //whereIn() katjib les valeurs li khass ykono dakhel wa7ed l array  $projects->pluck('id') dyal les valeurs
        $tasks = Task::whereIn('project_id', $projects->pluck('id'))->where('assigned_to', $user->id)->get();

        // Nbdlou relation projects b projects li filtrinahom f relation dyal $user
        // setRelation() kat7et les projects w tasks li filtrinahom f relations dyal $user,
        $user->setRelation('projects', $projects);

        // Nbdlou relation tasks b tasks li filtrinahom f relation dyal $user
        $user->setRelation('tasks', $tasks);

        return response()->json([
            'status' => 200,
            'user' => $user,
        ]);
    }

    public function employeesList() {

        $employees = User::where('role', 'employee')->get();
        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Employee found',
            ], 404);
        }else{
            return response()->json([
                'status' => 200,
                'employees' => $employees,
            ], 200);
        }

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
        //request = les donnees li jayin men frontend w kandiro lihom l validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cin' => 'required|string|max:10|unique:users,cin', //'cin' => '..|unique:users,cin' = 'unique:users' 7int smiya dyal column = smiya field request
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' =>  ['required', 'regex:/^0[0-9]{9}$/', 'unique:users,phone'],
            'role' => 'required|in:employee,manager,admin',
            //'image' => 'required|image|mimies:jpg,png,jpeg|max:2048',
            'description' => 'required|string|max:1000',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }else{
            $user = User::create([
                'name' => $request->name,
                'cin' => $request->cin,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                //'image' => $request->image,
                'description' => $request->description,
                'password' => $request->password,// Hash kayan f USER MODEL 
            ]);
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => 'User created successfully',
                    'user' => $user,
                ], 200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'User not created',
                ], 400);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['projects','managerProject', 'tasks'])->find($id);
        //managerProject  : katssma camelCase , LARAVEL kat7welha mn Objet l JSON b snake_case w katwli manager_project

        $completed = $user->tasks->where('status', 'completed')->count();
        $user->completedTasks = $completed;
        $user->progress = $user->tasks->count() ? round(($completed / $user->tasks->count()) * 100) : 0; 
        // l function round () kat9reb l re9m li mn be3d,  EX : round(59.4) // 59 

        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['projects','managerProject', 'tasks'])->find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cin' => 'required|string|max:10|unique:users,cin,' .$id,
            'email' => 'required|string|email|max:255|unique:users,email,'. $id, // .$id = ignoer l validation dyal l'email actuel du user
            'phone' =>  ['required', 'regex:/^0[0-9]{9}$/', 'unique:users,phone,'. $id],
            'role' => 'required|in:employee,manager,admin',
            //'image' => 'required|image|mimies:jpg,png,jpeg|max:2048',
            'description' => 'required|string|max:1000',
            'password' => 'nullable|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }else{
            $user = User::find($id);
            $user->update([
                'name' => $request->name,
                'cin' => $request->cin,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                //'image' => $request->image,
                'description' => $request->description,
            ]);
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
                // Hash::make($request->password)
                $user->save();
                //filled('password') ydir bycrypt l password ila ja mn request , ila la kayb9a l password l9dim 
            }
        }if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully',
                'user' => $user,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'User not updated',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully',
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }
}
