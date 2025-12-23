<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RawLuxMotion;
use Illuminate\Pagination\Paginator;

class RawLuxMotionController extends Controller
{
    /**
     * Get all raw lux motion data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $device_id = $request->input('device_id');
        $sensor_id = $request->input('sensor_id');

        $query = RawLuxMotion::query();

        if ($device_id) {
            $query->where('device_id', $device_id);
        }

        if ($sensor_id) {
            $query->where('sensor_id', $sensor_id);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new raw lux motion data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sensor_id' => 'required|string',
            'device_id' => 'required|string',
            'lux_level' => 'required|numeric|min:0',
            'movement' => 'required|boolean',
        ]);

        $rawLuxMotion = RawLuxMotion::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw lux motion data created successfully',
            'data' => $rawLuxMotion,
        ], 201);
    }

    /**
     * Get specific raw lux motion record
     */
    public function show($id): JsonResponse
    {
        $rawLuxMotion = RawLuxMotion::find($id);

        if (!$rawLuxMotion) {
            return response()->json([
                'success' => false,
                'message' => 'Raw lux motion data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rawLuxMotion,
        ], 200);
    }

    /**
     * Update raw lux motion data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rawLuxMotion = RawLuxMotion::find($id);

        if (!$rawLuxMotion) {
            return response()->json([
                'success' => false,
                'message' => 'Raw lux motion data not found',
            ], 404);
        }

        $validated = $request->validate([
            'sensor_id' => 'sometimes|string',
            'device_id' => 'sometimes|string',
            'lux_level' => 'sometimes|numeric|min:0',
            'movement' => 'sometimes|boolean',
        ]);

        $rawLuxMotion->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw lux motion data updated successfully',
            'data' => $rawLuxMotion,
        ], 200);
    }

    /**
     * Delete raw lux motion data (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        $rawLuxMotion = RawLuxMotion::find($id);

        if (!$rawLuxMotion) {
            return response()->json([
                'success' => false,
                'message' => 'Raw lux motion data not found',
            ], 404);
        }

        $rawLuxMotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Raw lux motion data deleted successfully',
        ], 200);
    }

    /**
     * Get lux level statistics for a device
     */
    public function getDeviceStats($deviceId): JsonResponse
    {
        $stats = RawLuxMotion::where('device_id', $deviceId)
            ->selectRaw('
                COUNT(*) as total_records,
                AVG(lux_level) as avg_lux,
                MIN(lux_level) as min_lux,
                MAX(lux_level) as max_lux,
                SUM(CASE WHEN movement = true THEN 1 ELSE 0 END) as motion_detected_count
            ')
            ->first();

        if (!$stats) {
            return response()->json([
                'success' => false,
                'message' => 'No data found for this device',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'data' => $stats,
        ], 200);
    }

    /**
     * Get motion detection history for a device
     */
    public function getMotionHistory($deviceId, Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);

        $motionEvents = RawLuxMotion::where('device_id', $deviceId)
            ->where('movement', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'total_events' => count($motionEvents),
            'data' => $motionEvents,
        ], 200);
    }

    /**
     * Batch create raw lux motion data
     */
    public function batchStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.sensor_id' => 'required|string',
            'data.*.device_id' => 'required|string',
            'data.*.lux_level' => 'required|numeric|min:0',
            'data.*.movement' => 'required|boolean',
        ]);

        $records = RawLuxMotion::insert($validated['data']);

        return response()->json([
            'success' => true,
            'message' => count($validated['data']) . ' records created successfully',
            'count' => count($validated['data']),
        ], 201);
    }
}
