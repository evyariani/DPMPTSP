@extends('layouts.admin')

@section('title', 'Detail Kwitansi Perjalanan Dinas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Detail Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Detail lengkap kwitansi perjalanan dinas</p>
        </div>
        <div class="flex gap-2">
            <!-- Tombol Cetak PDF -->
            <a href="{{ route('kwitansi.cetak', $kwitansi->id) }}" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak PDF
            </a>
            <!-- Tombol Kembali -->
            <a href="{{ route('kwitansi.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- ALERT SUCCESS/ERROR -->
    @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-6 mt-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- FORM DETAIL (READ-ONLY) -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kiri -->
            <div class="space-y-4">
                <!-- TAHUN ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->tahun_anggaran }}
                    </div>
                </div>

                <!-- KODE REKENING -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Rekening</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 font-mono">
                        {{ $kwitansi->kode_rekening }}
                    </div>
                </div>

                <!-- SUB KEGIATAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Kegiatan</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->sub_kegiatan }}
                    </div>
                </div>

                <!-- NO BUKU KAS UMUM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Buku Kas Umum</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->no_bku }}
                    </div>
                </div>

                <!-- NO BRPP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. BRPP</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->no_brpp ?? '-' }}
                    </div>
                </div>

                <!-- NOMINAL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 font-semibold">
                        Rp {{ number_format($kwitansi->nominal, 0, ',', '.') }}
                    </div>
                </div>

                <!-- TERBILANG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->terbilang }}
                    </div>
                </div>

                <!-- TANGGAL KWITANSI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kwitansi</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ \Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <!-- Kanan -->
            <div class="space-y-4">
                <!-- UNTUK PEMBAYARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Untuk Pembayaran</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 min-h-[100px]">
                        {{ $kwitansi->untuk_pembayaran }}
                    </div>
                </div>

                <!-- PENGGUNA ANGGARAN (KEPALA DINAS) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengguna Anggaran (Kepala Dinas)</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->pengguna_anggaran }}
                    </div>
                </div>

                <!-- NIP PENGGUNA ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pengguna Anggaran</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 font-mono">
                        {{ $kwitansi->nip_pengguna_anggaran }}
                    </div>
                </div>

                <!-- BENDAHARA PENGELUARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara Pengeluaran</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->bendahara_pengeluaran }}
                    </div>
                </div>

                <!-- NIP BENDAHARA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Bendahara</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 font-mono">
                        {{ $kwitansi->nip_bendahara }}
                    </div>
                </div>

                <!-- PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerima</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800">
                        {{ $kwitansi->penerima }}
                    </div>
                </div>

                <!-- NIP PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Penerima</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-800 font-mono">
                        {{ $kwitansi->nip_penerima }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection