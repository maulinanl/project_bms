<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Hvac;

class HvacController extends Controller
{
    /**
     * Get all hvac data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = Hvac::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new hvac data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hvac_type' => 'required|integer',
            'hvac_device_id' => 'required|string',
            'hvac_brand' => 'required|string',
            'hvac_pk' => 'required|numeric',
            'hvac_status' => 'required|boolean',
            'ruangan_id' => 'required|integer',
            'mode' => 'required|string',
            'fan_speed' => 'required|integer',
            'temp_set' => 'required|integer',
            'power' => 'required|boolean',
        ]);

        $hvac = Hvac::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'HVAC created successfully',
            'data' => $hvac,
        ], 201);
    }

    /**
     * Get specific hvac record
     */
    public function show($id): JsonResponse
    {
        $hvac = Hvac::find($id);
        if (!$hvac) {
            return response()->json([
                'success' => false,
                'message' => 'HVAC not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $hvac,
        ], 200);
    }

    /**
     * Update hvac data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $hvac = Hvac::find($id);
        if (!$hvac) {
            return response()->json([
                'success' => false,
                'message' => 'HVAC not found',
            ], 404);
        }
        $validated = $request->validate([
            'hvac_type' => 'sometimes|integer',
            'hvac_device_id' => 'sometimes|string',
            'hvac_brand' => 'sometimes|string',
            'hvac_pk' => 'sometimes|numeric',
            'hvac_status' => 'sometimes|boolean',
            'ruangan_id' => 'sometimes|integer',
            'mode' => 'sometimes|string',
            'fan_speed' => 'sometimes|integer',
            'temp_set' => 'sometimes|integer',
            'power' => 'sometimes|boolean',
        ]);
        $hvac->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'HVAC updated successfully',
            'data' => $hvac,
        ], 200);
    }

    /**
     * Delete hvac data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $hvac = Hvac::find($id);
        if (!$hvac) {
            return response()->json([
                'success' => false,
                'message' => 'HVAC not found',
            ], 404);
        }
        $hvac->delete();
        return response()->json([
            'success' => true,
            'message' => 'HVAC deleted successfully',
        ], 200);
    }

        /**
     * Get all ruangan for a specific lantai_id
     */
    public function byRuangan($ruangan_id): JsonResponse
    {
        $hvac = Hvac::where('ruangan_id', $ruangan_id)->orderBy('hvac_device_id')->get();
        return response()->json([
            'success' => true,
            'data' => $hvac,
        ], 200);
    }
}
