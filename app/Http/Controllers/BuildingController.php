<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        // Data dummy untuk simulasi tampilan.
        // Nanti bisa diganti dengan: $buildings = \App\Models\Gedung::all();
        $buildings = [
            [
                'id' => 1,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png', // Menggunakan aset yang sudah diupload
                'status' => 'Active'
            ],
            [
                'id' => 2,
                'name' => 'Tower 1', // Nama sengaja sama sperti di gambar referensi
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
            [
                'id' => 3,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Maintenance'
            ],
            [
                'id' => 4,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
            [
                'id' => 5,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
            [
                'id' => 6,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
            [
                'id' => 7,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
            [
                'id' => 8,
                'name' => 'Tower 1',
                'image' => 'assets/media/illustrations/building.png',
                'status' => 'Active'
            ],
        ];

        return view('buildings.index', compact('buildings'));
    }
}
