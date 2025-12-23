<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Lighting;

class LightingController extends Controller
{
    /**
     * Get all lighting data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = Lighting::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new lighting data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lighting_device_id' => 'required|string',
            'lighting_type' => 'required|integer',
            'lighting_brand' => 'required|string',
            'ruangan_id' => 'required|integer',
            'mode' => 'required|string',
            'power' => 'required|boolean',
            'lux_level' => 'required|numeric',
        ]);

        $lighting = Lighting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lighting created successfully',
            'data' => $lighting,
        ], 201);
    }

    /**
     * Get specific lighting record
     */
    public function show($id): JsonResponse
    {
        $lighting = Lighting::find($id);
        if (!$lighting) {
            return response()->json([
                'success' => false,
                'message' => 'Lighting not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $lighting,
        ], 200);
    }

    /**
     * Update lighting data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $lighting = Lighting::find($id);
        if (!$lighting) {
            return response()->json([
                'success' => false,
                'message' => 'Lighting not found',
            ], 404);
        }
        $validated = $request->validate([
            'lighting_device_id' => 'sometimes|string',
            'lighting_type' => 'sometimes|integer',
            'lighting_brand' => 'sometimes|string',
            'ruangan_id' => 'sometimes|integer',
            'mode' => 'sometimes|string',
            'power' => 'sometimes|boolean',
            'lux_level' => 'sometimes|numeric',
        ]);
        $lighting->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Lighting updated successfully',
            'data' => $lighting,
        ], 200);
    }

    /**
     * Delete lighting data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $lighting = Lighting::find($id);
        if (!$lighting) {
            return response()->json([
                'success' => false,
                'message' => 'Lighting not found',
            ], 404);
        }
        $lighting->delete();
        return response()->json([
            'success' => true,
            'message' => 'Lighting deleted successfully',
        ], 200);
    }

    /**
     * Get all ruangan for a specific lantai_id
     */
    public function byRuangan($ruangan_id): JsonResponse
    {
        $lighting = Lighting::where('ruangan_id', $ruangan_id)->orderBy('lighting_device_id')->get();
        return response()->json([
            'success' => true,
            'data' => $lighting,
        ], 200);
    }
}
