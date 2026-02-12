@extends('layouts.admin')

@section('title', 'Tambah Uang Harian')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Uang Harian Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data uang harian perjalanan dinas</p>
        </div>
        {{-- <a href="/uang-harian" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
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

@php
    $selectedProvinsi = old('provinsi_id') ? \App\Models\Daerah::find(old('provinsi_id')) : null;
    $selectedKabupaten = old('kabupaten_id') ? \App\Models\Daerah::find(old('kabupaten_id')) : null;
    $selectedKecamatan = old('kecamatan_id') ? \App\Models\Daerah::find(old('kecamatan_id')) : null;
    
    $isKalsel = $selectedProvinsi && trim($selectedProvinsi->kode) == '63';
    $isTanahLaut = $selectedKabupaten && strtolower(trim($selectedKabupaten->nama)) == 'tanah laut';
@endphp

<!-- Form Tambah Uang Harian -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="/uang-harian" method="POST" id="formUangHarian">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Provinsi -->
                    <div>
                        <label for="provinsi_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select id="provinsi_id" 
                                name="provinsi_id" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}" {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>
                                    {{ $provinsi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten/Kota (Hanya Muncul untuk KALSEL) -->
                    <div id="kabupaten-container" class="{{ $isKalsel ? '' : 'hidden' }}">
                        <label for="kabupaten_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kabupaten/Kota Tujuan @if($isKalsel)<span class="text-red-500">*</span>@endif
                        </label>
                        <select id="kabupaten_id" 
                                name="kabupaten_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Wajib pilih kabupaten/kota untuk provinsi Kalimantan Selatan</p>
                    </div>

                    <!-- Kecamatan (Hanya Muncul untuk TANAH LAUT) -->
                    <div id="kecamatan-container" class="{{ $isTanahLaut ? '' : 'hidden' }}">
                        <label for="kecamatan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan Tujuan @if($isTanahLaut)<span class="text-red-500">*</span>@endif
                        </label>
                        <select id="kecamatan_id" 
                                name="kecamatan_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Wajib pilih kecamatan untuk kabupaten Tanah Laut</p>
                    </div>

                    <!-- Daerah Tujuan (Hasil Akhir) -->
                    <div>
                        <label for="daerah_tujuan_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Daerah Tujuan <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p id="daerah_tujuan_text" class="text-gray-700 min-h-[42px]">
                                @if(old('provinsi_id'))
                                    {{ $selectedKecamatan->nama ?? $selectedKabupaten->nama ?? $selectedProvinsi->nama ?? '' }}
                                @else
                                    <span class="text-gray-400">Pilih provinsi terlebih dahulu</span>
                                @endif
                            </p>
                        </div>
                        <input type="hidden" id="daerah_id" name="daerah_id" value="{{ old('daerah_id') }}">
                        <p class="mt-1 text-sm text-gray-500">Daerah tujuan akan otomatis terisi berdasarkan pilihan Anda</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Uang Harian -->
                    <div>
                        <label for="uang_harian" class="block text-sm font-medium text-gray-700 mb-2">
                            Uang Harian (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" 
                                   id="uang_harian" 
                                   name="uang_harian" 
                                   value="{{ old('uang_harian') ? number_format(old('uang_harian'), 0, ',', '.') : '' }}"
                                   required
                                   class="w-full pl-12 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="0">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Besaran uang harian per hari</p>
                    </div>

                    <!-- Uang Transport -->
                    <div>
                        <label for="uang_transport" class="block text-sm font-medium text-gray-700 mb-2">
                            Uang Transport (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" 
                                   id="uang_transport" 
                                   name="uang_transport" 
                                   value="{{ old('uang_transport') ? number_format(old('uang_transport'), 0, ',', '.') : '' }}"
                                   required
                                   class="w-full pl-12 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="0">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Biaya transportasi perjalanan</p>
                    </div>

                    <!-- Total Otomatis -->
                    <div>
                        <label for="total" class="block text-sm font-medium text-gray-700 mb-2">
                            Total (Rp)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text" 
                                   id="total" 
                                   name="total" 
                                   readonly
                                   class="w-full pl-12 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700"
                                   placeholder="0">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Total uang harian + transport (otomatis)</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Wilayah Khusus -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Wilayah Khusus:</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Untuk provinsi <strong>Kalimantan Selatan</strong>, wajib memilih kabupaten/kota</li>
                                <li>Untuk kabupaten <strong>Tanah Laut</strong>, wajib memilih kecamatan</li>
                                <li>Untuk provinsi lainnya, cukup memilih provinsi saja</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="/uang-harian" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Uang Harian
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Format Rupiah
function formatRupiah(angka) {
    let numberString = angka.replace(/[^,\d]/g, '').toString(),
        split = numberString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

// Parse Rupiah ke Integer
function parseRupiah(rupiah) {
    if (!rupiah) return 0;
    return parseInt(rupiah.replace(/[^,\d]/g, '')) || 0;
}

// Hitung Total
function hitungTotal() {
    let uangHarian = parseRupiah(document.getElementById('uang_harian').value);
    let uangTransport = parseRupiah(document.getElementById('uang_transport').value);
    let total = uangHarian + uangTransport;
    
    document.getElementById('total').value = total > 0 ? formatRupiah(total.toString()) : '0';
}

// Update Daerah Tujuan
function updateDaerahTujuan() {
    let provinsiSelect = document.getElementById('provinsi_id');
    let kabupatenSelect = document.getElementById('kabupaten_id');
    let kecamatanSelect = document.getElementById('kecamatan_id');
    let daerahTujuanText = document.getElementById('daerah_tujuan_text');
    let daerahIdInput = document.getElementById('daerah_id');
    
    let provinsiId = provinsiSelect.value;
    let kabupatenId = kabupatenSelect.value;
    let kecamatanId = kecamatanSelect.value;
    
    let provinsiNama = provinsiSelect.options[provinsiSelect.selectedIndex]?.text || '';
    let kabupatenNama = kabupatenSelect.options[kabupatenSelect.selectedIndex]?.text || '';
    let kecamatanNama = kecamatanSelect.options[kecamatanSelect.selectedIndex]?.text || '';
    
    // Tentukan daerah tujuan dan ID berdasarkan level terakhir
    if (kecamatanId) {
        daerahTujuanText.innerHTML = kecamatanNama;
        daerahIdInput.value = kecamatanId;
    } else if (kabupatenId) {
        daerahTujuanText.innerHTML = kabupatenNama;
        daerahIdInput.value = kabupatenId;
    } else if (provinsiId) {
        daerahTujuanText.innerHTML = provinsiNama;
        daerahIdInput.value = provinsiId;
    } else {
        daerahTujuanText.innerHTML = '<span class="text-gray-400">Pilih provinsi terlebih dahulu</span>';
        daerahIdInput.value = '';
    }
}

// Event listener untuk format rupiah dan hitung total
document.getElementById('uang_harian').addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.replace(/[^0-9]/g, '');
    e.target.value = value ? formatRupiah(value) : '';
    hitungTotal();
});

document.getElementById('uang_transport').addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.replace(/[^0-9]/g, '');
    e.target.value = value ? formatRupiah(value) : '';
    hitungTotal();
});

// ========== PERBAIKAN: Dynamic Select Kabupaten ==========
document.getElementById('provinsi_id').addEventListener('change', function() {
    let provinsiId = this.value;
    let kabupatenContainer = document.getElementById('kabupaten-container');
    let kabupatenSelect = document.getElementById('kabupaten_id');
    let kecamatanContainer = document.getElementById('kecamatan-container');
    let kecamatanSelect = document.getElementById('kecamatan_id');
    
    // Reset
    kabupatenContainer.classList.add('hidden');
    kecamatanContainer.classList.add('hidden');
    kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    
    if (provinsiId) {
        fetch(`/uang-harian/get-kabupaten?provinsi_id=${provinsiId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Data kabupaten:', data); // Untuk debugging
                
                // HANYA MUNCULKAN JIKA can_select_kabupaten = true
                if (data.success && data.can_select_kabupaten && data.data && data.data.length > 0) {
                    kabupatenContainer.classList.remove('hidden');
                    
                    data.data.forEach(function(kabupaten) {
                        let option = document.createElement('option');
                        option.value = kabupaten.id;
                        option.textContent = kabupaten.nama;
                        @if(old('kabupaten_id'))
                            if (kabupaten.id == '{{ old('kabupaten_id') }}') {
                                option.selected = true;
                            }
                        @endif
                        kabupatenSelect.appendChild(option);
                    });
                    
                    @if(old('kabupaten_id'))
                        kabupatenSelect.dispatchEvent(new Event('change'));
                    @endif
                } else {
                    // PASTIKAN TIDAK MUNCUL
                    kabupatenContainer.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    updateDaerahTujuan();
});

// ========== PERBAIKAN: Dynamic Select Kecamatan ==========
document.getElementById('kabupaten_id').addEventListener('change', function() {
    let kabupatenId = this.value;
    let kecamatanContainer = document.getElementById('kecamatan-container');
    let kecamatanSelect = document.getElementById('kecamatan_id');
    
    // Reset
    kecamatanContainer.classList.add('hidden');
    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    
    if (kabupatenId) {
        fetch(`/uang-harian/get-kecamatan?kabupaten_id=${kabupatenId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Data kecamatan:', data); // Untuk debugging
                
                // HANYA MUNCULKAN JIKA can_select_kecamatan = true
                if (data.success && data.can_select_kecamatan && data.data && data.data.length > 0) {
                    kecamatanContainer.classList.remove('hidden');
                    
                    data.data.forEach(function(kecamatan) {
                        let option = document.createElement('option');
                        option.value = kecamatan.id;
                        option.textContent = kecamatan.nama;
                        @if(old('kecamatan_id'))
                            if (kecamatan.id == '{{ old('kecamatan_id') }}') {
                                option.selected = true;
                            }
                        @endif
                        kecamatanSelect.appendChild(option);
                    });
                } else {
                    // PASTIKAN TIDAK MUNCUL
                    kecamatanContainer.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    updateDaerahTujuan();
});

// Event listener untuk update daerah tujuan
document.getElementById('kecamatan_id').addEventListener('change', updateDaerahTujuan);
document.getElementById('provinsi_id').addEventListener('change', updateDaerahTujuan);
document.getElementById('kabupaten_id').addEventListener('change', updateDaerahTujuan);

// Load old values jika ada
document.addEventListener('DOMContentLoaded', function() {
    @if(old('provinsi_id'))
        let provinsiSelect = document.getElementById('provinsi_id');
        setTimeout(function() {
            provinsiSelect.dispatchEvent(new Event('change'));
        }, 100);
    @endif
    
    hitungTotal();
    updateDaerahTujuan();
});

// Validasi form sebelum submit
document.getElementById('formUangHarian').addEventListener('submit', function(e) {
    let provinsi = document.getElementById('provinsi_id').value;
    let kabupaten = document.getElementById('kabupaten_id').value;
    let kecamatan = document.getElementById('kecamatan_id').value;
    let uangHarian = document.getElementById('uang_harian').value;
    let uangTransport = document.getElementById('uang_transport').value;
    let daerahId = document.getElementById('daerah_id').value;
    
    if (!provinsi) {
        alert('Provinsi tujuan wajib dipilih');
        e.preventDefault();
        document.getElementById('provinsi_id').focus();
        return false;
    }
    
    if (!daerahId) {
        alert('Daerah tujuan tidak valid');
        e.preventDefault();
        return false;
    }
    
    if (!uangHarian) {
        alert('Uang harian wajib diisi');
        e.preventDefault();
        document.getElementById('uang_harian').focus();
        return false;
    }
    
    if (!uangTransport) {
        alert('Uang transport wajib diisi');
        e.preventDefault();
        document.getElementById('uang_transport').focus();
        return false;
    }
    
    // Convert format rupiah ke integer sebelum submit
    document.getElementById('uang_harian').value = parseRupiah(uangHarian);
    document.getElementById('uang_transport').value = parseRupiah(uangTransport);
    
    return true;
});
</script>
@endsection