@extends('layouts.admin')

@section('title', 'Surat Perintah Dinas (SPD)')

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

/* Custom untuk SPD */
.spd-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}

.spd-badge-dinas {
    @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.spd-badge-pribadi {
    @apply bg-green-100 text-green-800 border border-green-200;
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
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Dinas (SPD)</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Dinas</p>
        </div>
        <a href="{{ route('spd.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah SPD
        </a>
    </div>
</div>

<!-- Notifikasi Toast - POSISI DI BAWAH -->
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

<!-- Notifikasi Hapus - POSISI DI BAWAH -->
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
                <!-- Icon Warning -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>

                <!-- Message -->
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

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4">
                    <button type="button"
                            onclick="hideDeleteModal()"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>

                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
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
    <form method="GET" action="{{ route('spd.index') }}" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
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

            <select name="skpd" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua SKPD</option>
                @foreach($skpdList ?? [] as $skpd)
                    <option value="{{ $skpd }}" {{ request('skpd') == $skpd ? 'selected' : '' }}>{{ $skpd }}</option>
                @endforeach
            </select>

            <select name="alat_transportasi" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Transportasi</option>
                @foreach($alatTransportasiList ?? [] as $key => $value)
                    <option value="{{ $key }}" {{ request('alat_transportasi') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-search mr-2"></i> Cari
            </button>

            @if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('pengguna_anggaran') || request()->has('skpd') || request()->has('alat_transportasi'))
                <a href="{{ route('spd.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel SPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nomor">Nomor Surat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-pengguna">Pengguna Anggaran</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-maksud">Maksud Perjadin</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-transportasi">Transportasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tempat">Tempat Berangkat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tempat">Tempat Tujuan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal Perjadin</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-skpd">SKPD</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $spds = $spds ?? collect([]);
                    $isPaginated = method_exists($spds, 'currentPage');
                @endphp

                @forelse($spds as $index => $spd)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($isPaginated)
                            {{ ($spds->currentPage() - 1) * $spds->perPage() + $index + 1 }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>

                    <!-- Kolom Nomor Surat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-nomor table-cell-hover">
                        <div class="text-sm font-medium text-gray-900" title="{{ $spd->nomor_surat }}">
                            {{ Str::limit($spd->nomor_surat, 30) }}
                        </div>
                        @if(strlen($spd->nomor_surat) > 30)
                            <button type="button"
                                    onclick="showFullText(this, '{{ addslashes($spd->nomor_surat) }}', 'Nomor Surat')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>

                    <!-- Kolom Pengguna Anggaran -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-pengguna table-cell-hover">
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
                                    <div class="text-sm font-medium text-gray-900" title="{{ $spd->penggunaAnggaran->nama }}">
                                        {{ Str::limit($spd->penggunaAnggaran->nama, 25) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $spd->penggunaAnggaran->jabatan ?? '-' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Kolom Maksud Perjadin -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-maksud table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $spd->maksud_perjadin }}">
                            {{ Str::limit($spd->maksud_perjadin, 60) }}
                        </div>
                        @if(strlen($spd->maksud_perjadin) > 60)
                            <button type="button"
                                    onclick="showFullText(this, '{{ addslashes($spd->maksud_perjadin) }}', 'Maksud Perjalanan Dinas')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>

                    <!-- Kolom Alat Transportasi -->
                    <td class="px-6 py-4 whitespace-nowrap fixed-col-transportasi">
                        @php
                            $transportLabels = [
                                'transportasi_darat' => ['label' => 'Transportasi Darat', 'class' => 'transport-darat'],
                                'transportasi_udara' => ['label' => 'Transportasi Udara', 'class' => 'transport-udara'],
                                'transportasi_darat_udara' => ['label' => 'Darat & Udara', 'class' => 'transport-darat-udara'],
                                'angkutan_darat' => ['label' => 'Angkutan Darat', 'class' => 'transport-angkutan'],
                                'kendaraan_dinas' => ['label' => 'Kendaraan Dinas', 'class' => 'transport-kendaraan'],
                                'angkutan_umum' => ['label' => 'Angkutan Umum', 'class' => 'transport-umum']
                            ];
                            $transport = $transportLabels[$spd->alat_transportasi] ?? ['label' => $spd->alat_transportasi ?? '-', 'class' => ''];
                        @endphp
                        <span class="transport-badge {{ $transport['class'] }}">
                            <i class="fas fa-{{ $spd->alat_transportasi == 'transportasi_udara' ? 'plane' : ($spd->alat_transportasi == 'transportasi_darat' ? 'bus' : 'car') }} mr-1 text-xs"></i>
                            {{ $transport['label'] }}
                        </span>
                    </td>

                    <!-- Kolom Tempat Berangkat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tempat table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $spd->tempat_berangkat }}">
                            {{ Str::limit($spd->tempat_berangkat, 25) }}
                        </div>
                        @if(strlen($spd->tempat_berangkat) > 25)
                            <button type="button"
                                    onclick="showFullText(this, '{{ addslashes($spd->tempat_berangkat) }}', 'Tempat Berangkat')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>

                    <!-- Kolom Tempat Tujuan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tempat table-cell-hover">
                        @if($spd->tempatTujuan)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-6 w-6 mr-2">
                                    <div class="h-6 w-6 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-purple-600 text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-900" title="{{ $spd->tempatTujuan->nama_daerah }}">
                                        {{ Str::limit($spd->tempatTujuan->nama_daerah, 25) }}
                                    </div>
                                    @if($spd->tempatTujuan->kode_daerah)
                                        <div class="text-xs text-gray-500">{{ $spd->tempatTujuan->kode_daerah }}</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
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

                    <!-- Kolom SKPD -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-skpd table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $spd->skpd }}">
                            {{ Str::limit($spd->skpd ?? '-', 25) }}
                        </div>
                        @if($spd->kode_rek)
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-code mr-1"></i> {{ $spd->kode_rek }}
                            </div>
                        @endif
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col space-y-2">
                            <div class="flex space-x-2">
                                @if(isset($spd->id_spd))
                                <a href="{{ route('spd.edit', $spd->id_spd) }}"
                                   class="text-green-600 hover:text-green-900 px-3 py-1 rounded hover:bg-green-50 transition duration-150"
                                   title="Edit SPD">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <!-- Tombol Print PDF -->
                                <a href="{{ route('spd.print', $spd->id_spd) }}"
                                   target="_blank"
                                   class="text-purple-600 hover:text-purple-900 px-3 py-1 rounded hover:bg-purple-50 transition duration-150"
                                   title="Download PDF SPD">
                                    <i class="fas fa-download mr-1"></i> PDF
                                </a>

                                <!-- Tombol Preview PDF -->
                                <a href="{{ route('spd.preview-pdf', $spd->id_spd) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded hover:bg-blue-50 transition duration-150"
                                   title="Preview PDF SPD">
                                    <i class="fas fa-eye mr-1"></i> Preview
                                </a>

                                <!-- Tombol Hapus dengan Modal -->
                                <button type="button"
                                        onclick="showDeleteConfirmation(
                                            {{ $spd->id_spd }},
                                            '{{ addslashes(Str::limit($spd->nomor_surat, 30)) }}',
                                            '{{ addslashes(Str::limit($spd->maksud_perjadin, 50)) }}'
                                        )"
                                        class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                        title="Hapus SPD">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                                @else
                                <span class="text-gray-400 px-3 py-1">Edit</span>
                                <span class="text-gray-400 px-3 py-1">Hapus</span>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-file-alt text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPD</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan Surat Perintah Dinas baru</p>
                            <a href="{{ route('spd.create') }}" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah SPD Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@php
    $showPagination = isset($spds) && method_exists($spds, 'hasPages') && $spds->hasPages();
