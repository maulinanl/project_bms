@extends('layout.master')

@section('title', 'Power System')
@section('header_title', 'Power System')

@section('content')
<section id="page-dashboard" class="page-section">
    <div class="flex h-full overflow-hidden">
        <div class="flex-1 flex flex-col h-full bg-[#f4f6f8] relative">

            <main class="flex-1 overflow-y-auto p-8">

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800" id="dashboard-title">Power Monitoring</h2>
                        <p class="text-gray-500 text-sm mt-1">Kontrol dan analisis penggunaan energi listrik.</p>
                    </div>

                    <div onclick="switchView('nodeList')" class="bg-white px-5 py-3 rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:border-blue-300 transition flex items-center gap-4 group">
                        <div class="bg-blue-50 text-blue-600 w-12 h-12 rounded-lg flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                            <i class="fas fa-network-wired text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Connected Nodes</p>
                            <div class="flex items-end gap-1">
                                <h3 class="text-2xl font-bold text-gray-800 leading-none">18</h3>
                                <span class="text-sm text-gray-400 font-medium mb-0.5">/ 20</span>
                            </div>
                        </div>
                        <div class="pl-2 border-l border-gray-100">
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition"></i>
                        </div>
                    </div>
                </div>

                <!-- PAGE 4: NODE LIST PAGE (NEW)               -->
                <section id="page-node-list" class="page-section page-hidden min-h-screen flex flex-col bg-gray-50">
                    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-6 lg:px-12 sticky top-0 z-30 justify-between">
                        <div class="flex items-center">
                            <button onclick="switchView('dashboard')" class="text-gray-500 hover:text-blue-600 mr-4 transition">
                                <i class="fas fa-arrow-left text-xl"></i>
                            </button>
                            <div>
                                <h1 class="font-bold text-lg text-gray-800">Daftar Perangkat Node</h1>
                                <p class="text-xs text-gray-500">Detail status modul ESP32 & Sensor</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-3 py-1.5 bg-white border border-gray-200 rounded text-sm text-gray-600 hover:bg-gray-50">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            <button onclick="switchView('addNode')" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 shadow-sm transition">
                                <i class="fas fa-plus mr-1"></i> Add Node
                            </button>
                        </div>
                    </nav>

                    <div class="flex-1 p-6 md:p-10 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="node-grid-container">
                        </div>
                    </div>
                </section>

                <!-- PAGE: ADD NODE FORM (NEW)                  -->
                <section id="page-add-node" class="page-section page-hidden min-h-screen flex flex-col bg-gray-50">
                    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-6 lg:px-12 sticky top-0 z-30">
                        <button onclick="switchView('nodeList')" class="text-gray-500 hover:text-blue-600 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </button>
                        <span class="font-bold text-lg text-gray-800">Tambah Node Baru</span>
                    </nav>

                    <div class="flex-1 flex items-center justify-center p-6">
                        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-2xl">
                            <div class="mb-8 border-b border-gray-100 pb-4">
                                <h2 class="text-2xl font-bold text-gray-800">Konfigurasi Perangkat</h2>
                                <p class="text-gray-500 text-sm">Tambahkan perangkat ESP32 atau sensor baru ke dalam jaringan.</p>
                            </div>

                            <form onsubmit="handleSaveNode(event)">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Node</label>
                                        <input type="text" id="node-name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Sensor Suhu L1" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Perangkat</label>
                                        <div class="relative">
                                            <select id="node-type" class="w-full appearance-none px-4 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                                <option value="Master Controller">Master Controller</option>
                                                <option value="HVAC Sensor">HVAC Sensor</option>
                                                <option value="Power Meter">Power Meter</option>
                                                <option value="Relay Module">Relay Module</option>
                                                <option value="Smart Breaker">Smart Breaker</option>
                                                <option value="Env Sensor">Environmental Sensor</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                                        <input type="text" id="node-ip" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="192.168.1.x" required>
                                    </div>

                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Pemasangan</label>
                                        <input type="text" id="node-location" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Lantai 1 - Lobby Utama" required>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" onclick="switchView('nodeList')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">Batal</button>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md">Simpan Node</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- FILTER SECTION -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2"><i class="fas fa-filter text-blue-500"></i> Filter Data</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <!-- Filter Lantai -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Lantai</label>
                            <div class="relative">
                                <select id="filter-floor" class="w-full appearance-none px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                    <option value="all">Semua Lantai</option>
                                    @if(isset($gedung) && $gedung->lantai)
                                        @foreach($gedung->lantai as $lantaiOpt)
                                            <option value="{{ $lantaiOpt->id }}">Lantai {{ $lantaiOpt->floor_number }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Filter Ruangan -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Ruangan</label>
                            <div class="relative">
                                <select id="filter-room" class="w-full appearance-none px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                    <option value="all">Semua Ruangan</option>
                                    @if(isset($gedung) && $gedung->lantai)
                                        @foreach($gedung->lantai as $l2)
                                            @if($l2->ruangan)
                                                @foreach($l2->ruangan as $rOpt)
                                                    <option value="{{ $rOpt->id }}">{{ $rOpt->room_name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Filter Tanggal -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
                            <input type="date" id="filter-date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-600">
                        </div>
                        <!-- Tombol Apply -->
                        <div>
                            <button onclick="applyFilter()" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards (Reorganized) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Card: Energy Cost (Today) -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 group hover:border-green-300 transition relative overflow-hidden">
                         <div class="absolute right-0 top-0 h-full w-1 bg-green-500 group-hover:w-2 transition-all"></div>
                        <div class="flex justify-between items-start mb-3">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg"><i class="fas fa-money-bill-wave"></i></div>
                            <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded flex items-center gap-1"><i class="fas fa-arrow-up"></i> 2.4%</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase">Estimasi Biaya Hari Ini</p>
                            <h3 class="text-xl font-bold text-gray-800">Rp 452.500</h3>
                            <p class="text-[10px] text-gray-400 mt-1">Tarif: Rp 1.444/kWh</p>
                        </div>
                    </div>

                    <!-- Card: Total Consumption -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 group hover:border-blue-300 transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-bolt"></i></div>
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">Hari Ini</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase">Total Consumption</p>
                            <h3 class="text-2xl font-bold text-gray-800">315 <span class="text-sm font-normal text-gray-500">kWh</span></h3>
                        </div>
                    </div>

                    <!-- Card: Active Load -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 group hover:border-purple-300 transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><i class="fas fa-plug"></i></div>
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">Realtime</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase">Active Power Load</p>
                            <h3 class="text-2xl font-bold text-gray-800">12.5 <span class="text-sm font-normal text-gray-500">kW</span></h3>
                        </div>
                    </div>

                    <!-- Card: Alerts -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 group hover:border-red-300 transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="p-2 bg-red-50 text-red-600 rounded-lg"><i class="fas fa-exclamation-triangle"></i></div>
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">System</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase">System Alerts</p>
                            <h3 class="text-2xl font-bold text-gray-800">0 <span class="text-sm font-normal text-gray-500">Isu</span></h3>
                        </div>
                    </div>
                </div>

                <!-- Charts & other sections (reuse functions from dashboard view if present) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h4 class="font-bold text-gray-800">1. Voltage & Current Trend</h4>
                                <p class="text-xs text-gray-500">Tren Tegangan (V) dan Arus (A) hari ini.</p>
                            </div>
                        </div>
                        <!-- reduced height to make the chart less tall -->
                        <div class="h-56 w-full"><canvas id="voltageCurrentChart"></canvas></div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                        <h4 class="font-bold text-gray-800 mb-2 w-full text-left">2. Power Efficiency</h4>
                        <p class="text-xs text-gray-500 w-full text-left mb-6">Indikator efisiensi penggunaan daya.</p>
                        <div class="gauge-container h-48 w-full flex items-center justify-center relative">
                            <canvas id="efficiencyChart" style="max-height:220px; width:100%;"></canvas>
                            <div class="gauge-value absolute">
                                <h3 class="text-3xl font-bold text-gray-800" id="efficiencyValue">95%</h3>
                                <p class="text-xs text-green-600 font-bold bg-green-100 px-2 py-0.5 rounded-full inline-block mt-1">Excellent</p>
                            </div>
                        </div>
                        <div class="w-full mt-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Output Power</span>
                                <span class="font-bold text-gray-800">11.8 kW</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Input Power</span>
                                <span class="font-bold text-gray-800">12.5 kW</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- More charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h4 class="font-bold text-gray-800">4. Energy Consumption Over Time</h4>
                                <p class="text-xs text-gray-500">Tren penggunaan energi (kWh) per jam.</p>
                            </div>
                        </div>
                        <div class="h-64 w-full"><canvas id="energyConsumptionChart"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h4 class="font-bold text-gray-800">3. Load Distribution per ESP</h4>
                                <p class="text-xs text-gray-500">Distribusi beban aktif pada setiap modul.</p>
                            </div>
                        </div>
                        <div class="h-64 w-full"><canvas id="loadDistributionChart"></canvas></div>
                    </div>
                </div>

                <!-- DEVICE CONTROL (per kategori) -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Device Control</h4>
                            <p class="text-xs text-gray-500">Perangkat yang terdaftar pada {{ $activeGedungName ?? 'Gedung' }}.</p>
                        </div>
                        <div class="text-sm text-gray-500">Total Devices: {{ ($mainPowers->count() ?? 0) + ($hvacs->count() ?? 0) + ($lightings->count() ?? 0) + ($singlePowers->count() ?? 0) }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Main Power -->
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-3">Main Power</div>
                            <div id="main-power-list">
                                @forelse($mainPowers as $mp)
                                    <div class="p-3 bg-gray-50 rounded-lg mb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-semibold">{{ $mp->main_power_device_id ?? 'MainPower' }}</div>
                                                <div class="text-xs text-gray-500">Status: <span class="font-bold" data-device="main_{{ $mp->id }}">{{ $mp->main_power_status }}</span></div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="device-toggle text-blue-500 text-xs" data-type="main" data-id="{{ $mp->id }}">Toggle</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400">Tidak ada Main Power terdaftar.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- HVAC -->
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-3">HVAC</div>
                            <div id="hvac-list">
                                @forelse($hvacs as $h)
                                    <div class="p-3 bg-gray-50 rounded-lg mb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-semibold">{{ $h->hvac_device_id ?? ($h->hvac_brand ?? 'HVAC') }}</div>
                                                <div class="text-xs text-gray-500">Tipe: {{ $h->hvac_type ?? '-' }} • Status: <span class="font-bold device-status" data-device="hvac-{{ $h->id }}">{{ $h->hvac_status ? 'ON' : 'OFF' }}</span></div>
                                                <div class="text-xs text-gray-400">Ruangan: {{ optional($h->ruangan)->room_name ?? '-' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="device-toggle text-blue-500 text-xs" data-type="hvac" data-id="{{ $h->id }}">Toggle</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400">Tidak ada HVAC terdaftar.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Lighting -->
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-3">Lighting</div>
                            <div id="lighting-list">
                                @forelse($lightings as $l)
                                    <div class="p-3 bg-gray-50 rounded-lg mb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-semibold">{{ $l->lighting_device_id ?? ($l->lighting_type ?? 'Lighting') }}</div>
                                                <div class="text-xs text-gray-500">Tipe: {{ $l->lighting_type ?? '-' }}</div>
                                                <div class="text-xs text-gray-400">Ruangan: {{ optional($l->ruangan)->room_name ?? '-' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <a href="#" class="text-blue-500 text-xs">Control</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400">Tidak ada Lighting terdaftar.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Single Power -->
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-3">Single Power / Outlet</div>
                            <div id="singlepower-list">
                                @forelse($singlePowers as $s)
                                    <div class="p-3 bg-gray-50 rounded-lg mb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-semibold">{{ $s->single_power_device_id ?? 'Outlet' }}</div>
                                                <div class="text-xs text-gray-500">Limit: {{ $s->single_power_limit_beban ?? '-' }} • Status: <span class="font-bold" data-device="single_{{ $s->id }}">{{ $s->single_power_status ?? 'n/a' }}</span></div>
                                                <div class="text-xs text-gray-400">Ruangan: {{ optional($s->ruangan)->room_name ?? '-' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="device-toggle text-blue-500 text-xs" data-type="single" data-id="{{ $s->id }}">Toggle</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400">Tidak ada Single Power terdaftar.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const ctx = document.getElementById('energyConsumptionChart');
    if (!ctx) return;

    // Create Chart instance placeholder
    let energyChart = null;

    function renderChart(labels, data) {
        if (energyChart) energyChart.destroy();
        energyChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Devices ON',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { x: { display: true }, y: { beginAtZero: true } }
            }
        });
    }

    async function loadRange(range='weekly'){
        try{
            const res = await fetch("/dashboard/power/data?range="+encodeURIComponent(range), { credentials: 'same-origin' });
            const json = await res.json();
            if (!json.ok) return;
            renderChart(json.labels, json.data);
        }catch(err){
            console.error('Failed to load power data', err);
        }
    }

    // Add simple controls
    const container = document.createElement('div');
    container.className = 'mb-4 flex gap-2';
    container.innerHTML = '<button id="range-weekly" class="px-3 py-1 bg-gray-100 rounded">Weekly</button><button id="range-monthly" class="px-3 py-1 bg-gray-100 rounded">Monthly</button><button id="range-yearly" class="px-3 py-1 bg-gray-100 rounded">Yearly</button>';
    ctx.parentElement.parentElement.insertBefore(container, ctx.parentElement);

    document.getElementById('range-weekly').addEventListener('click', ()=> loadRange('weekly'));
    document.getElementById('range-monthly').addEventListener('click', ()=> loadRange('monthly'));
    document.getElementById('range-yearly').addEventListener('click', ()=> loadRange('yearly'));

    // initial load
    loadRange('weekly');
});
</script>
@endpush

@push('scripts')
<script>
// --- DATA GEDUNG ---
let buildings = [
    { id: 1, name: 'Gedung Pusat', location: 'Jakarta Selatan', floors: 12, power: 450, status: 'ONLINE', statusClass: 'bg-green-500/90', img: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' },
    { id: 2, name: 'Cabang Surabaya', location: 'Gubeng, Surabaya', floors: 8, power: 320, status: 'ONLINE', statusClass: 'bg-green-500/90', img: 'https://images.unsplash.com/photo-1554469384-e58fac16e23a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' },
    { id: 3, name: 'Warehouse Logistik', location: 'Cikarang, Jabar', floors: 2, power: 850, status: 'MAINTENANCE', statusClass: 'bg-yellow-500/90', img: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80', grayscale: true }
];

// --- DATA NODES (DUMMY) ---
const nodesData = [
    { id: 'ESP-L1-01', name: 'Panel Utama L1', type: 'Master Controller', ip: '192.168.1.101', status: 'Online', signal: 95, location: 'Lantai 1 - Ruang Panel' },
    { id: 'ESP-L1-02', name: 'AC Controller L1', type: 'HVAC Sensor', ip: '192.168.1.102', status: 'Online', signal: 82, location: 'Lantai 1 - Lobby' },
    { id: 'ESP-L2-01', name: 'Server Room Monitor', type: 'Power Meter', ip: '192.168.1.201', status: 'Online', signal: 100, location: 'Lantai 2 - Server A' },
    { id: 'ESP-L2-02', name: 'Lighting Relay L2', type: 'Relay Module', ip: '192.168.1.202', status: 'Offline', signal: 0, location: 'Lantai 2 - Corridor' },
    { id: 'ESP-L3-01', name: 'Meeting Room Power', type: 'Smart Breaker', ip: '192.168.1.301', status: 'Online', signal: 78, location: 'Lantai 3 - Meeting Room' },
    { id: 'ESP-L3-02', name: 'TH Sensor L3', type: 'Env Sensor', ip: '192.168.1.302', status: 'Online', signal: 65, location: 'Lantai 3 - Hall' },
];

const views = {
    dashboard: document.getElementById('page-dashboard'),
    nodeList: document.getElementById('page-node-list'),
    addNode: document.getElementById('page-add-node')
};

// --- FILTER FUNCTIONALITY ---
function applyFilter() {
    const floor = document.getElementById('filter-floor').value;
    const room = document.getElementById('filter-room').value;
    const date = document.getElementById('filter-date').value;

    const btn = document.querySelector('button[onclick="applyFilter()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    setTimeout(() => {
        btn.innerHTML = originalText;
        renderAllCharts();
        alert(`Filter diterapkan:\nLantai: ${floor}\nRuangan: ${room}\nTanggal: ${date || 'Hari ini'}`);
    }, 500);
}

// --- CHART JS INSTANCES ---
let chartInstances = {};
function renderAllCharts() {
    renderVoltageCurrentChart();
    renderEfficiencyChart();
    renderLoadDistributionChart();
    renderEnergyConsumptionChart();
}

function renderVoltageCurrentChart() {
    const ctx = document.getElementById('voltageCurrentChart');
    if(!ctx) return;
    if (chartInstances.voltageCurrent) chartInstances.voltageCurrent.destroy();
    const labels = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'];
    chartInstances.voltageCurrent = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Voltage (V)', data: labels.map(() => 218 + Math.random() * 5), borderColor: '#3b82f6', backgroundColor: '#3b82f6', yAxisID: 'y_volt', tension: 0.3, pointRadius: 2 },
                { label: 'Current (A)', data: labels.map((_, i) => (i > 2 && i < 6) ? 40 + Math.random() * 10 : 20 + Math.random() * 5), borderColor: '#ef4444', backgroundColor: '#ef4444', yAxisID: 'y_amp', borderDash: [5, 5], tension: 0.3, pointRadius: 2 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'top' } }, scales: { x: { grid: { display: false } }, y_volt: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Voltage (V)', color: '#3b82f6' } }, y_amp: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Current (A)', color: '#ef4444' }, grid: { drawOnChartArea: false } } } }
    });
}

function renderEfficiencyChart() {
    const ctx = document.getElementById('efficiencyChart');
    if(!ctx) return;
    if (chartInstances.efficiency) chartInstances.efficiency.destroy();
    const efficiency = 95;
    let color = '#22c55e'; if (efficiency < 80) color = '#ef4444'; else if (efficiency < 90) color = '#eab308';
    chartInstances.efficiency = new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Efficiency', 'Loss'], datasets: [{ data: [efficiency, 100 - efficiency], backgroundColor: [color, '#f3f4f6'], borderWidth: 0, circumference: 180, rotation: 270 }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
    });
    const el = document.getElementById('efficiencyValue'); if(el) el.innerText = efficiency + '%';
}

function renderLoadDistributionChart() {
    const ctx = document.getElementById('loadDistributionChart');
    if(!ctx) return;
    if (chartInstances.loadDist) chartInstances.loadDist.destroy();
    chartInstances.loadDist = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: { labels: ['ESP-L1-Main', 'ESP-L2-Server', 'ESP-L2-Meet', 'ESP-L1-Lobby', 'ESP-Outdoor'], datasets: [{ label: 'Active Power (Watt)', data: [3500, 4200, 1500, 2800, 900], backgroundColor: '#8b5cf6', borderRadius: 4 }] },
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { borderDash: [2, 2] } }, y: { grid: { display: false } } } }
    });
}

