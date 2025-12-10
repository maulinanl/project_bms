<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung; // Pastikan Model Gedung diimport

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman pilih gedung
     */
    public function selectBuilding()
    {
        // Ambil data gedung dari database
        $gedungList = Gedung::all();

        // Tampilkan view select.blade.php
        return view('pages.building.select', compact('gedungList'));
    }

    /**
     * Simpan gedung yang dipilih ke session
     */
    public function setBuilding(Request $request)
    {
        // Validasi ID gedung
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id'
        ]);

        // Ambil data gedung
        $gedung = Gedung::find($request->gedung_id);

        // Simpan ke Session
        session([
            'active_gedung_id' => $gedung->id,
            'active_gedung_name' => $gedung->nama_gedung ?? $gedung->name,
            'active_gedung_lokasi' => $gedung->lokasi ?? $gedung->address ?? 'Lokasi Utama',
        ]);

        // Redirect ke Dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Halaman Dashboard
     */
    public function index()
    {
        // Cek Session: Jika belum pilih gedung, tendang balik
        if (!session()->has('active_gedung_id')) {
            return redirect()->route('building.select');
        }

        // Ambil nama gedung dari session untuk ditampilkan di view
        $activeGedungName = session('active_gedung_name');

        // Initialize theme bootstrap (Kode Bawaan Metronic)
        $bootstrap = config('settings.KT_THEME_BOOTSTRAP.default');
        if ($bootstrap) {
            (new $bootstrap)->init();
        }

        return view('dashboard', compact('activeGedungName'));
    }
}
