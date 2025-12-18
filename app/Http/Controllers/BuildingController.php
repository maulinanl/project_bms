<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = [
            // Data dummy — nanti bisa diganti fetch dari model
            ['id' => 1, 'name' => 'Tower 1', 'image' => 'assets/media/illustrations/building.png', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Tower 2', 'image' => 'assets/media/illustrations/building.png', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Tower 3', 'image' => 'assets/media/illustrations/building.png', 'status' => 'Maintenance'],
            // dst ...
        ];

        return view('buildings.index', compact('buildings'));
    }

    /**
     * Show the create building form.
     */
    public function create()
    {
        return view('building.create');
    }

    /**
     * Store a newly created building in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gateway_id' => 'nullable|integer',
            'building_name' => 'required|string|max:255',
            'building_adress' => 'required|string|max:255',
            'building_longitude' => 'nullable|string|max:100',
            'building_latitude' => 'nullable|string|max:100',
            'building_daya' => 'nullable|integer',
            'gateway_status' => 'nullable',
            'foto_building' => 'nullable|image|max:2048',
        ]);

        $payload = [
            'gateway_id' => $validated['gateway_id'] ?? 0,
            'building_name' => $validated['building_name'],
            'building_adress' => $validated['building_adress'],
            'building_longitude' => $validated['building_longitude'] ?? '',
            'building_latitude' => $validated['building_latitude'] ?? '',
            'building_daya' => $validated['building_daya'] ?? 0,
            'gateway_status' => $request->has('gateway_status') ? 1 : 0,
        ];

        if ($request->hasFile('foto_building')) {
            $path = $request->file('foto_building')->store('foto_building', 'public');
            // store a web-accessible path (asset('storage/...') will work)
            $payload['foto_building'] = 'storage/' . $path;
        }

        $gedung = Gedung::create($payload);

        return redirect()->route('building.select')->with('success', 'Gedung berhasil dibuat.');
    }
}