function renderEnergyConsumptionChart() {
    const ctx = document.getElementById('energyConsumptionChart');
    if(!ctx) return;
    if (chartInstances.energy) chartInstances.energy.destroy();
    const labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    chartInstances.energy = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'Energy (kWh)', data: [280, 320, 310, 350, 380, 200, 180], borderColor: '#10b981', backgroundColor: (context) => { const ctx = context.chart.ctx; const gradient = ctx.createLinearGradient(0, 0, 0, 250); gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); gradient.addColorStop(1, 'rgba(16, 185, 129, 0)'); return gradient; }, borderWidth: 2, fill: true, tension: 0.4, pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointRadius: 4 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [2, 2] } }, x: { grid: { display: false } } } }
    });
}

// --- RENDER NODE LIST ---
function renderNodeList() {
    const container = document.getElementById('node-grid-container');
    if(!container) return;
    container.innerHTML = '';
    nodesData.forEach(node => {
        const isOnline = node.status === 'Online';
        const statusColor = isOnline ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50';
        const iconColor = isOnline ? 'text-blue-500 bg-blue-50' : 'text-gray-400 bg-gray-50';
        const signalColor = node.signal > 80 ? 'text-green-500' : (node.signal > 50 ? 'text-yellow-500' : 'text-red-500');

        container.innerHTML += `
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-blue-300 transition">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg ${iconColor} flex items-center justify-center text-lg">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase ${statusColor}">
                        ${node.status}
                    </span>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-1">${node.name}</h3>
                <p class="text-xs text-gray-500 mb-4">${node.type} • <span class="font-mono">${node.id}</span></p>

                <div class="border-t border-gray-50 pt-3 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">IP Address</span>
                        <span class="text-gray-600 font-mono">${node.ip}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Lokasi</span>
                        <span class="text-gray-600 truncate max-w-[120px]">${node.location}</span>
                    </div>
                    <div class="flex justify-between text-xs items-center">
                        <span class="text-gray-400">Signal</span>
                        <span class="${signalColor} font-bold"><i class="fas fa-wifi mr-1"></i> ${node.signal}%</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button class="flex-1 py-2 rounded-lg bg-gray-50 text-xs font-bold text-gray-600 hover:bg-gray-100" onclick="openNodeConfig('${node.id}')">Config</button>
                    <button class="flex-1 py-2 rounded-lg bg-blue-50 text-xs font-bold text-blue-600 hover:bg-blue-100" onclick="rebootNode('${node.id}')">Reboot</button>
                </div>
            </div>
        `;
    });
}

