<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class DashboardController extends Controller
{
    public function selectBuilding()
    {
        $gedungList = Gedung::all();
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
            'active_gedung_name' => $gedung->nama_gedung ?? $gedung->name,
            'active_gedung_lokasi' => $gedung->lokasi ?? $gedung->address ?? 'Lokasi Utama',
        ]);

        return redirect()->route('dashboard');
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
