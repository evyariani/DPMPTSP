@extends('layouts.admin')

@section('title', 'Laporan Hasil Perjalanan Dinas (LHPD)')

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
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.btn-loading {
    opacity: 0.7;
    cursor: wait;
}

.btn-loading i {
    animation: spin 1s linear infinite;
}

/* Badge untuk LHPD */
.lhpd-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    font-family: monospace;
    background-color: #e0e7ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
}

/* Fixed width untuk kolom */
.fixed-col-dasar {
    min-width: 180px;
    max-width: 250px;
}

.fixed-col-tujuan {
    min-width: 200px;
    max-width: 280px;
}

.fixed-col-tanggal {
    min-width: 100px;
    max-width: 120px;
}

.fixed-col-daerah {
    min-width: 140px;
    max-width: 180px;
}

.fixed-col-hasil {
    min-width: 200px;
    max-width: 280px;
}

.fixed-col-tempat {
    min-width: 120px;
    max-width: 150px;
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

/* Line clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Galeri foto */
.gallery-thumb {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 0.5rem;
}

.gallery-thumb img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 0.5rem;
}

.gallery-thumb .badge-count {
    position: absolute;
    bottom: 2px;
    right: 2px;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 20px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: bold;
}

/* Foto grid di modal galeri */
.foto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.foto-item {
    position: relative;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.foto-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Laporan Hasil Perjalanan Dinas (LHPD)</h2>
            <p class="text-gray-500">Kelola data Laporan Hasil Perjalanan Dinas</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="exportData()" id="btn-export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Notifikasi Toast -->
@if(session('success'))
<div id="success-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3 mt-0.5"></i>
            <div class="flex-1">
                <p class="font-medium">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="hideNotification('success')" class="text-green-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="mt-2 w-full bg-green-200 rounded-full h-1"><div class="bg-green-500 h-1 rounded-full progress-bar" style="width: 100%"></div></div>
    </div>
</div>
@endif

@if(session('error'))
<div id="error-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-0.5"></i>
            <div class="flex-1">
                <p class="font-medium">Terjadi Kesalahan!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            <button onclick="hideNotification('error')" class="text-red-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="mt-2 w-full bg-red-200 rounded-full h-1"><div class="bg-red-500 h-1 rounded-full progress-bar" style="width: 100%"></div></div>
    </div>
</div>
@endif

<!-- Notifikasi Hapus -->
<div id="delete-notification" class="hidden fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <i class="fas fa-trash-restore text-blue-500 text-xl mr-3 mt-0.5"></i>
            <div class="flex-1">
                <p class="font-medium">Data Dihapus!</p>
                <p id="delete-message" class="text-sm"></p>
            </div>
            <button onclick="hideNotification('delete')" class="text-blue-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1"><div class="bg-blue-500 h-1 rounded-full progress-bar" style="width: 100%"></div></div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan menghapus data LHPD:</p>
                    <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-tujuan"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-tanggal"></p>
                    </div>
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded">
                        <p class="text-sm text-red-700">Data yang dihapus <span class="font-semibold">tidak dapat dikembalikan</span>.</p>
                    </div>
                </div>
                <div class="flex justify-center gap-4">
                    <button onclick="hideDeleteModal()" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 rounded-lg">Batal</button>
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-transparent max-w-5xl mx-auto">
            <button onclick="hideImageModal()" class="absolute -top-12 right-0 text-white text-3xl"><i class="fas fa-times"></i></button>
            <img id="modal-image" src="" class="max-w-full max-h-[90vh] mx-auto rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<!-- Modal Galeri Foto -->
<div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
    <div class="relative min-h-screen p-4">
        <div class="sticky top-0 z-10 flex justify-end mb-4">
            <button onclick="hideGalleryModal()" class="text-white bg-black bg-opacity-50 rounded-full p-2"><i class="fas fa-times text-2xl"></i></button>
        </div>
        <div class="container mx-auto">
            <h3 class="text-white text-xl font-semibold mb-4 text-center">Galeri Foto LHPD</h3>
            <div id="gallery-container" class="foto-grid"></div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('lhpd.index') }}" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari tujuan, dasar, hasil LHPD, atau daerah..."
                       value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="bulan" class="px-4 py-2 border rounded-lg">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $b)
                        <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="px-4 py-2 border rounded-lg">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="id_daerah" class="px-4 py-2 border rounded-lg">
                    <option value="">Semua Daerah Tujuan</option>
                    @foreach($daerahList ?? [] as $daerah)
                        <option value="{{ $daerah->id }}" {{ request('id_daerah') == $daerah->id ? 'selected' : '' }}>{{ $daerah->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"><i class="fas fa-search mr-2"></i>Cari</button>
                @if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('id_daerah'))
                    <a href="{{ route('lhpd.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg"><i class="fas fa-redo mr-2"></i>Reset</a>
                @endif
                <button type="button" onclick="exportData()" id="btn-export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabel LHPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-dasar">Dasar</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-tujuan">Tujuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-tanggal">Tgl Berangkat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-daerah">Daerah Tujuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-hasil">Hasil LHPD</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-tempat">Tempat Dikeluarkan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase fixed-col-tanggal">Tgl LHPD</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-20">Foto</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($lhpdList as $index => $lhpd)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ ($lhpdList->currentPage() - 1) * $lhpdList->perPage() + $index + 1 }}</td>

                    <!-- Dasar -->
                    <td class="px-4 py-4">
                        @php 
                            $dasarList = $lhpd->dasar_list;
                            $dasarArray = $dasarList ? (is_array($dasarList) ? $dasarList : ($dasarList->toArray() ?? [])) : [];
                        @endphp
                        @if(count($dasarArray) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($dasarArray, 0, 2) as $dasar)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700" title="{{ $dasar }}">
                                        <i class="fas fa-gavel text-gray-400 mr-1"></i> {{ Str::limit($dasar, 20) }}
                                    </span>
                                @endforeach
                                @if(count($dasarArray) > 2)
                                    <button onclick="showFullDasar(this, {{ json_encode($dasarArray) }})" class="text-blue-500 text-xs">+{{ count($dasarArray) - 2 }}</button>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400 text-sm italic">-</span>
                        @endif
                    </td>

                    <!-- Tujuan -->
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-700 line-clamp-2" title="{{ $lhpd->tujuan }}">{{ Str::limit($lhpd->tujuan ?? '-', 50) }}</div>
                    </td>

                    <!-- Tanggal Berangkat -->
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $lhpd->tanggal_berangkat ? \Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d/m/Y') : '-' }}
                    </td>

                    <!-- Daerah Tujuan -->
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $lhpd->tempat_tujuan_snapshot ?: '-' }}
                    </td>

                    <!-- Hasil LHPD (dari JSON array) -->
                    <td class="px-4 py-4">
                        @php
                            $hasilList = $lhpd->hasil_list;
                            $hasilArray = $hasilList ? (is_array($hasilList) ? $hasilList : ($hasilList->toArray() ?? [])) : [];
                        @endphp
                        @if(count($hasilArray) > 0)
                            <div class="text-sm text-gray-700 line-clamp-2" title="{{ implode('; ', $hasilArray) }}">
                                {{ Str::limit(implode('; ', $hasilArray), 60) }}
                            </div>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="text-gray-400 text-sm italic">Perlu diisi</span>
                        @endif
                    </td>

                    <!-- Tempat Dikeluarkan -->
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $lhpd->tempat_dikeluarkan_snapshot ?: '-' }}
                    </td>

                    <!-- Tanggal LHPD -->
                    <td class="px-4 py-4 text-sm text-gray-600">
                        {{ $lhpd->tanggal_lhpd ? \Carbon\Carbon::parse($lhpd->tanggal_lhpd)->format('d/m/Y') : '-' }}
                    </td>

                    <!-- Foto -->
                    <td class="px-4 py-4 text-center">
                        @php $fotoCount = $lhpd->foto_count; @endphp
                        @if($fotoCount > 0)
                            <button onclick="showGallery({{ $lhpd->id_lhpd }})" class="gallery-thumb">
                                <div class="relative">
                                    @if($lhpd->first_foto_url)
                                        <img src="{{ $lhpd->first_foto_url }}" alt="Thumbnail" class="w-12 h-12 rounded-lg object-cover border">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>
                                    @endif
                                    <span class="badge-count">{{ $fotoCount }}</span>
                                </div>
                            </button>
                        @else
                            <div class="text-gray-400"><i class="fas fa-image text-xl opacity-50"></i></div>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('lhpd.edit', $lhpd->id_lhpd) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 tooltip" title="Edit LHPD">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('lhpd.preview-pdf', $lhpd->id_lhpd) }}" target="_blank" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 tooltip" title="Preview PDF">
                                <i class="fas fa-print"></i>
                            </a>
                            <button onclick="showDeleteConfirmation({{ $lhpd->id_lhpd }}, '{{ addslashes(Str::limit($lhpd->tujuan ?? '-', 50)) }}', '{{ addslashes($lhpd->tanggal_berangkat ? \Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d/m/Y') : '-') }}')" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 tooltip" title="Hapus LHPD">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Tidak ada data LHPD</p>
                            <p class="text-sm mt-1">LHPD akan dibuat otomatis saat membuat SPT/SPD</p>
                            <a href="{{ route('spt.index') }}" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Buat SPT</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION - SAMA PERSIS DENGAN RINCIAN BIAYA -->
