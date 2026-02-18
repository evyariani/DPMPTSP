@extends('layouts.admin')

@section('title', 'Program')

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

/* Custom untuk tabel program */
.program-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}

.program-badge-0001 {
    @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.program-badge-0003 {
    @apply bg-green-100 text-green-800 border border-green-200;
}

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Fixed width untuk kolom */
.fixed-col-nama {
    min-width: 200px;
    max-width: 250px;
}

.fixed-col-nip {
    min-width: 150px;
    max-width: 180px;
}

.fixed-col-jabatan {
    min-width: 180px;
    max-width: 250px;
}

.fixed-col-program {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-kegiatan {
    min-width: 250px;
    max-width: 350px;
}

.fixed-col-subkegiatan {
    min-width: 200px;
    max-width: 300px;
}

/* Hover effect untuk sel tabel */
.table-cell-hover:hover {
    background-color: #f9fafb;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Data Program</h2>
            <p class="text-gray-500">Kelola data program dan kegiatan</p>
        </div>
        <a href="{{ route('program.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Program
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
                    <p class="text-gray-600 mb-3">Anda akan menghapus data program:</p>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-nama"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-rekening"></p>
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
    <form method="GET" action="/program" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari program, kegiatan, sub kegiatan, kode rekening, atau nama pegawai..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-wrap gap-2">
            <select name="kode_rekening" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Rekening</option>
                @foreach(App\Models\Program::getRekeningOptions() as $key => $value)
                    <option value="{{ $key }}" {{ request('kode_rekening') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            @if(request()->has('search') || request()->has('kode_rekening'))
                <a href="/program" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Program -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-program">Program</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-kegiatan">Kegiatan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-subkegiatan">Sub Kegiatan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekening</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nama">Nama Pegawai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nip">NIP</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-jabatan">Jabatan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    // Pastikan $programs selalu ada dan bisa di-loop
                    $programs = $programs ?? collect([]);
                    $isPaginated = method_exists($programs, 'currentPage');
                @endphp
                
                @forelse($programs as $index => $program)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($isPaginated)
                            {{ ($programs->currentPage() - 1) * $programs->perPage() + $index + 1 }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    
                    <!-- Kolom Program -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-program table-cell-hover">
                        <div class="text-sm font-medium text-gray-900" title="{{ $program->program }}">
                            {{ Str::limit($program->program, 70) }}
                        </div>
                        @if(strlen($program->program) > 70)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($program->program) }}', 'Program')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Kegiatan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-kegiatan table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $program->kegiatan }}">
                            {{ Str::limit($program->kegiatan, 80) }}
                        </div>
                        @if(strlen($program->kegiatan) > 80)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($program->kegiatan) }}', 'Kegiatan')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Sub Kegiatan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-subkegiatan table-cell-hover">
                        <div class="text-sm text-gray-900" title="{{ $program->sub_kegiatan }}">
                            {{ Str::limit($program->sub_kegiatan, 70) }}
                        </div>
                        @if(strlen($program->sub_kegiatan) > 70)
                            <button type="button" 
                                    onclick="showFullText(this, '{{ addslashes($program->sub_kegiatan) }}', 'Sub Kegiatan')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        @endif
                    </td>
                    
                    <!-- Kolom Rekening -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($program->kode_rekening == '5.1.02.04.01.0001')
                            <span class="program-badge program-badge-0001" title="Belanja Barang Operasional">
                                {{ $program->kode_rekening }}
                            </span>
                        @elseif($program->kode_rekening == '5.1.02.04.01.0003')
                            <span class="program-badge program-badge-0003" title="Belanja Barang Perlengkapan">
                                {{ $program->kode_rekening }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $program->kode_rekening }}
                            </span>
                        @endif
                    </td>
                    
                    <!-- Kolom Nama Pegawai -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-nama table-cell-hover">
                        @if($program->pegawai)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            {{ strtoupper(substr($program->pegawai->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900" title="{{ $program->pegawai->nama }}">
                                    {{ Str::limit($program->pegawai->nama, 40) }}
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom NIP -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 fixed-col-nip">
                        @if($program->pegawai && $program->pegawai->nip)
                            <div class="font-mono text-xs" title="{{ $program->pegawai->nip }}">
                                {{ $program->pegawai->nip }}
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Jabatan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-jabatan table-cell-hover">
                        @if($program->pegawai && $program->pegawai->jabatan)
                            <div class="text-sm text-gray-700" title="{{ $program->pegawai->jabatan }}">
                                {{ Str::limit($program->pegawai->jabatan, 50) }}
                            </div>
                            @if(strlen($program->pegawai->jabatan) > 50)
                                <button type="button" 
                                        onclick="showFullText(this, '{{ addslashes($program->pegawai->jabatan) }}', 'Jabatan')"
                                        class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                    Lihat selengkapnya
                                </button>
                            @endif
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    
                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if(isset($program->id_program))
                            <a href="{{ route('program.edit', $program->id_program) }}" 
                               class="text-green-600 hover:text-green-900 px-3 py-1 rounded hover:bg-green-50 transition duration-150"
                               title="Edit Program">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            
                            <!-- Tombol Hapus dengan Modal -->
                            <button type="button" 
                                    onclick="showDeleteConfirmation(
                                        {{ $program->id_program }}, 
                                        '{{ addslashes(Str::limit($program->program, 30)) }}', 
                                        '{{ $program->kode_rekening }}'
                                    )"
                                    class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                    title="Hapus Program">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                            @else
                            <span class="text-gray-400 px-3 py-1">Edit</span>
                            <span class="text-gray-400 px-3 py-1">Hapus</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data program</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan program baru</p>
                            <a href="{{ route('program.create') }}" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Program Pertama
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
    // Cek apakah $programs ada dan memiliki method hasPages
    $showPagination = true;
    if (isset($programs) && method_exists($programs, 'hasPages') && $programs->hasPages()) {
        $showPagination = true;
    }
@endphp

@if($showPagination)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $programs->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $programs->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $programs->total() }}</span> 
        program
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($programs->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $programs->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements --}}
        @php
            $current = $programs->currentPage();
            $last = $programs->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        @if($start > 1)
            <a href="{{ $programs->url(1) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif
        
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $programs->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $programs->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($programs->hasMorePages())
            <a href="{{ $programs->nextPageUrl() }}" 
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
    // Auto hide success/error notifications
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNama = null;

function showDeleteConfirmation(id, nama, rekening) {
    currentDeleteId = id;
    currentDeleteNama = nama;
    
    // Update modal content
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('delete-rekening').textContent = rekening ? `Rekening: ${rekening}` : 'Tanpa Rekening';
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/program/${id}`;
    
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
        // Jika error, tampilkan alert biasa
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
    
    message.textContent = `Data program "${nama}" berhasil dihapus.`;
    
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

// ========== FULL TEXT MODAL ==========
function showFullText(element, text, title) {
    // Buat modal untuk menampilkan teks lengkap
    const modalId = 'full-text-modal';
    let modal = document.getElementById(modalId);
    
    if (!modal) {
        // Buat modal jika belum ada
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
    
    // Isi konten modal
    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;
    
    // Tampilkan modal
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

// Close full text modal when clicking outside
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