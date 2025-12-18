<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BMS - Javadwipa Technology')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-[#1e1e2d] text-white flex flex-col transition-all duration-300 hidden lg:flex">
            <!-- App Brand -->
            <div class="h-20 flex items-center px-6 bg-[#1a1a27] border-b border-gray-700">
                <i class="fas fa-building text-blue-500 text-2xl mr-3"></i>
                <span class="text-xl font-bold tracking-wide">BMS System</span>
            </div>

            <!-- Building Info Card -->
            <div class="p-4">
                <div class="bg-[#2b2b40] rounded-xl p-4 border border-gray-700">
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-1">Gedung Aktif</div>
                    <div class="flex items-center justify-between">
                        {{-- Nama gedung aktif: prioritas dari session, lalu dari controller variable, lalu fallback --}}
                        <div class="font-bold text-white truncate text-sm">
                            {{ session('active_gedung_name') ?? $activeBuildingName ?? 'Gedung Pusat' }}
                        </div>
                        @php $status = session('active_gedung_status'); @endphp
                        <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: {{ $status ? '#22c55e' : '#f59e0b' }}"></div>
                    </div>
                    <a href="{{ route('building.select') }}" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 rounded transition flex items-center justify-center gap-2">
                        <i class="fas fa-exchange-alt"></i> Ganti Gedung
                    </a>
                </div>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 overflow-y-auto px-4 pb-4 space-y-1">
                <div class="px-2 mt-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Dashboards</div>

                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-[#2b2b40] text-white' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} rounded-lg group transition">
                    <span class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-500 flex items-center justify-center mr-3 group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="fas fa-th-large text-sm"></i>
                    </span>
                    <span class="text-sm font-medium">Overview</span>
                </a>

                <div class="px-2 mt-6 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Manajemen</div>

                <a href="#" class="flex items-center px-3 py-2.5 text-gray-400 hover:text-white hover:bg-[#2b2b40] rounded-lg group transition">
                    <span class="w-8 h-8 rounded-lg bg-gray-700/50 flex items-center justify-center mr-3 group-hover:bg-gray-700 transition">
                        <i class="fas fa-layer-group text-sm"></i>
                    </span>
                    <span class="text-sm font-medium">Data Lantai & Ruang</span>
                </a>

                <div class="px-2 mt-6 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Device Control</div>

                <a href="#" class="flex items-center px-3 py-2.5 text-gray-400 hover:text-white hover:bg-[#2b2b40] rounded-lg group transition">
                    <span class="w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-500 flex items-center justify-center mr-3 group-hover:bg-yellow-500 group-hover:text-white transition">
                        <i class="fas fa-bolt text-sm"></i>
                    </span>
                    <span class="text-sm font-medium">Power System</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 text-gray-400 hover:text-white hover:bg-[#2b2b40] rounded-lg group transition">
                    <span class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center mr-3 group-hover:bg-cyan-500 group-hover:text-white transition">
                        <i class="fas fa-fan text-sm"></i>
                    </span>
                    <span class="text-sm font-medium">HVAC / AC</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2.5 text-gray-400 hover:text-white hover:bg-[#2b2b40] rounded-lg group transition">
                    <span class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center mr-3 group-hover:bg-orange-500 group-hover:text-white transition">
                        <i class="far fa-lightbulb text-sm"></i>
                    </span>
                    <span class="text-sm font-medium">Lighting</span>
                </a>
            </nav>

            <!-- User Profile Bottom -->
            <div class="p-4 border-t border-gray-700 bg-[#151521]">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=0D8ABC&color=fff" class="h-10 w-10 rounded-lg border-2 border-gray-600">
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@javadwipa.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Area -->
        <div class="flex-1 flex flex-col h-full bg-[#f4f6f8] relative">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-20">
                <div class="flex items-center text-gray-400">
                    <span class="text-xs uppercase font-bold tracking-wider">{{ session('active_gedung_name') ?? $activeBuildingName ?? 'Gedung Pusat' }}</span>
                    <i class="fas fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-800 text-xs uppercase font-bold tracking-wider">@yield('header_title', 'Dashboard')</span>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Search -->
                    <div class="hidden md:flex items-center bg-gray-100 rounded-lg px-3 py-2 w-64">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" placeholder="Cari ruangan..." class="bg-transparent border-none text-sm focus:outline-none w-full text-gray-600">
                    </div>

                    <!-- Notif -->
                    <div class="relative cursor-pointer">
                        <i class="far fa-bell text-gray-500 text-lg"></i>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500 transform translate-x-1/2 -translate-y-1/2"></span>
                    </div>

                     <!-- Mobile Menu Button (Visible on Small Screens) -->
                     <button class="lg:hidden text-gray-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </header>

            <!-- Content Scrollable -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
