@extends('layout.master')

@section('title', 'Lighting System')
@section('header_title', 'Lighting System')

@section('content')
<div class="flex h-full overflow-hidden">
    <div class="flex-1 flex flex-col h-full bg-[#f4f6f8] relative">
        <main class="flex-1 overflow-y-auto p-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Lighting Control System</h2>
                    <p class="text-gray-500 text-sm mt-2">Monitor dan kontrol pencahayaan di seluruh ruangan.</p>
                </div>
                <div class="bg-white px-6 py-3 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-[12px] text-gray-500 font-bold uppercase tracking-wider">Total Lighting Units</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ count($lightings) }}</h3>
                </div>
            </div>

            <!-- Lighting Units Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($lightings->count() > 0)
                    @foreach($lightings as $lighting)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $lighting->name ?? 'Lighting Unit #' . $lighting->id }}</h3>
                                    <p class="text-amber-100 text-xs mt-1">
                                        {{ optional($lighting->ruangan)->name ?? 'Ruangan Tidak Diketahui' }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 space-y-4">
                            <!-- Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 font-medium text-sm">Status</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full {{ $lighting->status == 1 ? 'bg-yellow-500' : 'bg-gray-400' }}"></div>
                                    <span class="text-sm font-semibold {{ $lighting->status == 1 ? 'text-yellow-600' : 'text-gray-600' }}">
                                        {{ $lighting->status == 1 ? 'Nyala' : 'Mati' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Brightness Info -->
                            <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Terang (Brightness)</p>
                                    <p class="text-2xl font-bold text-amber-600">{{ $lighting->brightness ?? '-- ' }}%</p>
                                </div>
                                <i class="fas fa-adjust text-amber-500 text-2xl"></i>
                            </div>

                            <!-- Power Info -->
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                <div>
                                    <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Daya</p>
                                    <p class="text-lg font-bold text-orange-600">{{ $lighting->power ?? '-- ' }}W</p>
                                </div>
                                <i class="fas fa-plug text-orange-500 text-xl"></i>
                            </div>

                            <!-- Control Button -->
                            <button 
                                class="device-toggle-lighting w-full py-2.5 px-4 rounded-lg font-semibold transition flex items-center justify-center gap-2 {{ $lighting->status == 1 ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-amber-50 text-amber-600 hover:bg-amber-100' }}"
                                data-lighting-id="{{ $lighting->id }}"
                                data-lighting-status="{{ $lighting->status }}">
                                <i class="fas {{ $lighting->status == 1 ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                <span>{{ $lighting->status == 1 ? 'Matikan Lampu' : 'Nyalakan Lampu' }}</span>
                            </button>

                            <!-- Brightness Slider (Optional) -->
                            <div class="pt-2 border-t border-gray-100">
                                <label class="text-xs font-semibold text-gray-600 mb-2 block">Atur Terang</label>
                                <input type="range" min="0" max="100" value="{{ $lighting->brightness ?? 50 }}" 
                                       class="brightness-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                       data-lighting-id="{{ $lighting->id }}">
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-500">
                            <p>ID: <span class="font-mono">{{ $lighting->id }}</span></p>
                            <p class="mt-1">Last Updated: <span class="font-medium">{{ $lighting->updated_at?->diffForHumans() ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="col-span-full py-12 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada Lighting Unit terdeteksi</p>
                    <p class="text-gray-400 text-sm mt-2">Pastikan ada Lighting Unit yang terdaftar di sistem</p>
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
        // Handle Lighting toggle buttons
        document.querySelectorAll('.device-toggle-lighting').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const lightingId = this.dataset.lightingId;
                const currentStatus = parseInt(this.dataset.lightingStatus);
                const newStatus = currentStatus === 1 ? 0 : 1;

                // Disable button during request
                this.disabled = true;
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Processing...</span>';

                // Send toggle request
                fetch(`/device/lighting/${lightingId}/toggle`, {
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
                        this.dataset.lightingStatus = newStatus;
                        if (newStatus === 1) {
                            this.classList.remove('bg-amber-50', 'text-amber-600', 'hover:bg-amber-100');
                            this.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                            this.innerHTML = '<i class="fas fa-toggle-on text-lg"></i><span>Matikan Lampu</span>';
                        } else {
                            this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                            this.classList.add('bg-amber-50', 'text-amber-600', 'hover:bg-amber-100');
                            this.innerHTML = '<i class="fas fa-toggle-off text-lg"></i><span>Nyalakan Lampu</span>';
                        }
                        // Show success message
                        console.log('Lighting toggled successfully');
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

        // Handle brightness slider (if needed in future)
        document.querySelectorAll('.brightness-slider').forEach(slider => {
            slider.addEventListener('change', function(e) {
                const lightingId = this.dataset.lightingId;
                const brightness = this.value;
                
                // Optional: Send brightness change to server
                console.log(`Brightness for lighting ${lightingId} changed to ${brightness}%`);
            });
        });
    });
</script>
@endpush
