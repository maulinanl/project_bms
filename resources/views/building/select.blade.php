<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Lokasi - Javadwipa BMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .building-card { transition: all 0.3s ease; }
        .building-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .page-hidden { display: none; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 lg:px-12 sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-building"></i>
            </div>
            <span class="font-bold text-lg text-gray-800 tracking-tight">Javadwipa BMS</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden md:flex flex-col items-end">
                <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name ?? 'Administrator' }}</span>
                <span class="text-xs text-gray-500">Super Admin</span>
            </div>
            <div class="h-8 w-8 rounded-full bg-gray-200 overflow-hidden border border-gray-300 cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=random" alt="Admin">
            </div>

            <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="ml-2 text-gray-400 hover:text-red-500 transition" title="Logout" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <section id="buildingList" class="page-section flex-1 overflow-y-auto">
        <div class="container mx-auto px-6 py-12 max-w-7xl">

            <div class="text-center max-w-2xl mx-auto mb-16">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Pilih Lokasi Gedung</h1>
                <p class="text-gray-500 text-lg">Silakan pilih gedung yang ingin Anda pantau. Setiap lokasi memiliki dashboard monitoringnya masing-masing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Dynamic loop of buildings from database --}}
                @forelse($gedungList as $gedung)
                    <form id="select-gedung-{{ $gedung->id }}" action="{{ route('building.set') }}" method="POST" class="hidden">@csrf<input type="hidden" name="gedung_id" value="{{ $gedung->id }}"></form>

                    <div class="building-card bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden group flex flex-col h-full">
                        <div class="h-56 overflow-hidden relative">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                            @php
                                $img = $gedung->foto_building ? asset($gedung->foto_building) : 'https://images.unsplash.com/photo-1486325212027-8081e485255e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                            @endphp
                            <img src="{{ $img }}" alt="Building" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 {{ $gedung->gateway_status ? '' : 'grayscale' }}">
                            <div class="absolute top-4 right-4 z-20">
                                @if($gedung->gateway_status)
                                    <span class="bg-green-500/90 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-sm shadow-sm flex items-center gap-1">
                                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> ONLINE
                                    </span>
                                @else
                                    <span class="bg-yellow-500/90 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-sm shadow-sm flex items-center gap-1">
                                        <i class="fas fa-tools text-[10px]"></i> MAINTENANCE
                                    </span>
                                @endif
                            </div>
                            <div class="absolute bottom-4 left-4 z-20 text-white">
                                <h3 class="text-xl font-bold">{{ $gedung->building_name }}</h3>
                                <p class="text-sm opacity-90"><i class="fas fa-map-marker-alt mr-1"></i> {{ $gedung->building_adress }}</p>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 p-3 rounded-lg text-center">
                                    <div class="text-xs text-gray-400 uppercase font-semibold">Lantai</div>
                                    <div class="text-lg font-bold text-gray-800">{{ $gedung->lantai_count ?? 0 }}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center">
                                    <div class="text-xs text-gray-400 uppercase font-semibold">Power</div>
                                    <div class="text-lg font-bold text-gray-800">{{ $gedung->building_daya ?? 0 }} kW</div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button type="button" onclick="document.getElementById('select-gedung-{{ $gedung->id }}').submit()" class="w-full py-2.5 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition flex items-center justify-center gap-2 group-hover:bg-blue-600 group-hover:text-white">
                                    Masuk Dashboard <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-12">
                        Belum ada gedung. Klik "Tambah Gedung Baru" untuk menambahkan lokasi.
                    </div>
                @endforelse

                <!-- Tambah Gedung -->
                <div onclick="window.location='{{ route('building.create') }}'" class="building-card bg-gray-50 rounded-2xl shadow-sm border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 transition h-full min-h-[350px]">
                    <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4">
                        <i class="fas fa-plus text-2xl"></i>
                    </div>
                    <span class="font-semibold text-lg">Tambah Gedung Baru</span>
                    <p class="text-sm text-gray-400 mt-2">Setup lokasi baru</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Add-building page moved to its own view (building.create) -->

</body>
</html>