@if($lhpdList->count() > 0)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $lhpdList->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $lhpdList->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $lhpdList->total() }}</span> 
        LHPD
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($lhpdList->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $lhpdList->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements dengan Range dan Ellipsis --}}
        @php
            $current = $lhpdList->currentPage();
            $last = $lhpdList->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        {{-- Tombol ke halaman 1 jika tidak dimulai dari 1 --}}
        @if($start > 1)
            <a href="{{ $lhpdList->url(1) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif
        
        {{-- Tombol halaman dalam range --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $lhpdList->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        {{-- Tombol ke halaman terakhir jika tidak sampai akhir --}}
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $lhpdList->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($lhpdList->hasMorePages())
            <a href="{{ $lhpdList->nextPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</div>
@endif

<!-- Modal Full Dasar -->
<div id="full-dasar-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Dasar Perjalanan</h3>
                    <button onclick="hideFullDasarModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4 max-h-96 overflow-y-auto">
                    <ul id="full-dasar-list" class="list-disc list-inside space-y-2"></ul>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="hideFullDasarModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Full Hasil -->
<div id="full-hasil-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Hasil Perjalanan</h3>
                    <button onclick="hideFullHasilModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4 max-h-96 overflow-y-auto">
                    <ul id="full-hasil-list" class="list-disc list-inside space-y-2"></ul>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="hideFullHasilModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentGalleryImages = [];
let currentImageIndex = 0;

// ========== EXPORT FUNCTION ==========
function exportData() {
    const btn = document.getElementById('btn-export');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    btn.disabled = true;
    const form = document.getElementById('filter-form');
    const params = new URLSearchParams(new FormData(form));
    window.location.href = "{{ route('lhpd.export') }}?" + params.toString();
    setTimeout(() => { btn.innerHTML = original; btn.disabled = false; }, 2000);
}

// ========== NOTIFICATION ==========
function hideNotification(type) {
    const el = document.getElementById(`${type}-notification`);
    if (el) {
        el.classList.remove('animate-slide-in-bottom');
        el.classList.add('animate-slide-out-bottom');
        setTimeout(() => { el.style.display = 'none'; }, 300);
    }
}
setTimeout(() => { 
    ['success', 'error'].forEach(t => hideNotification(t)); 
}, 5000);

// ========== DELETE ==========
let deleteId = null;
function showDeleteConfirmation(id, tujuan, tanggal) {
    deleteId = id;
    document.getElementById('delete-tujuan').textContent = tujuan;
    document.getElementById('delete-tanggal').textContent = `Berangkat: ${tanggal}`;
    document.getElementById('delete-form').action = `/lhpd/${id}`;
    document.getElementById('delete-confirm-modal').classList.remove('hidden');
}
function hideDeleteModal() { 
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = modal.querySelector('.bg-white');
    modalContent.classList.add('animate-fade-out');
    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('animate-fade-out');
    }, 300);
}

