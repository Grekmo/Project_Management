<?php

namespace App\Http\Controllers;

use App\Models\AIConversation;
use App\Models\AIMessage;
use App\Models\Project;
//use Dotenv\Validator;
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
        $prompt .= " USER QUESTION : {$request->message}

        Answer the user clearly, directly, and in a well-organized way.

        Response formatting rules:
        - Use Markdown formatting.
        - Use headings with ## when the answer contains different sections.
        - Use bullet points (-) when listing multiple items.
        - Use **bold** for important names, statuses, numbers, and key information.
        - Leave an empty line between sections.
        - Keep each project clearly separated from the others.
        - When listing tasks, display each task as a separate bullet point.
        - Do not put the entire answer in one paragraph.
        - Keep the answer concise and easy to read.
        - Do not use unnecessary decoration or excessive emojis.

        If the user asks about their own tasks, use the Current User name and compare it with the Employee field.
        If no project was selected, consider all available projects.
        If a project was selected, consider only that project.";

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
        );

        if (!$response->successful()) {
            return response()->json([
                'status' => $response->status(),
                'gemini_error' => $response->json(),
            ], 500);
        }

        $response = $response->json();

        return response()->json([
            'response' => $response['candidates'][0]['content']['parts'][0]['text'],
        ]);
    }

    public function conversations() 
    {
        $user = auth()->user();
        $conversations = AIConversation::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    public function conversation(string $id) {

        $user = auth()->user();
        $conversation = AIConversation::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $messages = AIMessage::where('conversation_id', $conversation->id)->orderBy('id')->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function deleteConversation(string $id) 
    {
        $user = auth()->user();

        $conversation =AIConversation::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $conversation->delete();
        return response()->json([
            'message' => ' Conversation deleted successfully',
        ]);
    }

    public function renameConversation(Request $request, string $id) 
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]); 

        $user = auth()->user();

        $conversation = AIConversation::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $conversation->update([
            'title' => $request->title,
        ]);
        return response()->json([
            'conversation' => $conversation,
            'message' => ' Conversation renamed successfully',
        ]);
    }
}