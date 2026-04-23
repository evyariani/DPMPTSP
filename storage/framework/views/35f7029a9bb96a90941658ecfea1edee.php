<?php $__env->startSection('title', 'Detail Rincian Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Detail Rincian Biaya</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat detail rincian biaya perjalanan dinas</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('rincian.edit', $rincian->id)); ?>" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="<?php echo e(route('rincian.index')); ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- INFORMASI SPPD -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Nomor SPPD</label>
                <p class="text-gray-800 font-medium"><?php echo e($rincian->nomor); ?></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Tanggal</label>
                <p class="text-gray-800 font-medium"><?php echo e(\Carbon\Carbon::parse($rincian->tanggal)->format('d F Y')); ?></p>
            </div>
            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Tujuan Perjalanan</label>
                <p class="text-gray-800 font-medium"><?php echo e($rincian->tujuan); ?></p>
            </div>
        </div>

        <!-- DATA PEGAWAI -->
        <div>
            <h3 class="font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Data Pegawai Yang Berangkat</h3>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 border-b">No</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 border-b">Nama Pegawai</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 border-b">NIP</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 border-b">Nominal / Hari</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 border-b">Hari</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $subtotalHarian = 0; ?>
                        <?php $__currentLoopData = $rincian->pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-500 border-b"><?php echo e($index + 1); ?></td>
                            <td class="px-4 py-2 text-sm text-gray-800 border-b"><?php echo e($pegawai['nama']); ?></td>
                            <td class="px-4 py-2 text-sm text-gray-600 border-b"><?php echo e($pegawai['nip'] ?? '-'); ?></td>
                            <td class="px-4 py-2 text-sm text-right text-gray-600 border-b">Rp <?php echo e(number_format($pegawai['nominal'])); ?></td>
                            <td class="px-4 py-2 text-sm text-right text-gray-600 border-b"><?php echo e($pegawai['hari']); ?> hari</td>
                            <td class="px-4 py-2 text-sm text-right font-semibold text-gray-800 border-b">
                                Rp <?php echo e(number_format($pegawai['nominal'] * $pegawai['hari'])); ?>

                                <?php $subtotalHarian += $pegawai['nominal'] * $pegawai['hari']; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-right font-semibold text-gray-800">Subtotal Uang Harian</td>
                            <td class="px-4 py-2 text-right font-bold text-blue-600">Rp <?php echo e(number_format($subtotalHarian)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- BENDAHARA & KEPALA DINAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Bendahara Pengeluaran</label>
                <p class="text-gray-800 font-medium"><?php echo e($rincian->bendahara['nama'] ?? '-'); ?></p>
                <p class="text-sm text-gray-500 mt-1">NIP: <?php echo e($rincian->bendahara['nip'] ?? '-'); ?></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Kepala Dinas</label>
                <p class="text-gray-800 font-medium"><?php echo e($rincian->kepala_dinas['nama'] ?? 'BUDI ANDRIAN SUTANTO, S. Sos., M.M'); ?></p>
                <p class="text-sm text-gray-500 mt-1">NIP: <?php echo e($rincian->kepala_dinas['nip'] ?? '19760218 200701 1 006'); ?></p>
            </div>
        </div>

        <!-- RINGKASAN PERHITUNGAN -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Ringkasan Perhitungan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                    <div class="text-xs text-gray-500">Jumlah Pegawai</div>
                    <div class="text-xl font-bold text-blue-600"><?php echo e(count($rincian->pegawai)); ?></div>
                    <div class="text-xs text-gray-400">orang</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                    <div class="text-xs text-gray-500">Total Uang Harian</div>
                    <div class="text-xl font-bold text-green-600">Rp <?php echo e(number_format($subtotalHarian)); ?></div>
                    <div class="text-xs text-gray-400">(pegawai × nominal × hari)</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200">
                    <div class="text-xs text-gray-500">Uang Transport</div>
                    <div class="text-xl font-bold text-orange-600">Rp <?php echo e(number_format($rincian->transport)); ?></div>
                    <div class="text-xs text-gray-400">biaya transport</div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-sm font-semibold text-blue-800">TOTAL KESELURUHAN</div>
                        <div class="text-xs text-blue-600">(Uang Harian + Transport)</div>
                    </div>
                    <div class="text-2xl font-bold text-blue-800">Rp <?php echo e(number_format($rincian->total)); ?></div>
                </div>
            </div>
        </div>

        <!-- TERBILANG -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Terbilang</label>
            <p class="text-gray-800 italic"><?php echo e($rincian->terbilang); ?></p>
        </div>
    </div>

    <!-- BUTTON ACTION -->
<div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
    <a href="<?php echo e(route('rincian.cetak', $rincian->id)); ?>" 
   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
    Cetak PDF
</a>
    <a href="<?php echo e(route('rincian.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
        Kembali ke Daftar
    </a>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/rincian-detail.blade.php ENDPATH**/ ?>