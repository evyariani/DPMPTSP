@extends('layouts.admin')

@section('title', 'Surat Perintah Tugas (SPT)')

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

/* Badge untuk SPT */
.spt-badge {
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

/* Line clamp untuk teks panjang */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<!-- HEADER -->
<div class="mb-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Tugas (SPT)</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Tugas</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="exportData()" id="btn-export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </button>
            <a href="{{ route('spt.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-plus mr-2"></i> Tambah SPT
            </a>
        </div>
    </div>
</div>

<!-- NOTIFIKASI TOAST -->
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

<!-- NOTIFIKASI HAPUS -->
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

<!-- MODAL KONFIRMASI HAPUS -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan menghapus data SPT:</p>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-nomor"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-tujuan"></p>
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
                    <button type="button" 
                            onclick="hideDeleteModal()"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FULL TEXT -->
<div id="full-text-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold" id="full-text-title"></h3>
                    <button onclick="hideFullTextModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4 max-h-96 overflow-y-auto">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap" id="full-text-content"></pre>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="hideFullTextModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FULL DASAR -->
<div id="full-dasar-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Dasar SPT</h3>
                    <button onclick="hideFullDasarModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4 max-h-96 overflow-y-auto">
                    <ul id="full-dasar-list" class="list-disc list-inside space-y-2"></ul>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="hideFullDasarModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER DAN SEARCH -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('spt.index') }}" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nomor surat, tujuan, lokasi, atau penanda tangan..." 
                       value="{{ request('search') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $b)
                        <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="penanda_tangan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Penanda Tangan</option>
                    @foreach($pegawais ?? [] as $pegawai)
                        <option value="{{ $pegawai->id_pegawai }}" {{ request('penanda_tangan') == $pegawai->id_pegawai ? 'selected' : '' }}>{{ $pegawai->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                @if(request()->hasAny(['search', 'bulan', 'tahun', 'penanda_tangan']))
                    <a href="{{ route('spt.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- TABEL SPT -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dasar</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanda Tangan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($spts as $index => $spt)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ ($spts->currentPage() - 1) * $spts->perPage() + $index + 1 }}
                    </td>

                    <!-- Nomor Surat -->
                    <td class="px-4 py-4">
                        <span class="spt-badge"><i class="fas fa-file-alt mr-1 text-xs"></i> {{ Str::limit($spt->nomor_surat, 25) }}</span>
                        @if(strlen($spt->nomor_surat) > 25)
                            <button onclick="showFullText(this, '{{ addslashes($spt->nomor_surat) }}', 'Nomor Surat')" class="text-blue-500 text-xs mt-1 block">Lihat selengkapnya</button>
                        @endif
                    </td>

                    <!-- Dasar -->
                    <td class="px-4 py-4">
                        @php 
                            $dasarList = $spt->dasar_list;
                            $dasarCount = is_array($dasarList) ? count($dasarList) : ($dasarList ? $dasarList->count() : 0);
                            $dasarArray = is_array($dasarList) ? $dasarList : ($dasarList ? $dasarList->toArray() : []);
                        @endphp
                        @if($dasarCount > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($dasarArray, 0, 2) as $dasar)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700" title="{{ $dasar }}">
                                        <i class="fas fa-gavel text-gray-400 mr-1"></i> {{ Str::limit($dasar, 20) }}
                                    </span>
                                @endforeach
                                @if($dasarCount > 2)
                                    <button onclick="showFullDasar(this, {{ json_encode($dasarArray) }})" class="text-blue-500 text-xs hover:underline">+{{ $dasarCount - 2 }} lainnya</button>
                                @endif
                            </div>
                        @else 
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Pegawai -->
                    <td class="px-4 py-4">
                        @php 
                            $pegawaiList = $spt->pegawai_list_from_snapshot;
                            $pegawaiCount = $pegawaiList ? $pegawaiList->count() : 0;
                        @endphp
                        @if($pegawaiCount > 0)
                            <div class="flex items-center gap-1">
                                <div class="flex -space-x-2">
                                    @foreach($pegawaiList->take(3) as $peg)
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-xs font-medium text-indigo-600 tooltip" title="{{ $peg->nama }} - {{ $peg->nip ?? '' }}">
                                            {{ strtoupper(substr($peg->nama ?? '?', 0, 1)) }}
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-xs text-gray-500 ml-1">{{ $pegawaiCount }} orang</span>
                            </div>
                        @else 
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Tujuan -->
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-700 line-clamp-2" title="{{ $spt->tujuan }}">{{ Str::limit($spt->tujuan, 60) }}</div>
                        @if(strlen($spt->tujuan) > 60)
                            <button onclick="showFullText(this, '{{ addslashes($spt->tujuan) }}', 'Tujuan Perjalanan')" class="text-blue-500 text-xs mt-1 hover:underline">Lihat selengkapnya</button>
                        @endif
                    </td>

                    <!-- Tanggal -->
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($spt->tanggal) 
                            {{ $spt->tanggal->format('d/m/Y') }}
                        @else 
                            -
                        @endif
                    </td>

                    <!-- Lokasi -->
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ Str::limit($spt->lokasi, 20) }}
                    </td>

                    <!-- Penanda Tangan -->
                    <td class="px-4 py-4">
                        @if($spt->penanda_tangan_nama)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-green-600 font-semibold text-xs">{{ strtoupper(substr($spt->penanda_tangan_nama, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-gray-900" title="{{ $spt->penanda_tangan_nama }}">{{ Str::limit($spt->penanda_tangan_nama, 18) }}</div>
                                    <div class="text-xs text-gray-500" title="{{ $spt->penanda_tangan_jabatan ?? '-' }}">{{ Str::limit($spt->penanda_tangan_jabatan ?? '-', 15) }}</div>
                                </div>
                            </div>
                        @else 
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-4 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('spt.edit', $spt->id_spt) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150 tooltip" title="Edit SPT">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit SPT</span>
                            </a>
                            <a href="{{ route('spt.preview-pdf', $spt->id_spt) }}" target="_blank" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-150 tooltip" title="Preview PDF">
                                <i class="fas fa-print"></i>
                                <span class="tooltip-text">Preview PDF</span>
                            </a>
                            <button onclick="showDeleteConfirmation({{ $spt->id_spt }}, '{{ addslashes(Str::limit($spt->nomor_surat, 30)) }}', '{{ addslashes(Str::limit($spt->tujuan, 50)) }}')" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150 tooltip" title="Hapus SPT">
                                <i class="fas fa-trash"></i>
                                <span class="tooltip-text">Hapus SPT</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPT</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan Surat Perintah Tugas baru</p>
                            <a href="{{ route('spt.create') }}" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200">
                                <i class="fas fa-plus mr-2"></i> Tambah SPT Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION - SAMA PERSIS DENGAN MENU USER -->
@if($spts->count() > 0)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $spts->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $spts->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $spts->total() }}</span> 
        Surat Perintah Tugas
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($spts->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $spts->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements dengan Range dan Ellipsis --}}
        @php
            $current = $spts->currentPage();
            $last = $spts->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        {{-- Tombol ke halaman 1 jika tidak dimulai dari 1 --}}
        @if($start > 1)
            <a href="{{ $spts->url(1) }}" 
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
                <a href="{{ $spts->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        {{-- Tombol ke halaman terakhir jika tidak sampai akhir --}}
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $spts->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($spts->hasMorePages())
            <a href="{{ $spts->nextPageUrl() }}" 
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

