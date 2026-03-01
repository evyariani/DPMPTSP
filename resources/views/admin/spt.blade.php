{{-- resources/views/admin/spt.blade.php --}}
@extends('layouts.admin')

@section('title', 'Surat Perintah Tugas (SPT)')

@section('subtitle', 'Kelola dokumen Surat Perintah Tugas')

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

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.2);
}
</style>

<div class="space-y-6">
    {{-- Header dengan Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-indigo-600"></i>
                Surat Perintah Tugas (SPT)
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Kelola dokumen Surat Perintah Tugas dan data terkait
            </p>
        </div>
        <a href="{{ route('spt.create') }}" 
           class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Buat SPT Baru
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total SPT</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalSpt ?? 0) }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-file-alt text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">SPT Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($sptBulanIni ?? 0) }}</p>
                </div>
                <div class="bg-emerald-100 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Penandatangan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($penandatanganList->count() ?? 0) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-signature text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Tahun {{ date('Y') }}</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($spts->total() ?? 0) }}</p>
                </div>
                <div class="bg-amber-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Toast dengan Alpine.js --}}
    @if(session('success'))
    <div id="success-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom" x-data="{ show: true }" x-show="show">
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Berhasil!</p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
                <button type="button" @click="show = false" class="ml-4 text-emerald-600 hover:text-emerald-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-emerald-200 rounded-full h-1">
                <div class="bg-emerald-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="error-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom" x-data="{ show: true }" x-show="show">
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-rose-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Terjadi Kesalahan!</p>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
                <button type="button" @click="show = false" class="ml-4 text-rose-600 hover:text-rose-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-rose-200 rounded-full h-1">
                <div class="bg-rose-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-auto animate-fade-in">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-2xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                    
                    <div class="mb-6 text-left">
                        <p class="text-gray-600 mb-3">Anda akan menghapus SPT:</p>
                        
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <p class="font-semibold text-gray-800 text-lg" id="delete-nomor"></p>
                            <p class="text-gray-600 text-sm mt-1" id="delete-keperluan"></p>
                            <p class="text-gray-500 text-xs mt-2" id="delete-tanggal"></p>
                        </div>
                        
                        <div class="bg-rose-50 border-l-4 border-rose-400 p-3 rounded">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-rose-500 mt-0.5"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-rose-700">
                                        Data yang dihapus <span class="font-semibold">tidak dapat dikembalikan</span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        <button type="button" 
                                onclick="hideDeleteModal()"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        
                        <form id="delete-form" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                                <i class="fas fa-trash mr-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <i class="fas fa-filter text-indigo-500 text-xs"></i>
                Filter Pencarian
            </h3>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('spt.index') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" name="search" placeholder="Cari nomor surat atau keperluan..." 
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <select name="penandatangan" class="w-56 px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                            <option value="">Semua Penandatangan</option>
                            @foreach($penandatanganList ?? [] as $pegawai)
                                <option value="{{ $pegawai->id_pegawai }}" {{ request('penandatangan') == $pegawai->id_pegawai ? 'selected' : '' }}>
                                    {{ $pegawai->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center shadow-sm hover:shadow">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('penandatangan') || request()->has('tanggal_awal') || request()->has('tanggal_akhir'))
                            <a href="{{ route('spt.index') }}" 
                               class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-redo mr-2"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Filter Tanggal -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                        <i class="fas fa-calendar-alt text-indigo-500 mr-1"></i>
                        Rentang Tanggal:
                    </span>
                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                               class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                        <span class="text-gray-400">s/d</span>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                               class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel SPT --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Untuk Keperluan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pegawai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penandatangan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $spts = $spts ?? collect([]);
                        $isPaginated = method_exists($spts, 'currentPage');
                    @endphp
                    
                    @forelse($spts as $index => $spt)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($isPaginated)
                                {{ ($spts->currentPage() - 1) * $spts->perPage() + $index + 1 }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 font-mono">{{ $spt->nomor_surat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 flex items-center">
                                <i class="far fa-calendar-alt text-indigo-400 mr-2 text-xs"></i>
                                {{ \Carbon\Carbon::parse($spt->tanggal_surat_dibuat)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $spt->untuk_keperluan }}">
                                {{ $spt->untuk_keperluan }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $jumlah = is_array($spt->pegawai_yang_diperintahkan) ? count($spt->pegawai_yang_diperintahkan) : 0;
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $jumlah > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                <i class="fas fa-users mr-1 text-xs"></i>
                                {{ $jumlah }} Orang
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                @php
                                    $nama = $spt->penandatangan_nama;
                                    if ($nama == '-' && $spt->penandatangan) {
                                        $nama = $spt->penandatangan->nama ?? '-';
                                    }
                                @endphp
                                {{ $nama }}
                            </div>
                            <div class="text-xs text-gray-500">
                                @php
                                    $jabatan = $spt->penandatangan_jabatan;
                                    if ($jabatan == '-' && $spt->penandatangan) {
                                        $jabatanBase = $spt->penandatangan->jabatan ?? '-';
                                        $nip = $spt->penandatangan->nip ?? null;
                                        $jabatan = $jabatanBase;
                                        if (!is_null($nip) && $nip !== '') {
                                            $jabatan .= ' (NIP: ' . $nip . ')';
                                        }
                                    }
                                @endphp
                                <i class="fas fa-briefcase text-indigo-300 mr-1 text-[10px]"></i>
                                {{ $jabatan }}
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center">
                                <i class="fas fa-map-marker-alt text-amber-500 mr-1 text-xs"></i>
                                {{ $spt->kota }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('spt.show', $spt->id_spt) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-colors duration-200 text-xs"
                                   title="Lihat Detail SPT">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                
                                <a href="{{ route('spt.edit', $spt->id_spt) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors duration-200 text-xs"
                                   title="Edit SPT">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                
                                <a href="{{ route('spt.cetak', $spt->id_spt) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors duration-200 text-xs"
                                   title="Cetak SPT" target="_blank">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </a>
                                
                                <button type="button" 
                                        onclick="showDeleteConfirmation({{ $spt->id_spt }}, '{{ addslashes($spt->nomor_surat) }}', '{{ addslashes($spt->untuk_keperluan) }}', '{{ $spt->tanggal_surat_dibuat }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors duration-200 text-xs"
                                        title="Hapus SPT">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-800 mb-2">Belum Ada Data SPT</p>
                                <p class="text-sm text-gray-500 mb-4">Mulai dengan membuat Surat Perintah Tugas baru</p>
                                <a href="{{ route('spt.create') }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-plus mr-2"></i> Buat SPT Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer dengan Info Pagination --}}
        @if(isset($spts) && method_exists($spts, 'hasPages') && $spts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan 
                    <span class="font-medium text-gray-900">{{ $spts->firstItem() ?: 0 }}</span> 
                    sampai 
                    <span class="font-medium text-gray-900">{{ $spts->lastItem() ?: 0 }}</span> 
                    dari 
                    <span class="font-medium text-gray-900">{{ $spts->total() }}</span> 
                    SPT
                </div>
                
                {{-- Pagination Links --}}
                <div class="flex items-center gap-1">
                    @if ($spts->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-200 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $spts->previousPageUrl() }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif
                    
                    @php
                        $current = $spts->currentPage();
                        $last = $spts->lastPage();
                        $start = max($current - 2, 1);
                        $end = min($current + 2, $last);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $spts->url(1) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">1</a>
                        @if($start > 2)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif
                    
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $spts->url($page) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $page }}</a>
                        @endif
                    @endfor
                    
                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $spts->url($last) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $last }}</a>
                    @endif
                    
                    @if ($spts->hasMorePages())
                        <a href="{{ $spts->nextPageUrl() }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-200 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
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
        
        if (successNotif && successNotif.__x) {
            successNotif.__x.$data.show = false;
        }
        if (errorNotif && errorNotif.__x) {
            errorNotif.__x.$data.show = false;
        }
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;

function showDeleteConfirmation(id, nomor, keperluan, tanggal) {
    currentDeleteId = id;
    
    // Update modal content
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-keperluan').textContent = keperluan;
    document.getElementById('delete-tanggal').textContent = `Tanggal: ${tanggal}`;
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/spt/${id}`;
    
    // Show modal
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
    }, 300);
}

// Close modal when clicking outside
document.getElementById('delete-confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});
</script>
@endsection