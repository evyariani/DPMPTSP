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

/* Custom untuk SPT */
.spt-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}

.spt-badge-dinas {
    @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.spt-badge-pribadi {
    @apply bg-green-100 text-green-800 border border-green-200;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-pending {
    background-color: #fef3c7;
    color: #d97706;
}

.status-approved {
    background-color: #d1fae5;
    color: #059669;
}

.status-rejected {
    background-color: #fee2e2;
    color: #dc2626;
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

.fixed-col-tujuan {
    min-width: 250px;
    max-width: 350px;
}

.fixed-col-tanggal {
    min-width: 120px;
    max-width: 150px;
}

.fixed-col-lokasi {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-pegawai {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-dasar {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-penandatangan {
    min-width: 180px;
    max-width: 250px;
}

.fixed-col-status {
    min-width: 120px;
    max-width: 150px;
}

/* Hover effect untuk sel tabel */
.table-cell-hover:hover {
    background-color: #f9fafb;
}

/* Badge untuk jumlah pegawai */
.pegawai-count-badge {
    @apply ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Tugas (SPT)</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Tugas</p>
        </div>
        <a href="{{ route('spt.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah SPT
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

<!-- Notifikasi Reset Approval -->
<div id="reset-notification" class="hidden fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-purple-50 border-l-4 border-purple-500 text-purple-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-undo-alt text-purple-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Status Direset!</p>
                <p id="reset-message" class="text-sm mt-1"></p>
            </div>
            <button type="button" onclick="hideNotification('reset')" class="ml-4 text-purple-600 hover:text-purple-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-purple-200 rounded-full h-1">
            <div id="reset-progress" class="bg-purple-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
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

<!-- Modal Konfirmasi Reset Approval -->
<div id="reset-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <i class="fas fa-undo-alt text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Reset Status Approval</h3>
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan mereset status approval SPT:</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="reset-nomor"></p>
                        <p class="text-gray-600 text-sm mt-1" id="reset-status"></p>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Status akan dikembalikan ke <span class="font-semibold">"Menunggu Persetujuan"</span>.
                                    TTD dan stempel akan dihapus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideResetModal()" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <form id="reset-form" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-undo-alt mr-2"></i> Reset Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('spt.index') }}" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari nomor surat, tujuan, lokasi, atau nama pegawai..." 
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
            
            <select name="penanda_tangan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Penanda Tangan</option>
                @foreach($pegawais ?? [] as $pegawai)
                    <option value="{{ $pegawai->id_pegawai }}" {{ request('penanda_tangan') == $pegawai->id_pegawai ? 'selected' : '' }}>
                        {{ $pegawai->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status_approval" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status_approval') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status_approval') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status_approval') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            
            @if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('penanda_tangan') || request()->has('status_approval'))
                <a href="{{ route('spt.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel SPT -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nomor">Nomor Surat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-dasar">Dasar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-pegawai">Pegawai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tujuan">Tujuan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-lokasi">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-penandatangan">Penanda Tangan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-status">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $spts = $spts ?? collect([]);
                    $isPaginated = method_exists($spts, 'currentPage');
                @endphp
                
                @forelse($spts as $index => $spt)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($isPaginated)
                            {{ ($spts->currentPage() - 1) * $spts->perPage() + $index + 1 }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    
                    <!-- Kolom Nomor Surat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-nomor table-cell-hover">
                        <div class="text-sm font-medium text-gray-900" title="{{ $spt->nomor_surat }}">
                            {{ Str::limit($spt->nomor_surat, 30) }}
                        </div>
                        @if(strlen($spt->nomor_surat) > 30)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($spt->nomor_surat) }}', 'Nomor Surat')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Dasar -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-dasar table-cell-hover">
                        @if(!empty($spt->dasar))
                            @php $dasarList = $spt->dasar_list; @endphp
                            @if(count($dasarList) > 1)
                                <div class="space-y-1">
                                    @foreach(array_slice($dasarList, 0, 2) as $dasar)
                                        <div class="text-sm text-gray-700">• {{ Str::limit($dasar, 30) }}</div>
                                    @endforeach
                                    @if(count($dasarList) > 2)
                                        <button type="button" 
                                                onclick="showFullDasar(this, {{ json_encode($dasarList) }})"
                                                class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                            + {{ count($dasarList) - 2 }} dasar lainnya
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-gray-700">{{ Str::limit($dasarList[0] ?? '', 50) }}</div>
                                @if(strlen($dasarList[0] ?? '') > 50)
                                    <button type="button" 
                                            onclick="showFullText(this, '{{ addslashes($dasarList[0]) }}', 'Dasar')"
                                            class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                        Lihat selengkapnya
                                    </button>
                                @endif
                            @endif
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Pegawai -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-pegawai table-cell-hover">
                        @if(!empty($spt->pegawai))
                            @php $pegawaiList = $spt->pegawai_list; @endphp
                            <div class="space-y-2">
                                @foreach($pegawaiList as $pegawai)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 mr-2">
                                            <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-xs">
                                                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-900" title="{{ $pegawai->nama }}">
                                            {{ Str::limit($pegawai->nama, 25) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Tujuan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tujuan table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $spt->tujuan }}">
                            {{ Str::limit($spt->tujuan, 80) }}
                        </div>
                        @if(strlen($spt->tujuan) > 80)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($spt->tujuan) }}', 'Tujuan')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Tanggal -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 fixed-col-tanggal">
                        @if($spt->tanggal)
                            <div class="font-medium">{{ $spt->tanggal->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $spt->tanggal->format('l') }}</div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Lokasi -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-lokasi table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $spt->lokasi }}">
                            {{ Str::limit($spt->lokasi, 30) }}
                        </div>
                        @if(strlen($spt->lokasi) > 30)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($spt->lokasi) }}', 'Lokasi')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Penanda Tangan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-penandatangan table-cell-hover">
                        @if($spt->penandaTangan)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-semibold text-sm">
                                            {{ strtoupper(substr($spt->penandaTangan->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900" title="{{ $spt->penandaTangan->nama }}">
                                        {{ Str::limit($spt->penandaTangan->nama, 30) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $spt->penandaTangan->jabatan ?? '-' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Status Approval -->
                    <td class="px-6 py-4 whitespace-nowrap fixed-col-status">
                        @if($spt->isApproved())
                            <span class="status-badge status-approved">
                                <i class="fas fa-check-circle mr-1"></i> Disetujui
                            </span>
                            @if($spt->approved_at)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $spt->approved_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        @elseif($spt->isRejected())
                            <span class="status-badge status-rejected">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </span>
                            @if($spt->rejection_reason)
                                <div class="text-xs text-gray-500 mt-1" title="{{ $spt->rejection_reason }}">
                                    {{ Str::limit($spt->rejection_reason, 20) }}
                                </div>
                            @endif
                        @else
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock mr-1"></i> Menunggu
                            </span>
                        @endif
                    </td>
                    
                    <!-- Kolom Aksi - PERBAIKAN: Edit dan Hapus bisa untuk PENDING dan REJECTED -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-wrap gap-2">
                            <!-- Tombol Edit - Bisa untuk PENDING dan REJECTED (untuk perbaikan) -->
                            @if($spt->isPending() || $spt->isRejected())
                            <a href="{{ route('spt.edit', $spt->id_spt) }}" 
                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150"
                               title="Edit SPT">
                                <i class="fas fa-edit"></i>
                            </a>
                            @else
                            <span class="text-gray-400 p-1 cursor-not-allowed" title="Tidak dapat diedit (sudah disetujui)">
                                <i class="fas fa-edit"></i>
                            </span>
                            @endif
                            
                            <!-- Tombol Download PDF -->
                            <a href="{{ route('spt.print', $spt->id_spt) }}" 
                               target="_blank"
                               class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition duration-150"
                               title="Download PDF SPT">
                                <i class="fas fa-download"></i>
                            </a>
                            
                            <!-- Tombol Preview PDF -->
                            <a href="{{ route('spt.preview-pdf', $spt->id_spt) }}" 
                               target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition duration-150"
                               title="Preview PDF SPT">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <!-- Tombol Reset Approval - Khusus Admin untuk reset status -->
                            @if(($spt->isApproved() || $spt->isRejected()) && (session('user')['level'] ?? 'guest') == 'admin')
                            <button type="button" 
                                    onclick="showResetConfirmation(
                                        {{ $spt->id_spt }}, 
                                        '{{ addslashes(Str::limit($spt->nomor_surat, 50)) }}', 
                                        '{{ $spt->isApproved() ? 'Disetujui' : 'Ditolak' }}'
                                    )"
                                    class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50 transition duration-150"
                                    title="Reset Status Approval">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                            @endif
                            
                            <!-- Tombol Hapus - Bisa untuk PENDING dan REJECTED (untuk perbaikan) -->
                            @if($spt->isPending() || $spt->isRejected())
                            <button type="button" 
                                    onclick="showDeleteConfirmation(
                                        {{ $spt->id_spt }}, 
                                        '{{ addslashes(Str::limit($spt->nomor_surat, 30)) }}', 
                                        '{{ addslashes(Str::limit($spt->tujuan, 50)) }}'
                                    )"
                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150"
                                    title="Hapus SPT">
                                <i class="fas fa-trash"></i>
                            </button>
                            @else
                            <span class="text-gray-400 p-1 cursor-not-allowed" title="Tidak dapat dihapus (sudah disetujui)">
                                <i class="fas fa-trash"></i>
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-file-alt text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPT</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan Surat Perintah Tugas baru</p>
                            <a href="{{ route('spt.create') }}" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
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

<!-- Pagination -->
@if(isset($spts) && method_exists($spts, 'hasPages') && $spts->hasPages())
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
        {{ $spts->links() }}
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
        hideNotification('success');
        hideNotification('error');
        hideNotification('warning');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNomor = null;

function showDeleteConfirmation(id, nomor, tujuan) {
    currentDeleteId = id;
    currentDeleteNomor = nomor;
    
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-tujuan').textContent = tujuan ? `Tujuan: ${tujuan}` : 'Tanpa Tujuan';
    
    const form = document.getElementById('delete-form');
    form.action = `/spt/${id}`;
    
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    currentDeleteId = null;
    currentDeleteNomor = null;
}

// ========== RESET APPROVAL FUNCTIONS ==========
let currentResetId = null;

function showResetConfirmation(id, nomor, status) {
    currentResetId = id;
    
    document.getElementById('reset-nomor').textContent = nomor;
    document.getElementById('reset-status').textContent = `Status saat ini: ${status}`;
    
    const form = document.getElementById('reset-form');
    form.action = `/spt/${id}/reset-approval`;
    
    const modal = document.getElementById('reset-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideResetModal() {
    const modal = document.getElementById('reset-confirm-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    currentResetId = null;
}

// Handle reset form submission
document.getElementById('reset-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mereset...';
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
            showResetSuccess(document.getElementById('reset-nomor').textContent);
            hideResetModal();
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Gagal mereset status');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showResetSuccess(nomor) {
    const notification = document.getElementById('reset-notification');
    const message = document.getElementById('reset-message');
    message.textContent = `Status approval SPT "${nomor}" berhasil direset ke Menunggu.`;
    
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    notification.classList.add('animate-slide-in-bottom');
    
    setTimeout(() => {
        hideNotification('reset');
    }, 5000);
}

// Handle delete form submission with AJAX
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
        alert('Terjadi kesalahan saat menghapus data: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showDeleteSuccess(nomor) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    message.textContent = `Data SPT dengan nomor "${nomor}" berhasil dihapus.`;
    
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

// ========== FULL DASAR MODAL ==========
function showFullDasar(element, dasarList) {
    const modalId = 'full-dasar-modal';
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
                            <h3 class="text-lg font-semibold text-gray-900">Dasar SPT</h3>
                            <button type="button" onclick="hideFullDasarModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <ul class="list-disc list-inside space-y-2" id="full-dasar-list"></ul>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="hideFullDasarModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    const listElement = document.getElementById('full-dasar-list');
    listElement.innerHTML = '';
    dasarList.forEach((dasar) => {
        const li = document.createElement('li');
        li.className = 'text-sm text-gray-700';
        li.textContent = dasar;
        listElement.appendChild(li);
    });
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideFullDasarModal() {
    const modal = document.getElementById('full-dasar-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Close modals when clicking outside
document.getElementById('delete-confirm-modal')?.addEventListener('click', function(e) {
    if (e.target === this) hideDeleteModal();
});

document.getElementById('reset-confirm-modal')?.addEventListener('click', function(e) {
    if (e.target === this) hideResetModal();
});

document.addEventListener('click', function(e) {
    const fullTextModal = document.getElementById('full-text-modal');
    if (fullTextModal && e.target === fullTextModal) hideFullTextModal();
    
    const fullDasarModal = document.getElementById('full-dasar-modal');
    if (fullDasarModal && e.target === fullDasarModal) hideFullDasarModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideResetModal();
        hideFullTextModal();
        hideFullDasarModal();
    }
});
</script>
@endsection