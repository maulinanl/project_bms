<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class DashboardController extends Controller
{
    public function selectBuilding()
    {
        // Eager load the count of floors (lantai) to display in the view
        $gedungList = Gedung::withCount('lantai')->get();
        return view('building.select', compact('gedungList'));
    }

    public function setBuilding(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id',
        ]);

        $gedung = Gedung::find($request->gedung_id);

        session([
            'active_gedung_id'   => $gedung->id,
            // Gedung model columns use 'building_name' and 'building_adress'
            'active_gedung_name' => $gedung->building_name ?? $gedung->name ?? 'Gedung',
            'active_gedung_lokasi' => $gedung->building_adress ?? $gedung->lokasi ?? 'Lokasi Utama',
            'active_gedung_status' => $gedung->gateway_status ?? 0,
            'active_gedung_daya' => $gedung->building_daya ?? 0,
        ]);

        // Redirect to dashboard while preserving the selected building id as a query
        // parameter so the URL/layout matches the previous behaviour (e.g. /dashboard?id=1).
        return redirect()->route('dashboard', ['id' => $gedung->id]);
    }

    public function index()
    {
        if (! session()->has('active_gedung_id')) {
            return redirect()->route('building.select');
        }

        $activeGedungName = session('active_gedung_name');

        // Jika kamu menggunakan theme/plugin, inisialisasi di sini
        // $bootstrap = config('settings.KT_THEME_BOOTSTRAP.default');
        // if ($bootstrap) { (new $bootstrap)->init(); }

        return view('dashboard', compact('activeGedungName'));
    }
}
