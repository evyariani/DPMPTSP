

<?php $__env->startSection('title', 'Surat Perintah Dinas (SPD) - Halaman Belakang'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Animasi untuk notifikasi */
@keyframes slideInFromBottom {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideOutToBottom {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}

.animate-slide-in-bottom {
    animation: slideInFromBottom 0.3s ease-out forwards;
}

.animate-slide-out-bottom {
    animation: slideOutToBottom 0.3s ease-out forwards;
}

/* Card styling */
.info-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.info-card-header {
    padding: 1rem 1.5rem;
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.info-card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
}

/* Notifikasi floating */
.notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    animation: slideInFromBottom 0.3s ease-out forwards;
}

.notification.hide {
    animation: slideOutToBottom 0.3s ease-out forwards;
}

/* Badge styling */
.info-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 500;
}

.info-badge-blue {
    background-color: #dbeafe;
    color: #1e40af;
}

.info-badge-green {
    background-color: #d1fae5;
    color: #065f46;
}

.info-badge-purple {
    background-color: #ede9fe;
    color: #5b21b6;
}

/* Progress bar */
@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.progress-bar {
    animation: progressBar 5s linear forwards;
}

/* Tooltip */
.tooltip {
    position: relative;
    display: inline-block;
    cursor: help;
}

