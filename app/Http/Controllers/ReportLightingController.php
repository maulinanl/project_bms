<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use App\Models\ReportLighting;

class ReportLightingController extends Controller
{
    /**
     * Get all report lighting data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = ReportLighting::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new report lighting data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'report_date' => 'required|date',
            'report_hour' => 'nullable|integer',
            'report_minute' => 'nullable|integer',
            'avg_lux' => 'nullable|numeric',
            'min_lux' => 'nullable|numeric',
            'max_lux' => 'nullable|numeric',
            'avg_temperature' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'energy_consumption' => 'nullable|numeric',
        ]);

        $reportLighting = ReportLighting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Report Lighting created successfully',
            'data' => $reportLighting,
        ], 201);
    }

    /**
     * Get specific report lighting record
     */
    public function show($id): JsonResponse
    {
        $reportLighting = ReportLighting::find($id);
        if (!$reportLighting) {
            return response()->json([
                'success' => false,
                'message' => 'Report Lighting not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $reportLighting,
        ], 200);
    }

    /**
     * Update report lighting data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $reportLighting = ReportLighting::find($id);
        if (!$reportLighting) {
            return response()->json([
                'success' => false,
                'message' => 'Report Lighting not found',
            ], 404);
        }
        $validated = $request->validate([
            'device_id' => 'sometimes|string',
            'report_date' => 'sometimes|date',
            'report_hour' => 'nullable|integer',
            'report_minute' => 'nullable|integer',
            'avg_lux' => 'nullable|numeric',
            'min_lux' => 'nullable|numeric',
            'max_lux' => 'nullable|numeric',
            'avg_temperature' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'energy_consumption' => 'nullable|numeric',
        ]);
        $reportLighting->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Report Lighting updated successfully',
            'data' => $reportLighting,
        ], 200);
    }

    /**
     * Delete report lighting data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $reportLighting = ReportLighting::find($id);
        if (!$reportLighting) {
            return response()->json([
                'success' => false,
                'message' => 'Report Lighting not found',
            ], 404);
        }
        $reportLighting->delete();
        return response()->json([
            'success' => true,
            'message' => 'Report Lighting deleted successfully',
        ], 200);
    }

   /**
     * Trigger the HVAC aggregation command via API
     */
    public function aggregate(Request $request): JsonResponse
    {
        Artisan::call('aggregate:lighting');
        return response()->json([
            'success' => true,
            'message' => 'Lighting aggregation triggered.'
        ], 200);
    }

    /**
     * Get report lighting data filtered by date and time range
     * Accepts 'start' and 'end' as ISO 8601 datetime strings (e.g., 2025-12-23T14:00:00)
     */
    public function filter(Request $request): JsonResponse
    {
        $query = ReportLighting::query();
        $start = $request->input('start');
        $end = $request->input('end');
        if ($start && $end) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) >= ?", [date('Y-m-d H:i', strtotime($start))])
                  ->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) <= ?", [date('Y-m-d H:i', strtotime($end))]);
        } elseif ($start) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) >= ?", [date('Y-m-d H:i', strtotime($start))]);
        } elseif ($end) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) <= ?", [date('Y-m-d H:i', strtotime($end))]);
        }
        $data = $query->orderBy('report_date')->orderBy('report_hour')->orderBy('report_minute')->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

        /**
     * Get report lighting data filtered by device_id and optional date/time range
     * Accepts 'device_id' (required), 'start' and 'end' (optional, ISO 8601 datetime strings)
     */
    public function filterByDevice(Request $request): JsonResponse
    {
        $deviceId = $request->input('device_id', null);
        $start = $request->has('start') ? $request->input('start') : null;
        $end = $request->has('end') ? $request->input('end') : null;
        if (empty($deviceId)) {
            return response()->json([
                'success' => false,
                'message' => 'device_id parameter is required.'
            ], 400);
        }
        $query = ReportLighting::where('device_id', $deviceId);
        if (!empty($start) && !empty($end)) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) >= ?", [date('Y-m-d H:i', strtotime($start))])
                  ->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) <= ?", [date('Y-m-d H:i', strtotime($end))]);
        } elseif (!empty($start)) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) >= ?", [date('Y-m-d H:i', strtotime($start))]);
        } elseif (!empty($end)) {
            $query->whereRaw("(report_date || ' ' || LPAD(COALESCE(report_hour::text,'0'),2,'0') || ':' || LPAD(COALESCE(report_minute::text,'0'),2,'0')) <= ?", [date('Y-m-d H:i', strtotime($end))]);
        }
        $data = $query->orderBy('report_date')
            ->orderBy('report_hour')
            ->orderBy('report_minute')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
}
