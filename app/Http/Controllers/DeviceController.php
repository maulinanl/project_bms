<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SinglePower;
use App\Models\MainPower;
use App\Models\Hvac;
use App\Models\Gedung;

class DeviceController extends Controller
{
    // Toggle single power device status (simple simulation: ON/OFF)
    public function toggleSinglePower(Request $request, $id)
    {
        $device = SinglePower::findOrFail($id);

        $current = $device->single_power_status;
        // Normalize common values
        if (in_array(strtoupper($current), ['ON', '1', 'TRUE'], true)) {
            $new = 'OFF';
        } else {
            $new = 'ON';
        }

        $device->single_power_status = $new;
        $device->save();

        return response()->json(['ok' => true, 'status' => $new, 'id' => $device->id]);
    }

    // Toggle main power device status (if needed)
    public function toggleMainPower(Request $request, $id)
    {
        $device = MainPower::findOrFail($id);
        $current = $device->main_power_status;
        if (in_array(strtoupper($current), ['ON', '1', 'TRUE'], true)) {
            $new = 'OFF';
        } else {
            $new = 'ON';
        }
        $device->main_power_status = $new;
        $device->save();
        return response()->json(['ok' => true, 'status' => $new, 'id' => $device->id]);
    }

    // Return devices for the current selected building, optionally filtered by lantai or ruangan
    public function getDevices(Request $request)
    {
        $gedungId = session('active_gedung_id');
        if (! $gedungId) {
            return response()->json(['ok' => false, 'message' => 'No building selected'], 400);
        }

        $gedung = Gedung::with(['mainPower', 'lantai.ruangan.hvac', 'lantai.ruangan.lighting', 'lantai.ruangan.singlePower'])
            ->find($gedungId);

        $lantaiId = $request->query('lantai');
        $ruanganId = $request->query('ruangan');

        $main = $gedung->mainPower ?? collect();
        $hvacs = collect();
        $light = collect();
        $single = collect();

        foreach ($gedung->lantai ?? [] as $lantai) {
            if ($lantaiId && $lantai->id != $lantaiId) continue;
            foreach ($lantai->ruangan ?? [] as $ruangan) {
                if ($ruanganId && $ruangan->id != $ruanganId) continue;
                $hvacs = $hvacs->concat($ruangan->hvac ?? []);
                $light = $light->concat($ruangan->lighting ?? []);
                $single = $single->concat($ruangan->singlePower ?? []);
            }
        }

        return response()->json([
            'ok' => true,
            'mainPowers' => $main->map(function($m){ return ['id'=>$m->id,'device_id'=>$m->main_power_device_id,'status'=>$m->main_power_status]; }),
            'hvacs' => $hvacs->map(function($h){ return ['id'=>$h->id,'device_id'=>$h->hvac_device_id ?? null,'type'=>$h->hvac_type ?? null,'status'=>$h->hvac_status ?? null,'ruangan'=>optional($h->ruangan)->room_name]; }),
            'lightings' => $light->map(function($l){ return ['id'=>$l->id,'device_id'=>$l->lighting_device_id ?? null,'type'=>$l->lighting_type ?? null,'ruangan'=>optional($l->ruangan)->room_name]; }),
            'singlePowers' => $single->map(function($s){ return ['id'=>$s->id,'device_id'=>$s->single_power_device_id ?? null,'limit'=>$s->single_power_limit_beban ?? null,'status'=>$s->single_power_status ?? null,'ruangan'=>optional($s->ruangan)->room_name]; }),
        ]);
    }

