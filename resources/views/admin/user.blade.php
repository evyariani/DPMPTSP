{{-- resources/views/admin/user.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('subtitle', 'Kelola data pengguna sistem')

@section('content')
<style>
/* Animasi untuk modal dan notifikasi */
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

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

.animate-slide-in-bottom {
    animation: slideInFromBottom 0.3s ease-out forwards;
}

.animate-slide-out-bottom {
    animation: slideOutToBottom 0.3s ease-out forwards;
}

.progress-bar {
    animation: progressBar 5s linear forwards;
}

/* Modal backdrop */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 40;
}
</style>

<div class="space-y-6">
    {{-- Header dengan Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-users text-indigo-600"></i>
                Data User
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Kelola data pengguna yang memiliki akses ke sistem
            </p>
        </div>
        <a href="/user/create" 
           class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Tambah User Baru
        </a>
    </div>

    {{-- Notifikasi Toast (Posisi Bawah Kanan) --}}
    @if(session('success'))
    <div id="success-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Berhasil!</p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="hideNotification('success')" class="text-emerald-600 hover:text-emerald-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-emerald-200 rounded-full h-1">
                <div id="success-progress" class="bg-emerald-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="error-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-rose-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Terjadi Kesalahan!</p>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="hideNotification('error')" class="text-rose-600 hover:text-rose-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-rose-200 rounded-full h-1">
                <div id="error-progress" class="bg-rose-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-auto animate-fade-in">
                <div class="p-6">
                    {{-- Icon Warning --}}
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    
                    {{-- Title --}}
                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">
                        Konfirmasi Hapus User
                    </h3>
                    
                    {{-- Message --}}
                    <div class="mb-6">
                        <p class="text-gray-600 text-center mb-4">
                            Anda yakin ingin menghapus user berikut?
                        </p>
                        
                        {{-- User Info Card --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-center mb-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-sm">
                                    <span id="modal-user-initial" class="text-white font-semibold text-lg"></span>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800 text-lg text-center" id="modal-username"></p>
                            <p class="text-gray-600 text-sm text-center mt-1" id="modal-level"></p>
                        </div>
                        
                        {{-- Warning Message --}}
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
                    
                    {{-- Action Buttons --}}
                    <div class="flex justify-center gap-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[100px]">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        
                        <form id="delete-form" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[100px]">
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
            <form method="GET" action="/user" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               placeholder="Cari username..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                    </div>
                </div>
                <div class="sm:w-48">
                    <select name="level" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm">
                        <option value="">Semua Level</option>
                        <option value="admin" {{ request('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pegawai" {{ request('level') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="pemimpin" {{ request('level') == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
                        <option value="admin_keuangan" {{ request('level') == 'admin_keuangan' ? 'selected' : '' }}>Admin Keuangan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center shadow-sm hover:shadow">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    @if(request()->has('search') || request()->has('level'))
                        <a href="/user" 
                           class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-gray-400 tracking-wider font-mono">••••••••</span>
                                <button class="ml-2 text-gray-400 hover:text-indigo-600 transition-colors" 
                                        onclick="alert('Password telah dienkripsi untuk keamanan.\nGunakan fitur reset password jika lupa.')"
                                        title="Password dienkripsi">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $levelColors = [
                                    'admin' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                    'pegawai' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    'pemimpin' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                    'admin_keuangan' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                ];
                                $color = $levelColors[$user->level] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-xs font-medium {{ $color }} inline-flex items-center">
                                <i class="fas fa-circle text-[6px] mr-1.5 opacity-70"></i>
                                {{ ucfirst(str_replace('_', ' ', $user->level)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt text-gray-400 mr-2 text-xs"></i>
                                {{ $user->created_at?->format('d M Y H:i') ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="/user/{{ $user->id }}/edit" 
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors duration-200 text-xs"
                                   title="Edit User">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                @if($user->id != session('user')['id'])
                                <button type="button" 
                                        onclick="showDeleteModal({{ $user->id }}, '{{ addslashes($user->username) }}', '{{ $user->level }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors duration-200 text-xs"
                                        title="Hapus User">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-xs cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-800 mb-2">Belum Ada Data User</p>
                                <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan user baru ke sistem</p>
                                <a href="/user/create" 
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer dengan Info Pagination --}}
        @if($users->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan 
                    <span class="font-medium text-gray-900">{{ $users->firstItem() ?: 0 }}</span> 
                    sampai 
                    <span class="font-medium text-gray-900">{{ $users->lastItem() ?: 0 }}</span> 
                    dari 
                    <span class="font-medium text-gray-900">{{ $users->total() }}</span> 
                    data user
                </div>
                
                {{-- Pagination Links --}}
                <div class="flex items-center gap-1">
                    @if ($users->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-200 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif
                    
                    @php
                        $current = $users->currentPage();
                        $last = $users->lastPage();
                        $start = max($current - 2, 1);
                        $end = min($current + 2, $last);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $users->url(1) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">1</a>
                        @if($start > 2)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif
                    
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $users->url($page) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $page }}</a>
                        @endif
                    @endfor
                    
                    @if($end < $last)
                        @if($end < $last - 1)
                            <span class="w-9 h-9 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $users->url($last) }}" 
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-150 text-sm">{{ $last }}</a>
                    @endif
                    
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" 
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
// ========== VARIABLES ==========
let currentDeleteId = null;
let currentDeleteUsername = null;

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
    // Auto hide success notification
    const successNotif = document.getElementById('success-notification');
    if (successNotif) {
        setTimeout(() => hideNotification('success'), 5000);
    }
    
    // Auto hide error notification
    const errorNotif = document.getElementById('error-notification');
    if (errorNotif) {
        setTimeout(() => hideNotification('error'), 5000);
    }
});

// ========== DELETE MODAL FUNCTIONS ==========
function showDeleteModal(id, username, level) {
    currentDeleteId = id;
    currentDeleteUsername = username;
    
    // Update modal content
    document.getElementById('modal-username').textContent = username;
    document.getElementById('modal-user-initial').textContent = username.charAt(0).toUpperCase();
    
    // Format level display
    let levelText = '';
    switch(level) {
        case 'admin':
            levelText = 'Administrator';
            break;
        case 'pemimpin':
            levelText = 'Pemimpin';
            break;
        case 'admin_keuangan':
            levelText = 'Admin Keuangan';
            break;
        case 'pegawai':
            levelText = 'Pegawai';
            break;
        default:
            levelText = level;
    }
    document.getElementById('modal-level').textContent = `Level: ${levelText}`;
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/user/${id}`;
    
    // Show modal
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    
    // Add animation to modal content
    const modalContent = modal.querySelector('.bg-white');
    modalContent.classList.remove('animate-fade-out');
    modalContent.classList.add('animate-fade-in');
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    const modalContent = modal.querySelector('.bg-white');
    
    // Add fade out animation
    modalContent.classList.remove('animate-fade-in');
    modalContent.classList.add('animate-fade-out');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('animate-fade-out');
        currentDeleteId = null;
        currentDeleteUsername = null;
    }, 300);
}

// Close modal when clicking backdrop
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('delete-modal');
        if (!modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});

// ========== FORM SUBMIT WITH AJAX ==========
document.getElementById('delete-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
    submitBtn.disabled = true;
    
    // Send AJAX request
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
            // Close modal
            closeDeleteModal();
            
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error notification
        showErrorNotification(error.message);
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Close modal
        closeDeleteModal();
    });
});

// Show error notification
function showErrorNotification(message) {
    // Create temporary error notification
    const errorDiv = document.createElement('div');
    errorDiv.id = 'temp-error-notification';
    errorDiv.className = 'fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom';
    errorDiv.innerHTML = `
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-lg shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-rose-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-medium">Terjadi Kesalahan!</p>
                    <p class="text-sm mt-1">${message}</p>
                </div>
                <button onclick="this.closest('#temp-error-notification').remove()" class="text-rose-600 hover:text-rose-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 w-full bg-rose-200 rounded-full h-1">
                <div class="bg-rose-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
            </div>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const notif = document.getElementById('temp-error-notification');
        if (notif) {
            notif.classList.remove('animate-slide-in-bottom');
            notif.classList.add('animate-slide-out-bottom');
            setTimeout(() => notif.remove(), 300);
        }
    }, 5000);
}
</script>
@endsection