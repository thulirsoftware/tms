<?php

namespace App\Http\Controllers;

use App\ClientServices;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    /**
     * Store new service for a client
     */
    public function store(Request $request, $clientId)
    {
        $service = ClientServices::create([
            'client_id'   => $clientId,
            'name'        => $request->name ?? '',
            'description' => $request->description ?? null,
            'status'      => $request->status ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service added successfully',
            'service' => $service
        ]);
    }

    /**
     * Inline update of service fields (AJAX)
     */
    public function update(Request $request, $id)
    {
        $service = ClientServices::findOrFail($id);

        $service->update($request->only([
            'name', 'description', 'status'
        ]));

        return response()->json(['success' => true]);
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        ClientServices::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
