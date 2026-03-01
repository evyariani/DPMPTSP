{{-- resources/views/admin/pegawai-create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Pegawai Baru')

@section('subtitle', 'Isi formulir untuk menambahkan data pegawai baru')

@section('content')
<div class="space-y-6">
    {{-- Header dengan Tombol Kembali --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-plus text-indigo-600"></i>
                Tambah Pegawai Baru
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Isi formulir di bawah dengan data pegawai yang valid
            </p>
        </div>
        <a href="/pegawai" 
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-lg shadow-sm flex items-start gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-rose-600 hover:text-rose-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-500 text-xs"></i>
                Formulir Data Pegawai
            </h3>
        </div>
        
        <div class="p-6">
            <form action="/pegawai" method="POST" id="formPegawai">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       required
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-info-circle text-indigo-400"></i>
                                Nama lengkap tanpa gelar
                            </p>
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                NIP
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="nip" 
                                       name="nip" 
                                       value="{{ old('nip') }}"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm font-mono"
                                       placeholder="Contoh: 198507152010011001">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-info-circle text-indigo-400"></i>
                                Format: 18 digit angka (akan terformat otomatis)
                            </p>
                        </div>

                        {{-- Pangkat --}}
                        <div>
                            <label for="pangkat" class="block text-sm font-medium text-gray-700 mb-2">
                                Pangkat
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-circle-up text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="pangkat" 
                                       name="pangkat" 
                                       value="{{ old('pangkat') }}"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                       placeholder="Contoh: Penata Tk. I">
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        {{-- Golongan --}}
                        <div>
                            <label for="gol" class="block text-sm font-medium text-gray-700 mb-2">
                                Golongan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-layer-group text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="gol" 
                                       name="gol" 
                                       value="{{ old('gol') }}"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                       placeholder="Contoh: III/d">
                            </div>
                        </div>

                        {{-- Jabatan --}}
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Jabatan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-briefcase text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="jabatan" 
                                       name="jabatan" 
                                       value="{{ old('jabatan') }}"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                       placeholder="Masukkan jabatan">
                            </div>
                        </div>

                        {{-- Tunjangan Jalan --}}
                        <div>
                            <label for="tk_jalan" class="block text-sm font-medium text-gray-700 mb-2">
                                Tunjangan Jalan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="tk_jalan" 
                                       name="tk_jalan" 
                                       value="{{ old('tk_jalan') }}"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                       placeholder="Contoh: A, B, C, TK I, atau 100000">
                            </div>
                            <div class="mt-1.5 text-xs text-gray-500 space-y-1">
                                <p class="flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] text-indigo-400"></i>
                                    Isi dengan huruf (A, B, C, TK I) atau angka (100000, 250000)
                                </p>
                                <p class="flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] text-indigo-400"></i>
                                    Huruf akan otomatis diubah ke UPPERCASE
                                </p>
                                <p class="flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] text-indigo-400"></i>
                                    Angka akan diformat dengan pemisah ribuan (Rp)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="mt-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-indigo-600 mt-0.5"></i>
                        <div class="text-sm text-indigo-800">
                            <span class="font-medium">Informasi:</span> Kolom bertanda <span class="text-rose-500 font-medium">*</span> wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="/pegawai" 
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Format input NIP dengan spasi setiap 8-6-1-3 digit
document.getElementById('nip').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        value = value.replace(/(\d{8})(\d{6})(\d{1})(\d{3})/, '$1 $2 $3 $4');
    }
    e.target.value = value;
});

// Auto uppercase untuk tk_jalan jika berisi huruf
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
        e.target.value = value;
    }
});

// Saat blur, uppercase jika huruf
document.getElementById('tk_jalan').addEventListener('blur', function(e) {
    let value = e.target.value.trim();
    if (value && !/^\d/.test(value.replace(/\./g, ''))) {
        e.target.value = value.toUpperCase();
    }
});

// Validasi sebelum submit
document.getElementById('formPegawai').addEventListener('submit', function(e) {
    // Validasi Nama wajib diisi
    const namaInput = document.getElementById('nama');
    if (!namaInput.value.trim()) {
        showValidationError('Nama lengkap wajib diisi');
        e.preventDefault();
        namaInput.focus();
        return false;
    }
    
    // Validasi NIP jika diisi harus 18 digit
    const nipInput = document.getElementById('nip');
    if (nipInput.value.trim()) {
        const nipDigits = nipInput.value.replace(/\s/g, '');
        if (nipDigits.length !== 18 || !/^\d+$/.test(nipDigits)) {
            showValidationError('NIP harus 18 digit angka');
            e.preventDefault();
            nipInput.focus();
            return false;
        }
        // Simpan NIP tanpa spasi
        nipInput.value = nipDigits;
    }
    
    // Untuk tk_jalan yang angka, hapus format titik sebelum submit
    const tkJalanInput = document.getElementById('tk_jalan');
    if (tkJalanInput.value) {
        const numericValue = tkJalanInput.value.replace(/\./g, '');
        if (/^\d+$/.test(numericValue)) {
            // Jika angka, hapus titik dan submit angka saja
            tkJalanInput.value = numericValue;
        }
    }
    
    return true;
});

// Fungsi untuk menampilkan error validasi
function showValidationError(message) {
    // Cek apakah sudah ada notifikasi error
    let existingAlert = document.querySelector('[x-data="show"]');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Buat alert baru
    const alertHTML = `
        <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-lg shadow-sm mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button @click="show = false" class="text-amber-600 hover:text-amber-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    // Insert alert setelah header
    const header = document.querySelector('.space-y-6');
    header.insertAdjacentHTML('afterbegin', alertHTML);
}

// Auto-focus ke input nama saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nama').focus();
    
    // Tambahkan tooltip untuk field yang required
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('invalid', function(e) {
            e.preventDefault();
            showValidationError('Harap isi field yang wajib diisi');
        });
    });
});

// Konfirmasi sebelum meninggalkan halaman jika ada perubahan
let formChanged = false;
document.getElementById('formPegawai').addEventListener('input', function() {
    formChanged = true;
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endsection