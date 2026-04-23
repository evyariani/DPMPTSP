<?php $__env->startSection('title', 'Edit Rincian Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Animasi untuk notifikasi */
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

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

/* Custom badge */
.info-badge {
    background-color: #e0e7ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Rincian Biaya</h2>
            <p class="text-gray-500">Edit rincian biaya perjalanan dinas (uang harian saja, transport dikwitansi terpisah)</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('rincian.cetak', $rincian->id)); ?>" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-print mr-2"></i> Cetak PDF
            </a>
            <a href="<?php echo e(route('rincian.index')); ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form method="POST" action="<?php echo e(route('rincian.update', $rincian->id)); ?>" id="formRincian">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <!-- Hidden input untuk spd_id -->
    <input type="hidden" name="spd_id" value="<?php echo e($rincian->spd_id); ?>">

    <!-- INFORMASI DARI SPD (READONLY) -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
            <div class="flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                <h3 class="font-semibold text-blue-800">Informasi dari SPD (Tidak dapat diubah)</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-gray-500 block">Nomor SPPD</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-file-alt text-gray-400 mr-1"></i>
                        <?php echo e($rincian->nomor_sppd ?? '-'); ?>

                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">Tanggal Berangkat</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                        <?php echo e($rincian->tanggal_berangkat ? $rincian->tanggal_berangkat->format('d/m/Y') : '-'); ?>

                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">Tanggal Kembali</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                        <?php echo e($rincian->tanggal_kembali ? $rincian->tanggal_kembali->format('d/m/Y') : '-'); ?>

                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">Lama Perjadin</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                        <?php echo e($rincian->lama_perjadin ?? 0); ?> Hari
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">Tempat Tujuan</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                        <?php echo e($rincian->tempat_tujuan ?? '-'); ?>

                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">Uang Harian per Hari</label>
                    <div class="font-medium text-green-600 mt-1">
                        <i class="fas fa-money-bill-wave text-gray-400 mr-1"></i>
                        Rp <?php echo e(number_format($rincian->uang_harian ?? 0, 0, ',', '.')); ?>

                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block">SPD ID</label>
                    <div class="font-medium text-gray-800 mt-1">
                        <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                        #<?php echo e($rincian->spd_id); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA PEGAWAI (READONLY) -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <i class="fas fa-users text-gray-500"></i>
                <h3 class="font-semibold text-gray-700">Daftar Pegawai Pelaksana</h3>
                <span class="text-xs text-gray-400 ml-2">(Data dari SPD - Tidak dapat diubah)</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Uang Harian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                        $pegawaiList = is_array($rincian->pegawai) ? $rincian->pegawai : [];
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            <?php echo e(strtoupper(substr($peg['nama'] ?? '?', 0, 1))); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($peg['nama'] ?? '-'); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono"><?php echo e($peg['nip'] ?? '-'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($peg['jabatan'] ?? '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">
                            Rp <?php echo e(number_format($peg['nominal'] ?? 0, 0, ',', '.')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            <i class="fas fa-user-slash text-3xl mb-2 block"></i>
                            Tidak ada data pegawai
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm font-semibold text-gray-700 text-right">
                            Total Uang Harian Keseluruhan:
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-blue-600 text-right">
                            Rp <?php echo e(number_format($rincian->total_uang_harian_keseluruhan ?? 0, 0, ',', '.')); ?>

                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Data pegawai diambil dari SPD. Untuk mengubah, silakan edit SPD terlebih dahulu.
            </p>
        </div>
    </div>

    <!-- BENDAHARA PENGELUARAN (READONLY) -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-tie text-gray-500"></i>
                <h3 class="font-semibold text-gray-700">Bendahara Pengeluaran</h3>
                <span class="text-xs text-gray-400 ml-2">(Data snapshot - Tidak dapat diubah)</span>
            </div>
        </div>
        <div class="p-6">
            <?php
                $bendahara = $rincian->bendahara_snapshot;
            ?>
            <?php if($bendahara && ($bendahara->nama ?? $rincian->bendahara_nama)): ?>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-user-circle text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 text-lg"><?php echo e($bendahara->nama ?? $rincian->bendahara_nama); ?></div>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-id-card mr-1"></i> NIP: <?php echo e($bendahara->nip ?? $rincian->bendahara_nip ?? '-'); ?>

                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-briefcase mr-1"></i> Jabatan: <?php echo e($bendahara->jabatan ?? $rincian->bendahara_jabatan ?? '-'); ?>

                        </div>
                        <?php if($rincian->bendahara_nama): ?>
                            <div class="text-xs text-blue-500 mt-2">
                                <i class="fas fa-camera mr-1"></i> Data snapshot (tidak berubah meskipun data master berubah)
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif($rincian->bendahara_pengeluaran_id && $rincian->bendaharaPengeluaran): ?>
                <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 text-lg"><?php echo e($rincian->bendaharaPengeluaran->nama); ?></div>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-id-card mr-1"></i> NIP: <?php echo e($rincian->bendaharaPengeluaran->nip ?? '-'); ?>

                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-briefcase mr-1"></i> Jabatan: <?php echo e($rincian->bendaharaPengeluaran->jabatan ?? '-'); ?>

                        </div>
                        <div class="text-xs text-yellow-500 mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Data dari relasi (akan berubah jika data master berubah)
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-question-circle text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Belum ditentukan</div>
                        <div class="text-sm text-gray-500">Bendahara pengeluaran belum dipilih</div>
                    </div>
                </div>
            <?php endif; ?>
            
            <input type="hidden" name="bendahara_pengeluaran_id" value="<?php echo e($rincian->bendahara_pengeluaran_id); ?>">
            
            <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                Bendahara pengeluaran diambil dari snapshot saat Rincian Biaya dibuat dan tidak dapat diubah di sini.
            </p>
        </div>
    </div>

    <!-- RINGKASAN PERHITUNGAN -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <i class="fas fa-calculator text-gray-500"></i>
                <h3 class="font-semibold text-gray-700">Ringkasan Perhitungan Biaya</h3>
                <span class="text-xs text-gray-400 ml-2">(Hanya Uang Harian - Transport di Kwitansi Terpisah)</span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-users mr-1"></i> Jumlah Pegawai
                    </div>
                    <div class="text-2xl font-bold text-blue-600 mt-1">
                        <?php echo e(count($pegawaiList)); ?> <span class="text-sm font-normal text-gray-500">orang</span>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-money-bill-wave mr-1"></i> Uang Harian per Hari
                    </div>
                    <div class="text-2xl font-bold text-green-600 mt-1">
                        Rp <?php echo e(number_format($rincian->uang_harian ?? 0, 0, ',', '.')); ?>

                    </div>
                    <div class="text-xs text-gray-400">per orang</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i> Lama Perjalanan
                    </div>
                    <div class="text-2xl font-bold text-purple-600 mt-1">
                        <?php echo e($rincian->lama_perjadin ?? 0); ?> <span class="text-sm font-normal text-gray-500">hari</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm font-semibold text-blue-800">
                                <i class="fas fa-chart-line mr-1"></i> TOTAL KESELURUHAN
                            </div>
                            <div class="text-[10px] text-blue-600 mt-0.5">
                                (Uang Harian × Pegawai × Hari)
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-blue-800" id="grandTotal">
                            Rp <?php echo e(number_format($rincian->total_uang_harian_keseluruhan ?? $rincian->total, 0, ',', '.')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TERBILANG -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <i class="fas fa-edit text-gray-500"></i>
                <h3 class="font-semibold text-gray-700">Terbilang</h3>
            </div>
        </div>
        <div class="p-6">
            <textarea name="terbilang" 
                      id="terbilangInput"
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                      readonly><?php echo e($rincian->terbilang ?? ''); ?></textarea>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Terbilang akan otomatis terisi berdasarkan total uang harian
            </p>
        </div>
    </div>

    <!-- BUTTON SUBMIT -->
    <div class="flex justify-end gap-3">
        <a href="<?php echo e(route('rincian.index')); ?>" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-times mr-2"></i> Batal
        </a>
        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-save mr-2"></i> Update Rincian
        </button>
    </div>
</form>

<script>
    // DOM Elements
    const terbilangInput = document.getElementById('terbilangInput');
    const grandTotalSpan = document.getElementById('grandTotal');
    
    // Data dari server
    const uangHarianPerOrang = <?php echo e($rincian->uang_harian ?? 0); ?>;
    const jumlahPegawai = <?php echo e(count(is_array($rincian->pegawai) ? $rincian->pegawai : [])); ?>;
    const lamaPerjadin = <?php echo e($rincian->lama_perjadin ?? 0); ?>;
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function convertToTerbilang(angka) {
        if (angka === 0 || isNaN(angka)) return 'Nol Rupiah';
        
        const satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        
        function terbilangRibuan(n) {
            if (n === 0) return '';
            if (n < 10) return satuan[n];
            if (n < 20) {
                if (n === 10) return 'Sepuluh';
                if (n === 11) return 'Sebelas';
                return satuan[n - 10] + ' Belas';
            }
            if (n < 100) {
                const puluh = Math.floor(n / 10);
                const sisa = n % 10;
                if (sisa === 0) return satuan[puluh] + ' Puluh';
                return satuan[puluh] + ' Puluh ' + satuan[sisa];
            }
            if (n < 1000) {
                const ratus = Math.floor(n / 100);
                const sisa = n % 100;
                if (ratus === 1) return 'Seratus ' + terbilangRibuan(sisa);
                if (sisa === 0) return satuan[ratus] + ' Ratus';
                return satuan[ratus] + ' Ratus ' + terbilangRibuan(sisa);
            }
            return '';
        }
        
        let result = '';
        let i = 0;
        let tempAngka = angka;
        
        while (tempAngka > 0) {
            const segment = tempAngka % 1000;
            if (segment > 0) {
                let segmentText = terbilangRibuan(segment);
                if (segment === 1 && i === 1) segmentText = 'Seribu';
                else if (segment === 1 && i > 0) segmentText = 'Satu';
                result = segmentText + ' ' + ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'][i] + ' ' + result;
            }
            tempAngka = Math.floor(tempAngka / 1000);
            i++;
        }
        
        result = result.trim().replace(/\s+/g, ' ');
        result = result.charAt(0).toUpperCase() + result.slice(1).toLowerCase();
        
        return result + ' Rupiah';
    }
    
    function calculateTotal() {
        // Hanya uang harian (transport tidak termasuk)
        const totalUangHarian = uangHarianPerOrang * jumlahPegawai * lamaPerjadin;
        
        grandTotalSpan.innerHTML = 'Rp ' + formatNumber(totalUangHarian);
        terbilangInput.value = convertToTerbilang(totalUangHarian);
        
        return totalUangHarian;
    }
    
    // Initial calculation
    calculateTotal();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/rincian-edit.blade.php ENDPATH**/ ?>