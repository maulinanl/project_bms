<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Gedung;

class GedungController extends Controller
{
    /**
     * Get all gedung data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = Gedung::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new gedung data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gateway_id' => 'required|integer',
            'building_name' => 'required|string',
            'building_adress' => 'required|string',
            'building_longitude' => 'required|string',
            'building_latitude' => 'required|string',
            'building_daya' => 'required|numeric',
            'gateway_status' => 'required|boolean',
            'foto_building' => 'nullable|string',
        ]);

        $gedung = Gedung::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gedung created successfully',
            'data' => $gedung,
        ], 201);
    }

    /**
     * Get specific gedung record
     */
    public function show($id): JsonResponse
    {
        $gedung = Gedung::find($id);
        if (!$gedung) {
            return response()->json([
                'success' => false,
                'message' => 'Gedung not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $gedung,
        ], 200);
    }

    /**
     * Update gedung data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $gedung = Gedung::find($id);
        if (!$gedung) {
            return response()->json([
                'success' => false,
                'message' => 'Gedung not found',
            ], 404);
        }
        $validated = $request->validate([
            'gateway_id' => 'sometimes|integer',
            'building_name' => 'sometimes|string',
            'building_adress' => 'sometimes|string',
            'building_longitude' => 'sometimes|string',
            'building_latitude' => 'sometimes|string',
            'building_daya' => 'sometimes|numeric',
            'gateway_status' => 'sometimes|boolean',
            'foto_building' => 'nullable|string',
        ]);
        $gedung->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Gedung updated successfully',
            'data' => $gedung,
        ], 200);
    }

    /**
     * Delete gedung data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $gedung = Gedung::find($id);
        if (!$gedung) {
            return response()->json([
                'success' => false,
                'message' => 'Gedung not found',
            ], 404);
        }
        $gedung->delete();
        return response()->json([
            'success' => true,
            'message' => 'Gedung deleted successfully',
        ], 200);
    }
}
