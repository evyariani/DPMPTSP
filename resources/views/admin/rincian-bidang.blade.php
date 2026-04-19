@extends('layouts.admin')

@section('title', 'Daftar Rincian Biaya Perjalanan Dinas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Daftar Rincian Biaya</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola semua rincian biaya perjalanan dinas</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rincian.sync-all') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200"
               onclick="return confirm('Sync semua data dari SPD? Ini akan memperbarui semua rincian biaya berdasarkan data SPD terbaru.')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Sync All
            </a>
        </div>
    </div>

    <!-- SEARCH & FILTER SECTION -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('rincian.index') }}" class="flex flex-wrap gap-3 items-center justify-between">
            <div class="relative flex-1 max-w-md">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nomor SPPD, tujuan, atau pegawai..." 
                       class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Cari
                </button>
                <a href="{{ route('rincian.index') }}" class="px-3 py-2 text-gray-600 hover:text-gray-800 text-sm rounded-lg hover:bg-gray-100">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if(session('error'))
        <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- TABLE SECTION -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor SPPD</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Berangkat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pegawai</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Uang Harian</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Transport</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($rincian as $key => $item)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $rincian->firstItem() + $key }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md text-xs font-mono">
                            {{ $item->nomor_sppd ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $item->tanggal_berangkat ? $item->tanggal_berangkat->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <span class="bg-gray-100 px-2 py-1 rounded-md text-xs">
                            {{ $item->lama_perjadin ?? 0 }} hari
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <div class="max-w-xs">
                            <div class="font-medium text-gray-800">{{ $item->tempat_tujuan ?? '-' }}</div>
                            @if($item->spd && $item->spd->tempatTujuan)
                                <div class="text-xs text-gray-400">{{ $item->spd->tempatTujuan->nama ?? '' }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <div class="flex -space-x-2">
                            @php
                                $pegawaiList = is_array($item->pegawai) ? $item->pegawai : [];
                                $displayPegawai = array_slice($pegawaiList, 0, 3);
                            @endphp
                            @foreach($displayPegawai as $peg)
                                <div class="w-7 h-7 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-xs font-medium text-blue-600" 
                                     title="{{ $peg['nama'] ?? '' }} - {{ $peg['nip'] ?? '' }}">
                                    {{ substr($peg['nama'] ?? '?', 0, 1) }}
                                </div>
                            @endforeach
                            @if(count($pegawaiList) > 3)
                                <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs font-medium text-gray-600" 
                                     title="{{ count($pegawaiList) - 3 }} pegawai lainnya">
                                    +{{ count($pegawaiList) - 3 }}
                                </div>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ count($pegawaiList) }} orang
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-right">
                        <div class="font-medium text-gray-800">
                            Rp {{ number_format($item->uang_harian ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            per orang/hari
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-right">
                        <div class="font-medium text-gray-800">
                            Rp {{ number_format($item->transport ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            @if(($item->uang_transport ?? 0) > 0)
                                + transport Rp {{ number_format($item->uang_transport, 0, ',', '.') }}/org
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-right font-semibold">
                        <div class="text-blue-600">
                            Rp {{ number_format($item->total_keseluruhan ?? $item->total, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $item->lama_perjadin ?? 0 }} hari × {{ count($pegawaiList) }} orang
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            {{-- <a href="{{ route('rincian.show', $item->id) }}" 
                               class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition" 
                               title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a> --}}
                            <a href="{{ route('rincian.cetak', $item->id) }}" 
                               class="p-1.5 text-green-500 hover:bg-green-50 rounded-lg transition" 
                               title="Cetak PDF"
                               target="_blank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('rincian.edit', $item->id) }}" 
                               class="p-1.5 text-yellow-500 hover:bg-yellow-50 rounded-lg transition" 
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('rincian.destroy', $item->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus data ini? Rincian biaya akan dihapus permanen.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Belum ada data rincian biaya</p>
                        <div class="mt-3 flex gap-2 justify-center">
                            <a href="{{ route('rincian.create') }}" class="text-blue-500 text-sm hover:underline">Tambah rincian manual</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('rincian.sync-all') }}" class="text-green-500 text-sm hover:underline">Sync dari SPD</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION SECTION -->
    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-medium">{{ $rincian->firstItem() ?? 0 }}</span> 
                sampai <span class="font-medium">{{ $rincian->lastItem() ?? 0 }}</span> 
                dari <span class="font-medium">{{ $rincian->total() }}</span> data
            </div>
            <div>
                {{ $rincian->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom pagination style */
    nav div:first-child {
        display: flex;
        justify-content: center;
    }
    
    nav div:first-child > div {
        display: flex;
        gap: 0.25rem;
    }
    
    nav span[aria-current="page"] span {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    
    nav a, nav span[aria-disabled="true"] span {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        background-color: white;
        color: #374151;
    }
    
    nav a:hover {
        background-color: #f9fafb;
    }
    
    nav span[aria-disabled="true"] span {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@endsection