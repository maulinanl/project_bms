<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RawSuhuHumidity;

class RawSuhuHumidityController extends Controller
{
    /**
     * Get all raw suhu humidity data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $device_id = $request->input('device_id');
        $sensor_id = $request->input('sensor_id');

        $query = RawSuhuHumidity::query();

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
     * Store new raw suhu humidity data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sensor_id' => 'required|string',
            'device_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        $rawSuhuHumidity = RawSuhuHumidity::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw suhu humidity data created successfully',
            'data' => $rawSuhuHumidity,
        ], 201);
    }

    /**
     * Get specific raw suhu humidity record
     */
    public function show($id): JsonResponse
    {
        $rawSuhuHumidity = RawSuhuHumidity::find($id);

        if (!$rawSuhuHumidity) {
            return response()->json([
                'success' => false,
                'message' => 'Raw suhu humidity data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rawSuhuHumidity,
        ], 200);
    }

    /**
     * Update raw suhu humidity data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rawSuhuHumidity = RawSuhuHumidity::find($id);

        if (!$rawSuhuHumidity) {
            return response()->json([
                'success' => false,
                'message' => 'Raw suhu humidity data not found',
            ], 404);
        }

        $validated = $request->validate([
            'sensor_id' => 'sometimes|string',
            'device_id' => 'sometimes|string',
            'temperature' => 'sometimes|numeric',
            'humidity' => 'sometimes|numeric',
        ]);

        $rawSuhuHumidity->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw suhu humidity data updated successfully',
            'data' => $rawSuhuHumidity,
        ], 200);
    }

    /**
     * Delete raw suhu humidity data (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        $rawSuhuHumidity = RawSuhuHumidity::find($id);

        if (!$rawSuhuHumidity) {
            return response()->json([
                'success' => false,
                'message' => 'Raw suhu humidity data not found',
            ], 404);
        }

        $rawSuhuHumidity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Raw suhu humidity data deleted successfully',
        ], 200);
    }

    /**
     * Get temperature and humidity statistics for a device
     */
    public function getDeviceStats($deviceId): JsonResponse
    {
        $stats = RawSuhuHumidity::where('device_id', $deviceId)
            ->selectRaw('
                COUNT(*) as total_records,
                AVG(temperature) as avg_temperature,
                MIN(temperature) as min_temperature,
                MAX(temperature) as max_temperature,
                AVG(humidity) as avg_humidity,
                MIN(humidity) as min_humidity,
                MAX(humidity) as max_humidity
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
     * Get temperature and humidity trend for a device
     */
    public function getTrend($deviceId, Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);

        $trend = RawSuhuHumidity::where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->select(['created_at', 'temperature', 'humidity'])
            ->get()
            ->reverse();

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'total_records' => count($trend),
            'data' => $trend,
        ], 200);
    }

    /**
     * Batch create raw suhu humidity data
     */
    public function batchStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.sensor_id' => 'required|string',
            'data.*.device_id' => 'required|string',
            'data.*.temperature' => 'required|numeric',
            'data.*.humidity' => 'required|numeric',
        ]);

        RawSuhuHumidity::insert($validated['data']);

        return response()->json([
            'success' => true,
            'message' => count($validated['data']) . ' records created successfully',
            'count' => count($validated['data']),
        ], 201);
    }
}
