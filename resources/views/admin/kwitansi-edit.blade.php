@extends('layouts.admin')

@section('title', 'Edit Kwitansi Perjalanan Dinas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Edit Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Edit data kwitansi perjalanan dinas</p>
        </div>
        <a href="{{ route('kwitansi.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('kwitansi.update', $kwitansi->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <!-- SPD ID (hidden, karena sudah diketahui dari kwitansi yang diedit) -->
        <input type="hidden" name="spd_id" value="{{ $kwitansi->spd_id }}">

        <div class="space-y-6">
            <!-- INFO SPD (READONLY) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Informasi SPD</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Data SPD (Tidak Bisa Diubah)</div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nomor SPD</label>
                                <input type="text" value="{{ $kwitansi->spd->nomor_surat ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Maksud Perjalanan</label>
                                <input type="text" value="{{ $kwitansi->spd->maksud_perjadin ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA DARI SPD & RINCIAN BIDANG -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data dari SPD & Rincian Bidang</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Informasi SPD dan Rincian Biaya</div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tahun Anggaran</label>
                                <input type="text" value="{{ $kwitansi->tahun_anggaran ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kode Rekening</label>
                                <input type="text" value="{{ $kwitansi->kode_rekening ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Sub Kegiatan</label>
                                <textarea rows="2" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>{{ $kwitansi->sub_kegiatan ?? '-' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Untuk Pembayaran</label>
                                <textarea name="untuk_pembayaran" id="untuk_pembayaran" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('untuk_pembayaran', $kwitansi->untuk_pembayaran) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA PEGAWAI & BENDAHARA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengguna Anggaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengguna Anggaran</label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-700">Data Pengguna Anggaran</div>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama</label>
                                <input type="text" value="{{ $kwitansi->pengguna_anggaran ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                <input type="text" value="{{ $kwitansi->nip_pengguna_anggaran ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bendahara Pengeluaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara Pengeluaran</label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-700">Data Bendahara Pengeluaran</div>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama</label>
                                <input type="text" value="{{ $kwitansi->bendahara_pengeluaran ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                <input type="text" value="{{ $kwitansi->nip_bendahara ?? '-' }}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA PENERIMA -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Penerima</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Data Penerima Kwitansi (Bisa Diedit)</div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="penerima" id="penerima" value="{{ old('penerima', $kwitansi->penerima) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="nip_penerima" id="nip_penerima" value="{{ old('nip_penerima', $kwitansi->nip_penerima) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA KWITANSI -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Kwitansi</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Informasi Kwitansi (Bisa Diedit)</div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nominal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="nominal" id="nominal" value="{{ old('nominal', $kwitansi->nominal) }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">No. BKU <span class="text-red-500">*</span></label>
                                <input type="text" name="no_bku" id="no_bku" value="{{ old('no_bku', $kwitansi->no_bku) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">No. BRPP</label>
                                <input type="text" name="no_brpp" id="no_brpp" value="{{ old('no_brpp', $kwitansi->no_brpp) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Kwitansi <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_kwitansi" id="tanggal_kwitansi" value="{{ old('tanggal_kwitansi', $kwitansi->tanggal_kwitansi ? \Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Terbilang <span class="text-red-500">*</span></label>
                                <textarea name="terbilang" id="terbilang" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('terbilang', $kwitansi->terbilang) }}</textarea>
                                <p class="text-xs text-gray-400 mt-1">Terbilang akan otomatis terisi saat nominal berubah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('kwitansi.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Update Kwitansi
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function generateTerbilang(nominal) {
        if (!nominal || nominal == 0) {
            $('#terbilang').val('Nol Rupiah');
            return;
        }
        $.ajax({
            url: '/kwitansi/terbilang/' + nominal,
            type: 'GET',
            success: function(res) {
                if (res.success) $('#terbilang').val(res.terbilang);
            },
            error: function() {
                // Fallback terbilang sederhana jika API error
                $('#terbilang').val('Nominal ' + nominal + ' Rupiah');
            }
        });
    }
    
    $('#nominal').on('change keyup', function() {
        var nominal = $(this).val();
        if (nominal && nominal > 0) {
            generateTerbilang(nominal);
        } else {
            $('#terbilang').val('Nol Rupiah');
        }
    });
});
</script>

@endsection