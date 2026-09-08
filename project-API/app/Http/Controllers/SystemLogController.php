<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', SystemLog::class);
        $logs = SystemLog::with('user')->latest()->paginate(20);

        return response()->json([
            'status' => 200,
            'message' => 'System Logs retrieved successfully',
            'logs' => $logs
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
        $this->authorize('viewAny', SystemLog::class);

        $log = SystemLog::with('user')->findOrFail($id);

        return response()->json([
            'status' => 200,
            'message' => 'System Log retrieved successfully',
            'log' => $log,
        ]);
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
