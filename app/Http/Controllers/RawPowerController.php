<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RawPower;

class RawPowerController extends Controller
{
    /**
     * Get all raw power data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $device_id = $request->input('device_id');
        $sensor_id = $request->input('sensor_id');

        $query = RawPower::query();

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
     * Store new raw power data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sensor_id' => 'required|string',
            'device_id' => 'required|string',
            'tegangan' => 'required|numeric|min:0',
            'arus' => 'required|numeric|min:0',
            'daya_aktif' => 'required|numeric|min:0',
            'faktor_daya' => 'required|numeric|between:0,1',
            'energi' => 'required|numeric|min:0',
        ]);

        $rawPower = RawPower::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw power data created successfully',
            'data' => $rawPower,
        ], 201);
    }

    /**
     * Get specific raw power record
     */
    public function show($id): JsonResponse
    {
        $rawPower = RawPower::find($id);

        if (!$rawPower) {
            return response()->json([
                'success' => false,
                'message' => 'Raw power data not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rawPower,
        ], 200);
    }

    /**
     * Update raw power data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rawPower = RawPower::find($id);

        if (!$rawPower) {
            return response()->json([
                'success' => false,
                'message' => 'Raw power data not found',
            ], 404);
        }

        $validated = $request->validate([
            'sensor_id' => 'sometimes|string',
            'device_id' => 'sometimes|string',
            'tegangan' => 'sometimes|numeric|min:0',
            'arus' => 'sometimes|numeric|min:0',
            'daya_aktif' => 'sometimes|numeric|min:0',
            'faktor_daya' => 'sometimes|numeric|between:0,1',
            'energi' => 'sometimes|numeric|min:0',
        ]);

        $rawPower->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Raw power data updated successfully',
            'data' => $rawPower,
        ], 200);
    }

    /**
     * Delete raw power data (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        $rawPower = RawPower::find($id);

        if (!$rawPower) {
            return response()->json([
                'success' => false,
                'message' => 'Raw power data not found',
            ], 404);
        }

        $rawPower->delete();

        return response()->json([
            'success' => true,
            'message' => 'Raw power data deleted successfully',
        ], 200);
    }

    /**
     * Get power statistics for a device
     */
    public function getDeviceStats($deviceId): JsonResponse
    {
        $stats = RawPower::where('device_id', $deviceId)
            ->selectRaw('
                COUNT(*) as total_records,
                AVG(tegangan) as avg_tegangan,
                MIN(tegangan) as min_tegangan,
                MAX(tegangan) as max_tegangan,

                AVG(arus) as avg_arus,
                MIN(arus) as min_arus,
                MAX(arus) as max_arus,

                AVG(daya_aktif) as avg_daya_aktif,
                MIN(daya_aktif) as min_daya_aktif,
                MAX(daya_aktif) as max_daya_aktif,

                AVG(faktor_daya) as avg_faktor_daya,

                SUM(energi) as total_energi
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
     * Get power consumption trend for a device
     */
    public function getPowerTrend($deviceId, Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);

        $trend = RawPower::where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->selectRaw('
                created_at,
                tegangan,
                arus,
                daya_aktif,
                faktor_daya,
                energi
            ')
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
     * Get devices with high power consumption (alert threshold)
     */
    public function getHighConsumption(Request $request): JsonResponse
    {
        $threshold = $request->input('threshold', 5000); // Default 5000W
        $limit = $request->input('limit', 50);

        $devices = RawPower::where('daya_aktif', '>', $threshold)
            ->selectRaw('
                device_id,
                MAX(daya_aktif) as max_power,
                AVG(daya_aktif) as avg_power,
                COUNT(*) as record_count,
                MAX(created_at) as last_reading
            ')
            ->groupBy('device_id')
            ->orderBy('max_power', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'threshold' => $threshold,
            'total_devices' => count($devices),
            'data' => $devices,
        ], 200);
    }

    /**
     * Batch create raw power data
     */
    public function batchStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.sensor_id' => 'required|string',
            'data.*.device_id' => 'required|string',
            'data.*.tegangan' => 'required|numeric|min:0',
            'data.*.arus' => 'required|numeric|min:0',
            'data.*.daya_aktif' => 'required|numeric|min:0',
            'data.*.faktor_daya' => 'required|numeric|between:0,1',
            'data.*.energi' => 'required|numeric|min:0',
        ]);

        RawPower::insert($validated['data']);

        return response()->json([
            'success' => true,
            'message' => count($validated['data']) . ' records created successfully',
            'count' => count($validated['data']),
        ], 201);
    }

    /**
     * Get power factor analysis for a device
     */
    public function getPowerFactorAnalysis($deviceId, Request $request): JsonResponse
    {
        $limit = $request->input('limit', 100);

        $analysis = RawPower::where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->selectRaw('
                created_at,
                faktor_daya,
                CASE
                    WHEN faktor_daya >= 0.95 THEN "excellent"
                    WHEN faktor_daya >= 0.85 THEN "good"
                    WHEN faktor_daya >= 0.75 THEN "fair"
                    ELSE "poor"
                END as power_factor_status
            ')
            ->get()
            ->reverse();

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'total_records' => count($analysis),
            'data' => $analysis,
        ], 200);
    }
}
