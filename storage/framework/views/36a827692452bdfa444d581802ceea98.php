

<?php $__env->startSection('title', 'Edit Kwitansi Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Edit Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah data kwitansi perjalanan dinas</p>
        </div>
        <a href="<?php echo e(route('kwitansi.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- ALERT ERROR -->
    <?php if($errors->any()): ?>
        <div class="mx-6 mt-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <strong class="font-semibold">Terjadi kesalahan:</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- FORM EDIT -->
    <form action="<?php echo e(route('kwitansi.update', $kwitansi->id)); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kiri -->
            <div class="space-y-4">
                <!-- TAHUN ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun_anggaran" value="<?php echo e(old('tahun_anggaran', $kwitansi->tahun_anggaran)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="2025" maxlength="4" required>
                </div>

                <!-- KODE REKENING -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_rekening" value="<?php echo e(old('kode_rekening', $kwitansi->kode_rekening)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           placeholder="5.1.02.04.01.0001" required>
                </div>

                <!-- SUB KEGIATAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="sub_kegiatan" value="<?php echo e(old('sub_kegiatan', $kwitansi->sub_kegiatan)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Sub kegiatan" required>
                </div>

                <!-- NO BUKU KAS UMUM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Buku Kas Umum <span class="text-red-500">*</span></label>
                    <input type="text" name="no_bku" value="<?php echo e(old('no_bku', $kwitansi->no_bku)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="...../SRJ/GU-/2025" required>
                </div>

                <!-- NO BRPP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. BRPP</label>
                    <input type="text" name="no_brpp" value="<?php echo e(old('no_brpp', $kwitansi->no_brpp)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Opsional">
                </div>

                <!-- NOMINAL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal" value="<?php echo e(old('nominal', $kwitansi->nominal)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="600000" required>
                    <p class="text-xs text-gray-500 mt-1">Nominal dalam Rupiah (tanpa titik atau koma)</p>
                </div>

                <!-- TERBILANG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang <span class="text-red-500">*</span></label>
                    <input type="text" name="terbilang" value="<?php echo e(old('terbilang', $kwitansi->terbilang)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enam Ratus Ribu Rupiah" required>
                </div>

                <!-- TANGGAL KWITANSI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kwitansi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kwitansi" value="<?php echo e(old('tanggal_kwitansi', $kwitansi->tanggal_kwitansi)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>
            </div>

            <!-- Kanan -->
            <div class="space-y-4">
                <!-- UNTUK PEMBAYARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Untuk Pembayaran <span class="text-red-500">*</span></label>
                    <textarea name="untuk_pembayaran" rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              required><?php echo e(old('untuk_pembayaran', $kwitansi->untuk_pembayaran)); ?></textarea>
                </div>

                <!-- PENGGUNA ANGGARAN (KEPALA DINAS) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengguna Anggaran (Kepala Dinas) <span class="text-red-500">*</span></label>
                    <input type="text" name="pengguna_anggaran" value="<?php echo e(old('pengguna_anggaran', $kwitansi->pengguna_anggaran)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP PENGGUNA ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pengguna Anggaran <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_pengguna_anggaran" value="<?php echo e(old('nip_pengguna_anggaran', $kwitansi->nip_pengguna_anggaran)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           required>
                </div>

                <!-- BENDAHARA PENGELUARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara Pengeluaran <span class="text-red-500">*</span></label>
                    <input type="text" name="bendahara_pengeluaran" value="<?php echo e(old('bendahara_pengeluaran', $kwitansi->bendahara_pengeluaran)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP BENDAHARA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Bendahara <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_bendahara" value="<?php echo e(old('nip_bendahara', $kwitansi->nip_bendahara)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           required>
                </div>

                <!-- PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="penerima" value="<?php echo e(old('penerima', $kwitansi->penerima)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_penerima" value="<?php echo e(old('nip_penerima', $kwitansi->nip_penerima)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           required>
                </div>
            </div>
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200">
            <a href="<?php echo e(route('kwitansi.index')); ?>" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Kwitansi
            </button>
        </div>
    </form>
</div>

<script>
    // Auto generate terbilang from nominal (optional)
    document.querySelector('input[name="nominal"]')?.addEventListener('keyup', function() {
        let nominal = this.value;
        // You can implement auto terbilang here if needed
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-edit.blade.php ENDPATH**/ ?>