function switchView(viewName) {
    // page-switching for in-page node list / dashboard / add-node
    const pageDashboard = document.getElementById('page-dashboard');
    const pageNodeList = document.getElementById('page-node-list');
    const pageAddNode = document.getElementById('page-add-node');

    const hide = (el) => { if(!el) return; el.classList.add('page-hidden'); el.classList.add('hidden'); };
    const show = (el) => { if(!el) return; el.classList.remove('page-hidden'); el.classList.remove('hidden'); };

    if (viewName === 'nodeList') {
        hide(pageDashboard);
        show(pageNodeList);
        hide(pageAddNode);
        renderNodeList();
        return;
    }

    if (viewName === 'addNode') {
        hide(pageDashboard);
        hide(pageNodeList);
        show(pageAddNode);
        return;
    }

    // default -> dashboard
    hide(pageNodeList);
    hide(pageAddNode);
    show(pageDashboard);
    renderAllCharts();
}

// Attach device handlers and initial render
// --- NODE FORM HANDLERS ---
function handleSaveNode(e) {
    e.preventDefault();
    const name = document.getElementById('node-name').value;
    const type = document.getElementById('node-type').value;
    const ip = document.getElementById('node-ip').value;
    const location = document.getElementById('node-location').value;

    const newNode = {
        id: 'ESP-NEW-' + Math.floor(Math.random() * 10000),
        name: name,
        type: type,
        ip: ip,
        status: 'Online',
        signal: 100,
        location: location
    };

    nodesData.push(newNode);
    try { e.target.reset(); } catch (err) { /* ignore */ }
    switchView('nodeList');
    alert('Node berhasil ditambahkan!');
}

