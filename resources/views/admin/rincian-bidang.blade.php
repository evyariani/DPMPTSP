@extends('layouts.admin')

@section('title', 'Rincian Biaya Perjalanan Dinas')

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

/* Custom badge untuk SPPD */
.sppd-badge {
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

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Loading spinner untuk export */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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

/* Hover effect untuk sel tabel */
.table-cell-hover:hover {
    background-color: #f9fafb;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Rincian Biaya Perjalanan Dinas</h2>
            <p class="text-gray-500">Kelola rincian biaya perjalanan dinas (uang harian saja, transport dikwitansi terpisah)</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rincian.sync-all') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200"
               onclick="return confirm('Sync semua data dari SPD? Ini akan memperbarui semua rincian biaya berdasarkan data SPD terbaru.')">
                <i class="fas fa-sync-alt mr-2"></i> Sync All
            </a>
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

@if(session('warning'))
<div id="warning-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Peringatan!</p>
                <p class="text-sm mt-1">{{ session('warning') }}</p>
            </div>
            <button type="button" onclick="hideNotification('warning')" class="ml-4 text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-yellow-200 rounded-full h-1">
            <div id="warning-progress" class="bg-yellow-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
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
                    <p class="text-gray-600 mb-3">Anda akan menghapus data rincian biaya:</p>
                    
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

<!-- Modal Full Text -->
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

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('rincian.index') }}" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nomor SPPD, tujuan, atau pegawai..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                @if(request()->has('search'))
                    <a href="{{ route('rincian.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                @endif
            </div>
        </div>

        <!-- ACTIVE FILTERS -->
        @if(request()->has('search'))
        <div class="mt-4 pt-3 border-t border-gray-200">
            <div class="flex items-center flex-wrap gap-2">
                <span class="text-sm text-gray-600">Filter aktif:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-search mr-1"></i> {{ request('search') }}
                    <a href="{{ route('rincian.index') }}" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            </div>
        </div>
        @endif
    </form>
</div>

