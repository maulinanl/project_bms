@extends('layout.master')

@section('title', 'HVAC System')
@section('header_title', 'HVAC System')

@section('content')
<div class="flex h-full overflow-hidden">
    <div class="flex-1 flex flex-col h-full bg-[#f4f6f8] relative">
        <main class="flex-1 overflow-y-auto p-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">HVAC System Control</h2>
                    <p class="text-gray-500 text-sm mt-2">Monitor dan kontrol sistem pendingin udara (AC) di seluruh ruangan.</p>
                </div>
                <div class="bg-white px-6 py-3 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-[12px] text-gray-500 font-bold uppercase tracking-wider">Total HVAC Units</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ count($hvacs) }}</h3>
                </div>
            </div>

            <!-- HVAC Units Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($hvacs->count() > 0)
                    @foreach($hvacs as $hvac)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $hvac->name ?? 'HVAC Unit #' . $hvac->id }}</h3>
                                    <p class="text-cyan-100 text-xs mt-1">
                                        {{ optional($hvac->ruangan)->name ?? 'Ruangan Tidak Diketahui' }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-fan text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 space-y-4">
                            <!-- Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 font-medium text-sm">Status</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full {{ $hvac->status == 1 ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                                    <span class="text-sm font-semibold {{ $hvac->status == 1 ? 'text-green-600' : 'text-gray-600' }}">
                                        {{ $hvac->status == 1 ? 'Aktif' : 'Mati' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Temperature Info -->
                            <div class="flex items-center justify-between p-3 bg-cyan-50 rounded-lg">
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Suhu Saat Ini</p>
                                    <p class="text-2xl font-bold text-cyan-600">{{ $hvac->temperature ?? '-- ' }}°C</p>
                                </div>
                                <i class="fas fa-thermometer-half text-cyan-500 text-2xl"></i>
                            </div>

                            <!-- Power Info -->
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Daya</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $hvac->power ?? '-- ' }}W</p>
                                </div>
                                <i class="fas fa-plug text-blue-500 text-xl"></i>
                            </div>

                            <!-- Control Button -->
                            <button 
                                class="device-toggle-hvac w-full py-2.5 px-4 rounded-lg font-semibold transition flex items-center justify-center gap-2 {{ $hvac->status == 1 ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                data-hvac-id="{{ $hvac->id }}"
                                data-hvac-status="{{ $hvac->status }}">
                                <i class="fas {{ $hvac->status == 1 ? 'fa-power-off' : 'fa-play' }} text-sm"></i>
                                <span>{{ $hvac->status == 1 ? 'Matikan' : 'Nyalakan' }}</span>
                            </button>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500">
                            <p>ID: <span class="font-mono">{{ $hvac->id }}</span></p>
                            <p class="mt-1">Last Updated: <span class="font-medium">{{ $hvac->updated_at?->diffForHumans() ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="col-span-full py-12 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada HVAC Unit terdeteksi</p>
                    <p class="text-gray-400 text-sm mt-2">Pastikan ada HVAC Unit yang terdaftar di sistem</p>
                </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle HVAC toggle buttons
        document.querySelectorAll('.device-toggle-hvac').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const hvacId = this.dataset.hvacId;
                const currentStatus = parseInt(this.dataset.hvacStatus);
                const newStatus = currentStatus === 1 ? 0 : 1;

                // Disable button during request
                this.disabled = true;
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Processing...</span>';

                // Send toggle request
                fetch(`/device/hvac/${hvacId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update button state
                        this.dataset.hvacStatus = newStatus;
                        if (newStatus === 1) {
                            this.classList.remove('bg-green-50', 'text-green-600', 'hover:bg-green-100');
                            this.classList.add('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                            this.innerHTML = '<i class="fas fa-power-off text-sm"></i><span>Matikan</span>';
                        } else {
                            this.classList.remove('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                            this.classList.add('bg-green-50', 'text-green-600', 'hover:bg-green-100');
                            this.innerHTML = '<i class="fas fa-play text-sm"></i><span>Nyalakan</span>';
                        }
                        // Show success message
                        console.log('HVAC toggled successfully');
                    } else {
                        alert('Gagal mengubah status: ' + (data.message || 'Unknown error'));
                        this.innerHTML = originalHtml;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                    this.innerHTML = originalHtml;
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    });
</script>
@endpush
