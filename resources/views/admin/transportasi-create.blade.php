@extends('layouts.admin')

@section('title', 'Tambah Transportasi')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Transportasi Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data transportasi baru</p>
        </div>
        <a href="/transportasi" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Notifikasi -->
@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">Terjadi kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form Tambah Transportasi -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/transportasi" method="POST" id="formTransportasi">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom 1 -->
                <div class="space-y-6">
                    <!-- Jenis Transportasi -->
                    <div>
                        <label for="jenis_transportasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Transportasi <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_transportasi" 
                                name="jenis_transportasi" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Jenis Transportasi --</option>
                            @foreach($jenisTransportasiOptions as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis_transportasi') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih jenis transportasi dari daftar yang tersedia</p>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="space-y-6">
                    <!-- Lama Perjalanan -->
                    <div>
                        <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 mb-2">
                            Lama Perjalanan (Hari) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="lama_perjalanan" 
                                   name="lama_perjalanan" 
                                   value="{{ old('lama_perjalanan') }}"
                                   min="0"
                                   max="365"
                                   step="1"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Contoh: 5">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500">hari</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Masukkan jumlah hari (0-365 hari)</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/transportasi" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Transportasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Contoh Data Transportasi -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Contoh Data Transportasi:</h3>
            <div class="mt-2 text-sm text-blue-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium mb-2">Jenis Transportasi:</p>
                        <ul class="space-y-1">
                            <li>• <span class="font-medium">Transportasi Darat dan Udara</span> (input: 1)</li>
                            <li>• <span class="font-medium">Transportasi Udara</span> (input: 2)</li>
                            <li>• <span class="font-medium">Transportasi Darat</span> (input: 3)</li>
                            <li>• <span class="font-medium">Angkutan Darat</span> (input: 4)</li>
                            <li>• <span class="font-medium">Kendaraan Dinas</span> (input: 5)</li>
                            <li>• <span class="font-medium">Angkutan Umum</span> (input: 0 atau kosong)</li>
                        </ul>
                    </div>
                    <div class="bg-white p-3 rounded border border-blue-100">
                        <p class="font-medium mb-2">Tips Pengisian:</p>
                        <ul class="space-y-1 text-blue-600">
                            <li>• Pilih jenis transportasi dari dropdown</li>
                            <li>• Input jumlah hari (angka saja)</li>
                            <li>• Otomatis akan ditambah "hari" saat disimpan</li>
                            <li>• Contoh: Input "5" akan menjadi "5 hari"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Validasi sebelum submit
document.getElementById('formTransportasi').addEventListener('submit', function(e) {
    // Validasi Jenis Transportasi wajib dipilih
    const jenisInput = document.getElementById('jenis_transportasi');
    if (!jenisInput.value) {
        alert('Jenis Transportasi wajib dipilih');
        e.preventDefault();
        jenisInput.focus();
        return false;
    }
    
    // Validasi Lama Perjalanan wajib diisi
    const lamaInput = document.getElementById('lama_perjalanan');
    if (!lamaInput.value.trim()) {
        alert('Lama Perjalanan wajib diisi');
        e.preventDefault();
        lamaInput.focus();
        return false;
    }
    
    // Validasi angka minimal 0
    const lamaValue = parseInt(lamaInput.value);
    if (lamaValue < 0 || lamaValue > 365) {
        alert('Lama perjalanan harus antara 0-365 hari');
        e.preventDefault();
        lamaInput.focus();
        return false;
    }
    
    return true;
});

// Auto-focus ke dropdown jenis transportasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('jenis_transportasi').focus();
    
    // Set placeholder untuk input lama perjalanan
    const lamaInput = document.getElementById('lama_perjalanan');
    lamaInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: 1, 2, 3, 5';
        }
    });
    
    lamaInput.addEventListener('blur', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: 1, 2, 3, 5';
        }
    });
});
</script>
@endsection