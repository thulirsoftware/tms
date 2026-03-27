<?php

namespace App\Http\Controllers;

use App\Clients;
use App\ClientProjects;
use App\ClientServices;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Clients::latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        // Create client
        $client = Clients::create($request->only(['name', 'description', 'location']));

        // Get the latest project by projectId (sorted descending)
        $lastProject = Project::orderBy('id', 'desc')->first();

        // Determine starting number
        if ($lastProject && preg_match('/P-(\d+)/', $lastProject->projectId, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1; // First project
        }

        // Save projects (each project gets a unique incremental ID)
        if ($request->projects) {
            foreach ($request->projects as $project) {
                $nextProjectId = 'P-' . str_pad($nextNumber++, 3, '0', STR_PAD_LEFT);

                Project::create([
                    'clientId' => $client->id,
                    'projectId' => $nextProjectId,
                    'projectName' => $project['projectName'],
                    'projectDesc' => $project['projectDesc'] ?? null,
                    'startDate' => $project['startDate'],
                    'endDate' => $project['endDate'] ?? null,
                ]);
            }
        }

        // Save services
        if ($request->services) {
            foreach ($request->services as $service) {
                ClientServices::create([
                    'client_id' => $client->id,
                    'name' => $service['name'],
                    'description' => $service['description'] ?? null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Client and related projects/services created successfully',
        ]);
    }


    public function view($id)
    {
        $client = Clients::with(['projects', 'services'])->findOrFail($id);
        return view('clients.view', compact('client'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Update Request:', $request->all());
        $client = Clients::findOrFail($id);
        $client->update($request->only(['name', 'description', 'location', 'status', 'isvisible']));
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Clients::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
