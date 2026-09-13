<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'message' => 'required|string',
        ]);

        $user = auth()->user();

        if ($request->has('project_id')) {

            $project = Project::with(['manager', 'employees', 'tasks.employee'])->findOrFail($request->project_id);
            $this->authorize('view', $project);
            $projects = collect([$project]);
        }
        else{
            if ($user->role === 'admin') {
                $projects = Project::with(['manager', 'employees', 'tasks.employee'])->get();
            }
            else if ($user->role === 'manager') {
                $projects = Project::where('manager_id', $user->id)->with(['manager', 'employees', 'tasks.employee'])->get();
            }
            else if ($user->role === 'employee') {
                $projects = Project::whereHas('employees', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->with(['manager', 'employees','tasks'])->get();
            }
            else {
                return response()->json([
                    'status' => 403,
                    'message' => 'You are not authorized to access this resource'
                ], 403);
            }
        }
        $prompt = "You are an AI assistant for a project management application. 
        Current User : 
        Name : {$user->name}
        Role : {$user->role} The following project data is available to you. Only use this data to answer the user's question. "; 

        foreach ($projects as $project) {
            $prompt .= " PROJECT : 
            Name : {$project->name} 
            Description : {$project->description} 
            Manager : {$project->manager?->name} 
            TASKS : "; 

            foreach ($project->tasks as $task) { 
                $prompt .= " - Task : {$task->name} 
                Description : {$task->description} 
                Status : {$task->status} 
                Employee : {$task->employee?->name} "; 
            } 
        } 
        $prompt .= " USER QUESTION : {$request->message} Answer the user clearly and directly. 
        If the user asks about their own tasks, use the Current User name and compare it with the Employee field. 
        If no project was selected, consider all available projects. If a project was selected, consider only that project. ";

        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]
        )->json();

        return response()->json([
        'response' => $response['candidates'][0]['content']['parts'][0]['text'],
    ]);
    }
}