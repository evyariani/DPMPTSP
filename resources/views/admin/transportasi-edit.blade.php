@extends('layouts.admin')

@section('title', 'Edit Transportasi')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Transportasi</h2>
            <p class="text-gray-500">Ubah data transportasi yang sudah ada</p>
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

<!-- Form Edit Transportasi -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/transportasi/{{ $transportasi->id_transportasi }}" method="POST" id="formTransportasi">
            @csrf
            @method('PUT')
            
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
                                <option value="{{ $jenis }}" {{ old('jenis_transportasi', $transportasi->jenis_transportasi) == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
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
                                   value="{{ old('lama_perjalanan', $lamaAngka) }}"
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
                        <p class="mt-1 text-sm text-gray-500">Saat disimpan akan menjadi: <span id="preview-lama" class="font-medium">{{ $transportasi->lama_perjalanan }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/transportasi" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update Transportasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Update preview lama perjalanan
document.getElementById('lama_perjalanan').addEventListener('input', function(e) {
    const value = e.target.value;
    const preview = document.getElementById('preview-lama');
    
    if (value && !isNaN(value)) {
        preview.textContent = value + ' hari';
    } else {
        preview.textContent = '-';
    }
});

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
});
</script>
@endsection