function openNodeConfig(nodeId) {
    // placeholder: open a modal or navigate to detailed config
    alert('Open config for ' + nodeId);
}

function rebootNode(nodeId) {
    if (!confirm('Reboot device ' + nodeId + ' ?')) return;
    // placeholder for reboot action
    alert('Reboot command sent to ' + nodeId);
}
function attachDeviceToggleHandlers() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    document.addEventListener('click', function(e) {
        // debug: log every click to help diagnose why toggles seem unresponsive
        // (will filter below for .device-toggle elements)
        // console.debug('document click', e.target);
        const btn = (e.target && e.target.closest) ? e.target.closest('.device-toggle') : (e.target.classList && e.target.classList.contains('device-toggle') ? e.target : null);
        if (!btn) return;
        e.preventDefault();

        console.debug('[power] device-toggle clicked', { target: e.target, btn });

        const type = btn.getAttribute('data-type');
        const id = btn.getAttribute('data-id');
        if (!type || !id) { console.debug('[power] missing data-type or data-id', { type, id }); return; }

        // Visual feedback
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        // Map to server routes
        const routes = {
            main: '/device/main-power/' + id + '/toggle',
            single: '/device/single-power/' + id + '/toggle',
            hvac: '/device/hvac/' + id + '/toggle'
        };

        const url = routes[type];
        if (!url) { btn.innerHTML = original; btn.disabled = false; return; }

        console.debug('[power] sending toggle request', { url, type, id });

        fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({})
        }).then(r => r.json()).then(data => {
            console.debug('[power] toggle response', data);
            if (data && data.ok) {
                // find corresponding status element
                let selector = null;
                if (type === 'main') selector = `[data-device="main_${id}"]`;
                if (type === 'single') selector = `[data-device="single_${id}"]`;
                if (type === 'hvac') selector = `[data-device="hvac-${id}"]`;
                const el = selector ? document.querySelector(selector) : null;
                if (el) el.innerText = data.status;
            } else {
                console.warn('[power] Toggle failed', data);
                alert('Gagal toggle device.');
            }
        }).catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menghubungi server.');
        }).finally(() => {
            btn.innerHTML = original;
            btn.disabled = false;
        });
    });
}

// Poll statuses periodically and update UI
function startStatusPolling(intervalMs = 10000) {
    const fetchAndUpdate = () => {
        fetch('/dashboard/power/statuses', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data && data.ok && data.statuses) {
                    Object.keys(data.statuses).forEach(key => {
                        const el = document.querySelector(`[data-device="${key}"]`);
                        if (el) el.innerText = data.statuses[key];
                    });
                }
            }).catch(err => { /* ignore polling errors */ console.debug('Status poll error', err); });
    };
    fetchAndUpdate();
    setInterval(fetchAndUpdate, intervalMs);
}

// Initial render — ensure dashboard is the visible view on load
document.addEventListener('DOMContentLoaded', function() {
    try {
        // force dashboard view so node-list/add-node aren't shown accidentally
        if (typeof switchView === 'function') switchView('dashboard');
    } catch (err) {
        console.debug('switchView call failed on load', err);
    }

    // attach handlers and start polling
    attachDeviceToggleHandlers();
    startStatusPolling();
});
</script>
@endpush
