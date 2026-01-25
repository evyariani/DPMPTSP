{{-- resources/views/admin/pegawai.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pegawai')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Data Pegawai</h2>
            <p class="text-gray-500">Kelola data pegawai sistem</p>
        </div>
        <a href="/pegawai/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Pegawai
        </a>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="/pegawai" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari nama, NIP, atau TK Jalan..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-wrap gap-2">
            <select name="pangkat" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Pangkat</option>
                @foreach($pangkatList ?? [] as $pangkat)
                    <option value="{{ $pangkat }}" {{ request('pangkat') == $pangkat ? 'selected' : '' }}>
                        {{ $pangkat }}
                    </option>
                @endforeach
            </select>
            <select name="golongan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Golongan</option>
                @foreach($golonganList ?? [] as $golongan)
                    <option value="{{ $golongan }}" {{ request('golongan') == $golongan ? 'selected' : '' }}>
                        {{ $golongan }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            @if(request()->has('search') || request()->has('pangkat') || request()->has('gol'))
                <a href="/pegawai" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            @endif
        </div>
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

<!-- Tabel Pegawai -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pangkat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tunjangan Jalan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    // Pastikan $pegawais selalu ada dan bisa di-loop
                    $pegawais = $pegawais ?? collect([]);
                    $isPaginated = method_exists($pegawais, 'currentPage');
                @endphp
                
                @forelse($pegawais as $index => $pegawai)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isPaginated)
                            {{ ($pegawais->currentPage() - 1) * $pegawais->perPage() + $index + 1 }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold">{{ strtoupper(substr($pegawai->nama ?? '', 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pegawai->nama ?? '-' }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $pegawai->id_pegawai ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $pegawai->nip ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $pegawai->pangkat ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $pegawai->gol ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $pegawai->jabatan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if(!empty($pegawai->tk_jalan))
                            @php
                                // Cek apakah tk_jalan berupa angka (bisa diformat sebagai uang)
                                $isNumeric = is_numeric($pegawai->tk_jalan);
                            @endphp
                            
                            @if($isNumeric && $pegawai->tk_jalan > 0)
                                {{-- Jika angka dan lebih dari 0, format sebagai uang --}}
                                <span class="text-green-600 font-medium">
                                    Rp {{ number_format($pegawai->tk_jalan, 0, ',', '.') }}
                                </span>
                            @elseif($isNumeric && $pegawai->tk_jalan == 0)
                                {{-- Jika angka 0 --}}
                                <span class="text-gray-500">
                                    Rp 0
                                </span>
                            @elseif($isNumeric)
                                {{-- Jika angka negatif --}}
                                <span class="text-red-500">
                                    Rp {{ number_format($pegawai->tk_jalan, 0, ',', '.') }}
                                </span>
                            @else
                                {{-- Jika bukan angka (huruf seperti A, B, C, TK I, dll) --}}
                                <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    {{ strtoupper($pegawai->tk_jalan) }}
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if(isset($pegawai->id_pegawai))
                            <a href="/pegawai/{{ $pegawai->id_pegawai }}/edit" 
                               class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded hover:bg-blue-50 transition duration-150"
                               title="Edit Pegawai">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="/pegawai/{{ $pegawai->id_pegawai }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai {{ $pegawai->nama }}?')"
                                        class="text-red-600 hover:text-red-900 px-3 py-1 rounded hover:bg-red-50 transition duration-150"
                                        title="Hapus Pegawai">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 px-3 py-1">Edit</span>
                            <span class="text-gray-400 px-3 py-1">Hapus</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-user-tie text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data pegawai</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan pegawai baru</p>
                            <a href="/pegawai/create" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Pegawai Pertama
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
    // Cek apakah $pegawais ada dan memiliki method hasPages
    $showPagination = false;
    if (isset($pegawais) && method_exists($pegawais, 'hasPages') && $pegawais->hasPages()) {
        $showPagination = true;
    }
@endphp

@if($showPagination)
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium">{{ $pegawais->firstItem() ?: 0 }}</span> 
        sampai 
        <span class="font-medium">{{ $pegawais->lastItem() ?: 0 }}</span> 
        dari 
        <span class="font-medium">{{ $pegawais->total() }}</span> 
        pegawai
    </div>
    
    <div class="flex items-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($pegawais->onFirstPage())
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $pegawais->previousPageUrl() }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif
        
        {{-- Pagination Elements --}}
        @php
            $current = $pegawais->currentPage();
            $last = $pegawais->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        @endphp
        
        @if($start > 1)
            <a href="{{ $pegawais->url(1) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            @if($start > 2)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
        @endif
        
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $pegawais->url($page) }}" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $page }}</a>
            @endif
        @endfor
        
        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-3 py-1.5 text-gray-500">...</span>
            @endif
            <a href="{{ $pegawais->url($last) }}" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">{{ $last }}</a>
        @endif
        
        {{-- Next Page Link --}}
        @if ($pegawais->hasMorePages())
            <a href="{{ $pegawais->nextPageUrl() }}" 
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

// Format NIP input (jika ada)
document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endsection