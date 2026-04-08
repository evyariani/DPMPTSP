@extends('layouts.admin')

@section('title', 'Tambah Surat Perintah Dinas (SPD)')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Surat Perintah Dinas Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data SPD baru</p>
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

<!-- Form Tambah SPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('spd.store') }}" method="POST" id="formSPD">
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
                               placeholder="Contoh: SPD-001/2024">
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor surat dengan format yang sesuai</p>
                    </div>

                    <!-- Tanggal Berangkat -->
                    <div>
                        <label for="tanggal_berangkat" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_berangkat"
                               name="tanggal_berangkat"
                               value="{{ old('tanggal_berangkat', date('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal mulai perjalanan dinas</p>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_kembali"
                               name="tanggal_kembali"
                               value="{{ old('tanggal_kembali', date('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal selesai perjalanan dinas</p>
                    </div>

                    <!-- Lama Perjadin (Auto Calculate) -->
                    <div>
                        <label for="lama_perjadin" class="block text-sm font-medium text-gray-700 mb-2">
                            Lama Perjalanan Dinas
                        </label>
                        <input type="number"
                               id="lama_perjadin"
                               name="lama_perjadin"
                               value="{{ old('lama_perjadin') }}"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none cursor-not-allowed text-gray-600">
                        <p class="mt-1 text-sm text-gray-500">Otomatis dihitung berdasarkan tanggal berangkat dan kembali</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Pengguna Anggaran -->
                    <div>
                        <label for="pengguna_anggaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Pengguna Anggaran <span class="text-red-500">*</span>
                        </label>
                        <select id="pengguna_anggaran"
                                name="pengguna_anggaran"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Pengguna Anggaran</option>
                            @foreach($pegawais as $pegawai)
                                <option value="{{ $pegawai->id_pegawai }}"
                                        data-nama="{{ $pegawai->nama }}"
                                        data-nip="{{ $pegawai->nip }}"
                                        data-jabatan="{{ $pegawai->jabatan }}"
                                        {{ old('pengguna_anggaran') == $pegawai->id_pegawai ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} - {{ $pegawai->nip }} ({{ $pegawai->jabatan }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih pegawai yang menjadi pengguna anggaran</p>
                    </div>

                    <!-- Tempat Dikeluarkan -->
                    <div>
                        <label for="tempat_dikeluarkan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Dikeluarkan
                        </label>
                        <input type="text"
                               id="tempat_dikeluarkan"
                               name="tempat_dikeluarkan"
                               value="{{ old('tempat_dikeluarkan', 'Pelaihari') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Pelaihari"
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">Tempat surat dikeluarkan</p>
                    </div>

                    <!-- Tanggal Dikeluarkan -->
                    <div>
                        <label for="tanggal_dikeluarkan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Dikeluarkan
                        </label>
                        <input type="date"
                               id="tanggal_dikeluarkan"
                               name="tanggal_dikeluarkan"
                               value="{{ old('tanggal_dikeluarkan', date('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal surat dikeluarkan</p>
                    </div>
                </div>
            </div>

            <!-- Tempat Berangkat dan Tempat Tujuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tempat Berangkat -->
                <div>
                    <label for="tempat_berangkat" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Berangkat <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="tempat_berangkat"
                           name="tempat_berangkat"
                           value="{{ old('tempat_berangkat', 'Pelaihari') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Tempat asal keberangkatan</p>
                </div>

                <!-- Tempat Tujuan -->
                <div>
                    <label for="tempat_tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select id="tempat_tujuan"
                            name="tempat_tujuan"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Pilih Tempat Tujuan</option>
                        @foreach($daerahs as $daerah)
                            <option value="{{ $daerah->id }}"
                                    data-nama="{{ $daerah->nama }}"
                                    data-kode="{{ $daerah->kode_daerah ?? '' }}"
                                    {{ old('tempat_tujuan') == $daerah->id ? 'selected' : '' }}>
                                {{ $daerah->nama }} @if($daerah->kode_daerah) ({{ $daerah->kode_daerah }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Pilih daerah tujuan perjalanan dinas</p>
                </div>
            </div>

            <!-- Maksud Perjadin -->
            <div class="mb-6">
                <label for="maksud_perjadin" class="block text-sm font-medium text-gray-700 mb-2">
                    Maksud Perjalanan Dinas <span class="text-red-500">*</span>
                </label>
                <textarea id="maksud_perjadin"
                          name="maksud_perjadin"
                          rows="3"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Jelaskan maksud perjalanan dinas...">{{ old('maksud_perjadin') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Uraikan maksud dan tujuan pelaksanaan perjalanan dinas</p>
            </div>

            <!-- Alat Transportasi -->
            <div class="mb-6">
                <label for="alat_transportasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Alat Transportasi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($alatTransportasiList as $key => $label)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                        <input type="radio"
                               name="alat_transportasi"
                               value="{{ $key }}"
                               {{ old('alat_transportasi') == $key ? 'checked' : '' }}
                               required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            @php
                                $icon = match($key) {
                                    'transportasi_darat' => 'fa-bus',
                                    'transportasi_udara' => 'fa-plane',
                                    'transportasi_darat_udara' => 'fa-exchange-alt',
                                    'angkutan_darat' => 'fa-truck',
                                    'kendaraan_dinas' => 'fa-car',
                                    'angkutan_umum' => 'fa-taxi',
                                    default => 'fa-road'
                                };
                            @endphp
                            <i class="fas {{ $icon }} text-gray-500 mr-1"></i>
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
                <p class="mt-2 text-sm text-gray-500">Pilih jenis transportasi yang digunakan</p>
            </div>

            <!-- SKPD dan Kode Rekening -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="skpd" class="block text-sm font-medium text-gray-700 mb-2">
                        SKPD
                    </label>
                    <input type="text"
                           id="skpd"
                           name="skpd"
                           value="{{ old('skpd') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: Dinas Penanaman Modal dan PTSP">
                    <p class="mt-1 text-sm text-gray-500">Satuan Kerja Perangkat Daerah</p>
                </div>

                <div>
                    <label for="kode_rek" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Rekening
                    </label>
                    <input type="text"
                           id="kode_rek"
                           name="kode_rek"
                           value="{{ old('kode_rek') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: 5.2.1.01.01">
                    <p class="mt-1 text-sm text-gray-500">Kode rekening anggaran</p>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea id="keterangan"
                          name="keterangan"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Keterangan tambahan (jika ada)...">{{ old('keterangan') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Informasi tambahan yang perlu dicantumkan</p>
            </div>

            <!-- Preview Informasi -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Ringkasan Informasi
                </h4>
                <div id="info-preview" class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <div><span class="font-medium">Pengguna Anggaran:</span> <span id="preview-pengguna">-</span></div>
                    <div><span class="font-medium">Tempat Tujuan:</span> <span id="preview-tujuan">-</span></div>
                    <div><span class="font-medium">Lama Perjalanan:</span> <span id="preview-lama">-</span></div>
                    <div><span class="font-medium">Transportasi:</span> <span id="preview-transportasi">-</span></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('spd.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan SPD
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ========== AUTO CALCULATE LAMA PERJADIN ==========
function calculateLamaPerjadin() {
    const tglBerangkat = document.getElementById('tanggal_berangkat').value;
    const tglKembali = document.getElementById('tanggal_kembali').value;
    const lamaInput = document.getElementById('lama_perjadin');

    if (tglBerangkat && tglKembali) {
        const start = new Date(tglBerangkat);
        const end = new Date(tglKembali);

        if (end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 karena termasuk hari berangkat
            lamaInput.value = diffDays;

            // Update preview
            document.getElementById('preview-lama').textContent = diffDays + ' Hari';
        } else {
            lamaInput.value = '';
            document.getElementById('preview-lama').textContent = '-';
            alert('Tanggal kembali harus setelah atau sama dengan tanggal berangkat');
        }
    } else {
        lamaInput.value = '';
        document.getElementById('preview-lama').textContent = '-';
    }
}

// Event listener untuk tanggal
document.getElementById('tanggal_berangkat').addEventListener('change', calculateLamaPerjadin);
document.getElementById('tanggal_kembali').addEventListener('change', calculateLamaPerjadin);

// ========== PREVIEW FUNCTIONS ==========
function updatePreviewPenggunaAnggaran() {
    const select = document.getElementById('pengguna_anggaran');
    const selectedOption = select.options[select.selectedIndex];
    const preview = document.getElementById('preview-pengguna');

    if (selectedOption && selectedOption.value) {
        const nama = selectedOption.getAttribute('data-nama') || selectedOption.text.split(' - ')[0];
        preview.textContent = nama;
    } else {
        preview.textContent = '-';
    }
}

function updatePreviewTempatTujuan() {
    const select = document.getElementById('tempat_tujuan');
    const selectedOption = select.options[select.selectedIndex];
    const preview = document.getElementById('preview-tujuan');

    if (selectedOption && selectedOption.value) {
        const nama = selectedOption.getAttribute('data-nama') || selectedOption.text;
        preview.textContent = nama;
    } else {
        preview.textContent = '-';
    }
}

function updatePreviewTransportasi() {
    const radios = document.querySelectorAll('input[name="alat_transportasi"]');
    const preview = document.getElementById('preview-transportasi');

    let selectedLabel = '';
    for (const radio of radios) {
        if (radio.checked) {
            const label = radio.nextElementSibling;
            if (label) {
                selectedLabel = label.innerText.trim();
            }
            break;
        }
    }

    preview.textContent = selectedLabel || '-';
}

// Event listener untuk preview
document.getElementById('pengguna_anggaran').addEventListener('change', updatePreviewPenggunaAnggaran);
document.getElementById('tempat_tujuan').addEventListener('change', updatePreviewTempatTujuan);
document.querySelectorAll('input[name="alat_transportasi"]').forEach(radio => {
    radio.addEventListener('change', updatePreviewTransportasi);
});

// ========== VALIDASI FORM ==========
document.getElementById('formSPD').addEventListener('submit', function(e) {
    // Validasi Nomor Surat
    const nomorSurat = document.getElementById('nomor_surat');
    if (!nomorSurat.value.trim()) {
        alert('Nomor Surat wajib diisi');
        e.preventDefault();
        nomorSurat.focus();
        return false;
    }

    // Validasi Pengguna Anggaran
    const penggunaAnggaran = document.getElementById('pengguna_anggaran');
    if (!penggunaAnggaran.value) {
        alert('Pengguna Anggaran wajib dipilih');
        e.preventDefault();
        penggunaAnggaran.focus();
        return false;
    }

    // Validasi Maksud Perjadin
    const maksudPerjadin = document.getElementById('maksud_perjadin');
    if (!maksudPerjadin.value.trim()) {
        alert('Maksud Perjalanan Dinas wajib diisi');
        e.preventDefault();
        maksudPerjadin.focus();
        return false;
    }

    // Validasi Alat Transportasi
    const transportasiRadios = document.querySelectorAll('input[name="alat_transportasi"]');
    let transportasiSelected = false;
    for (const radio of transportasiRadios) {
        if (radio.checked) {
            transportasiSelected = true;
            break;
        }
    }
    if (!transportasiSelected) {
        alert('Alat Transportasi wajib dipilih');
        e.preventDefault();
        return false;
    }

    // Validasi Tempat Berangkat
    const tempatBerangkat = document.getElementById('tempat_berangkat');
    if (!tempatBerangkat.value.trim()) {
        alert('Tempat Berangkat wajib diisi');
        e.preventDefault();
        tempatBerangkat.focus();
        return false;
    }

    // Validasi Tempat Tujuan
    const tempatTujuan = document.getElementById('tempat_tujuan');
    if (!tempatTujuan.value) {
        alert('Tempat Tujuan wajib dipilih');
        e.preventDefault();
        tempatTujuan.focus();
        return false;
    }

    // Validasi Tanggal
    const tglBerangkat = document.getElementById('tanggal_berangkat');
    const tglKembali = document.getElementById('tanggal_kembali');

    if (!tglBerangkat.value) {
        alert('Tanggal Berangkat wajib diisi');
        e.preventDefault();
        tglBerangkat.focus();
        return false;
    }

    if (!tglKembali.value) {
        alert('Tanggal Kembali wajib diisi');
        e.preventDefault();
        tglKembali.focus();
        return false;
    }

    const start = new Date(tglBerangkat.value);
    const end = new Date(tglKembali.value);

    if (end < start) {
        alert('Tanggal Kembali harus setelah atau sama dengan Tanggal Berangkat');
        e.preventDefault();
        tglKembali.focus();
        return false;
    }

    return true;
});

// ========== AUTO FORMAT NOMOR SURAT ==========
document.getElementById('nomor_surat').addEventListener('input', function(e) {
    this.value = this.value.replace(/\s+/g, ' ').trim();
});

// ========== INITIAL PREVIEW ON LOAD ==========
document.addEventListener('DOMContentLoaded', function() {
    // Focus ke input nomor surat
    document.getElementById('nomor_surat').focus();

    // Hitung lama perjadin awal
    calculateLamaPerjadin();

    // Update preview awal
    updatePreviewPenggunaAnggaran();
    updatePreviewTempatTujuan();
    updatePreviewTransportasi();

    // Set default transportasi jika ada old value
    @if(old('alat_transportasi'))
        document.querySelector(`input[name="alat_transportasi"][value="{{ old('alat_transportasi') }}"]`).checked = true;
        updatePreviewTransportasi();
    @endif
});

// ========== FUNGSI UNTUK MENAMPILKAN DATA PEGAWAI (Optional) ==========
function showPegawaiDetail(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (selectedOption && selectedOption.value) {
        const nama = selectedOption.getAttribute('data-nama');
        const nip = selectedOption.getAttribute('data-nip');
        const jabatan = selectedOption.getAttribute('data-jabatan');

        console.log(`Pegawai Terpilih: ${nama} - ${nip} (${jabatan})`);
    }
}
</script>
@endsection

