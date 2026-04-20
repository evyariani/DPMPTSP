

<?php $__env->startSection('title', 'Tambah Kwitansi Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Tambah Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Buat kwitansi perjalanan dinas baru</p>
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

    <!-- FORM CREATE -->
    <form action="<?php echo e(route('kwitansi.store')); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kiri -->
            <div class="space-y-4">
                <!-- TAHUN ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun_anggaran" value="<?php echo e(old('tahun_anggaran', date('Y'))); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="2025" maxlength="4" required>
                </div>

                <!-- KODE REKENING -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_rekening" value="<?php echo e(old('kode_rekening', '5.1.02.04.01.0001')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           placeholder="5.1.02.04.01.0001" required>
                </div>

                <!-- SUB KEGIATAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="sub_kegiatan" value="<?php echo e(old('sub_kegiatan', 'Penyelenggaraan Konsultasi SKPD / Rapat Koordinasi')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Sub kegiatan" required>
                </div>

                <!-- NO BUKU KAS UMUM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Buku Kas Umum <span class="text-red-500">*</span></label>
                    <input type="text" name="no_bku" value="<?php echo e(old('no_bku', '...../SRJ/GU-/2025')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="...../SRJ/GU-/2025" required>
                </div>

                <!-- NO BRPP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. BRPP</label>
                    <input type="text" name="no_brpp" value="<?php echo e(old('no_brpp')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Opsional">
                </div>

                <!-- NOMINAL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal" value="<?php echo e(old('nominal', 600000)); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="600000" required>
                    <p class="text-xs text-gray-500 mt-1">Nominal dalam Rupiah (tanpa titik atau koma)</p>
                </div>

                <!-- TERBILANG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang <span class="text-red-500">*</span></label>
                    <input type="text" name="terbilang" value="<?php echo e(old('terbilang', 'Enam Ratus Ribu Rupiah')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enam Ratus Ribu Rupiah" required>
                </div>

                <!-- TANGGAL KWITANSI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kwitansi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kwitansi" value="<?php echo e(old('tanggal_kwitansi', date('Y-m-d'))); ?>" 
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
                              placeholder="Biaya perjalanan dinas ke Kota Banjarbaru pada tanggal 14 Oktober 2025 Dalam rangka Mengikuti Kegiatan Peningkatan Kapabilitas Verifikator dan PPK (Sekretaris) SKPD" required><?php echo e(old('untuk_pembayaran', 'Biaya perjalanan dinas ke Kota Banjarbaru pada tanggal 14 Oktober 2025 Dalam rangka Mengikuti Kegiatan Peningkatan Kapabilitas Verifikator dan PPK (Sekretaris) SKPD an. LIDIA MIRANTI MAYASARI, SE, NIK 6301035708840013 Rek Bank Kalsel 2001337508 Alamat Jl. Bougenvil No. 25 Blok. A Raya Kel. Pabahanan, SPT dan SPD terlampir')); ?></textarea>
                </div>

                <!-- PENGGUNA ANGGARAN (KEPALA DINAS) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengguna Anggaran (Kepala Dinas) <span class="text-red-500">*</span></label>
                    <input type="text" name="pengguna_anggaran" value="<?php echo e(old('pengguna_anggaran', 'BUDI ANDRIAN SUTANTO, S. Sos., MM')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP PENGGUNA ANGGARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pengguna Anggaran <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_pengguna_anggaran" value="<?php echo e(old('nip_pengguna_anggaran', '19760218 200701 1 006')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           required>
                </div>

                <!-- BENDAHARA PENGELUARAN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara Pengeluaran <span class="text-red-500">*</span></label>
                    <input type="text" name="bendahara_pengeluaran" value="<?php echo e(old('bendahara_pengeluaran', 'NURLITA FEBRANA PRATWI, A.Md')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP BENDAHARA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Bendahara <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_bendahara" value="<?php echo e(old('nip_bendahara', '19980208 202012 2 007')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                           required>
                </div>

                <!-- PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="penerima" value="<?php echo e(old('penerima', 'LIDIA MIRANTI MAYASARI, SE')); ?>" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- NIP PENERIMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="nip_penerima" value="<?php echo e(old('nip_penerima', '19840817 200903 2 022')); ?>" 
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
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Kwitansi
            </button>
        </div>
    </form>
</div>

<script>
    // Auto generate terbilang from nominal
    document.querySelector('input[name="nominal"]')?.addEventListener('keyup', function() {
        let nominal = this.value;
        // You can implement auto terbilang here if needed
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-create.blade.php ENDPATH**/ ?>