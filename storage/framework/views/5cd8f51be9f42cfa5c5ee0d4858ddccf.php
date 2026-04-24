<?php $__env->startSection('title', 'Tambah Kwitansi Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Tambah Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih SPD, semua data akan terisi otomatis dari SPD & Rincian Bidang</p>
        </div>
        <a href="<?php echo e(route('kwitansi.index')); ?>" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- INFO BANNER -->
    <div class="mx-6 mt-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-lg">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm">✨ Pilih SPD, maka data akan terisi otomatis dari SPD, Rincian Bidang, dan data pegawai. Kwitansi siap cetak!</span>
        </div>
    </div>

    <form action="<?php echo e(route('kwitansi.store')); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- KOLOM KIRI: Data SPD & Rincian -->
            <div class="space-y-4">
                <!-- Pilih SPD -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih SPD <span class="text-red-500">*</span></label>
                    <select name="spd_id" id="spd_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih SPD --</option>
                        <?php $__currentLoopData = $spdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($spd->id_spd); ?>" 
                                data-nomor="<?php echo e($spd->nomor_surat); ?>"
                                data-maksud="<?php echo e($spd->maksud_perjadin); ?>">
                            <?php echo e($spd->nomor_surat); ?> - <?php echo e(substr($spd->maksud_perjadin, 0, 50)); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Info Ringkas SPD -->
                <div id="spdInfo" class="hidden bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs text-gray-500">Nomor SPD</span>
                        <span id="spd_nomor" class="text-sm font-semibold text-gray-800"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Maksud Perjalanan</span>
                        <span id="spd_maksud" class="text-sm text-gray-700 text-right max-w-[70%]"></span>
                    </div>
                </div>

                <!-- Data dari SPD/Rincian (Readonly) -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-100 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800">📋 Data dari SPD & Rincian Bidang</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Tahun Anggaran</label>
                            <input type="text" id="tahun_anggaran" class="w-full border-0 bg-transparent text-sm font-semibold" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Kode Rekening</label>
                            <input type="text" id="kode_rekening" class="w-full border-0 bg-transparent text-sm font-mono" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Sub Kegiatan</label>
                            <textarea id="sub_kegiatan" rows="2" class="w-full border-0 bg-transparent text-sm" readonly></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Untuk Pembayaran</label>
                            <textarea name="untuk_pembayaran" id="untuk_pembayaran" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Data Kwitansi -->
            <div class="space-y-4">
                <!-- Data Pegawai & Bendahara -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-100 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800">👥 Data Pegawai & Bendahara</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Pengguna Anggaran</label>
                            <input type="text" id="pengguna_anggaran_display" class="w-full border-0 bg-transparent text-sm" readonly>
                            <input type="hidden" name="pengguna_anggaran" id="pengguna_anggaran">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">NIP Pengguna Anggaran</label>
                            <input type="text" id="nip_pengguna_anggaran_display" class="w-full border-0 bg-transparent text-sm font-mono" readonly>
                            <input type="hidden" name="nip_pengguna_anggaran" id="nip_pengguna_anggaran">
                        </div>
                        <div class="border-t border-gray-200 my-2"></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Bendahara Pengeluaran</label>
                            <input type="text" id="bendahara_pengeluaran_display" class="w-full border-0 bg-transparent text-sm" readonly>
                            <input type="hidden" name="bendahara_pengeluaran" id="bendahara_pengeluaran">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">NIP Bendahara</label>
                            <input type="text" id="nip_bendahara_display" class="w-full border-0 bg-transparent text-sm font-mono" readonly>
                            <input type="hidden" name="nip_bendahara" id="nip_bendahara">
                        </div>
                        <div class="border-t border-gray-200 my-2"></div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penerima <span class="text-red-500">*</span></label>
                            <input type="text" name="penerima" id="penerima" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP Penerima <span class="text-red-500">*</span></label>
                            <input type="text" name="nip_penerima" id="nip_penerima" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Data Kwitansi -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-100 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800">💰 Data Kwitansi</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nominal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" name="nominal" id="nominal" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <p class="text-xs text-green-600 mt-1" id="nominalSource"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terbilang <span class="text-red-500">*</span></label>
                            <input type="text" name="terbilang" id="terbilang" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. BKU <span class="text-red-500">*</span></label>
                            <input type="text" name="no_bku" id="no_bku" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. BRPP</label>
                            <input type="text" name="no_brpp" id="no_brpp" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Kwitansi <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_kwitansi" id="tanggal_kwitansi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
            <a href="<?php echo e(route('kwitansi.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Kwitansi
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#spd_id').change(function() {
        var spdId = $(this).val();
        
        if (spdId) {
            // Tampilkan loading
            $('#spdInfo').removeClass('hidden');
            $('#spd_nomor').text('Loading...');
            $('#spd_maksud').text('Loading...');
            
            $.ajax({
                url: '/kwitansi/get-by-spd/' + spdId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        
                        // Isi semua field dari response
                        $('#tahun_anggaran').val(data.tahun_anggaran || '');
                        $('#kode_rekening').val(data.kode_rekening || '');
                        $('#sub_kegiatan').val(data.sub_kegiatan || '');
                        $('#untuk_pembayaran').val(data.untuk_pembayaran || '');
                        
                        // Nominal & Terbilang
                        $('#nominal').val(data.nominal || 0);
                        $('#terbilang').val(data.terbilang || '');
                        
                        // Tampilkan sumber nominal
                        if (data.nominal > 0) {
                            $('#nominalSource').html('✅ Nominal dari Rincian Bidang: Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal));
                        } else {
                            $('#nominalSource').html('⚠️ Rincian Bidang belum diisi, nominal 0');
                        }
                        
                        // Pengguna Anggaran
                        $('#pengguna_anggaran_display').val(data.pengguna_anggaran || '');
                        $('#pengguna_anggaran').val(data.pengguna_anggaran || '');
                        $('#nip_pengguna_anggaran_display').val(data.nip_pengguna_anggaran || '');
                        $('#nip_pengguna_anggaran').val(data.nip_pengguna_anggaran || '');
                        
                        // Bendahara
                        $('#bendahara_pengeluaran_display').val(data.bendahara_pengeluaran || '');
                        $('#bendahara_pengeluaran').val(data.bendahara_pengeluaran || '');
                        $('#nip_bendahara_display').val(data.nip_bendahara || '');
                        $('#nip_bendahara').val(data.nip_bendahara || '');
                        
                        // Penerima (bisa diedit)
                        if (data.penerima) {
                            $('#penerima').val(data.penerima);
                            $('#nip_penerima').val(data.nip_penerima);
                        }
                        
                        // No BKU otomatis
                        if (data.no_bku) {
                            $('#no_bku').val(data.no_bku);
                        }
                        
                        // Info SPD
                        var selectedOption = $('#spd_id option:selected');
                        $('#spd_nomor').text(selectedOption.data('nomor') || '-');
                        $('#spd_maksud').text(selectedOption.data('maksud') || '-');
                        
                        // Notifikasi sukses
                        toastr?.success('Data kwitansi terisi otomatis dari SPD & Rincian Bidang');
                        
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengambil data');
                }
            });
        } else {
            // Reset semua field
            $('#tahun_anggaran, #kode_rekening, #sub_kegiatan, #untuk_pembayaran').val('');
            $('#pengguna_anggaran_display, #pengguna_anggaran').val('');
            $('#nip_pengguna_anggaran_display, #nip_pengguna_anggaran').val('');
            $('#bendahara_pengeluaran_display, #bendahara_pengeluaran').val('');
            $('#nip_bendahara_display, #nip_bendahara').val('');
            $('#penerima, #nip_penerima').val('');
            $('#nominal, #terbilang, #no_bku').val('');
            $('#spdInfo').addClass('hidden');
            $('#nominalSource').html('');
        }
    });
    
    // Auto-generate terbilang saat nominal berubah
    function generateTerbilang(nominal) {
        if (!nominal || nominal == 0) return 'Nol Rupiah';
        // Panggil API atau fungsi lokal
        $.ajax({
            url: '/kwitansi/terbilang/' + nominal,
            type: 'GET',
            success: function(res) {
                if (res.success) $('#terbilang').val(res.terbilang);
            }
        });
    }
    
    $('#nominal').on('change keyup', function() {
        var nominal = $(this).val();
        if (nominal && nominal > 0) {
            generateTerbilang(nominal);
        }
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-create.blade.php ENDPATH**/ ?>