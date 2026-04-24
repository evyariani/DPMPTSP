<?php $__env->startSection('title', 'Detail Kwitansi Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION - TANPA TOMBOL -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Detail Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat detail kwitansi perjalanan dinas</p>
        </div>
        <!-- ❌ TOMBOL DI HEADER DIHAPUS SEMUA -->
    </div>

    <div class="p-6 space-y-6">
        <!-- INFORMASI DASAR KWITANSI -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">No. BKU</label>
                <p class="text-gray-800 font-medium"><?php echo e($kwitansi->no_bku ?? '-'); ?></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Tahun Anggaran</label>
                <p class="text-gray-800 font-medium"><?php echo e($kwitansi->tahun_anggaran ?? '-'); ?></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Kode Rekening</label>
                <p class="text-gray-800 font-mono"><?php echo e($kwitansi->kode_rekening ?? '-'); ?></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">No. BRPP</label>
                <p class="text-gray-800 font-medium"><?php echo e($kwitansi->no_brpp ?? '-'); ?></p>
            </div>
            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sub Kegiatan</label>
                <p class="text-gray-800"><?php echo e($kwitansi->sub_kegiatan ?? '-'); ?></p>
            </div>
        </div>

        <!-- UNTUK PEMBAYARAN -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Untuk Pembayaran</label>
            <p class="text-gray-800"><?php echo e($kwitansi->untuk_pembayaran ?? '-'); ?></p>
        </div>

        <!-- DATA PEJABAT -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PENGGUNA ANGGARAN -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Pengguna Anggaran</label>
                <p class="text-gray-800 font-medium"><?php echo e($kwitansi->pengguna_anggaran ?? '-'); ?></p>
                <p class="text-sm text-gray-500 mt-1">NIP: <?php echo e($kwitansi->nip_pengguna_anggaran ?? '-'); ?></p>
            </div>

            <!-- BENDAHARA PENGELUARAN -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Bendahara Pengeluaran</label>
                <p class="text-gray-800 font-medium"><?php echo e($kwitansi->bendahara_pengeluaran ?? '-'); ?></p>
                <p class="text-sm text-gray-500 mt-1">NIP: <?php echo e($kwitansi->nip_bendahara ?? '-'); ?></p>
            </div>
        </div>

        <!-- DATA PENERIMA -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Penerima Kwitansi</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <p class="text-sm text-gray-500">Nama Penerima</p>
                    <p class="text-gray-800 font-medium"><?php echo e($kwitansi->penerima ?? '-'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">NIP Penerima</p>
                    <p class="text-gray-800 font-mono"><?php echo e($kwitansi->nip_penerima ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <!-- RINGKASAN NOMINAL -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Ringkasan Nominal</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                    <div class="text-xs text-gray-500">Tanggal Kwitansi</div>
                    <div class="text-xl font-bold text-blue-600"><?php echo e(\Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('d F Y')); ?></div>
                    <div class="text-xs text-gray-400">tanggal diterbitkan</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                    <div class="text-xs text-gray-500">Nominal</div>
                    <div class="text-xl font-bold text-green-600">Rp <?php echo e(number_format($kwitansi->nominal, 0, ',', '.')); ?></div>
                    <div class="text-xs text-gray-400">total pembayaran</div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-semibold text-blue-800">TERBILANG</div>
                        <div class="text-xs text-blue-600">dalam huruf</div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-blue-800 italic"><?php echo e($kwitansi->terbilang ?? 'Nol Rupiah'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BUTTON ACTION - HANYA EDIT DAN KEMBALI -->
    <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
        <a href="<?php echo e(route('kwitansi.edit', $kwitansi->id)); ?>" 
           class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Kwitansi
        </a>
        <a href="<?php echo e(route('kwitansi.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
            Kembali ke Daftar
        </a>
    </div>
</div>

<style>
    @media print {
        .bg-white {
            box-shadow: none;
            border: none;
            padding: 0;
        }
        .flex.justify-end.gap-3 {
            display: none;
        }
        .bg-gray-50, .bg-gray-100 {
            background-color: white !important;
        }
        .border, .border-b, .border-t {
            border-color: #000 !important;
        }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-detail.blade.php ENDPATH**/ ?>