<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Ruangan;

class RuanganController extends Controller
{
    /**
     * Get all ruangan data with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 50);
        $data = Ruangan::orderBy('id', 'desc')->paginate($per_page);
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Store new ruangan data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lantai_id' => 'required|integer',
            'room_name' => 'required|string',
            'room_limit_beban' => 'required|numeric',
            'room_lux_level' => 'required|numeric',
            'room_temperature' => 'required|numeric',
        ]);

        $ruangan = Ruangan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan created successfully',
            'data' => $ruangan,
        ], 201);
    }

    /**
     * Get specific ruangan record
     */
    public function show($id): JsonResponse
    {
        $ruangan = Ruangan::find($id);
        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $ruangan,
        ], 200);
    }

    /**
     * Update ruangan data
     */
    public function update(Request $request, $id): JsonResponse
    {
        $ruangan = Ruangan::find($id);
        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found',
            ], 404);
        }
        $validated = $request->validate([
            'lantai_id' => 'sometimes|integer',
            'room_name' => 'sometimes|string',
            'room_limit_beban' => 'sometimes|numeric',
            'room_lux_level' => 'sometimes|numeric',
            'room_temperature' => 'sometimes|numeric',
        ]);
        $ruangan->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Ruangan updated successfully',
            'data' => $ruangan,
        ], 200);
    }

    /**
     * Delete ruangan data (soft delete if enabled)
     */
    public function destroy($id): JsonResponse
    {
        $ruangan = Ruangan::find($id);
        if (!$ruangan) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan not found',
            ], 404);
        }
        $ruangan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ruangan deleted successfully',
        ], 200);
    }

    /**
     * Get all ruangan for a specific lantai_id
     */
    public function byLantai($lantai_id): JsonResponse
    {
        $ruangan = Ruangan::where('lantai_id', $lantai_id)->orderBy('room_name')->get();
        return response()->json([
            'success' => true,
            'data' => $ruangan,
        ], 200);
    }
}
