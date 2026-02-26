{{-- resources/views/admin/user.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('subtitle', 'Kelola data pengguna sistem')

@section('content')
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

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm flex items-start gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-lg shadow-sm flex items-start gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-rose-600 hover:text-rose-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

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
                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
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
                                <form action="/user/{{ $user->id }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->username }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors duration-200 text-xs"
                                            title="Hapus User">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
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
                    {{-- Previous Page --}}
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
                    
                    {{-- Pagination Numbers --}}
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
                    
                    {{-- Next Page --}}
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
// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('[x-data="show"]');
        alerts.forEach(function(alert) {
            if (alert.__x) {
                alert.__x.$data.show = false;
            }
        });
    }, 5000);
});

// Konfirmasi hapus yang lebih baik
function confirmDelete(username, form) {
    if (confirm(`Apakah Anda yakin ingin menghapus user "${username}"?`)) {
        form.submit();
    }
}
</script>
@endsection