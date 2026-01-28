@extends('layouts.admin')

@section('title', 'Edit Rekening')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Rekening</h2>
            <p class="text-gray-500">Ubah data rekening yang sudah ada</p>
        </div>
        <a href="/rekening" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
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

<!-- Form Edit Rekening -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/rekening/{{ $rekening->id_rekening }}" method="POST" id="formRekening">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom 1 -->
                <div class="space-y-6">
                    <!-- Kode Rekening -->
                    <div>
                        <label for="kode_rek" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="kode_rek" 
                               name="kode_rek" 
                               value="{{ old('kode_rek', $rekening->kode_rek) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: 5.1.2.01.01">
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label for="nomor_rek" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nomor_rek" 
                               name="nomor_rek" 
                               value="{{ old('nomor_rek', $rekening->nomor_rek) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: 1234567890">
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="space-y-6">
                    <!-- Uraian -->
                    <div>
                        <label for="uraian" class="block text-sm font-medium text-gray-700 mb-2">
                            Uraian <span class="text-red-500">*</span>
                        </label>
                        <textarea id="uraian" 
                                  name="uraian" 
                                  rows="4"
                                  required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Deskripsi atau keterangan rekening">{{ old('uraian', $rekening->uraian) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/rekening" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update Rekening
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Info Rekening -->
<div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Informasi Rekening Saat Ini:</h3>
            <div class="mt-2 text-sm text-green-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-3 rounded border border-green-100">
                        <p class="font-medium">Kode Rekening:</p>
                        <p class="mt-1 font-semibold">{{ $rekening->kode_rek }}</p>
                    </div>
                    <div class="bg-white p-3 rounded border border-green-100">
                        <p class="font-medium">Nomor Rekening:</p>
                        <p class="mt-1 font-semibold">{{ $rekening->nomor_rek }}</p>
                    </div>
                    <div class="bg-white p-3 rounded border border-green-100">
                        <p class="font-medium">ID Rekening:</p>
                        <p class="mt-1 font-semibold">{{ $rekening->id_rekening }}</p>
                    </div>
                </div>
                <div class="mt-3 bg-white p-3 rounded border border-green-100">
                    <p class="font-medium">Uraian Saat Ini:</p>
                    <p class="mt-1">{{ $rekening->uraian }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Format input nomor rekening (otomatis spasi setiap 4 digit)
document.getElementById('nomor_rek').addEventListener('input', function(e) {
    // Hapus semua karakter selain angka
    let value = e.target.value.replace(/\D/g, '');
    
    // Tambahkan spasi setiap 4 digit
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    
    e.target.value = value;
});

// Format input kode rekening (otomatis titik setelah angka)
document.getElementById('kode_rek').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Hapus semua karakter selain angka dan titik
    value = value.replace(/[^\d\.]/g, '');
    
    // Pastikan tidak ada titik berurutan
    value = value.replace(/\.\.+/g, '.');
    
    // Batasi jumlah titik maksimal 4
    const dotCount = (value.match(/\./g) || []).length;
    if (dotCount > 4) {
        value = value.substring(0, value.length - 1);
    }
    
    e.target.value = value;
});

// Validasi sebelum submit
document.getElementById('formRekening').addEventListener('submit', function(e) {
    // Validasi Kode Rekening wajib diisi
    const kodeInput = document.getElementById('kode_rek');
    if (!kodeInput.value.trim()) {
        alert('Kode Rekening wajib diisi');
        e.preventDefault();
        kodeInput.focus();
        return false;
    }
    
    // Validasi Nomor Rekening wajib diisi
    const nomorInput = document.getElementById('nomor_rek');
    if (!nomorInput.value.trim()) {
        alert('Nomor Rekening wajib diisi');
        e.preventDefault();
        nomorInput.focus();
        return false;
    }
    
    // Validasi Uraian wajib diisi
    const uraianInput = document.getElementById('uraian');
    if (!uraianInput.value.trim()) {
        alert('Uraian wajib diisi');
        e.preventDefault();
        uraianInput.focus();
        return false;
    }
    
    // Hapus spasi dari nomor rekening sebelum submit
    if (nomorInput.value) {
        nomorInput.value = nomorInput.value.replace(/\s/g, '');
    }
    
    return true;
});

// Auto-focus ke input kode rekening saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('kode_rek').focus();
});
</script>
@endsection