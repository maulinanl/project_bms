@extends('layout.master')

@section('title', 'Dashboard Overview')
@section('header_title', 'Overview')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800" id="dashboard-title">Dashboard Overview</h2>
        <p class="text-gray-500 text-sm mt-1">Laporan real-time status gedung Anda.</p>
    </div>
    <div class="flex gap-2">
        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fas fa-download mr-2"></i> Export
        </button>
        <button class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm shadow-blue-200">
            <i class="fas fa-plus mr-2"></i> Quick Action
        </button>
    </div>
</div>

<!-- Kpi Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- KPI 1 -->
    <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-blue-500 group-hover:w-2 transition-all"></div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Power Usage</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">1,452 <span class="text-sm font-normal text-gray-500">kWh</span></h3>
            </div>
            <span class="p-2 bg-blue-50 text-blue-600 rounded-lg text-xl"><i class="fas fa-bolt"></i></span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 70%"></div>
        </div>
        <p class="text-xs text-gray-400">70% dari kapasitas maksimal</p>
    </div>

    <!-- KPI 2 -->
    <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-green-500 group-hover:w-2 transition-all"></div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Ruangan Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">24 <span class="text-sm font-normal text-gray-500">/ 30</span></h3>
            </div>
            <span class="p-2 bg-green-50 text-green-600 rounded-lg text-xl"><i class="fas fa-door-open"></i></span>
        </div>
        <div class="flex items-center text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded w-fit">
            <i class="fas fa-arrow-up mr-1"></i> +2 dari kemarin
        </div>
    </div>

     <!-- KPI 3 -->
     <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-yellow-400 group-hover:w-2 transition-all"></div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Suhu Avg</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">22.5 <span class="text-sm font-normal text-gray-500">°C</span></h3>
            </div>
            <span class="p-2 bg-yellow-50 text-yellow-600 rounded-lg text-xl"><i class="fas fa-temperature-low"></i></span>
        </div>
        <p class="text-xs text-gray-400">Terkendali oleh HVAC System</p>
    </div>

     <!-- KPI 4 -->
     <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-red-500 group-hover:w-2 transition-all"></div>
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase">Alerts</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">3 <span class="text-sm font-normal text-gray-500">Isu</span></h3>
            </div>
            <span class="p-2 bg-red-50 text-red-600 rounded-lg text-xl"><i class="fas fa-exclamation-triangle"></i></span>
        </div>
        <p class="text-xs text-red-500 font-medium">Perlu perhatian segera</p>
    </div>
</div>

<!-- Chart Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-bold text-gray-800">Statistik Penggunaan Listrik</h4>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded cursor-pointer">Hari Ini</span>
                <span class="px-3 py-1 text-gray-400 text-xs font-bold rounded cursor-pointer hover:bg-gray-50">Minggu Ini</span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="powerChart"></canvas>
        </div>
    </div>

    <!-- Log List -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <h4 class="font-bold text-gray-800 mb-4">Aktivitas Terakhir</h4>
        <div class="flex-1 overflow-y-auto pr-2 space-y-4">
            <!-- Item -->
            <div class="flex gap-3">
                <div class="mt-1">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                        AC
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">HVAC Lantai 3 Menyala</p>
                    <p class="text-xs text-gray-500">Otomatisasi Sistem • 10:30 WIB</p>
                </div>
            </div>
            <!-- Item -->
            <div class="flex gap-3">
                <div class="mt-1">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xs font-bold">
                        ERR
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Koneksi Sensor Terputus</p>
                    <p class="text-xs text-gray-500">Ruang Server A • 09:15 WIB</p>
                </div>
            </div>
            <!-- Item -->
            <div class="flex gap-3">
                <div class="mt-1">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xs font-bold">
                        USR
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Login Administrator</p>
                    <p class="text-xs text-gray-500">Web Dashboard • 08:00 WIB</p>
                </div>
            </div>
        </div>
        <button class="mt-4 w-full py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-50">
            Lihat Semua Log
        </button>
    </div>
</div>

<!-- Footer Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h4 class="font-bold text-gray-800">Status Perangkat Lantai 1</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3">Nama Ruangan</th>
                    <th class="px-6 py-3">Lampu</th>
                    <th class="px-6 py-3">AC (HVAC)</th>
                    <th class="px-6 py-3">Suhu</th>
                    <th class="px-6 py-3">Power</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                {{-- Di sini bisa looping data ruangan --}}
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">Lobby Utama</td>
                    <td class="px-6 py-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">ON</span></td>
                    <td class="px-6 py-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">ON</span></td>
                    <td class="px-6 py-4 text-gray-500">22°C</td>
                    <td class="px-6 py-4 text-gray-500">2.4 kW</td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-blue-500 hover:bg-blue-50 p-1 rounded"><i class="fas fa-cog"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">Ruang Meeting A</td>
                    <td class="px-6 py-4"><span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-bold">OFF</span></td>
                    <td class="px-6 py-4"><span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-bold">OFF</span></td>
                    <td class="px-6 py-4 text-gray-500">26°C</td>
                    <td class="px-6 py-4 text-gray-500">0.0 kW</td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-blue-500 hover:bg-blue-50 p-1 rounded"><i class="fas fa-cog"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('powerChart');
        if(ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'],
                    datasets: [{
                        label: 'Total Power (kW)',
                        data: [50, 45, 120, 250, 230, 180, 90],
                        borderColor: '#3b82f6',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1e2d',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: { font: { family: 'Inter', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 10 } }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }
    });
</script>
@endpush