.tooltip .tooltip-text {
    visibility: hidden;
    background-color: #1f2937;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 10px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 11px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>

<!-- Meta tag untuk mencegah cache -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Dinas (SPD) - Halaman Belakang</h2>
            <p class="text-gray-500">Detail Program, Kode Rekening, dan Penanda Tangan</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('spd.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Depan
            </a>
            <a href="<?php echo e(route('spd.edit', $spd->id_spd)); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-edit mr-2"></i> Edit Halaman Depan
            </a>
        </div>
    </div>
</div>

<!-- Notifikasi Toast -->
<?php if(session('success')): ?>
<div id="success-notification" class="notification">
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Berhasil!</p>
                <p class="text-sm mt-1"><?php echo e(session('success')); ?></p>
            </div>
            <button type="button" onclick="hideNotification('success')" class="ml-4 text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-green-200 rounded-full h-1">
            <div id="success-progress" class="bg-green-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div id="error-notification" class="notification">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Terjadi Kesalahan!</p>
                <p class="text-sm mt-1"><?php echo e(session('error')); ?></p>
            </div>
            <button type="button" onclick="hideNotification('error')" class="ml-4 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-red-200 rounded-full h-1">
            <div id="error-progress" class="bg-red-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(session('warning')): ?>
<div id="warning-notification" class="notification">
    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Peringatan!</p>
                <p class="text-sm mt-1"><?php echo e(session('warning')); ?></p>
            </div>
            <button type="button" onclick="hideNotification('warning')" class="ml-4 text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-yellow-200 rounded-full h-1">
            <div id="warning-progress" class="bg-yellow-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- CARD 1: INFORMASI SPD -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-file-alt text-blue-500 mr-2"></i>
            Informasi SPD
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <span class="text-gray-500 text-xs">Nomor Surat</span>
                <p class="font-semibold text-gray-800 text-sm"><?php echo e($spd->nomor_surat); ?></p>
            </div>
            <div>
                <span class="text-gray-500 text-xs">Maksud Perjalanan</span>
                <p class="text-gray-800 text-sm"><?php echo e(Str::limit($spd->maksud_perjadin, 80)); ?></p>
            </div>
            <div>
                <span class="text-gray-500 text-xs">Tanggal Perjalanan</span>
                <p class="text-gray-800 text-sm"><?php echo e($spd->tanggal_berangkat ? $spd->tanggal_berangkat->format('d/m/Y') : '-'); ?> s/d <?php echo e($spd->tanggal_kembali ? $spd->tanggal_kembali->format('d/m/Y') : '-'); ?></p>
                <p class="text-xs text-gray-500"><?php echo e($spd->lama_perjadin); ?> Hari</p>
            </div>
            <div>
                <span class="text-gray-500 text-xs">Tempat Tujuan</span>
                <p class="text-gray-800 text-sm"><?php echo e($spd->tempatTujuan?->nama ?? '-'); ?></p>
            </div>
            <div>
                <span class="text-gray-500 text-xs">Alat Transportasi</span>
                <p class="text-gray-800 text-sm"><?php echo e($spd->label_alat_transportasi ?? '-'); ?></p>
            </div>
            <div>
                <span class="text-gray-500 text-xs">SKPD</span>
                <p class="text-gray-800 text-sm"><?php echo e($spd->skpd ?? '-'); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- CARD 2: PROGRAM & KODE REKENING -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-folder-open text-purple-500 mr-2"></i>
            Program & Kode Rekening
        </h3>
        <span class="info-badge info-badge-purple ml-3">Data dari Program</span>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-purple-50 rounded-lg p-3">
                <span class="text-gray-600 text-xs">Program</span>
                <p class="font-medium text-gray-800 text-sm"><?php echo e($spd->pejabat_teknis_program ?? '-'); ?></p>
            </div>
            <div class="bg-purple-50 rounded-lg p-3">
                <span class="text-gray-600 text-xs">Kegiatan</span>
                <p class="font-medium text-gray-800 text-sm"><?php echo e($spd->pejabat_teknis_kegiatan ?? '-'); ?></p>
            </div>
            <div class="bg-purple-50 rounded-lg p-3">
                <span class="text-gray-600 text-xs">Sub Kegiatan</span>
                <p class="font-medium text-gray-800 text-sm"><?php echo e($spd->pejabat_teknis_sub_kegiatan ?? '-'); ?></p>
            </div>
            <div class="bg-purple-50 rounded-lg p-3">
                <span class="text-gray-600 text-xs">Kode Rekening</span>
                <p class="font-mono font-medium text-gray-800 text-sm"><?php echo e($spd->pejabat_teknis_kode_rekening ?? '-'); ?></p>
            </div>
            <div class="bg-purple-50 rounded-lg p-3 md:col-span-2">
                <span class="text-gray-600 text-xs">Pejabat Teknis</span>
                <p class="font-medium text-gray-800 text-sm"><?php echo e($spd->pejabatTeknisPegawai?->nama ?? '-'); ?></p>
                <p class="text-xs text-gray-500">NIP: <?php echo e($spd->pejabatTeknisPegawai?->nip ?? '-'); ?></p>
                <p class="text-xs text-gray-500">Jabatan: <?php echo e($spd->pejabatTeknisPegawai?->jabatan ?? '-'); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- CARD 3: PELAKSANA PERJALANAN DINAS -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-users text-teal-500 mr-2"></i>
            Pelaksana Perjalanan Dinas
        </h3>
        <span class="info-badge info-badge-blue ml-3">Jumlah: <?php echo e($spd->pelaksanaPerjadin->count()); ?> orang</span>
    </div>
    <div class="p-6">
        <?php if($spd->pelaksanaPerjadin->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php $__currentLoopData = $spd->pelaksanaPerjadin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pelaksana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-150">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center">
                            <i class="fas fa-user-check text-teal-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($pelaksana->nama); ?></p>
                            <p class="text-xs text-gray-500 truncate">NIP: <?php echo e($pelaksana->nip ?? '-'); ?></p>
                            <p class="text-xs text-gray-500 truncate"><?php echo e($pelaksana->jabatan ?? '-'); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">Tidak ada data pelaksana</p>
        <?php endif; ?>
    </div>
</div>

<!-- CARD 4: PENANDA TANGAN SPD TUJUAN -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-signature text-indigo-500 mr-2"></i>
            Penanda Tangan SPD Tujuan
        </h3>
        <span class="info-badge info-badge-blue ml-3">Isi jika diperlukan</span>
    </div>
    <div class="p-6">
        <form action="<?php echo e(route('spd.update-belakang', $spd->id_spd)); ?>" method="POST" id="formPenandaTangan">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="penanda_tangan_nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penanda Tangan
                    </label>
                    <input type="text" 
                           id="penanda_tangan_nama" 
                           name="penanda_tangan_nama" 
                           value="<?php echo e(old('penanda_tangan_nama', $spd->penanda_tangan_nama)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: Dr. H. Ahmad, M.Si">
                    <p class="mt-1 text-xs text-gray-500">Nama pejabat yang menandatangani SPD di tempat tujuan</p>
                </div>
                
                <div>
                    <label for="penanda_tangan_nip" class="block text-sm font-medium text-gray-700 mb-2">
                        NIP Penanda Tangan
                    </label>
                    <input type="text" 
                           id="penanda_tangan_nip" 
                           name="penanda_tangan_nip" 
                           value="<?php echo e(old('penanda_tangan_nip', $spd->penanda_tangan_nip)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: 197501011998031001">
                </div>
                
                <div>
                    <label for="penanda_tangan_jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Jabatan Penanda Tangan
                    </label>
                    <input type="text" 
                           id="penanda_tangan_jabatan" 
                           name="penanda_tangan_jabatan" 
                           value="<?php echo e(old('penanda_tangan_jabatan', $spd->penanda_tangan_jabatan)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: Kepala Dinas">
                </div>
                
                <div>
                    <label for="penanda_tangan_instansi" class="block text-sm font-medium text-gray-700 mb-2">
                        Instansi / Lembaga
                    </label>
                    <input type="text" 
                           id="penanda_tangan_instansi" 
                           name="penanda_tangan_instansi" 
                           value="<?php echo e(old('penanda_tangan_instansi', $spd->penanda_tangan_instansi)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: Dinas Pendidikan Kab. Bandung">
                    <p class="mt-1 text-xs text-gray-500">Instansi/lembaga tempat penanda tangan bertugas</p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CARD 6: TOMBOL CETAK -->
<div class="info-card">
    <div class="p-6">
        <div class="flex justify-end space-x-3">
            <a href="<?php echo e(route('spd.print-belakang', $spd->id_spd)); ?>" 
               target="_blank"
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-print mr-2"></i> Cetak Halaman Belakang
            </a>
            <a href="<?php echo e(route('spd.preview-belakang', $spd->id_spd)); ?>" 
               target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-eye mr-2"></i> Preview PDF
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function hideNotification(type) {
    const notification = document.getElementById(`${type}-notification`);
    if (notification) {
        notification.classList.add('hide');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }
}

// Auto hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        const warningNotif = document.getElementById('warning-notification');
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
        if (warningNotif) hideNotification('warning');
    }, 5000);
});

// Pastikan form tidak double submit
document.getElementById('formPenandaTangan')?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn.disabled) {
        e.preventDefault();
        return false;
    }
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    // Reset button after 5 seconds if something wrong
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Perubahan';
    }, 5000);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/spd-belakang.blade.php ENDPATH**/ ?>