// Auto hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
    }, 5000);
});

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
        if (value && value !== '') params.append(key, value);
    }
    
    window.location.href = "{{ route('spt.export') }}?" + params.toString();
    
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-loading');
        btn.disabled = false;
    }, 2000);
}

// ========== DELETE FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNomor = null;

function showDeleteConfirmation(id, nomor, tujuan) {
    currentDeleteId = id;
    currentDeleteNomor = nomor;
    
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-tujuan').textContent = tujuan ? `Tujuan: ${tujuan}` : 'Tanpa Tujuan';
    document.getElementById('delete-form').action = `/spt/${id}`;
    
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    const modalContent = modal.querySelector('.bg-white');
    modalContent.classList.add('animate-fade-in');
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = modal.querySelector('.bg-white');
    
    modalContent.classList.remove('animate-fade-in');
    modalContent.classList.add('animate-fade-out');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        modalContent.classList.remove('animate-fade-out');
        currentDeleteId = null;
        currentDeleteNomor = null;
    }, 300);
}

document.getElementById('delete-form').addEventListener('submit', function(e) {
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
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDeleteSuccess(currentDeleteNomor, data.message);
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
        showErrorNotification('Terjadi kesalahan saat menghapus data: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showDeleteSuccess(nomor, message) {
    const notification = document.getElementById('delete-notification');
    const messageEl = document.getElementById('delete-message');
    
    messageEl.textContent = message || `SPT dengan nomor "${nomor}" berhasil dihapus.`;
    
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

function showErrorNotification(message) {
    const errorDiv = document.createElement('div');
    errorDiv.id = 'temp-error-notification';
    errorDiv.className = 'fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom';
    errorDiv.innerHTML = `
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Terjadi Kesalahan!</p>
                    <p class="text-sm mt-1">${message}</p>
                </div>
                <button type="button" onclick="this.closest('#temp-error-notification').remove()" class="ml-4 text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-red-200 rounded-full h-1">
                <div class="bg-red-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        const notif = document.getElementById('temp-error-notification');
        if (notif) {
            notif.classList.remove('animate-slide-in-bottom');
            notif.classList.add('animate-slide-out-bottom');
            setTimeout(() => notif.remove(), 300);
        }
    }, 5000);
}

// ========== FULL TEXT MODAL ==========
function showFullText(element, text, title) {
    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;
    document.getElementById('full-text-modal').classList.remove('hidden');
}

function hideFullTextModal() {
    document.getElementById('full-text-modal').classList.add('hidden');
}

// ========== FULL DASAR MODAL ==========
function showFullDasar(element, dasarList) {
    const container = document.getElementById('full-dasar-list');
    container.innerHTML = '';
    if (dasarList && Array.isArray(dasarList)) {
        dasarList.forEach(d => {
            let li = document.createElement('li');
            li.className = 'text-sm text-gray-700 mb-2';
            li.innerHTML = '<i class="fas fa-gavel text-gray-400 mr-2"></i> ' + d;
            container.appendChild(li);
        });
    }
    document.getElementById('full-dasar-modal').classList.remove('hidden');
}

function hideFullDasarModal() {
    document.getElementById('full-dasar-modal').classList.add('hidden');
}

// ========== CLOSE MODALS ==========
document.getElementById('delete-confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideFullTextModal();
        hideFullDasarModal();
    }
});
</script>
@endsection