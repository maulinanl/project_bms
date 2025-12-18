<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Gedung - Javadwipa BMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>body{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial}</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-6 lg:px-12 sticky top-0 z-30">
        <a href="{{ route('building.select') }}" class="text-gray-500 hover:text-blue-600 mr-4"><i class="fas fa-arrow-left text-xl"></i></a>
        <span class="font-bold text-lg text-gray-800">Tambah Gedung Baru</span>
    </nav>

    <div class="flex items-center justify-center py-16 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-2xl">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">Informasi Gedung</h2>
                <p class="text-gray-500 text-sm">Lengkapi detail properti baru untuk mulai memonitoring.</p>
            </div>

            <form action="{{ route('building.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Gedung</label>
                        <input name="building_name" value="{{ old('building_name') }}" type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Gedung Menara A" required>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi / Alamat</label>
                        <input name="building_adress" value="{{ old('building_adress') }}" type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Jl. Sudirman No. 1" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gateway ID</label>
                        <input name="gateway_id" value="{{ old('gateway_id') }}" type="number" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: 0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas Power (kW)</label>
                        <input name="building_daya" value="{{ old('building_daya') }}" type="number" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: 500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input name="building_longitude" value="{{ old('building_longitude') }}" type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: 106.827153">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input name="building_latitude" value="{{ old('building_latitude') }}" type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: -6.175110">
                    </div>

                    <div class="col-span-2">
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" name="gateway_status" value="1" class="form-checkbox h-4 w-4 text-blue-600" {{ old('gateway_status') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Gateway aktif (ONLINE)</span>
                        </label>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Gedung (opsional)</label>
                        <input name="foto_building" type="file" accept="image/*" class="w-full">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('building.select') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md">Simpan Gedung</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