document.getElementById('delete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, { 
        method: 'POST', 
        body: new FormData(this), 
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } 
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('delete-message').textContent = 'Data LHPD berhasil dihapus.';
            document.getElementById('delete-notification').classList.remove('hidden');
            hideDeleteModal();
            setTimeout(() => location.reload(), 2000);
        } else throw new Error(data.message);
    })
    .catch(err => alert('Gagal: ' + err.message));
});

// ========== GALLERY ==========
async function showGallery(id) {
    try {
        const res = await fetch(`/lhpd/api/get-fotos/${id}`);
        const data = await res.json();
        if (data.success && data.fotos.length) {
            currentGalleryImages = data.fotos;
            const container = document.getElementById('gallery-container');
            container.innerHTML = '';
            currentGalleryImages.forEach((f, i) => {
                const div = document.createElement('div');
                div.className = 'foto-item';
                div.innerHTML = `<img src="${f.url}" onclick="openImageViewer(${i})">`;
                container.appendChild(div);
            });
            document.getElementById('gallery-modal').classList.remove('hidden');
        } else alert('Tidak ada foto');
    } catch(e) { alert('Gagal memuat foto'); }
}
function hideGalleryModal() { document.getElementById('gallery-modal').classList.add('hidden'); }

// ========== IMAGE VIEWER ==========
function openImageViewer(index) {
    currentImageIndex = index;
    const modal = document.getElementById('image-modal');
    document.getElementById('modal-image').src = currentGalleryImages[currentImageIndex].url;
    modal.classList.remove('hidden');
    hideGalleryModal();
}
function hideImageModal() { document.getElementById('image-modal').classList.add('hidden'); }
function prevImage() { if (currentImageIndex > 0) { currentImageIndex--; document.getElementById('modal-image').src = currentGalleryImages[currentImageIndex].url; } }
function nextImage() { if (currentImageIndex < currentGalleryImages.length - 1) { currentImageIndex++; document.getElementById('modal-image').src = currentGalleryImages[currentImageIndex].url; } }

