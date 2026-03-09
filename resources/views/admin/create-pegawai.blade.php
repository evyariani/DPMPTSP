@extends('layouts.admin')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Pegawai Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data pegawai baru</p>
        </div>
        {{-- <a href="/pegawai" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a> --}}
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

<!-- Form Tambah Pegawai -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/pegawai" method="POST" id="formPegawai">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom 1 -->
                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan nama lengkap">
                        <p class="mt-1 text-sm text-gray-500">Nama lengkap tanpa gelar</p>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP
                        </label>
                        <input type="text" 
                               id="nip" 
                               name="nip" 
                               value="{{ old('nip') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan NIP (18 digit)">
                        <p class="mt-1 text-sm text-gray-500">Format: 8 digit, 6 digit, 1 digit, 3 digit</p>
                    </div>

                    <!-- Pangkat -->
                    <div>
                        <label for="pangkat" class="block text-sm font-medium text-gray-700 mb-2">
                            Pangkat
                        </label>
                        <input type="text" 
                               id="pangkat" 
                               name="pangkat" 
                               value="{{ old('pangkat') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Penata Tk. I">
                    </div>

                    <!-- Golongan -->
                    <div>
                        <label for="gol" class="block text-sm font-medium text-gray-700 mb-2">
                            Golongan
                        </label>
                        <input type="text" 
                               id="gol" 
                               name="gol" 
                               value="{{ old('gol') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: III/d">
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="space-y-6">
                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jabatan
                        </label>
                        <input type="text" 
                               id="jabatan" 
                               name="jabatan" 
                               value="{{ old('jabatan') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan jabatan">
                    </div>

                    <!-- Tunjangan Jalan -->
                    <div>
                        <label for="tk_jalan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tunjangan Jalan
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="tk_jalan" 
                                   name="tk_jalan" 
                                   value="{{ old('tk_jalan') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Contoh: A, B, C, TK I, atau 100000">
                        </div>
                        <div class="mt-1 text-sm text-gray-500">
                            <p class="mb-1">• Isi dengan huruf (A, B, C, TK I) atau angka (100000, 250000)</p>
                            <p>• Huruf akan otomatis diubah ke UPPERCASE</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/pegawai" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Format input NIP
document.getElementById('nip').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        value = value.replace(/(\d{8})(\d{6})(\d{1})(\d{3})/, '$1 $2 $3 $4');
    }
    e.target.value = value;
});

// Handle input tunjangan jalan
function handleTkJalanInput(input) {
    let value = input.value;
    
    // Jika input dimulai dengan angka, format sebagai mata uang
    if (/^\d/.test(value)) {
        // Hapus semua karakter selain angka
        value = value.replace(/[^\d]/g, '');
        
        // Format dengan titik sebagai pemisah ribuan jika lebih dari 0
        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
    } else {
        // Jika huruf, uppercase otomatis
        value = value.toUpperCase();
    }
    
    input.value = value;
}

// Auto uppercase untuk tk_jalan jika blur dan berisi huruf
document.getElementById('tk_jalan').addEventListener('blur', function(e) {
    let value = e.target.value.trim();
    if (value && !/^\d/.test(value.replace(/\./g, ''))) {
        e.target.value = value.toUpperCase();
    }
});

// Auto format saat input untuk tk_jalan (angka)
document.getElementById('tk_jalan').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Jika input dimulai dengan angka, format sebagai mata uang
    if (/^\d/.test(value)) {
        // Hapus semua karakter selain angka
        value = value.replace(/[^\d]/g, '');
        
        // Format dengan titik sebagai pemisah ribuan jika lebih dari 0
        if (value.length > 0) {
            value = parseInt(value, 10).toLocaleString('id-ID');
        }
    }
    
    e.target.value = value;
});

// Validasi sebelum submit
document.getElementById('formPegawai').addEventListener('submit', function(e) {
    // Untuk tk_jalan yang angka, hapus format titik sebelum submit
    const tkJalanInput = document.getElementById('tk_jalan');
    if (tkJalanInput.value) {
        // Cek apakah value berupa angka (setelah hapus titik)
        const numericValue = tkJalanInput.value.replace(/\./g, '');
        if (/^\d+$/.test(numericValue)) {
            // Jika angka, hapus titik dan submit angka saja
            tkJalanInput.value = numericValue;
        } else {
            // Jika huruf, uppercase dan trim
            tkJalanInput.value = tkJalanInput.value.trim().toUpperCase();
        }
    }
    
    // Validasi NIP jika diisi harus 18 digit
    const nipInput = document.getElementById('nip');
    if (nipInput.value) {
        const nipDigits = nipInput.value.replace(/\s/g, '').replace(/\D/g, '');
        if (nipDigits.length !== 18) {
            alert('NIP harus 18 digit angka');
            e.preventDefault();
            nipInput.focus();
            return false;
        }
    }
    
    // Validasi Nama wajib diisi
    const namaInput = document.getElementById('nama');
    if (!namaInput.value.trim()) {
        alert('Nama lengkap wajib diisi');
        e.preventDefault();
        namaInput.focus();
        return false;
    }
    
    return true;
});

// Auto-focus ke input nama saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nama').focus();
    
    // Set placeholder contoh untuk tk_jalan
    const tkJalanInput = document.getElementById('tk_jalan');
    tkJalanInput.addEventListener('focus', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: A, B, C, TK I, atau 100000';
        }
    });
    
    tkJalanInput.addEventListener('blur', function() {
        if (!this.value) {
            this.placeholder = 'Contoh: A, B, C, TK I, atau 100000';
        }
    });
});
</script>
@endsection