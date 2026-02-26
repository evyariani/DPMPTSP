{{-- resources/views/admin/pegawai.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Pegawai')

@section('subtitle', 'Kelola data pegawai sistem')

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
</style>

<div class="space-y-6">
    {{-- Header dengan Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-tie text-indigo-600"></i>
                Data Pegawai
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Kelola data pegawai yang terdaftar dalam sistem
            </p>
        </div>
        <a href="/pegawai/create" 
           class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pegawai Baru
        </a>
    </div>

    {{-- Notifikasi Toast --}}
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

    {{-- Notifikasi Hapus --}}
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

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-auto animate-fade-in">
                <div class="p-6 text-center">
                    <!-- Icon Warning -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-2xl"></i>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                    
                    <!-- Message -->
                    <div class="mb-6 text-left">
                        <p class="text-gray-600 mb-3">Anda akan menghapus data pegawai:</p>
                        
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <p class="font-semibold text-gray-800 text-lg" id="delete-nama"></p>
                            <p class="text-gray-600 text-sm mt-1" id="delete-nip"></p>
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
                    
                    <!-- Action Buttons -->
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
            <form method="GET" action="/pegawai" class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="search" placeholder="Cari nama, NIP, atau TK Jalan..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <select name="pangkat" class="w-40 px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                        <option value="">Semua Pangkat</option>
                        @foreach($pangkatList ?? [] as $pangkat)
                            <option value="{{ $pangkat }}" {{ request('pangkat') == $pangkat ? 'selected' : '' }}>
                                {{ $pangkat }}
                            </option>
                        @endforeach
                    </select>
                    <select name="golongan" class="w-40 px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                        <option value="">Semua Golongan</option>
                        @foreach($golonganList ?? [] as $golongan)
                            <option value="{{ $golongan }}" {{ request('golongan') == $golongan ? 'selected' : '' }}>
                                {{ $golongan }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center shadow-sm hover:shadow">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    @if(request()->has('search') || request()->has('pangkat') || request()->has('gol'))
                        <a href="/pegawai" 
                           class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pegawai --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pangkat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tunjangan Jalan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $pegawais = $pegawais ?? collect([]);
                        $isPaginated = method_exists($pegawais, 'currentPage');
                    @endphp
                    
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($isPaginated)
                                {{ ($pegawais->currentPage() - 1) * $pegawais->perPage() + $index + 1 }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($pegawai->nama ?? '', 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pegawai->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ $pegawai->nip ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                <i class="fas fa-chevron-circle-up text-indigo-500 mr-1 text-xs"></i>
                                {{ $pegawai->pangkat ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <i class="fas fa-layer-group text-emerald-500 mr-1 text-xs"></i>
                                {{ $pegawai->gol ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $pegawai->jabatan ?? '-' }}">
                                {{ $pegawai->jabatan ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(!empty($pegawai->tk_jalan))
                                @php
                                    $isNumeric = is_numeric($pegawai->tk_jalan);
                                @endphp
                                
                                @if($isNumeric && $pegawai->tk_jalan > 0)
                                    <span class="text-emerald-600 font-medium inline-flex items-center">
                                        <i class="fas fa-tag mr-1 text-emerald-500 text-xs"></i>
                                        Rp {{ number_format($pegawai->tk_jalan, 0, ',', '.') }}
                                    </span>
                                @elseif($isNumeric && $pegawai->tk_jalan == 0)
                                    <span class="text-gray-500 inline-flex items-center">
                                        <i class="fas fa-tag mr-1 text-gray-400 text-xs"></i>
                                        Rp 0
                                    </span>
                                @elseif($isNumeric)
                                    <span class="text-rose-600 font-medium inline-flex items-center">
                                        <i class="fas fa-tag mr-1 text-rose-500 text-xs"></i>
                                        Rp {{ number_format($pegawai->tk_jalan, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fas fa-tag mr-1 text-amber-500 text-xs"></i>
                                        {{ strtoupper($pegawai->tk_jalan) }}
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 inline-flex items-center">
                                    <i class="fas fa-minus mr-1 text-xs"></i>
                                    -
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                @if(isset($pegawai->id_pegawai))
                                <a href="/pegawai/{{ $pegawai->id_pegawai }}/edit" 
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors duration-200 text-xs"
                                   title="Edit Pegawai">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                <button type="button" 
                                        onclick="showDeleteConfirmation({{ $pegawai->id_pegawai }}, '{{ addslashes($pegawai->nama) }}', '{{ $pegawai->nip ?? '' }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors duration-200 text-xs"
                                        title="Hapus Pegawai">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-xs cursor-not-allowed">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </span>
                                <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-xs cursor-not-allowed">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-tie text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-800 mb-2">Belum Ada Data Pegawai</p>
                                <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan pegawai baru ke sistem</p>
                                <a href="/pegawai/create" 
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Pegawai Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer dengan Info Pagination --}}
        @if(isset($pegawais) && $pegawais->count() > 0 && method_exists($pegawais, 'hasPages') && $pegawais->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan 
                    <span class="font-medium text-gray-900">{{ $pegawais->firstItem() ?: 0 }}</span> 
                    sampai 
                    <span class="font-medium text-gray-900">{{ $pegawais->lastItem() ?: 0 }}</span> 
                    dari 
                    <span class="font-medium text-gray-900">{{ $pegawais->total() }}</span> 
                    pegawai
                </div>
                
                {{-- Pagination Links --}}
                <div class="flex items-center gap-1">
                    {{-- Previous Page --}}
                    @if ($pegawais->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-200 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $pegawais->previousPageUrl() }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif
                    
                    {{-- Pagination Numbers --}}
                    @php
                        $current = $pegawais->currentPage();
                        $last = $pegawais->lastPage();
                        $start = max($current - 2, 1);
                        $end = min($current + 2, $last);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $pegawais->url(1) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">1</a>
                        @if($start > 2)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif
                    
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $pegawais->url($page) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $page }}</a>
                        @endif
                    @endfor
                    
                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $pegawais->url($last) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $last }}</a>
                    @endif
                    
                    {{-- Next Page --}}
                    @if ($pegawais->hasMorePages())
                        <a href="{{ $pegawais->nextPageUrl() }}" 
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
    // Auto hide success/error notifications menggunakan Alpine
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
    
    // Format NIP input (jika ada di form create/edit)
    const nipInputs = document.querySelectorAll('input[name="nip"]');
    nipInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.replace(/(\d{8})(\d{6})(\d{1})(\d{3})/, '$1 $2 $3 $4');
            }
            e.target.value = value;
        });
    });
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNama = null;

function showDeleteConfirmation(id, nama, nip) {
    currentDeleteId = id;
    currentDeleteNama = nama;
    
    // Update modal content
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('delete-nip').textContent = nip ? `NIP: ${nip}` : 'Tanpa NIP';
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/pegawai/${id}`;
    
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
        currentDeleteNama = null;
    }, 300);
}

// Handle form submission dengan AJAX untuk notifikasi lebih baik
document.getElementById('delete-form').addEventListener('submit', function(e) {
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
            // Tampilkan notifikasi hapus sukses
            showDeleteSuccess(currentDeleteNama);
            // Sembunyikan modal
            hideDeleteModal();
            // Refresh halaman setelah 2 detik
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
        // Reset tombol
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Tampilkan notifikasi hapus sukses
function showDeleteSuccess(nama) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    
    message.textContent = `Data pegawai "${nama}" berhasil dihapus.`;
    
    // Reset progress bar
    const progress = document.getElementById('delete-progress');
    progress.style.width = '100%';
    progress.style.animation = 'none';
    void progress.offsetWidth; // Trigger reflow
    progress.style.animation = 'progressBar 5s linear forwards';
    
    // Show notification dengan animasi bawah
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    notification.classList.add('animate-slide-in-bottom');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideNotification('delete');
    }, 5000);
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