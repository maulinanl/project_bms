<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SinglePower;

class SinglePowerController extends Controller
{
    /**
     * Get all single power data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = SinglePower::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new single power data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'single_power_device_id' => 'required|string',
            'single_power_limit_beban' => 'required|numeric',
            'single_power_brand' => 'required|string',
            'single_power_status' => 'required|boolean',
            'ruangan_id' => 'required|integer',
            'switch' => 'required|boolean',
        ]);

        $singlePower = SinglePower::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Single power created successfully',
            'data' => $singlePower,
        ], 201);
    }

    /**
     * Get specific single power record
     */
    public function show($id): JsonResponse
    {
        $singlePower = SinglePower::find($id);
        if (!$singlePower) {
            return response()->json([
                'success' => false,
                'message' => 'Single power not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $singlePower,
        ], 200);
    }

    /**
     * Update single power data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $singlePower = SinglePower::find($id);
        if (!$singlePower) {
            return response()->json([
                'success' => false,
                'message' => 'Single power not found',
            ], 404);
        }
        $validated = $request->validate([
            'single_power_device_id' => 'sometimes|string',
            'single_power_limit_beban' => 'sometimes|numeric',
            'single_power_brand' => 'sometimes|string',
            'single_power_status' => 'sometimes|boolean',
            'ruangan_id' => 'sometimes|integer',
            'switch' => 'sometimes|boolean',
        ]);
        $singlePower->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Single power updated successfully',
            'data' => $singlePower,
        ], 200);
    }

    /**
     * Delete single power data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $singlePower = SinglePower::find($id);
        if (!$singlePower) {
            return response()->json([
                'success' => false,
                'message' => 'Single power not found',
            ], 404);
        }
        $singlePower->delete();
        return response()->json([
            'success' => true,
            'message' => 'Single power deleted successfully',
        ], 200);
    }

    /**
     * Get all ruangan for a specific lantai_id
     */
    public function byRuangan($ruangan_id): JsonResponse
    {
        $singlepower = SinglePower::where('ruangan_id', $ruangan_id)->orderBy('single_power_device_id')->get();
        return response()->json([
            'success' => true,
            'data' => $singlepower,
        ], 200);
    }
}