@endphp

@if($showPagination)
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
        {{-- Previous Page Link --}}
        @if ($spds->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $spds->previousPageUrl() }}"
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @php
            $current = $spds->currentPage();
            $last = $spds->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp

        @if($start > 1)
            <a href="{{ $spds->url(1) }}"
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $spds->url($page) }}"
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor

        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $spds->url($last) }}"
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif

        {{-- Next Page Link --}}
        @if ($spds->hasMorePages())
            <a href="{{ $spds->nextPageUrl() }}"
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
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

// Auto-hide notifications after 5 seconds
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
let currentDeleteNomor = null;

function showDeleteConfirmation(id, nomor, maksud) {
    currentDeleteId = id;
    currentDeleteNomor = nomor;

    // Update modal content
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-maksud').textContent = maksud ? `Maksud: ${maksud}` : 'Tanpa Maksud';

    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/spd/${id}`;

    // Show modal with animation
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';

    // Add animation class to modal content
    const modalContent = modal.querySelector('.bg-white');
    modalContent.classList.add('animate-fade-in');
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = modal.querySelector('.bg-white');

    // Add fade out animation
    modalContent.classList.remove('animate-fade-in');
    modalContent.classList.add('animate-fade-out');

    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        modalContent.classList.remove('animate-fade-out');
        currentDeleteId = null;
        currentDeleteNomor = null;
    }, 300);
}

// Handle form submission dengan AJAX
document.getElementById('delete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Tampilkan loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
    submitBtn.disabled = true;

    // Kirim request DELETE
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDeleteSuccess(currentDeleteNomor);
            hideDeleteModal();
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showDeleteSuccess(nomor) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');

    message.textContent = `Data SPD dengan nomor "${nomor}" berhasil dihapus.`;

    const progress = document.getElementById('delete-progress');
    progress.style.width = '100%';
    progress.style.animation = 'none';
    void progress.offsetWidth;
    progress.style.animation = 'progressBar 5s linear forwards';

    notification.classList.remove('hidden');
    notification.style.display = 'block';
    notification.classList.add('animate-slide-in-bottom');

    setTimeout(() => {
        hideNotification('delete');
    }, 5000);
}

// ========== FULL TEXT MODAL ==========
function showFullText(element, text, title) {
    const modalId = 'full-text-modal';
    let modal = document.getElementById(modalId);

    if (!modal) {
        modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden';
        modal.innerHTML = `
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto animate-fade-in">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900" id="full-text-title"></h3>
                            <button type="button" onclick="hideFullTextModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap max-h-96 overflow-y-auto" id="full-text-content"></pre>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="hideFullTextModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideFullTextModal() {
    const modal = document.getElementById('full-text-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.getElementById('delete-confirm-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

document.addEventListener('click', function(e) {
    const fullTextModal = document.getElementById('full-text-modal');
    if (fullTextModal && e.target === fullTextModal) {
        hideFullTextModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideFullTextModal();
    }
});
</script>
@endsection

