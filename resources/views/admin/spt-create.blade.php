@extends('layouts.admin')

@section('title', 'Tambah Surat Perintah Tugas (SPT)')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Surat Perintah Tugas Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data SPT baru</p>
        </div>
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

<!-- Form Tambah SPT -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('spt.store') }}" method="POST" id="formSPT">
            @csrf
            
            <!-- Grid 2 kolom untuk informasi dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Nomor Surat -->
                    <div>
                        <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nomor_surat" 
                               name="nomor_surat" 
                               value="{{ old('nomor_surat') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: SPT-001/2024">
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor surat dengan format yang sesuai</p>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal pembuatan surat</p>
                    </div>

                    <!-- Lokasi - DEFAULT PELAIHARI DAN DISABLE -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="lokasi" 
                               name="lokasi" 
                               value="Pelaihari"
                               required
                               disabled
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none cursor-not-allowed text-gray-600">
                        <input type="hidden" name="lokasi" value="Pelaihari">
                        <p class="mt-1 text-sm text-gray-500">Lokasi default kantor (Pelaihari)</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Penanda Tangan -->
                    <div>
                        <label for="penanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Penanda Tangan <span class="text-red-500">*</span>
                        </label>
                        <select id="penanda_tangan" 
                                name="penanda_tangan" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Penanda Tangan</option>
                            @foreach($penandaTangans as $pegawai)
                                <option value="{{ $pegawai->id_pegawai }}" {{ old('penanda_tangan') == $pegawai->id_pegawai ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} - {{ $pegawai->jabatan }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih pejabat yang menandatangani surat</p>
                    </div>
                </div>
            </div>

            <!-- Dasar (Dynamic Fields) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dasar <span class="text-red-500">*</span>
                </label>
                <div id="dasar-container" class="space-y-3">
                    @php
                        $oldDasar = old('dasar', ['']);
                    @endphp
                    @foreach($oldDasar as $index => $value)
                    <div class="flex items-start space-x-2 dasar-item">
                        <div class="flex-grow">
                            <input type="text" 
                                   name="dasar[]" 
                                   value="{{ $value }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Contoh: Undangan Rapat Nomor ...">
                        </div>
                        <button type="button" 
                                class="remove-dasar bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                                title="Hapus dasar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="tambah-dasar" class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Dasar Lainnya
                </button>
            </div>

            <!-- PEGAWAI YANG DITUGASKAN (Dynamic Fields seperti Dasar) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pegawai yang Ditugaskan <span class="text-red-500">*</span>
                </label>
                <div id="pegawai-container" class="space-y-3">
                    @php
                        $oldPegawai = old('pegawai', ['']);
                    @endphp
                    @foreach($oldPegawai as $index => $value)
                    <div class="flex items-start space-x-2 pegawai-item">
                        <div class="flex-grow">
                            <select name="pegawai[]" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pegawai-select">
                                <option value="">Pilih Pegawai</option>
                                @foreach($semuaPegawai as $pegawai)
                                    <option value="{{ $pegawai->id_pegawai }}" 
                                        data-nama="{{ $pegawai->nama }}"
                                        data-nip="{{ $pegawai->nip }}"
                                        data-jabatan="{{ $pegawai->jabatan }}"
                                        {{ $value == $pegawai->id_pegawai ? 'selected' : '' }}>
                                        {{ $pegawai->nama }} - {{ $pegawai->nip }} ({{ $pegawai->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" 
                                class="remove-pegawai bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                                title="Hapus pegawai">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="tambah-pegawai" class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pegawai Lainnya
                </button>
                
                <!-- Preview Pegawai yang Dipilih -->
                <div id="selected-pegawai-preview" class="mt-3 flex flex-wrap gap-2"></div>
            </div>

            <!-- Tujuan -->
            <div class="mb-6">
                <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                    Tujuan <span class="text-red-500">*</span>
                </label>
                <textarea id="tujuan" 
                          name="tujuan" 
                          rows="4"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Jelaskan tujuan penugasan...">{{ old('tujuan') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Uraikan maksud dan tujuan pelaksanaan tugas</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('spt.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan SPT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ========== DASAR DYNAMIC FIELDS ==========
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus ke input nomor surat
    document.getElementById('nomor_surat').focus();
    
    // Setup dasar fields
    updateDasarButtons();
    setupPegawaiFields();
    updatePegawaiPreview();
    
    // Tambah dasar baru
    document.getElementById('tambah-dasar').addEventListener('click', function() {
        const container = document.getElementById('dasar-container');
        const newItem = document.createElement('div');
        newItem.className = 'flex items-start space-x-2 dasar-item';
        newItem.innerHTML = `
            <div class="flex-grow">
                <input type="text" 
                       name="dasar[]" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                       placeholder="Contoh: Undangan Rapat Nomor ...">
            </div>
            <button type="button" 
                    class="remove-dasar bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                    title="Hapus dasar">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
        
        // Focus ke input baru
        newItem.querySelector('input').focus();
        
        // Update tombol remove
        updateDasarButtons();
    });
    
    // Fungsi untuk update tombol remove dasar
    function updateDasarButtons() {
        const items = document.querySelectorAll('.dasar-item');
        
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-dasar');
            if (items.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
        
        // Event listener untuk remove
        document.querySelectorAll('.remove-dasar').forEach(btn => {
            btn.removeEventListener('click', removeDasar);
            btn.addEventListener('click', removeDasar);
        });
    }
    
    function removeDasar(e) {
        const item = e.currentTarget.closest('.dasar-item');
        const container = document.getElementById('dasar-container');
        
        if (container.children.length > 1) {
            item.remove();
            updateDasarButtons();
        }
    }
});

// ========== PEGAWAI DYNAMIC FIELDS ==========
function setupPegawaiFields() {
    // Tambah pegawai baru
    document.getElementById('tambah-pegawai').addEventListener('click', function() {
        const container = document.getElementById('pegawai-container');
        const newItem = document.createElement('div');
        newItem.className = 'flex items-start space-x-2 pegawai-item';
        
        // Clone select options dari item pertama
        const firstSelect = document.querySelector('.pegawai-select');
        let options = '';
        if (firstSelect) {
            options = firstSelect.innerHTML;
        } else {
            // Generate options dari data semuaPegawai
            options = `<option value="">Pilih Pegawai</option>`;
            @foreach($semuaPegawai as $pegawai)
                options += `<option value="{{ $pegawai->id_pegawai }}" 
                    data-nama="{{ $pegawai->nama }}"
                    data-nip="{{ $pegawai->nip }}"
                    data-jabatan="{{ $pegawai->jabatan }}">
                    {{ $pegawai->nama }} - {{ $pegawai->nip }} ({{ $pegawai->jabatan }})
                </option>`;
            @endforeach
        }
        
        newItem.innerHTML = `
            <div class="flex-grow">
                <select name="pegawai[]" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pegawai-select">
                    ${options}
                </select>
            </div>
            <button type="button" 
                    class="remove-pegawai bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                    title="Hapus pegawai">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
        
        // Focus ke select baru
        newItem.querySelector('select').focus();
        
        // Update event listener untuk select baru
        newItem.querySelector('select').addEventListener('change', updatePegawaiPreview);
        newItem.querySelector('select').addEventListener('change', function() {
            disableSelectedPegawaiOptions();
        });
        
        // Update tombol remove
        updatePegawaiButtons();
        disableSelectedPegawaiOptions();
    });
    
    // Update tombol remove pegawai
    updatePegawaiButtons();
    
    // Event listener untuk semua select pegawai
    document.querySelectorAll('.pegawai-select').forEach(select => {
        select.addEventListener('change', updatePegawaiPreview);
        select.addEventListener('change', function() {
            disableSelectedPegawaiOptions();
        });
    });
    
    // Initial disable options
    disableSelectedPegawaiOptions();
}

function updatePegawaiButtons() {
    const items = document.querySelectorAll('.pegawai-item');
    
    items.forEach((item, index) => {
        const removeBtn = item.querySelector('.remove-pegawai');
        if (items.length === 1) {
            removeBtn.style.display = 'none';
        } else {
            removeBtn.style.display = 'block';
        }
    });
    
    // Event listener untuk remove
    document.querySelectorAll('.remove-pegawai').forEach(btn => {
        btn.removeEventListener('click', removePegawai);
        btn.addEventListener('click', removePegawai);
    });
}

function removePegawai(e) {
    const item = e.currentTarget.closest('.pegawai-item');
    const container = document.getElementById('pegawai-container');
    
    if (container.children.length > 1) {
        item.remove();
        updatePegawaiButtons();
        updatePegawaiPreview();
        disableSelectedPegawaiOptions();
    }
}

function updatePegawaiPreview() {
    const preview = document.getElementById('selected-pegawai-preview');
    const selects = document.querySelectorAll('.pegawai-select');
    const selectedData = [];
    
    preview.innerHTML = '';
    
    selects.forEach(select => {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const nama = selectedOption.getAttribute('data-nama') || selectedOption.text.split(' - ')[0];
            const nip = selectedOption.getAttribute('data-nip') || '';
            const jabatan = selectedOption.getAttribute('data-jabatan') || '';
            
            selectedData.push({
                nama: nama,
                nip: nip,
                jabatan: jabatan
            });
            
            const badge = document.createElement('span');
            badge.className = 'bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded flex items-center';
            badge.title = `${nip} - ${jabatan}`;
            badge.innerHTML = `
                <i class="fas fa-user mr-1"></i>
                ${nama}
            `;
            preview.appendChild(badge);
        }
    });
    
    // Jika tidak ada pegawai dipilih
    if (selectedData.length === 0) {
        const emptyMsg = document.createElement('span');
        emptyMsg.className = 'text-gray-400 text-sm';
        emptyMsg.textContent = 'Belum ada pegawai dipilih';
        preview.appendChild(emptyMsg);
    }
}

// Fungsi untuk menonaktifkan option yang sudah dipilih di select lain
function disableSelectedPegawaiOptions() {
    const selects = document.querySelectorAll('.pegawai-select');
    const selectedValues = [];
    
    // Kumpulkan semua nilai yang sudah dipilih
    selects.forEach(select => {
        if (select.value) {
            selectedValues.push(select.value);
        }
    });
    
    // Loop semua select
    selects.forEach(select => {
        // Loop semua option dalam select
        Array.from(select.options).forEach(option => {
            if (option.value) {
                // Jika option ada di selectedValues DAN bukan option yang sedang dipilih di select ini
                if (selectedValues.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            }
        });
    });
}

// ========== VALIDASI FORM ==========
document.getElementById('formSPT').addEventListener('submit', function(e) {
    // Validasi Nomor Surat
    const nomorSurat = document.getElementById('nomor_surat');
    if (!nomorSurat.value.trim()) {
        alert('Nomor Surat wajib diisi');
        e.preventDefault();
        nomorSurat.focus();
        return false;
    }
    
    // Validasi Dasar (minimal 1)
    const dasarInputs = document.querySelectorAll('input[name="dasar[]"]');
    let dasarValid = false;
    dasarInputs.forEach(input => {
        if (input.value.trim()) dasarValid = true;
    });
    
    if (!dasarValid) {
        alert('Minimal 1 dasar harus diisi');
        e.preventDefault();
        document.querySelector('input[name="dasar[]"]').focus();
        return false;
    }
    
    // Validasi Pegawai (minimal 1)
    const pegawaiSelects = document.querySelectorAll('select[name="pegawai[]"]');
    let pegawaiValid = false;
    pegawaiSelects.forEach(select => {
        if (select.value) pegawaiValid = true;
    });
    
    if (!pegawaiValid) {
        alert('Minimal 1 pegawai harus dipilih');
        e.preventDefault();
        document.querySelector('select[name="pegawai[]"]').focus();
        return false;
    }
    
    // Validasi Tujuan
    const tujuan = document.getElementById('tujuan');
    if (!tujuan.value.trim()) {
        alert('Tujuan wajib diisi');
        e.preventDefault();
        tujuan.focus();
        return false;
    }
    
    // Validasi Tanggal
    const tanggal = document.getElementById('tanggal');
    if (!tanggal.value) {
        alert('Tanggal wajib diisi');
        e.preventDefault();
        tanggal.focus();
        return false;
    }
    
    // Validasi Penanda Tangan
    const penandaTangan = document.getElementById('penanda_tangan');
    if (!penandaTangan.value) {
        alert('Penanda Tangan wajib dipilih');
        e.preventDefault();
        penandaTangan.focus();
        return false;
    }
    
    return true;
});

// ========== AUTO FORMAT NOMOR SURAT ==========
document.getElementById('nomor_surat').addEventListener('input', function(e) {
    this.value = this.value.replace(/\s+/g, ' ').trim();
});

// ========== AUTO UPDATE PREVIEW SAAT LOAD ==========
document.addEventListener('DOMContentLoaded', function() {
    // Update preview pegawai
    updatePegawaiPreview();
});
</script>
@endsection