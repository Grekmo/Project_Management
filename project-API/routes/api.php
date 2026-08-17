<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
 Route::get('/test', function () {
    return response()->json(['ok' => true]);
});
   //Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
   Route::get('/test-me', function () {
        return response()->json(['status' => 'Laravel is Alive!']);
    });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

/*Route::middleware('guest')->group(function() {
   
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});*/

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


/*------------- ROLE ADMIN -------------- */
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::get('/dashboard',[DashboardController::class,'index']);
    // users
    /*Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);*/
    Route::get('/managers', [UserController::class, 'managers']);
    Route::get('/employees-list', [UserController::class, 'employeesList']);
    Route::apiResource('users', UserController::class);

    Route::apiResource('tasks', TaskController::class);
    // projects (CRUD full)
    Route::apiResource('projects', ProjectController::class);
});

/*------------- ROLE MANAGER -------------- */
Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {

    //Dashboard 
    Route::get('/managerDashboard', [ManagerDashboardController::class, 'index']);
    // projects 
    Route::get('/manager/my-projects', [ProjectController::class, 'myProjects']);
    Route::get('manager/projects/{id}', [ProjectController::class, 'show']);
    Route::put('manager/projects/{id}', [ProjectController::class, 'update']);
    Route::patch('manager/projects/{id}', [ProjectController::class, 'update']);

    // tasks (CRUD)
    Route::get('/manager/tasks', [TaskController::class, 'managerTasks']);
    Route::post('/manager/tasks', [TaskController::class, 'store']);
    Route::put('/manager/tasks/{id}', [TaskController::class, 'update']);
    Route::get('/manager/tasks/{id}', [TaskController::class, 'edit']);
    Route::get('/manager/tasks/{id}', [TaskController::class, 'show']);
    Route::get('/manager/employees-list', [UserController::class, 'employeesList']);
    Route::delete('/manager/tasks/{id}', [TaskController::class, 'destroy']);

    //TEAMS 
    Route::get('/manager/team', [UserController::class, 'teams']);
    Route::post('/manager/users', [UserController::class, 'store']);
    Route::get('/manager/show/user/{id}', [UserController::class, 'managerShow']);
    Route::get('/manager/user/{id}', [UserController::class, 'edit']);
    Route::put('/manager/user/{id}', [UserController::class, 'update']);
    //Route::delete('/manager/users/{id}', [UserController::class, 'destroy']);

});


/*------------- ROLE EMPLOYEE -------------- */
Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {

    Route::get('/employee/dashboard', [DashboardController::class, 'employeeDashboard']);
    // projects 
    Route::get('/employee/my-projects', [ProjectController::class, 'employeeProjects']);
    Route::get('/employee/projects/{id}', [ProjectController::class, 'show']);
    
    //TASKS
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    Route::get('/employee/show/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/employee/tasks/{id}', [TaskController::class, 'update']);
    Route::get('/employee/tasks/{id}', [TaskController::class, 'edit']);
    Route::get('/employee/employees-list', [UserController::class, 'employeesList']);
    //Route::get('/employee/projects/{id}', [ProjectController::class, 'index']);

    //Users
    Route::get('/employee/team-members', [UserController::class, 'teamMembers']);
    Route::get('/employee/member/show/{id}', [UserController::class, 'show']);

});


/* -----------    OPTIONAL     ----------- */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* **********  FIRST  OLD   ROUTE  **********   */
/*

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/projects/{id}/edit', [ProjectController::class, 'edit']);
Route::put('/projects/{id}/edit', [ProjectController::class, 'update']);
Route::delete('/projects/{id}/delete', [ProjectController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::put('/users/{id}/edit', [UserController::class, 'update']);
Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
Route::put('/tasks/{id}/edit', [TaskController::class, 'update']);
Route::delete('/tasks/{id}/delete', [TaskController::class, 'destroy']);


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'inedx']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/{id}/delete', [UserController::class, 'destroy']);
    Route::resource('/projects', [ProjectController::class]);
    Route::resource('/tasks', [TaskController::class]);
});

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::resource('/tasks', [TaskController::class]);
});

Route::middleware(['auth:sanctum', 'role:employee'])->group(function() {
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
});




*/