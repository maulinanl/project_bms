<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pilih Lokasi Gedung | Project BMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />

    <!-- Load Font & CSS Bawaan Metronic -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS untuk efek hover kartu -->
    <style>
        .building-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .building-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: #009ef7 !important; /* Warna Primary Metronic */
        }
    </style>
</head>

<body id="kt_body" class="bg-body">
    <!-- Main Container -->
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid">

            <!-- Konten Tengah -->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">

                <!-- Logo -->
                <a href="#" class="mb-12">
                    <img alt="Logo" src="{{ asset('assets/media/logos/logo-javadwipa.png') }}" class="h-60px" />
                </a>

                <!-- Card Wrapper -->
                <div class="w-lg-900px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto border">

                    <!-- Header Teks -->
                    <div class="text-center mb-10">
                        <h1 class="text-dark mb-3 fs-1">Pilih Lokasi Gedung</h1>
                        <div class="text-gray-400 fw-bold fs-4">
                            Halo, <span class="text-primary">{{ Auth::user()->name ?? 'User' }}</span>.
                            Pilih gedung yang ingin Anda kelola.
                        </div>
                    </div>

                    <!-- Cek jika data gedung kosong -->
                    @if(isset($gedungList) && $gedungList->isEmpty())
                        <div class="alert alert-warning d-flex align-items-center p-5 mb-10">
                            <!-- Icon Warning -->
                            <span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
                                <i class="fas fa-exclamation-triangle fs-2"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-dark">Data Kosong</h4>
                                <span>Belum ada data gedung yang tersedia. Silakan hubungi Super Admin.</span>
                            </div>
                        </div>
                    @else
                        <!-- Grid Gedung -->
                        <div class="row g-6 g-xl-9">
                            @foreach($gedungList as $gedung)
                            <div class="col-md-6 col-lg-4">
                                <!-- Form submit saat kartu diklik -->
                                <form action="{{ route('building.set') }}" method="POST" class="h-100">
                                    @csrf
                                    <input type="hidden" name="gedung_id" value="{{ $gedung->id }}">

                                    <!-- Kartu Gedung -->
                                    <button type="submit" class="card building-card border border-dashed border-gray-300 bg-light-primary h-100 w-100 p-6 d-flex flex-column align-items-start text-start bg-white">

                                        <!-- Icon / Gambar Gedung -->
                                        <div class="symbol symbol-60px mb-5">
                                            <div class="symbol-label bg-white border border-gray-200">
                                                <i class="fas fa-building fs-2 text-primary"></i>
                                            </div>
                                        </div>

                                        <!-- Informasi Gedung -->
                                        <div class="fs-4 fw-bolder text-dark mb-2">
                                            {{ $gedung->nama_gedung ?? $gedung->name }}
                                        </div>

                                        <div class="fw-bold text-gray-400 fs-7 mb-4">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{Str::limit($gedung->lokasi ?? $gedung->address ?? 'Lokasi Utama', 30) }}
                                        </div>

                                        <!-- Badge Status -->
                                        <div class="mt-auto w-100 d-flex justify-content-between align-items-center">
                                            <span class="badge badge-light-success fw-bolder">Online</span>
                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                        </div>
                                    </button>
                                </form>
                            </div>
                            @endforeach

                            <!-- Tombol Dummy Tambah (Hanya Visual) -->
                            <div class="col-md-6 col-lg-4">
                                <a href="#" class="card building-card border border-dashed border-gray-300 bg-light h-100 w-100 p-6 d-flex flex-column flex-center text-center">
                                    <span class="symbol symbol-50px mb-5">
                                        <span class="symbol-label bg-white">
                                            <i class="fas fa-plus fs-2 text-gray-400"></i>
                                        </span>
                                    </span>
                                    <span class="fs-4 fw-bolder text-gray-600">Tambah Gedung</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Footer: Tombol Logout -->
                    <div class="text-center mt-15 border-top pt-10">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light-danger fw-bolder px-6">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar Akun
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
