<?php $__env->startSection('title', 'Tambah Kwitansi Perjalanan Dinas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Tambah Kwitansi</h2>
            <p class="text-sm text-gray-500 mt-1">Isi form untuk menambah kwitansi perjalanan dinas</p>
        </div>
        <a href="<?php echo e(route('kwitansi.index')); ?>" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <form action="<?php echo e(route('kwitansi.store')); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>

        <div class="space-y-6">
            <!-- Pilih SPD -->
            <div>
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
                <div id="spdInfo" class="hidden mt-2 bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs text-gray-500">Nomor SPD</span>
                        <span id="spd_nomor" class="text-sm font-semibold text-gray-800"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Maksud Perjalanan</span>
                        <span id="spd_maksud" class="text-sm text-gray-700 text-right max-w-[70%]"></span>
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
                                <input type="text" id="tahun_anggaran" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kode Rekening</label>
                                <input type="text" id="kode_rekening" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Sub Kegiatan</label>
                                <textarea id="sub_kegiatan" rows="2" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Untuk Pembayaran</label>
                                <textarea name="untuk_pembayaran" id="untuk_pembayaran" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
                                <input type="text" id="pengguna_anggaran_display" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                                <input type="hidden" name="pengguna_anggaran" id="pengguna_anggaran">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                <input type="text" id="nip_pengguna_anggaran_display" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                                <input type="hidden" name="nip_pengguna_anggaran" id="nip_pengguna_anggaran">
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
                                <input type="text" id="bendahara_pengeluaran_display" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm" readonly>
                                <input type="hidden" name="bendahara_pengeluaran" id="bendahara_pengeluaran">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                <input type="text" id="nip_bendahara_display" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-mono" readonly>
                                <input type="hidden" name="nip_bendahara" id="nip_bendahara">
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
                        <div class="text-sm font-medium text-gray-700">Data Penerima Kwitansi</div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="penerima" id="penerima" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="nip_penerima" id="nip_penerima" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <div class="text-sm font-medium text-gray-700">Informasi Kwitansi</div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nominal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="nominal" id="nominal" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <p class="text-xs text-green-600 mt-1" id="nominalSource"></p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">No. BKU <span class="text-red-500">*</span></label>
                                <input type="text" name="no_bku" id="no_bku" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">No. BRPP</label>
                                <input type="text" name="no_brpp" id="no_brpp" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Kwitansi <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_kwitansi" id="tanggal_kwitansi" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Terbilang <span class="text-red-500">*</span></label>
                                <textarea name="terbilang" id="terbilang" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                                <p class="text-xs text-gray-400 mt-1">Terbilang akan otomatis terisi saat nominal berubah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="<?php echo e(route('kwitansi.index')); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
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
                        
                        $('#tahun_anggaran').val(data.tahun_anggaran || '');
                        $('#kode_rekening').val(data.kode_rekening || '');
                        $('#sub_kegiatan').val(data.sub_kegiatan || '');
                        $('#untuk_pembayaran').val(data.untuk_pembayaran || '');
                        $('#nominal').val(data.nominal || 0);
                        $('#terbilang').val(data.terbilang || '');
                        
                        if (data.nominal > 0) {
                            $('#nominalSource').html('✅ Nominal dari Rincian Bidang: Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal));
                        } else {
                            $('#nominalSource').html('⚠️ Rincian Bidang belum diisi, nominal 0');
                        }
                        
                        $('#pengguna_anggaran_display').val(data.pengguna_anggaran || '');
                        $('#pengguna_anggaran').val(data.pengguna_anggaran || '');
                        $('#nip_pengguna_anggaran_display').val(data.nip_pengguna_anggaran || '');
                        $('#nip_pengguna_anggaran').val(data.nip_pengguna_anggaran || '');
                        
                        $('#bendahara_pengeluaran_display').val(data.bendahara_pengeluaran || '');
                        $('#bendahara_pengeluaran').val(data.bendahara_pengeluaran || '');
                        $('#nip_bendahara_display').val(data.nip_bendahara || '');
                        $('#nip_bendahara').val(data.nip_bendahara || '');
                        
                        if (data.penerima) {
                            $('#penerima').val(data.penerima);
                            $('#nip_penerima').val(data.nip_penerima);
                        }
                        
                        if (data.no_bku) {
                            $('#no_bku').val(data.no_bku);
                        }
                        
                        var selectedOption = $('#spd_id option:selected');
                        $('#spd_nomor').text(selectedOption.data('nomor') || '-');
                        $('#spd_maksud').text(selectedOption.data('maksud') || '-');
                        
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/kwitansi-create.blade.php ENDPATH**/ ?>