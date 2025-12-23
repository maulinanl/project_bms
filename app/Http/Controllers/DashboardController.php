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

    /**
     * Show Power System page (Device Control -> Power System)
     */
    public function power()
    {
        if (! session()->has('active_gedung_id')) {
            return redirect()->route('building.select');
        }

        $activeGedungName = session('active_gedung_name');
        // Load gedung with related devices: mainPower and ruangan -> hvac/lighting/singlePower
        $gedung = Gedung::with(['mainPower', 'lantai.ruangan.hvac', 'lantai.ruangan.lighting', 'lantai.ruangan.singlePower'])
            ->find(session('active_gedung_id'));

        // Flatten devices
        $mainPowers = $gedung->mainPower ?? collect();

        $hvacs = collect();
        $lightings = collect();
        $singlePowers = collect();

        if ($gedung && $gedung->lantai) {
            foreach ($gedung->lantai as $lantai) {
                if ($lantai->ruangan) {
                    foreach ($lantai->ruangan as $ruangan) {
                        if ($ruangan->hvac) $hvacs = $hvacs->concat($ruangan->hvac);
                        if ($ruangan->lighting) $lightings = $lightings->concat($ruangan->lighting);
                        if ($ruangan->singlePower) $singlePowers = $singlePowers->concat($ruangan->singlePower);
                    }
                }
            }
        }

        return view('dashboard.power', compact('activeGedungName', 'gedung', 'mainPowers', 'hvacs', 'lightings', 'singlePowers'));
    }

    /**
     * Show HVAC System page (Device Control -> HVAC System)
     */
    public function hvac()
    {
        if (! session()->has('active_gedung_id')) {
            return redirect()->route('building.select');
        }

        $activeGedungName = session('active_gedung_name');
        // Load gedung with related devices: hvac from lantai -> ruangan
        $gedung = Gedung::with(['lantai.ruangan.hvac'])
            ->find(session('active_gedung_id'));

        // Flatten HVAC devices
        $hvacs = collect();

        if ($gedung && $gedung->lantai) {
            foreach ($gedung->lantai as $lantai) {
                if ($lantai->ruangan) {
                    foreach ($lantai->ruangan as $ruangan) {
                        if ($ruangan->hvac) $hvacs = $hvacs->concat($ruangan->hvac);
                    }
                }
            }
        }

        return view('dashboard.hvac', compact('activeGedungName', 'gedung', 'hvacs'));
    }

        /**
         * Show Lighting System page (Device Control -> Lighting System)
         */
        public function lighting()
        {
            if (! session()->has('active_gedung_id')) {
                return redirect()->route('building.select');
            }

            $activeGedungName = session('active_gedung_name');
            // Load gedung with related devices: lighting from lantai -> ruangan
            $gedung = Gedung::with(['lantai.ruangan.lighting'])
                ->find(session('active_gedung_id'));

            // Flatten Lighting devices
            $lightings = collect();

            if ($gedung && $gedung->lantai) {
                foreach ($gedung->lantai as $lantai) {
                    if ($lantai->ruangan) {
                        foreach ($lantai->ruangan as $ruangan) {
                            if ($ruangan->lighting) $lightings = $lightings->concat($ruangan->lighting);
                        }
                    }
                }
            }

            return view('dashboard.lighting', compact('activeGedungName', 'gedung', 'lightings'));
        }
    }