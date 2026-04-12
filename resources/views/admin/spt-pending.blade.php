@extends('layouts.admin')

@section('title', 'Persetujuan SPT - Dashboard Kadis')

@section('content')
<style>
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

/* Card hover effect */
.approval-card {
    transition: all 0.2s ease;
}

.approval-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Persetujuan Surat Perintah Tugas (SPT)</h2>
            <p class="text-gray-500">Menunggu persetujuan Kepala Dinas</p>
        </div>
        <div class="flex space-x-2">
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                <i class="fas fa-clock mr-1"></i> Menunggu: {{ $spts->total() ?? 0 }}
            </span>
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

@if(session('info'))
<div id="info-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Informasi!</p>
                <p class="text-sm mt-1">{{ session('info') }}</p>
            </div>
            <button type="button" onclick="hideNotification('info')" class="ml-4 text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1">
            <div id="info-progress" class="bg-blue-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
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

<!-- Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('kadis.spt.approval') }}" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari nomor surat, tujuan, atau nama penanda tangan..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            @if(request('search'))
            <a href="{{ route('kadis.spt.approval') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Daftar SPT Pending -->
<div class="space-y-4">
    @forelse($spts as $spt)
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-yellow-500 approval-card">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="status-badge status-pending">
                            <i class="fas fa-clock mr-1"></i> Menunggu Persetujuan
                        </span>
                        <span class="text-gray-400 text-sm">#{{ $spt->id_spt }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $spt->nomor_surat }}</h3>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i> {{ $spt->lokasi }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i> Dibuat: {{ $spt->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-calendar-week mr-1"></i> Tanggal SPT: {{ $spt->tanggal->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            
            <!-- Detail Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Tujuan -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <label class="text-xs text-gray-500 uppercase font-semibold">
                        <i class="fas fa-bullseye mr-1"></i> Tujuan
                    </label>
                    <p class="text-gray-800 mt-1">{{ $spt->tujuan }}</p>
                </div>
                
                <!-- Dasar -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <label class="text-xs text-gray-500 uppercase font-semibold">
                        <i class="fas fa-gavel mr-1"></i> Dasar
                    </label>
                    <ul class="list-disc list-inside mt-1">
                        @foreach($spt->dasar_list as $dasar)
                            <li class="text-gray-800 text-sm">{{ $dasar }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Pegawai yang Ditugaskan -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <label class="text-xs text-gray-500 uppercase font-semibold">
                        <i class="fas fa-users mr-1"></i> Pegawai yang Ditugaskan
                    </label>
                    <div class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                        @foreach($spt->pegawai_list as $pegawai)
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2 flex-shrink-0">
                                <span class="text-indigo-600 font-semibold text-sm">
                                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $pegawai->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $pegawai->jabatan ?? '-' }} | NIP: {{ $pegawai->nip ?? '-' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Total: {{ count($spt->pegawai_list) }} orang
                    </div>
                </div>
                
                <!-- Penanda Tangan -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <label class="text-xs text-gray-500 uppercase font-semibold">
                        <i class="fas fa-pen mr-1"></i> Penanda Tangan
                    </label>
                    <div class="flex items-center mt-2">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <span class="text-green-600 font-semibold">
                                {{ strtoupper(substr($spt->penandaTangan->nama ?? '-', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $spt->penandaTangan->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $spt->penandaTangan->jabatan ?? '-' }}</p>
                            @if($spt->penandaTangan->nip)
                            <p class="text-xs text-gray-400">NIP: {{ $spt->penandaTangan->nip }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <!-- Tombol Tolak -->
                <button type="button" 
                        onclick="showRejectModal({{ $spt->id_spt }}, '{{ addslashes($spt->nomor_surat) }}')"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Tolak
                </button>
                
                <!-- Tombol Setujui -->
                <button type="button" 
                        onclick="confirmApprove({{ $spt->id_spt }}, '{{ addslashes($spt->nomor_surat) }}')"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center">
                    <i class="fas fa-check mr-2"></i> Setujui
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pengajuan</h3>
        <p class="text-gray-500">Semua SPT sudah diproses. Tidak ada pengajuan yang menunggu persetujuan.</p>
    </div>
    @endforelse
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

<!-- Modal Approve Konfirmasi -->
<div id="approve-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Setujui SPT?</h3>
                    <p class="text-gray-600 mb-2" id="approve-nomor"></p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded text-left mb-6">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Dengan menyetujui, surat akan ditandatangani secara digital dan stempel akan otomatis muncul di PDF.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" 
                                onclick="hideApproveModal()"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                            Batal
                        </button>
                        <form id="approve-form" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <i class="fas fa-check mr-2"></i> Ya, Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject (dengan alasan) -->
<div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tolak SPT</h3>
                    <p class="text-gray-600 mb-4" id="reject-nomor"></p>
                    
                    <form id="reject-form" method="POST" class="text-left">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason" 
                                      id="rejection_reason"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                      placeholder="Masukkan alasan penolakan..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimal 5 karakter</p>
                        </div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" 
                                    onclick="hideRejectModal()"
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                <i class="fas fa-times mr-2"></i> Ya, Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide notifications
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

// Auto hide after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        hideNotification('success');
        hideNotification('error');
        hideNotification('info');
        hideNotification('warning');
    }, 5000);
});

let currentApproveId = null;
let currentRejectId = null;

// Approve functions
function confirmApprove(id, nomor) {
    currentApproveId = id;
    document.getElementById('approve-nomor').innerHTML = `<strong>${nomor}</strong>`;
    const form = document.getElementById('approve-form');
    form.action = `/kadis/spt/${id}/approve`;
    
    const modal = document.getElementById('approve-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideApproveModal() {
    const modal = document.getElementById('approve-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    currentApproveId = null;
}

// Reject functions
function showRejectModal(id, nomor) {
    currentRejectId = id;
    document.getElementById('reject-nomor').innerHTML = `<strong>${nomor}</strong>`;
    const form = document.getElementById('reject-form');
    form.action = `/kadis/spt/${id}/reject`;
    
    const modal = document.getElementById('reject-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideRejectModal() {
    const modal = document.getElementById('reject-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('rejection_reason').value = '';
    currentRejectId = null;
}

// ===== PERBAIKAN: Approve form - SUBMIT BIASA (tanpa AJAX) =====
document.getElementById('approve-form')?.addEventListener('submit', function(e) {
    // TIDAK ADA preventDefault() - biarkan submit normal
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        submitBtn.disabled = true;
    }
    // Form akan submit normal, redirect akan mengikuti dari controller
});

// ===== PERBAIKAN: Reject form - SUBMIT BIASA (tanpa AJAX) =====
document.getElementById('reject-form')?.addEventListener('submit', function(e) {
    const reason = document.getElementById('rejection_reason').value.trim();
    
    // Validasi alasan penolakan
    if (reason.length < 5) {
        e.preventDefault(); // Hanya cegah jika validasi gagal
        alert('Alasan penolakan minimal 5 karakter!');
        return;
    }
    
    // Jika validasi lolos, submit normal
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        submitBtn.disabled = true;
    }
    // Form akan submit normal
});

// Close modals on outside click
document.getElementById('approve-modal')?.addEventListener('click', function(e) {
    if (e.target === this) hideApproveModal();
});

document.getElementById('reject-modal')?.addEventListener('click', function(e) {
    if (e.target === this) hideRejectModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
    }
});
</script>
@endsection