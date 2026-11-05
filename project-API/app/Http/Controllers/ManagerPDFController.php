<?php

namespace App\Http\Controllers;

use App\Models\Project;
//use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagerPDFController extends Controller
{
    public function export(string $id)
    {
        $project = Project::with([
            'tasks.employee',
            'employees',
            'manager'
        ])->findOrFail($id);

        $this->authorize('view', $project);

        $pdf = Pdf::loadView('pdf.manager-project', [
            'project' => $project
        ]);

        return $pdf->download('project-' . $project->id . '.pdf');
    }
}