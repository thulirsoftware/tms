<?php

namespace App\Http\Controllers;


use App\Project;
use Illuminate\Http\Request;

class ClientProjectController extends Controller
{
    /**
     * Store new project for a client
     */
    public function store(Request $request, $clientId)
    {
        // Get the latest project by projectId (sorted descending)
        $lastProject = Project::orderBy('id', 'desc')->first();

        // Generate next projectId
        if ($lastProject && preg_match('/P-(\d+)/', $lastProject->projectId, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1; // First project
        }

        // Format as P-001, P-002, etc.
        $nextProjectId = 'P-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Create new project
        $project = Project::create([
            'clientId' => $clientId,
            'projectId' => $nextProjectId,
            'projectName' => $request->projectName ?? '',
            'projectDesc' => $request->projectDesc ?? null,
            'startDate' => $request->startDate ?? now()->toDateString(),
            'endDate' => $request->endDate ?? null,
            'status' => $request->status ?? 'Open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project added successfully',
            'project' => $project
        ]);
    }


    /**
     * Inline update of project fields (AJAX)
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Update only changed field
        $project->update($request->only([
            'projectName',
            'projectDesc',
            'startDate',
            'endDate',
            'status'
        ]));

        return response()->json(['success' => true]);

    }

    /**
     * Delete project
     */
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
