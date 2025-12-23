<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use App\Models\ReportHvac;

class ReportHvacController extends Controller
{
    /**
     * Get all report hvac data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = ReportHvac::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new report hvac data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'report_date' => 'required|date',
            'report_hour' => 'nullable|integer',
            'report_minute' => 'nullable|integer',
            'avg_temperature' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'avg_humidity' => 'nullable|numeric',
            'min_humidity' => 'nullable|numeric',
            'max_humidity' => 'nullable|numeric',
            'energy_consumption' => 'nullable|numeric',
        ]);

        $reportHvac = ReportHvac::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Report HVAC created successfully',
            'data' => $reportHvac,
        ], 201);
    }

    /**
     * Get specific report hvac record
     */
    public function show($id): JsonResponse
    {
        $reportHvac = ReportHvac::find($id);
        if (!$reportHvac) {
            return response()->json([
                'success' => false,
                'message' => 'Report HVAC not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $reportHvac,
        ], 200);
    }

    /**
     * Update report hvac data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $reportHvac = ReportHvac::find($id);
        if (!$reportHvac) {
            return response()->json([
                'success' => false,
                'message' => 'Report HVAC not found',
            ], 404);
        }
        $validated = $request->validate([
            'device_id' => 'sometimes|string',
            'report_date' => 'sometimes|date',
            'report_hour' => 'nullable|integer',
            'report_minute' => 'nullable|integer',
            'avg_temperature' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'avg_humidity' => 'nullable|numeric',
            'min_humidity' => 'nullable|numeric',
            'max_humidity' => 'nullable|numeric',
            'energy_consumption' => 'nullable|numeric',
        ]);
        $reportHvac->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Report HVAC updated successfully',
            'data' => $reportHvac,
        ], 200);
    }

    /**
     * Delete report hvac data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $reportHvac = ReportHvac::find($id);
        if (!$reportHvac) {
            return response()->json([
                'success' => false,
                'message' => 'Report HVAC not found',
            ], 404);
        }
        $reportHvac->delete();
        return response()->json([
            'success' => true,
            'message' => 'Report HVAC deleted successfully',
        ], 200);
    }

    /**
     * Trigger the HVAC aggregation command via API
     */
    public function aggregate(Request $request): JsonResponse
    {
        Artisan::call('aggregate:hvac');
        return response()->json([
            'success' => true,
            'message' => 'HVAC aggregation triggered.'
        ], 200);
    }

    /**
     * Get report hvac data filtered by date and time range
     * Accepts 'start' and 'end' as ISO 8601 datetime strings (e.g., 2025-12-23T14:00:00)
     */
    public function filter(Request $request): JsonResponse
    {
        $query = ReportHvac::query();
        $start = $request->input('start');
        $end = $request->input('end');
        if ($start && $end) {
            // Combine date, hour, minute into a datetime for comparison
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
     * Get report hvac data filtered by device_id and optional date/time range
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
        $query = ReportHvac::where('device_id', $deviceId);
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
