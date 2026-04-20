<?php $__env->startSection('title', 'Edit Rincian Biaya Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Edit Rincian Biaya</h2>
            <p class="text-sm text-gray-500 mt-1">Edit rincian biaya perjalanan dinas (berasal dari SPD)</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('rincian.cetak', $rincian->id)); ?>" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak PDF
            </a>
            
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('rincian.update', $rincian->id)); ?>" id="formRincian">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Hidden input untuk spd_id -->
        <input type="hidden" name="spd_id" value="<?php echo e($rincian->spd_id); ?>">

        <div class="p-6 space-y-6">
            <!-- INFORMASI DARI SPD (READONLY) -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-semibold text-blue-800">Informasi dari SPD (Tidak dapat diubah)</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <div class="text-xs text-blue-600">Nomor SPPD</div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->nomor_sppd ?? '-'); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tanggal Berangkat</div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->tanggal_berangkat ? $rincian->tanggal_berangkat->format('d/m/Y') : '-'); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tanggal Kembali</div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->tanggal_kembali ? $rincian->tanggal_kembali->format('d/m/Y') : '-'); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Lama Perjadin</div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->lama_perjadin ?? 0); ?> Hari</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tempat Tujuan</div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->tempat_tujuan ?? '-'); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Uang Harian per Hari</div>
                        <div class="font-medium text-gray-800">Rp <?php echo e(number_format($rincian->uang_harian ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Uang Transport per Orang</div>
                        <div class="font-medium text-gray-800">Rp <?php echo e(number_format($rincian->uang_transport ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">SPD ID</div>
                        <div class="font-medium text-gray-800">#<?php echo e($rincian->spd_id); ?></div>
                    </div>
                </div>
            </div>

            <!-- DATA PEGAWAI (READONLY) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Pegawai Pelaksana</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Data dari SPD (Tidak dapat diubah)</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">No</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">Nama Pegawai</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">NIP</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">Jabatan</th>
                                    <th class="px-4 py-2 text-right text-xs text-gray-500">Uang Harian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php
                                    $pegawaiList = is_array($rincian->pegawai) ? $rincian->pegawai : [];
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($index + 1); ?></td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-800"><?php echo e($peg['nama'] ?? '-'); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-600"><?php echo e($peg['nip'] ?? '-'); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-600"><?php echo e($peg['jabatan'] ?? '-'); ?></td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-600">
                                        Rp <?php echo e(number_format($peg['nominal'] ?? 0, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                                        Tidak ada data pegawai
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">
                                        Total Uang Harian Keseluruhan:
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-blue-600 text-right">
                                        Rp <?php echo e(number_format($rincian->total_uang_harian_keseluruhan ?? 0, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    * Data pegawai diambil dari SPD. Untuk mengubah, silakan edit SPD terlebih dahulu.
                </p>
            </div>

            <!-- BENDAHARA PENGELUARAN (READONLY - TIDAK BISA DIUBAH) -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Bendahara Pengeluaran
    </label>
    <div class="border border-gray-300 rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
            <div class="text-sm font-medium text-gray-700">Data dari sistem (Tidak dapat diubah)</div>
        </div>
        <div class="p-4">
            <?php
                // Gunakan snapshot bendahara jika ada
                $bendahara = $rincian->bendahara_snapshot;
            ?>
            <?php if($bendahara && ($bendahara->nama || $rincian->bendahara_nama)): ?>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800"><?php echo e($bendahara->nama ?? $rincian->bendahara_nama); ?></div>
                        <div class="text-xs text-gray-500">
                            NIP: <?php echo e($bendahara->nip ?? $rincian->bendahara_nip ?? '-'); ?> | 
                            Jabatan: <?php echo e($bendahara->jabatan ?? $rincian->bendahara_jabatan ?? '-'); ?>

                        </div>
                        <?php if($rincian->bendahara_nama): ?>
                            <div class="text-xs text-blue-500 mt-1">
                                <i class="fas fa-camera mr-1"></i> Data snapshot (tidak berubah meskipun data master berubah)
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif($rincian->bendahara_pengeluaran_id && $rincian->bendaharaPengeluaran): ?>
                
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800"><?php echo e($rincian->bendaharaPengeluaran->nama); ?></div>
                        <div class="text-xs text-gray-500">
                            NIP: <?php echo e($rincian->bendaharaPengeluaran->nip ?? '-'); ?> | 
                            Jabatan: <?php echo e($rincian->bendaharaPengeluaran->jabatan ?? '-'); ?>

                        </div>
                        <div class="text-xs text-yellow-500 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Data dari relasi (akan berubah jika data master berubah)
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium text-yellow-800">Belum ditentukan</div>
                        <div class="text-xs text-yellow-600">Bendahara pengeluaran belum dipilih</div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Hidden input untuk mengirim bendahara yang sudah ada -->
            <input type="hidden" name="bendahara_pengeluaran_id" value="<?php echo e($rincian->bendahara_pengeluaran_id); ?>">
            
            <p class="text-xs text-gray-500 mt-3">
                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Bendahara pengeluaran diambil dari snapshot saat Rincian Biaya dibuat dan tidak dapat diubah di sini.
                <?php if($rincian->bendahara_nama): ?>
                    
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
            <!-- RINGKASAN PERHITUNGAN -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h3 class="font-semibold text-gray-800 mb-3">Ringkasan Perhitungan Biaya</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Jumlah Pegawai</div>
                        <div class="text-xl font-bold text-blue-600">
                            <?php echo e(count($pegawaiList)); ?> orang
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Uang Harian per Hari</div>
                        <div class="text-xl font-bold text-green-600">
                            Rp <?php echo e(number_format($rincian->uang_harian ?? 0, 0, ',', '.')); ?>

                        </div>
                        <div class="text-xs text-gray-400">per orang</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Uang Transport per Orang</div>
                        <div class="text-xl font-bold text-orange-600">
                            Rp <?php echo e(number_format($rincian->uang_transport ?? 0, 0, ',', '.')); ?>

                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Lama Perjalanan</div>
                        <div class="text-xl font-bold text-purple-600">
                            <?php echo e($rincian->lama_perjadin ?? 0); ?> hari
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Transport Tambahan (Darat/Udara)</div>
                        <input type="number" 
                               name="transport" 
                               id="transportInput"
                               value="<?php echo e(old('transport', $rincian->transport ?? 0)); ?>"
                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Biaya transport tambahan">
                        <div class="text-xs text-gray-400 mt-1">
                            * Biaya transportasi selain uang transport per orang
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-blue-800">TOTAL KESELURUHAN</div>
                                <div class="text-[10px] text-blue-600">
                                    (Uang Harian × Pegawai × Hari) + (Uang Transport × Pegawai) + Transport Tambahan
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-blue-800" id="grandTotal">
                                Rp <?php echo e(number_format($rincian->total_keseluruhan ?? $rincian->total, 0, ',', '.')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TERBILANG -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang</label>
                <textarea name="terbilang" 
                          id="terbilangInput"
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          readonly
                          style="background-color: #f9fafb;"><?php echo e($rincian->terbilang ?? ''); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Terbilang akan otomatis terisi saat nominal berubah</p>
            </div>
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
            <a href="<?php echo e(route('rincian.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Update Rincian
            </button>
        </div>
    </form>
</div>

<script>
    // DOM Elements
    const transportInput = document.getElementById('transportInput');
    const terbilangInput = document.getElementById('terbilangInput');
    const grandTotalSpan = document.getElementById('grandTotal');
    
    // Data dari server (PHP variables to JavaScript)
    const uangHarianPerOrang = <?php echo e($rincian->uang_harian ?? 0); ?>;
    const uangTransportPerOrang = <?php echo e($rincian->uang_transport ?? 0); ?>;
    const jumlahPegawai = <?php echo e(count(is_array($rincian->pegawai) ? $rincian->pegawai : [])); ?>;
    const lamaPerjadin = <?php echo e($rincian->lama_perjadin ?? 0); ?>;
    const currentTotal = <?php echo e($rincian->total_keseluruhan ?? $rincian->total ?? 0); ?>;
    
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
        const transportTambahan = parseInt(transportInput.value) || 0;
        
        const totalUangHarian = uangHarianPerOrang * jumlahPegawai * lamaPerjadin;
        const totalUangTransport = uangTransportPerOrang * jumlahPegawai;
        const grandTotal = totalUangHarian + totalUangTransport + transportTambahan;
        
        grandTotalSpan.innerHTML = 'Rp ' + formatNumber(grandTotal);
        terbilangInput.value = convertToTerbilang(grandTotal);
        
        return grandTotal;
    }
    
    // Event Listeners
    transportInput.addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/rincian-edit.blade.php ENDPATH**/ ?>