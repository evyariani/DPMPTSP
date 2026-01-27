{{-- resources/views/admin/transportasi.blade.php --}}
@extends('layouts.admin')

@section('title', 'Transportasi')

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

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Data Transportasi</h2>
            <p class="text-gray-500">Kelola data transportasi sistem</p>
        </div>
        <a href="/transportasi/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Transportasi
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
                    <p class="text-gray-600 mb-3">Anda akan menghapus data transportasi:</p>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-nama"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-lama"></p>
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
    <form method="GET" action="/transportasi" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari jenis transportasi atau lama perjalanan..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-wrap gap-2">
            <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Jenis</option>
                @foreach($jenisList ?? [] as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            @if(request()->has('search') || request()->has('jenis'))
                <a href="/transportasi" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Transportasi -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Transportasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lama Perjalanan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    // Pastikan $transportasis selalu ada dan bisa di-loop
                    $transportasis = $transportasis ?? collect([]);
                    $isPaginated = method_exists($transportasis, 'currentPage');
                @endphp
                
                @forelse($transportasis as $index => $transport)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isPaginated)
                            {{ ($transportasis->currentPage() - 1) * $transportasis->perPage() + $index + 1 }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    @if(strpos(strtolower($transport->jenis_transportasi), 'mobil') !== false)
                                        <i class="fas fa-car text-indigo-600"></i>
                                    @elseif(strpos(strtolower($transport->jenis_transportasi), 'motor') !== false)
                                        <i class="fas fa-motorcycle text-indigo-600"></i>
                                    @elseif(strpos(strtolower($transport->jenis_transportasi), 'pesawat') !== false)
                                        <i class="fas fa-plane text-indigo-600"></i>
                                    @elseif(strpos(strtolower($transport->jenis_transportasi), 'kereta') !== false)
                                        <i class="fas fa-train text-indigo-600"></i>
                                    @elseif(strpos(strtolower($transport->jenis_transportasi), 'kapal') !== false)
                                        <i class="fas fa-ship text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-bus text-indigo-600"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $transport->jenis_transportasi ?? '-' }}</div>
                                {{-- <div class="text-sm text-gray-500">ID: {{ $transport->id_transportasi ?? '-' }}</div> --}}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $transport->lama_perjalanan ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if(isset($transport->id_transportasi))
                            <a href="/transportasi/{{ $transport->id_transportasi }}/edit" 
                               class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded hover:bg-blue-50 transition duration-150"
                               title="Edit Transportasi">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            
                            <!-- Tombol Hapus dengan Modal -->
                            <button type="button" 
                                    onclick="showDeleteConfirmation({{ $transport->id_transportasi }}, '{{ addslashes($transport->jenis_transportasi) }}', '{{ $transport->lama_perjalanan ?? '' }}')"
                                    class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                    title="Hapus Transportasi">
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
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-bus text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data transportasi</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan transportasi baru</p>
                            <a href="/transportasi/create" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Transportasi Pertama
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
    // Cek apakah $transportasis ada dan memiliki method hasPages
    $showPagination = false;
    if (isset($transportasis) && method_exists($transportasis, 'hasPages') && $transportasis->hasPages()) {
        $showPagination = true;
    }
@endphp

@if($showPagination)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $transportasis->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $transportasis->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $transportasis->total() }}</span> 
        transportasi
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($transportasis->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $transportasis->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements --}}
        @php
            $current = $transportasis->currentPage();
            $last = $transportasis->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        @if($start > 1)
            <a href="{{ $transportasis->url(1) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif
        
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $transportasis->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $transportasis->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($transportasis->hasMorePages())
            <a href="{{ $transportasis->nextPageUrl() }}" 
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

function showDeleteConfirmation(id, jenis, lama) {
    currentDeleteId = id;
    currentDeleteNama = jenis;
    
    // Update modal content
    document.getElementById('delete-nama').textContent = jenis;
    document.getElementById('delete-lama').textContent = lama ? `Lama: ${lama}` : 'Tanpa Lama Perjalanan';
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/transportasi/${id}`;
    
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
function showDeleteSuccess(jenis) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    
    message.textContent = `Data transportasi "${jenis}" berhasil dihapus.`;
    
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