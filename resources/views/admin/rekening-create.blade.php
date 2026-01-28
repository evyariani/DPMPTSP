@extends('layouts.admin')

@section('title', 'Tambah Rekening')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Rekening Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data rekening baru</p>
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

<!-- Form Tambah Rekening -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/rekening" method="POST" id="formRekening">
            @csrf
            
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
                               value="{{ old('kode_rek') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: 5.1.2.01.01">
                        <p class="mt-1 text-sm text-gray-500">Masukkan kode rekening sesuai struktur akuntansi</p>
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label for="nomor_rek" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nomor_rek" 
                               name="nomor_rek" 
                               value="{{ old('nomor_rek') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: 1234567890">
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor rekening tanpa spasi</p>
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
                                  placeholder="Deskripsi atau keterangan rekening">{{ old('uraian') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Masukkan sub kegiatan</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/rekening" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Contoh Data Rekening -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Contoh Data Rekening:</h3>
            <div class="mt-2 text-sm text-blue-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-medium mb-2">Struktur Data:</p>
                        <ul class="space-y-2">
                            <li>
                                <span class="font-medium">Kode Rekening:</span>
                                <ul class="ml-4 mt-1 space-y-1">
                                    <li>• 5.1.2.01.01 (Belanja Barang)</li>
                                    <li>• 5.1.2.01.02 (Belanja Jasa)</li>
                                    <li>• 5.1.2.02.01 (Belanja Modal)</li>
                                    <li>• 5.2.1.01.01 (Beban Transportasi)</li>
                                </ul>
                            </li>
                            <li>
                                <span class="font-medium">Nomor Rekening:</span>
                                <ul class="ml-4 mt-1 space-y-1">
                                    <li>• 1234567890 (16-20 digit)</li>
                                    <li>• 1122334455</li>
                                    <li>• Tanpa spasi atau tanda baca</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white p-3 rounded border border-blue-100">
                        <p class="font-medium mb-2">Uraian:</p>
                        <ul class="space-y-2 text-blue-600">
                            <li>• <span class="font-medium">Belanja Barang Pakai Habis</span><br>
                                Untuk pembelian ATK dan bahan habis pakai
                            </li>
                            <li>• <span class="font-medium">Belanja Jasa Konsultan</span><br>
                                Untuk pembayaran jasa konsultasi
                            </li>
                            <li>• <span class="font-medium">Belanja Modal Peralatan</span><br>
                                Untuk pembelian peralatan kantor
                            </li>
                            <li>• <span class="font-medium">Beban Perjalanan Dinas</span><br>
                                Untuk biaya transportasi dan akomodasi
                            </li>
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
    
    // Set placeholder untuk input
    const kodeInput = document.getElementById('kode_rek');
    const nomorInput = document.getElementById('nomor_rek');
    const uraianInput = document.getElementById('uraian');
    
    kodeInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: 5.1.2.01.01';
        }
    });
    
    nomorInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: 1234567890';
        }
    });
    
    uraianInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: Belanja Barang Pakai Habis untuk ATK kantor';
        }
    });
});
</script>
@endsection