@extends('layouts.admin')

@section('title', 'Uang Harian')

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

.badge-total {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.badge-provinsi {
    background-color: #4299e1;
    color: white;
}

.badge-kabupaten {
    background-color: #48bb78;
    color: white;
}

.badge-kecamatan {
    background-color: #ed8936;
    color: white;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 0.25rem;
    display: block;
}
</style>

<!-- HEADER -->
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Data Uang Harian</h2>
            <p class="text-gray-500">Kelola data uang harian dan transport berdasarkan tempat tujuan</p>
        </div>
        <a href="/uang-harian/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Uang Harian
        </a>
    </div>
</div>

<!-- NOTIFIKASI -->
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
                    <p class="text-gray-600 mb-3">Anda akan menghapus data uang harian:</p>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-tempat"></p>
                        <p class="text-gray-600 text-sm mt-1">Uang Harian: <span id="delete-harian" class="font-medium"></span></p>
                        <p class="text-gray-600 text-sm">Uang Transport: <span id="delete-transport" class="font-medium"></span></p>
                        <p class="text-gray-600 text-sm">Total: <span id="delete-total" class="font-medium text-blue-600"></span></p>
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
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER - SEARCH & TINGKAT DAERAH (SEPERTI PROGRAM) -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="/uang-harian" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari tempat tujuan, uang harian, atau uang transport..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-wrap gap-2">
            <select name="tingkat" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Tingkat</option>
                <option value="provinsi" {{ request('tingkat') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                <option value="kabupaten" {{ request('tingkat') == 'kabupaten' ? 'selected' : '' }}>Kabupaten/Kota</option>
                <option value="kecamatan" {{ request('tingkat') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'tingkat']))
                <a href="/uang-harian" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
    </form>
    
    <!-- ACTIVE FILTERS -->
    @if(request()->hasAny(['search', 'tingkat']))
    <div class="mt-4 pt-3 border-t border-gray-200">
        <div class="flex items-center flex-wrap gap-2">
            <span class="text-sm text-gray-600">Filter aktif:</span>
            
            @if(request('search'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-search mr-1"></i> {{ request('search') }}
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            
            @if(request('tingkat'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                <i class="fas fa-tag mr-1"></i> {{ ucfirst(request('tingkat')) }}
                <a href="{{ request()->fullUrlWithQuery(['tingkat' => null]) }}" class="ml-2 text-purple-600 hover:text-purple-800">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- INFO URUTAN DATA -->
{{-- <div class="mb-3 flex justify-between items-center">
    <div class="text-sm text-gray-600">
        <i class="fas fa-info-circle mr-1"></i> 
        Menampilkan <span class="font-semibold">Kecamatan</span> terlebih dahulu, diikuti Kabupaten/Kota, lalu Provinsi
    </div>
    <div class="flex items-center space-x-2">
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium badge-kecamatan">
            <i class="fas fa-map-marker-alt mr-1"></i> Kecamatan
        </span>
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium badge-kabupaten">
            <i class="fas fa-city mr-1"></i> Kabupaten/Kota
        </span>
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium badge-provinsi">
            <i class="fas fa-flag mr-1"></i> Provinsi
        </span>
    </div>
</div> --}}

<!-- TABEL UANG HARIAN -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uang Harian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uang Transport</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($uangHarians as $index => $uang)
                @php
                    $total = $uang->uang_harian + $uang->uang_transport;
                    $tingkat = $uang->daerah->tingkat ?? 'unknown';
                    $badgeClass = $tingkat == 'provinsi' ? 'badge-provinsi' : ($tingkat == 'kabupaten' ? 'badge-kabupaten' : 'badge-kecamatan');
                    $icon = $tingkat == 'provinsi' ? 'fa-flag' : ($tingkat == 'kabupaten' ? 'fa-city' : 'fa-map-marker-alt');
                @endphp
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ($uangHarians->currentPage() - 1) * $uangHarians->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas {{ $icon }} text-indigo-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $uang->tempat_tujuan ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ ucfirst($tingkat) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-green-600 font-medium">
                            Rp {{ number_format($uang->uang_harian, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-blue-600 font-medium">
                            Rp {{ number_format($uang->uang_transport, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-medium badge-total">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="/uang-harian/{{ $uang->id_uang_harian }}/edit" 
                               class="text-green-600 hover:text-green-900 px-3 py-1 rounded hover:bg-green-50 transition duration-150"
                               title="Edit Uang Harian">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            
                            <button type="button" 
                                    onclick="showDeleteConfirmation(
                                        {{ $uang->id_uang_harian }}, 
                                        '{{ addslashes($uang->tempat_tujuan) }}', 
                                        'Rp {{ number_format($uang->uang_harian ?? 0, 0, ',', '.') }}',
                                        'Rp {{ number_format($uang->uang_transport ?? 0, 0, ',', '.') }}',
                                        'Rp {{ number_format($total, 0, ',', '.') }}'
                                    )"
                                    class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                    title="Hapus Uang Harian">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-money-bill-wave text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data uang harian</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan uang harian baru</p>
                            <a href="/uang-harian/create" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Uang Harian Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
@if($uangHarians->hasPages())
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $uangHarians->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $uangHarians->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $uangHarians->total() }}</span> 
        uang harian
    </div>
    
    <div class="flex items-center space-x-1">
        @if ($uangHarians->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $uangHarians->previousPageUrl() }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        @foreach ($uangHarians->getUrlRange(max($uangHarians->currentPage() - 2, 1), min($uangHarians->currentPage() + 2, $uangHarians->lastPage())) as $page => $url)
            @if ($page == $uangHarians->currentPage())
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endforeach
        
        @if ($uangHarians->hasMorePages())
            <a href="{{ $uangHarians->nextPageUrl() }}" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
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

<!-- FOOTER INFO -->
<div class="mt-4 text-xs text-gray-400 text-center">
    <i class="fas fa-database mr-1"></i> Total {{ $totalData ?? 0 }} data • 
    <i class="fas fa-sort-amount-down ml-2 mr-1"></i> Kecamatan → Kabupaten → Provinsi • 
    <i class="fas fa-clock ml-2 mr-1"></i> Terbaru di atas
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
let currentDeleteNama = null;

function showDeleteConfirmation(id, tempat, uangHarian, uangTransport, total) {
    currentDeleteId = id;
    currentDeleteNama = tempat;
    
    document.getElementById('delete-tempat').textContent = tempat;
    document.getElementById('delete-harian').textContent = uangHarian;
    document.getElementById('delete-transport').textContent = uangTransport;
    document.getElementById('delete-total').textContent = total;
    
    const form = document.getElementById('delete-form');
    form.action = `/uang-harian/${id}`;
    
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
        currentDeleteNama = null;
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
            showDeleteSuccess(currentDeleteNama);
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

function showDeleteSuccess(tempat) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    
    message.textContent = `Data uang harian "${tempat}" berhasil dihapus.`;
    
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

document.getElementById('delete-confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});
</script>
@endsection