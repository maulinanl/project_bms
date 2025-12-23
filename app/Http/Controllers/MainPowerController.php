<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\MainPower;

class MainPowerController extends Controller
{
    /**
     * Get all main power data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = MainPower::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new main power data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'main_power_device_id' => 'required|string',
            'main_power_status' => 'required|boolean',
            'gedung_id' => 'required|integer',
            'switch' => 'required|boolean',
        ]);

        $mainPower = MainPower::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Main power created successfully',
            'data' => $mainPower,
        ], 201);
    }

    /**
     * Get specific main power record
     */
    public function show($id): JsonResponse
    {
        $mainPower = MainPower::find($id);
        if (!$mainPower) {
            return response()->json([
                'success' => false,
                'message' => 'Main power not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $mainPower,
        ], 200);
    }

    /**
     * Update main power data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $mainPower = MainPower::find($id);
        if (!$mainPower) {
            return response()->json([
                'success' => false,
                'message' => 'Main power not found',
            ], 404);
        }
        $validated = $request->validate([
            'main_power_device_id' => 'sometimes|string',
            'main_power_status' => 'sometimes|boolean',
            'gedung_id' => 'sometimes|integer',
            'switch' => 'sometimes|boolean',
        ]);
        $mainPower->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Main power updated successfully',
            'data' => $mainPower,
        ], 200);
    }

    /**
     * Delete main power data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $mainPower = MainPower::find($id);
        if (!$mainPower) {
            return response()->json([
                'success' => false,
                'message' => 'Main power not found',
            ], 404);
        }
        $mainPower->delete();
        return response()->json([
            'success' => true,
            'message' => 'Main power deleted successfully',
        ], 200);
    }
}