<!-- Tabel Rincian Biaya -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor SPPD</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Berangkat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Uang Harian</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rincian as $index => $item)
                @php
                    $pegawaiList = is_array($item->pegawai) ? $item->pegawai : [];
                    $displayPegawai = array_slice($pegawaiList, 0, 3);
                @endphp
                <tr class="hover:bg-gray-50 transition duration-150">
                    <!-- No - menggunakan perhitungan manual seperti menu lain -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ ($rincian->currentPage() - 1) * $rincian->perPage() + $index + 1 }}
                    </td>
                    
                    <!-- Kolom Nomor SPPD -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="sppd-badge">
                            <i class="fas fa-file-alt mr-1 text-xs"></i> {{ Str::limit($item->nomor_sppd ?? '-', 25) }}
                        </span>
                        @if(strlen($item->nomor_sppd ?? '') > 25)
                            <button onclick="showFullText(this, '{{ addslashes($item->nomor_sppd) }}', 'Nomor SPPD')" class="text-blue-500 text-xs mt-1 block hover:underline">Lihat selengkapnya</button>
                        @endif
                    </td>
                    
                    <!-- Kolom Tanggal Berangkat -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-gray-400 mr-1 text-xs"></i>
                        {{ $item->tanggal_berangkat ? \Carbon\Carbon::parse($item->tanggal_berangkat)->format('d/m/Y') : '-' }}
                    </td>
                    
                    <!-- Kolom Tanggal Kembali -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-gray-400 mr-1 text-xs"></i>
                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                    </td>
                    
                    <!-- Kolom Lama -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <i class="fas fa-clock mr-1"></i> {{ $item->lama_perjadin ?? 0 }} hari
                        </span>
                    </td>
                    
                    <!-- Kolom Tujuan -->
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700 line-clamp-2 tooltip" title="{{ $item->tempat_tujuan ?? '-' }}">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1 text-xs"></i>
                            {{ Str::limit($item->tempat_tujuan ?? '-', 50) }}
                        </div>
                        @if(strlen($item->tempat_tujuan ?? '') > 50)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($item->tempat_tujuan) }}', 'Tujuan Perjalanan')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Pegawai -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            <div class="flex -space-x-2">
                                @foreach($displayPegawai as $peg)
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-xs font-medium text-indigo-600 tooltip" 
                                         title="{{ $peg['nama'] ?? '' }} - {{ $peg['nip'] ?? '' }}">
                                        {{ strtoupper(substr($peg['nama'] ?? '?', 0, 1)) }}
                                    </div>
                                @endforeach
                                @if(count($pegawaiList) > 3)
                                    <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs font-medium text-gray-600 tooltip" 
                                         title="{{ count($pegawaiList) - 3 }} pegawai lainnya">
                                        +{{ count($pegawaiList) - 3 }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 ml-1">{{ count($pegawaiList) }} orang</span>
                        </div>
                    </td>
                    
                    <!-- Kolom Uang Harian per orang per hari -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                        <div class="font-medium text-gray-800">
                            Rp {{ number_format($item->uang_harian ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            /org/hari
                        </div>
                    </td>
                    
                    <!-- Kolom Total Keseluruhan -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                        <div class="text-blue-600">
                            Rp {{ number_format($item->total_keseluruhan ?? $item->total ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $item->lama_perjadin ?? 0 }} hari × {{ count($pegawaiList) }} org
                        </div>
                    </td>
                    
                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('rincian.show', $item->id) }}" 
                               class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-150 tooltip" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                                <span class="tooltip-text">Lihat Detail</span>
                            </a>
                            <a href="{{ route('rincian.cetak', $item->id) }}" 
                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150 tooltip" 
                               title="Cetak PDF"
                               target="_blank">
                                <i class="fas fa-print"></i>
                                <span class="tooltip-text">Cetak PDF</span>
                            </a>
                            <a href="{{ route('rincian.edit', $item->id) }}" 
                               class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50 transition duration-150 tooltip" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit</span>
                            </a>
                            
                            <button type="button" 
                                    onclick="showDeleteConfirmation(
                                        {{ $item->id }}, 
                                        '{{ addslashes($item->nomor_sppd ?? 'Rincian Biaya') }}', 
                                        '{{ addslashes($item->tempat_tujuan ?? '-') }}'
                                    )"
                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150 tooltip" 
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                                <span class="tooltip-text">Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-receipt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Belum ada data rincian biaya</p>
                            <p class="text-sm mt-1">Sync dari SPD atau tambah rincian manual</p>
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('rincian.sync-all') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200">
                                    <i class="fas fa-sync-alt mr-2"></i> Sync dari SPD
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION - SAMA PERSIS DENGAN MENU LAIN -->
@if($rincian->count() > 0)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $rincian->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $rincian->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $rincian->total() }}</span> 
        rincian biaya
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($rincian->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $rincian->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements dengan Range dan Ellipsis --}}
        @php
            $current = $rincian->currentPage();
            $last = $rincian->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        {{-- Tombol ke halaman 1 jika tidak dimulai dari 1 --}}
        @if($start > 1)
            <a href="{{ $rincian->url(1) }}" 
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
                <a href="{{ $rincian->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        {{-- Tombol ke halaman terakhir jika tidak sampai akhir --}}
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $rincian->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($rincian->hasMorePages())
            <a href="{{ $rincian->nextPageUrl() }}" 
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

// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        const warningNotif = document.getElementById('warning-notification');
        
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
        if (warningNotif) hideNotification('warning');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNomor = null;

function showDeleteConfirmation(id, nomor, tujuan) {
    currentDeleteId = id;
    currentDeleteNomor = nomor;
    
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-tujuan').innerHTML = tujuan ? `<i class="fas fa-map-marker-alt mr-1"></i> ${tujuan}` : 'Tanpa tujuan';
    
    const form = document.getElementById('delete-form');
    form.action = `/rincian/${id}`;
    
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
    
    message.textContent = `Rincian biaya dengan nomor SPPD "${nomor}" berhasil dihapus.`;
    
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
    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;
    document.getElementById('full-text-modal').classList.remove('hidden');
}

function hideFullTextModal() {
    document.getElementById('full-text-modal').classList.add('hidden');
}

// ========== CLOSE MODALS ==========
document.getElementById('delete-confirm-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideFullTextModal();
    }
});

window.onclick = function(event) {
    const fullTextModal = document.getElementById('full-text-modal');
    if (event.target === fullTextModal) {
        hideFullTextModal();
    }
}
</script>
@endsection