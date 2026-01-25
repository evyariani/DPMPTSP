{{-- resources/views/admin/user.blade.php --}}
@extends('layouts.admin') {{-- PERUBAHAN DI SINI: dari 'admin' ke 'layouts.admin' --}}

@section('title', 'User')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Data User</h2>
            <p class="text-gray-500">Kelola data pengguna sistem</p>
        </div>
        <a href="/user/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="/user" class="flex items-center space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari username..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <select name="level" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Level</option>
            <option value="admin" {{ request('level') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="pegawai" {{ request('level') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
            <option value="pemimpin" {{ request('level') == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
            <option value="admin_keuangan" {{ request('level') == 'admin_keuangan' ? 'selected' : '' }}>Admin Keuangan</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        @if(request()->has('search') || request()->has('level'))
            <a href="/user" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        @endif
    </form>
</div>

<!-- Notifikasi -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Tabel User -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-gray-400 tracking-wider">••••••••</span>
                            <button class="ml-3 text-blue-600 hover:text-blue-800 text-sm" 
                                    onclick="alert('Password telah dienkripsi untuk keamanan.\\nGunakan fitur reset password jika lupa.')"
                                    title="Password dienkripsi">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $levelColors = [
                                'admin' => 'bg-purple-100 text-purple-800',
                                'pegawai' => 'bg-blue-100 text-blue-800',
                                'pemimpin' => 'bg-green-100 text-green-800',
                                'admin_keuangan' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $color = $levelColors[$user->level] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $user->level)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="/user/{{ $user->id }}/edit" 
                               class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded hover:bg-blue-50 transition duration-150"
                               title="Edit User">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @if($user->id != session('user')['id'])
                            <form action="/user/{{ $user->id }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->username }}?')"
                                        class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                        title="Hapus User">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 px-3 py-1 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data user</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan user baru</p>
                            <a href="/user/create" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah User Pertama
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
@if($users->hasPages())
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $users->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $users->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $users->total() }}</span> 
        user
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($users->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $users->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements --}}
        @php
            $current = $users->currentPage();
            $last = $users->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        @if($start > 1)
            <a href="{{ $users->url(1) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif
        
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $users->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $users->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" 
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
// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});
</script>
@endsection