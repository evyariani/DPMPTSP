@extends('layouts.admin')

@section('title', 'Surat Perintah Dinas (SPD) - Halaman Depan')

@section('content')
<style>
/* Animasi untuk notifikasi bawah */
@keyframes slideInFromBottom {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideOutToBottom {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
}

.animate-slide-in-bottom {
    animation: slideInFromBottom 0.3s ease-out forwards;
}

.animate-slide-out-bottom {
    animation: slideOutToBottom 0.3s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

.progress-bar {
    animation: progressBar 5s linear forwards;
}

/* Loading spinner untuk export */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.btn-loading {
    opacity: 0.7;
    cursor: wait;
}

.btn-loading i {
    animation: spin 1s linear infinite;
}

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Fixed width untuk kolom */
.fixed-col-nomor {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-maksud {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-tanggal {
    min-width: 130px;
    max-width: 160px;
}

.fixed-col-tempat {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-transportasi {
    min-width: 140px;
    max-width: 180px;
}

.fixed-col-skpd {
    min-width: 120px;
    max-width: 180px;
}

.fixed-col-pengguna {
    min-width: 180px;
    max-width: 250px;
}

.fixed-col-pelaksana {
    min-width: 200px;
    max-width: 300px;
}

/* Hover effect untuk sel tabel */
.table-cell-hover:hover {
    background-color: #f9fafb;
}

/* Badge untuk transportasi */
.transport-badge {
    @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium;
}

.transport-darat {
    @apply bg-green-100 text-green-800;
}

.transport-udara {
    @apply bg-blue-100 text-blue-800;
}

.transport-darat-udara {
    @apply bg-purple-100 text-purple-800;
}

.transport-angkutan {
    @apply bg-yellow-100 text-yellow-800;
}

.transport-kendaraan {
    @apply bg-indigo-100 text-indigo-800;
}

.transport-umum {
    @apply bg-gray-100 text-gray-800;
}

/* Badge untuk pelaksana */
.pelaksana-badge {
    @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700;
}

/* Tooltip */
.tooltip {
    position: relative;
    display: inline-block;
    cursor: help;
}

.tooltip .tooltip-text {
    visibility: hidden;
    background-color: #1f2937;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 10px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* Button styling */
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background-color: #2563eb;
    color: white;
}

.btn-primary:hover {
    background-color: #1d4ed8;
}

.btn-secondary {
    background-color: #9ca3af;
    color: white;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

.btn-danger {
    background-color: #dc2626;
    color: white;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background-color: #d97706;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Dinas (SPD) - Halaman Depan</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Dinas (Data Perjalanan)</p>
        </div>
    </div>
</div>

<!-- Notifikasi Toast -->
@if(session('success'))
<div id="success-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Berhasil!</p>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="hideNotification('success')" class="ml-4 text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-green-200 rounded-full h-1">
            <div id="success-progress" class="bg-green-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div id="error-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Terjadi Kesalahan!</p>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="hideNotification('error')" class="ml-4 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-red-200 rounded-full h-1">
            <div id="error-progress" class="bg-red-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
@endif

<!-- Notifikasi Hapus -->
<div id="delete-notification" class="hidden fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-trash-restore text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Data Dihapus!</p>
                <p id="delete-message" class="text-sm mt-1"></p>
            </div>
            <button type="button" onclick="hideNotification('delete')" class="ml-4 text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1">
            <div id="delete-progress" class="bg-blue-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan menghapus data SPD:</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-nomor"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-maksud"></p>
                    </div>
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Data yang dihapus <span class="font-semibold">tidak dapat dikembalikan</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideDeleteModal()" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('spd.index') }}" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nomor surat, maksud perjadin, SKPD, atau nama pegawai..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Bulan</option>
                    <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                    <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                    <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                </select>

                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y')-5; $year--)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>

                <select name="pengguna_anggaran" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Pengguna Anggaran</option>
                    @foreach($pegawais ?? [] as $pegawai)
                        <option value="{{ $pegawai->id_pegawai }}" {{ request('pengguna_anggaran') == $pegawai->id_pegawai ? 'selected' : '' }}>
                            {{ $pegawai->nama }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>

                @if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('pengguna_anggaran'))
                    <a href="{{ route('spd.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                @endif
                
                <!-- Tombol Export Excel -->
                <button type="button" 
                        onclick="exportData()"
                        id="btn-export"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabel SPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nomor">Nomor Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-pengguna">Pengguna Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-pelaksana">Pelaksana Perjadin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-maksud">Maksud Perjadin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal Perjadin</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($spds as $index => $spd)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ $spds->firstItem() + $index }}
                    </td>

                    <!-- Kolom Nomor Surat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-nomor">
                        <div class="text-sm font-medium text-gray-900" title="{{ $spd->nomor_surat }}">
                            {{ Str::limit($spd->nomor_surat, 35) }}
                        </div>
                        @if($spd->spt_id)
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-link mr-1 text-xs"></i> dari SPT
                            </span>
                        @endif
                    </td>

                    <!-- Kolom Pengguna Anggaran (Kepala Dinas) -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-pengguna">
                        @if($spd->penggunaAnggaran)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-semibold text-sm">
                                            {{ strtoupper(substr($spd->penggunaAnggaran->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($spd->penggunaAnggaran->nama, 25) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $spd->penggunaAnggaran->jabatan ?? '-' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Kolom Pelaksana Perjalanan Dinas - MENGGUNAKAN SNAPSHOT -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-pelaksana">
                        @php
                            // Gunakan snapshot jika ada, fallback ke relasi
                            $pelaksanaList = [];
                            if ($spd->pelaksana_snapshot && count($spd->pelaksana_snapshot) > 0) {
                                $pelaksanaList = $spd->pelaksana_snapshot;
                            } elseif ($spd->pelaksanaPerjadin && $spd->pelaksanaPerjadin->count() > 0) {
                                // Fallback untuk data lama
                                foreach ($spd->pelaksanaPerjadin as $p) {
                                    $pelaksanaList[] = [
                                        'nama' => $p->nama,
                                        'nip' => $p->nip,
                                        'jabatan' => $p->jabatan,
                                    ];
                                }
                            }
                        @endphp
                        
                        @if(count($pelaksanaList) > 0)
                            <div class="space-y-1">
                                @foreach(array_slice($pelaksanaList, 0, 2) as $pelaksana)
                                    <div class="pelaksana-badge">
                                        <i class="fas fa-user-check mr-1 text-xs"></i>
                                        {{ Str::limit($pelaksana['nama'] ?? '-', 20) }}
                                    </div>
                                @endforeach
                                @if(count($pelaksanaList) > 2)
                                    <div class="text-xs text-blue-600 mt-1 cursor-pointer hover:underline"
                                         onclick="showPelaksanaDetail({{ json_encode($pelaksanaList) }})">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        +{{ count($pelaksanaList) - 2 }} lainnya
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Kolom Maksud Perjadin -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-maksud">
                        <div class="text-sm text-gray-900" title="{{ $spd->maksud_perjadin }}">
                            {{ Str::limit($spd->maksud_perjadin, 60) }}
                        </div>
                    </td>

                    <!-- Kolom Tanggal Perjadin -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tanggal">
                        @if($spd->tanggal_berangkat && $spd->tanggal_kembali)
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                s/d {{ \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $spd->lama_perjadin }} Hari
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('spd.edit', $spd->id_spd) }}"
                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150 tooltip"
                               title="Edit SPD Halaman Depan">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('spd.belakang', $spd->id_spd) }}"
                               class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition duration-150 tooltip"
                               title="Halaman Belakang SPD">
                                <i class="fas fa-file-alt"></i>
                            </a>

                            <a href="{{ route('spd.print-depan', $spd->id_spd) }}"
                               target="_blank"
                               class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition duration-150 tooltip"
                               title="Download PDF Halaman Depan">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            <a href="{{ route('spd.preview-depan', $spd->id_spd) }}"
                               target="_blank"
                               class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-150 tooltip"
                               title="Preview PDF Halaman Depan">
                                <i class="fas fa-eye"></i>
                            </a>

                            <button type="button"
                                    onclick="showDeleteConfirmation(
                                        {{ $spd->id_spd }},
                                        '{{ addslashes(Str::limit($spd->nomor_surat, 30)) }}',
                                        '{{ addslashes(Str::limit($spd->maksud_perjadin, 50)) }}'
                                    )"
                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150 tooltip"
                                    title="Hapus SPD">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPD</p>
                            <p class="text-sm mt-1">SPD akan dibuat otomatis saat membuat SPT</p>
                            <a href="{{ route('spt.index') }}" class="mt-3 btn-primary btn">
                                <i class="fas fa-plus"></i> Buat SPD dari SPT
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Pelaksana -->
<div id="pelaksana-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pelaksana Perjalanan Dinas</h3>
                    <button type="button" onclick="hidePelaksanaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="pelaksana-list" class="space-y-2 max-h-96 overflow-y-auto">
                    <!-- Dynamic content -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="hidePelaksanaModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($spds->hasPages())
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan
        <span class="font-medium">{{ $spds->firstItem() ?: 0 }}</span>
        sampai
        <span class="font-medium">{{ $spds->lastItem() ?: 0 }}</span>
        dari
        <span class="font-medium">{{ $spds->total() }}</span>
        Surat Perintah Dinas
    </div>

    <div class="flex items-center space-x-1">
        @if ($spds->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $spds->previousPageUrl() }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif

        @php
            $current = $spds->currentPage();
            $last = $spds->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp

        @if($start > 1)
            <a href="{{ $spds->url(1) }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $spds->url($page) }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor

        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $spds->url($last) }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif

        @if ($spds->hasMorePages())
            <a href="{{ $spds->nextPageUrl() }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// ========== EXPORT FUNCTION ==========
function exportData() {
    const btn = document.getElementById('btn-export');
    const originalHtml = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    btn.classList.add('btn-loading');
    btn.disabled = true;
    
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value && value !== '') {
            params.append(key, value);
        }
    }
    
    const exportUrl = "{{ route('spd.export') }}?" + params.toString();
    window.location.href = exportUrl;
    
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-loading');
        btn.disabled = false;
    }, 2000);
}

