<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