// ========== FULL DASAR ==========
function showFullDasar(el, list) {
    const container = document.getElementById('full-dasar-list');
    container.innerHTML = '';
    list.forEach(d => { let li = document.createElement('li'); li.className = 'text-sm text-gray-700'; li.textContent = d; container.appendChild(li); });
    document.getElementById('full-dasar-modal').classList.remove('hidden');
}
function hideFullDasarModal() { document.getElementById('full-dasar-modal').classList.add('hidden'); }

// ========== FULL HASIL ==========
function showFullHasil(el, list) {
    const container = document.getElementById('full-hasil-list');
    container.innerHTML = '';
    list.forEach(h => { let li = document.createElement('li'); li.className = 'text-sm text-gray-700'; li.textContent = h; container.appendChild(li); });
    document.getElementById('full-hasil-modal').classList.remove('hidden');
}
function hideFullHasilModal() { document.getElementById('full-hasil-modal').classList.add('hidden'); }

// ========== CLOSE MODALS ==========
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        hideDeleteModal(); hideImageModal(); hideGalleryModal(); hideFullDasarModal(); hideFullHasilModal();
    }
    if (e.key === 'ArrowLeft') prevImage();
    if (e.key === 'ArrowRight') nextImage();
});
window.onclick = e => {
    if (e.target === document.getElementById('delete-confirm-modal')) hideDeleteModal();
    if (e.target === document.getElementById('image-modal')) hideImageModal();
    if (e.target === document.getElementById('gallery-modal')) hideGalleryModal();
    if (e.target === document.getElementById('full-dasar-modal')) hideFullDasarModal();
    if (e.target === document.getElementById('full-hasil-modal')) hideFullHasilModal();
}
</script>
@endsection