// ========== NOTIFICATION FUNCTIONS ==========
function hideNotification(type) {
    const notification = document.getElementById(`${type}-notification`);
    if (notification) {
        notification.classList.remove('animate-slide-in-bottom');
        notification.classList.add('animate-slide-out-bottom');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;

function showDeleteConfirmation(id, nomor, maksud) {
    currentDeleteId = id;
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-maksud').textContent = maksud ? `Maksud: ${maksud}` : 'Tanpa Maksud';
    const form = document.getElementById('delete-form');
    form.action = `/spd/${id}`;
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

document.getElementById('delete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDeleteSuccess(currentDeleteId);
            hideDeleteModal();
            setTimeout(() => { window.location.reload(); }, 2000);
        } else {
            throw new Error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat menghapus data: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showDeleteSuccess(id) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    message.textContent = `Data SPD berhasil dihapus.`;
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    setTimeout(() => { hideNotification('delete'); }, 5000);
}

// ========== PELAKSANA MODAL ==========
function showPelaksanaDetail(pelaksanaList) {
    const modal = document.getElementById('pelaksana-modal');
    const listContainer = document.getElementById('pelaksana-list');
    listContainer.innerHTML = '';
    
    if (pelaksanaList && pelaksanaList.length > 0) {
        pelaksanaList.forEach(pelaksana => {
            const item = document.createElement('div');
            item.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg mb-2';
            item.innerHTML = `
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${escapeHtml(pelaksana.nama || '-')}</p>
                    <p class="text-xs text-gray-500">NIP: ${escapeHtml(pelaksana.nip || '-')}</p>
                    <p class="text-xs text-gray-500">Jabatan: ${escapeHtml(pelaksana.jabatan || '-')}</p>
                </div>
            `;
            listContainer.appendChild(item);
        });
    } else {
        listContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada data pelaksana</p>';
    }
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hidePelaksanaModal() {
    const modal = document.getElementById('pelaksana-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hidePelaksanaModal();
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('delete-confirm-modal');
    const pelaksanaModal = document.getElementById('pelaksana-modal');
    if (event.target === deleteModal) {
        hideDeleteModal();
    }
    if (event.target === pelaksanaModal) {
        hidePelaksanaModal();
    }
}
</script>
@endsection