    // Return current statuses for polling (flatten)
    public function getStatuses(Request $request)
    {
        $gedungId = session('active_gedung_id');
        if (! $gedungId) {
            return response()->json(['ok' => false, 'message' => 'No building selected'], 400);
        }

        $gedung = Gedung::with(['mainPower', 'lantai.ruangan.singlePower'])
            ->find($gedungId);

        $statuses = [];
        foreach ($gedung->mainPower ?? [] as $m) {
            $statuses['main_'.$m->id] = $m->main_power_status;
        }
        foreach ($gedung->lantai ?? [] as $lantai) {
            foreach ($lantai->ruangan ?? [] as $ruangan) {
                foreach ($ruangan->singlePower ?? [] as $s) {
                    $statuses['single_'.$s->id] = $s->single_power_status;
                }
            }
        }

        return response()->json(['ok' => true, 'statuses' => $statuses]);
    }

    /**
     * Return aggregated single_power ON counts grouped by date/month.
     * Query params: ?range=weekly|monthly|yearly (default: weekly)
     */
    public function powerData(Request $request)
    {
        $gedungId = session('active_gedung_id');
        if (! $gedungId) {
            return response()->json(['ok' => false, 'message' => 'No building selected'], 400);
        }

        $range = $request->query('range', 'weekly');
        $query = \DB::table('single_power')
            ->join('ruangan', 'single_power.ruangan_id', '=', 'ruangan.id')
            ->join('lantai', 'ruangan.lantai_id', '=', 'lantai.id')
            ->where('lantai.gedung_id', $gedungId);

        if ($range === 'yearly') {
            // Last 12 months grouped by YYYY-MM
            $rows = $query->selectRaw("DATE_FORMAT(single_power.updated_at, '%Y-%m') as period, SUM(CASE WHEN single_power.single_power_status IN (1,'1','ON','ON','true','TRUE') THEN 1 ELSE 0 END) as on_count")
                ->where('single_power.updated_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            $labels = $rows->pluck('period')->map(function($v){ return $v; })->toArray();
            $data = $rows->pluck('on_count')->map(function($v){ return (int) $v; })->toArray();

            return response()->json(['ok' => true, 'labels' => $labels, 'data' => $data]);
        }

        if ($range === 'monthly') {
            // Last 30 days grouped by date
            $rows = $query->selectRaw("DATE(single_power.updated_at) as period, SUM(CASE WHEN single_power.single_power_status IN (1,'1','ON','ON','true','TRUE') THEN 1 ELSE 0 END) as on_count")
                ->where('single_power.updated_at', '>=', now()->subDays(29))
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            $labels = $rows->pluck('period')->map(function($v){ return $v; })->toArray();
            $data = $rows->pluck('on_count')->map(function($v){ return (int) $v; })->toArray();

            return response()->json(['ok' => true, 'labels' => $labels, 'data' => $data]);
        }

        // default: weekly (last 7 days)
        $rows = $query->selectRaw("DATE(single_power.updated_at) as period, SUM(CASE WHEN single_power.single_power_status IN (1,'1','ON','ON','true','TRUE') THEN 1 ELSE 0 END) as on_count")
            ->where('single_power.updated_at', '>=', now()->subDays(6))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Ensure we have a slot for each day in the range even if zero
        $labels = [];
        $start = now()->subDays(6)->startOfDay();
        for ($i = 0; $i < 7; $i++) {
            $labels[] = $start->copy()->addDays($i)->toDateString();
        }

        $map = $rows->pluck('on_count', 'period')->toArray();
        $data = array_map(function($label) use ($map) { return isset($map[$label]) ? (int)$map[$label] : 0; }, $labels);

        return response()->json(['ok' => true, 'labels' => $labels, 'data' => $data]);
    }

    // Toggle HVAC device status
    public function toggleHvac(Request $request, $id)
    {
        $device = Hvac::findOrFail($id);
        $current = $device->hvac_status;
        if (in_array(strtoupper((string) $current), ['ON', '1', 'TRUE'], true) || $current === 1) {
            $new = 0; // store as boolean/integer
        } else {
            $new = 1;
        }

        $device->hvac_status = $new;
        $device->save();

        // Return human readable
        $label = $new ? 'ON' : 'OFF';
        return response()->json(['ok' => true, 'status' => $label, 'id' => $device->id]);
    }
}
