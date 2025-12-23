<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Lantai;

class LantaiController extends Controller
{
    /**
     * Get all lantai data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = Lantai::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new lantai data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gedung_id' => 'required|integer',
            'floor_number' => 'required|integer',
            'floor_limit_beban' => 'required|numeric',
            'floor_temperature' => 'required|numeric',
            'floor_lux_level' => 'required|numeric',
        ]);

        $lantai = Lantai::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lantai created successfully',
            'data' => $lantai,
        ], 201);
    }

    /**
     * Get specific lantai record
     */
    public function show($id): JsonResponse
    {
        $lantai = Lantai::find($id);
        if (!$lantai) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $lantai,
        ], 200);
    }

    /**
     * Update lantai data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $lantai = Lantai::find($id);
        if (!$lantai) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai not found',
            ], 404);
        }
        $validated = $request->validate([
            'gedung_id' => 'sometimes|integer',
            'floor_number' => 'sometimes|integer',
            'floor_limit_beban' => 'sometimes|numeric',
            'floor_temperature' => 'sometimes|numeric',
            'floor_lux_level' => 'sometimes|numeric',
        ]);
        $lantai->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Lantai updated successfully',
            'data' => $lantai,
        ], 200);
    }

    /**
     * Delete lantai data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $lantai = Lantai::find($id);
        if (!$lantai) {
            return response()->json([
                'success' => false,
                'message' => 'Lantai not found',
            ], 404);
        }
        $lantai->delete();
        return response()->json([
            'success' => true,
            'message' => 'Lantai deleted successfully',
        ], 200);
    }

    /**
     * Get all lantai for a specific gedung_id
     */
    public function byGedung($gedung_id): JsonResponse
    {
        $lantai = Lantai::where('gedung_id', $gedung_id)->orderBy('floor_number')->get();
        return response()->json([
            'success' => true,
            'data' => $lantai,
        ], 200);
    }
}
