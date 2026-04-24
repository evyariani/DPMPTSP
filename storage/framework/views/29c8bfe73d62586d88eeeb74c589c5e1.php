<?php $__env->startSection('title', 'Surat Perintah Tugas (SPT)'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Animasi untuk notifikasi bawah */
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

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

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

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
}

.animate-slide-in-bottom {
    animation: slideInFromBottom 0.3s ease-out forwards;
}

.animate-slide-out-bottom {
    animation: slideOutToBottom 0.3s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

.progress-bar {
    animation: progressBar 5s linear forwards;
}

/* Custom untuk SPT */
.spt-badge {
    @apply px-2 py-1 rounded-full text-xs font-medium;
}

.spt-badge-dinas {
    @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.spt-badge-pribadi {
    @apply bg-green-100 text-green-800 border border-green-200;
}

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Fixed width untuk kolom */
.fixed-col-nomor {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-tujuan {
    min-width: 250px;
    max-width: 350px;
}

.fixed-col-tanggal {
    min-width: 120px;
    max-width: 150px;
}

.fixed-col-lokasi {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-pegawai {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-dasar {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-penandatangan {
    min-width: 180px;
    max-width: 250px;
}

/* Hover effect untuk sel tabel */
.table-cell-hover:hover {
    background-color: #f9fafb;
}

/* Badge untuk jumlah pegawai */
.pegawai-count-badge {
    @apply ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded;
}

/* Loading spinner untuk export */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.btn-loading {
    opacity: 0.7;
    cursor: wait;
}

.btn-loading i {
    animation: spin 1s linear infinite;
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
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Tugas (SPT)</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Tugas</p>
        </div>
        <div class="flex space-x-2">
            <!-- SATU TOMBOL EXPORT - akan mengambil semua filter yang aktif -->
            <button type="button" 
                    onclick="exportData()"
                    id="btn-export"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </button>
            
            <!-- Tombol Tambah SPT -->
            <a href="<?php echo e(route('spt.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <i class="fas fa-plus mr-2"></i> Tambah SPT
            </a>
        </div>
    </div>
</div>

<!-- Notifikasi Toast - POSISI DI BAWAH -->
<?php if(session('success')): ?>
<div id="success-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
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
<div id="error-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
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
<div id="warning-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Perhatian!</p>
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

<!-- Notifikasi Hapus - POSISI DI BAWAH -->
<div id="delete-notification" class="hidden fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-trash-restore text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Data Dihapus!</p>
                <p id="delete-message" class="text-sm mt-1"></p>
            </div>
            <button type="button" onclick="hideNotification('delete')" class="ml-4 text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1">
            <div id="delete-progress" class="bg-blue-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto animate-fade-in">
            <div class="p-6 text-center">
                <!-- Icon Warning -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                
                <!-- Title -->
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                
                <!-- Message -->
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan menghapus data SPT:</p>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-nomor"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-tujuan"></p>
                    </div>
                    
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Data yang dihapus <span class="font-semibold">tidak dapat dikembalikan</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4">
                    <button type="button" 
                            onclick="hideDeleteModal()"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    
                    <form id="delete-form" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo e(route('spt.index')); ?>" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nomor surat, tujuan, lokasi, atau nama pegawai..." 
                       value="<?php echo e(request('search')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Bulan</option>
                    <option value="1" <?php echo e(request('bulan') == '1' ? 'selected' : ''); ?>>Januari</option>
                    <option value="2" <?php echo e(request('bulan') == '2' ? 'selected' : ''); ?>>Februari</option>
                    <option value="3" <?php echo e(request('bulan') == '3' ? 'selected' : ''); ?>>Maret</option>
                    <option value="4" <?php echo e(request('bulan') == '4' ? 'selected' : ''); ?>>April</option>
                    <option value="5" <?php echo e(request('bulan') == '5' ? 'selected' : ''); ?>>Mei</option>
                    <option value="6" <?php echo e(request('bulan') == '6' ? 'selected' : ''); ?>>Juni</option>
                    <option value="7" <?php echo e(request('bulan') == '7' ? 'selected' : ''); ?>>Juli</option>
                    <option value="8" <?php echo e(request('bulan') == '8' ? 'selected' : ''); ?>>Agustus</option>
                    <option value="9" <?php echo e(request('bulan') == '9' ? 'selected' : ''); ?>>September</option>
                    <option value="10" <?php echo e(request('bulan') == '10' ? 'selected' : ''); ?>>Oktober</option>
                    <option value="11" <?php echo e(request('bulan') == '11' ? 'selected' : ''); ?>>November</option>
                    <option value="12" <?php echo e(request('bulan') == '12' ? 'selected' : ''); ?>>Desember</option>
                </select>
                
                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    <?php for($year = date('Y'); $year >= date('Y')-5; $year--): ?>
                        <option value="<?php echo e($year); ?>" <?php echo e(request('tahun') == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                    <?php endfor; ?>
                </select>
                
                <select name="penanda_tangan" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Penanda Tangan</option>
                    <?php $__currentLoopData = $pegawais ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pegawai->id_pegawai); ?>" <?php echo e(request('penanda_tangan') == $pegawai->id_pegawai ? 'selected' : ''); ?>>
                            <?php echo e($pegawai->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                
                <?php if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('penanda_tangan')): ?>
                    <a href="<?php echo e(route('spt.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Tabel SPT -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-nomor">Nomor Surat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-dasar">Dasar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-pegawai">Pegawai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tujuan">Tujuan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-lokasi">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-penandatangan">Penanda Tangan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                    // Pastikan $spts selalu ada dan bisa di-loop
                    $spts = $spts ?? collect([]);
                    $isPaginated = method_exists($spts, 'currentPage');
                ?>
                
                <?php $__empty_1 = true; $__currentLoopData = $spts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $spt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php if($isPaginated): ?>
                            <?php echo e(($spts->currentPage() - 1) * $spts->perPage() + $index + 1); ?>

                        <?php else: ?>
                            <?php echo e($index + 1); ?>

                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Nomor Surat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-nomor table-cell-hover">
                        <div class="text-sm font-medium text-gray-900" title="<?php echo e($spt->nomor_surat); ?>">
                            <?php echo e(Str::limit($spt->nomor_surat, 30)); ?>

                        </div>
                        <?php if(strlen($spt->nomor_surat) > 30): ?>
                            <button type="button" 
                                    onclick="showFullText(this, '<?php echo e(addslashes($spt->nomor_surat)); ?>', 'Nomor Surat')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Dasar -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-dasar table-cell-hover">
                        <?php if(!empty($spt->dasar)): ?>
                            <?php $dasarList = $spt->dasar_list; ?>
                            <?php if(count($dasarList) > 1): ?>
                                <div class="space-y-1">
                                    <?php $__currentLoopData = array_slice($dasarList, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dasar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="text-sm text-gray-700">• <?php echo e(Str::limit($dasar, 30)); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($dasarList) > 2): ?>
                                        <button type="button" 
                                                onclick="showFullDasar(this, <?php echo e(json_encode($dasarList)); ?>)"
                                                class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                            + <?php echo e(count($dasarList) - 2); ?> dasar lainnya
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-sm text-gray-700"><?php echo e(Str::limit($dasarList[0] ?? '', 50)); ?></div>
                                <?php if(strlen($dasarList[0] ?? '') > 50): ?>
                                    <button type="button" 
                                            onclick="showFullText(this, '<?php echo e(addslashes($dasarList[0])); ?>', 'Dasar')"
                                            class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                        Lihat selengkapnya
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Pegawai -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-pegawai table-cell-hover">
                        <?php if(!empty($spt->pegawai)): ?>
                            <?php $pegawaiList = $spt->pegawai_list; ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center tooltip">
                                        <div class="flex-shrink-0 h-6 w-6 mr-2">
                                            <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-xs">
                                                    <?php echo e(strtoupper(substr($pegawai->nama, 0, 1))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-900" title="<?php echo e($pegawai->nama); ?>">
                                            <?php echo e(Str::limit($pegawai->nama, 25)); ?>

                                        </div>
                                        <span class="tooltip-text">
                                            <?php echo e($pegawai->nama); ?><br>
                                            NIP: <?php echo e($pegawai->nip ?? '-'); ?><br>
                                            Jabatan: <?php echo e($pegawai->jabatan ?? '-'); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Tujuan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tujuan table-cell-hover">
                        <div class="text-sm text-gray-900" title="<?php echo e($spt->tujuan); ?>">
                            <?php echo e(Str::limit($spt->tujuan, 80)); ?>

                        </div>
                        <?php if(strlen($spt->tujuan) > 80): ?>
                            <button type="button" 
                                    onclick="showFullText(this, '<?php echo e(addslashes($spt->tujuan)); ?>', 'Tujuan')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Tanggal -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 fixed-col-tanggal">
                        <?php if($spt->tanggal): ?>
                            <div class="font-medium"><?php echo e($spt->tanggal->format('d/m/Y')); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($spt->tanggal->format('l')); ?></div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Lokasi -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-lokasi table-cell-hover">
                        <div class="text-sm text-gray-900" title="<?php echo e($spt->lokasi); ?>">
                            <?php echo e(Str::limit($spt->lokasi, 30)); ?>

                        </div>
                        <?php if(strlen($spt->lokasi) > 30): ?>
                            <button type="button" 
                                    onclick="showFullText(this, '<?php echo e(addslashes($spt->lokasi)); ?>', 'Lokasi')"
                                    class="mt-1 text-xs text-blue-600 hover:text-blue-800">
                                Lihat selengkapnya
                            </button>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Penanda Tangan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-penandatangan table-cell-hover">
                        <?php if($spt->penandaTangan): ?>
                            <div class="flex items-center tooltip">
                                <div class="flex-shrink-0 h-8 w-8 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-semibold text-sm">
                                            <?php echo e(strtoupper(substr($spt->penandaTangan->nama, 0, 1))); ?>

                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900" title="<?php echo e($spt->penandaTangan->nama); ?>">
                                        <?php echo e(Str::limit($spt->penandaTangan->nama, 30)); ?>

                                    </div>
                                    <div class="text-xs text-gray-500"><?php echo e($spt->penandaTangan->jabatan ?? '-'); ?></div>
                                </div>
                                <span class="tooltip-text">
                                    <?php echo e($spt->penandaTangan->nama); ?><br>
                                    NIP: <?php echo e($spt->penandaTangan->nip ?? '-'); ?><br>
                                    Jabatan: <?php echo e($spt->penandaTangan->jabatan ?? '-'); ?>

                                </span>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-wrap gap-2">
                            <?php if(isset($spt->id_spt)): ?>
                            <!-- Tombol Edit -->
                            <a href="<?php echo e(route('spt.edit', $spt->id_spt)); ?>" 
                               class="text-green-600 hover:text-green-900 px-2 py-1 rounded hover:bg-green-50 transition duration-150 tooltip"
                               title="Edit SPT">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit SPT</span>
                            </a>
                            
                            <!-- Tombol Buat SPD dari SPT -->
                            <a href="<?php echo e(route('spd.create-from-spt', $spt->id_spt)); ?>" 
                               class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50 transition duration-150 tooltip"
                               title="Buat SPD dari SPT ini"
                               onclick="return confirmCreateSpd('<?php echo e(addslashes($spt->nomor_surat)); ?>')">
                                <i class="fas fa-file-signature"></i>
                                <span class="tooltip-text">Buat SPD dari SPT ini</span>
                            </a>
                            
                            <!-- Tombol Print PDF -->
                            

                            <!-- Tombol Preview PDF -->
                            <a href="<?php echo e(route('spt.preview-pdf', $spt->id_spt)); ?>" 
                               target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 px-2 py-1 rounded hover:bg-indigo-50 transition duration-150 tooltip"
                               title="Preview PDF SPT">
                                <i class="fas fa-eye"></i>
                                <span class="tooltip-text">Preview PDF SPT</span>
                            </a>
                            
                            <!-- Tombol Hapus dengan Modal -->
                            <button type="button" 
                                    onclick="showDeleteConfirmation(
                                        <?php echo e($spt->id_spt); ?>, 
                                        '<?php echo e(addslashes(Str::limit($spt->nomor_surat, 30))); ?>', 
                                        '<?php echo e(addslashes(Str::limit($spt->tujuan, 50))); ?>'
                                    )"
                                    class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50 transition duration-150 tooltip"
                                    title="Hapus SPT">
                                <i class="fas fa-trash"></i>
                                <span class="tooltip-text">Hapus SPT</span>
                            </button>
                            <?php else: ?>
                            <span class="text-gray-400 px-2 py-1">-</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-file-alt text-gray-300 text-4xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPT</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan Surat Perintah Tugas baru</p>
                            <a href="<?php echo e(route('spt.create')); ?>" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i> Tambah SPT Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php
    // Cek apakah $spts ada dan memiliki method hasPages
    $showPagination = false;
    if (isset($spts) && method_exists($spts, 'hasPages') && $spts->hasPages()) {
        $showPagination = true;
    }
?>

<?php if($showPagination && $spts->count() > 0): ?>
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium"><?php echo e($spts->firstItem() ?: 0); ?></span> 
        sampai 
        <span class="font-medium"><?php echo e($spts->lastItem() ?: 0); ?></span> 
        dari 
        <span class="font-medium"><?php echo e($spts->total()); ?></span> 
        Surat Perintah Tugas
    </div>
    
    <div class="flex items-center space-x-1">
        
        <?php if($spts->onFirstPage()): ?>
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        <?php else: ?>
            <a href="<?php echo e($spts->previousPageUrl()); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        <?php endif; ?>
        
        
        <?php
            $current = $spts->currentPage();
            $last = $spts->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        ?>
        
        <?php if($start > 1): ?>
            <a href="<?php echo e($spts->url(1)); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            <?php if($start > 2): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php for($page = $start; $page <= $end; $page++): ?>
            <?php if($page == $current): ?>
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($spts->url($page)); ?>" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if($end < $last): ?>
            <?php if($end < $last - 1): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
            <a href="<?php echo e($spts->url($last)); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($last); ?></a>
        <?php endif; ?>
        
        
        <?php if($spts->hasMorePages()): ?>
            <a href="<?php echo e($spts->nextPageUrl()); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        <?php else: ?>
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// ========== CONFIRM CREATE SPD FROM SPT ==========
function confirmCreateSpd(nomorSurat) {
    return confirm(`Apakah Anda yakin ingin membuat SPD dari SPT dengan nomor "${nomorSurat}"?\n\nData SPD akan dibuat otomatis berdasarkan data SPT ini.`);
}

// ========== EXPORT FUNCTION (SATU UNTUK SEMUA FILTER) ==========
function exportData() {
    const btn = document.getElementById('btn-export');
    const originalHtml = btn.innerHTML;
    
    // Show loading state
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    btn.classList.add('btn-loading');
    btn.disabled = true;
    
    // Get all filter values from the form
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value && value !== '') {
            params.append(key, value);
        }
    }
    
    // Redirect to export URL with filters (gunakan route spt.export)
    const exportUrl = "<?php echo e(route('spt.export')); ?>?" + params.toString();
    window.location.href = exportUrl;
    
    // Reset button after 2 seconds
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-loading');
        btn.disabled = false;
    }, 2000);
}

// ========== NOTIFICATION FUNCTIONS ==========
function hideNotification(type) {
    const notification = document.getElementById(`${type}-notification`);
    if (notification) {
        notification.classList.remove('animate-slide-in-bottom');
        notification.classList.add('animate-slide-out-bottom');
        setTimeout(() => {
            notification.style.display = 'none';
            notification.classList.add('hidden');
        }, 300);
    }
}

// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide success/error notifications
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        const warningNotif = document.getElementById('warning-notification');
        
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
        if (warningNotif) hideNotification('warning');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;
let currentDeleteNomor = null;

function showDeleteConfirmation(id, nomor, tujuan) {
    currentDeleteId = id;
    currentDeleteNomor = nomor;
    
    // Update modal content
    document.getElementById('delete-nomor').textContent = nomor;
    document.getElementById('delete-tujuan').textContent = tujuan ? `Tujuan: ${tujuan}` : 'Tanpa Tujuan';
    
    // Update form action
    const form = document.getElementById('delete-form');
    form.action = `/spt/${id}`;
    
    // Show modal with animation
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    // Add animation class to modal content
    const modalContent = modal.querySelector('.bg-white');
    modalContent.classList.add('animate-fade-in');
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const modalContent = modal.querySelector('.bg-white');
    
    // Add fade out animation
    modalContent.classList.remove('animate-fade-in');
    modalContent.classList.add('animate-fade-out');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        modalContent.classList.remove('animate-fade-out');
        currentDeleteId = null;
        currentDeleteNomor = null;
    }, 300);
}

// Handle form submission dengan AJAX untuk notifikasi lebih baik
document.getElementById('delete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    // Tampilkan loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
    submitBtn.disabled = true;
    
    // Kirim request DELETE
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tampilkan notifikasi hapus sukses
            showDeleteSuccess(currentDeleteNomor);
            // Sembunyikan modal
            hideDeleteModal();
            // Refresh halaman setelah 2 detik
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Jika error, tampilkan alert biasa
        alert('Terjadi kesalahan saat menghapus data: ' + error.message);
        // Reset tombol
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Tampilkan notifikasi hapus sukses
function showDeleteSuccess(nomor) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    
    message.textContent = `Data SPT dengan nomor "${nomor}" berhasil dihapus.`;
    
    // Reset progress bar
    const progress = document.getElementById('delete-progress');
    progress.style.width = '100%';
    progress.style.animation = 'none';
    void progress.offsetWidth; // Trigger reflow
    progress.style.animation = 'progressBar 5s linear forwards';
    
    // Show notification dengan animasi bawah
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    notification.classList.add('animate-slide-in-bottom');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideNotification('delete');
    }, 5000);
}

// ========== FULL TEXT MODAL ==========
function showFullText(element, text, title) {
    // Buat modal untuk menampilkan teks lengkap
    const modalId = 'full-text-modal';
    let modal = document.getElementById(modalId);
    
    if (!modal) {
        // Buat modal jika belum ada
        modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden';
        modal.innerHTML = `
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto animate-fade-in">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900" id="full-text-title"></h3>
                            <button type="button" onclick="hideFullTextModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap max-h-96 overflow-y-auto" id="full-text-content"></pre>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="hideFullTextModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Isi konten modal
    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideFullTextModal() {
    const modal = document.getElementById('full-text-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// ========== FULL DASAR MODAL ==========
function showFullDasar(element, dasarList) {
    const modalId = 'full-dasar-modal';
    let modal = document.getElementById(modalId);
    
    if (!modal) {
        modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden';
        modal.innerHTML = `
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto animate-fade-in">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Dasar SPT</h3>
                            <button type="button" onclick="hideFullDasarModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <ul class="list-disc list-inside space-y-2" id="full-dasar-list"></ul>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="hideFullDasarModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Isi list dasar
    const listElement = document.getElementById('full-dasar-list');
    listElement.innerHTML = '';
    dasarList.forEach((dasar, index) => {
        const li = document.createElement('li');
        li.className = 'text-sm text-gray-700';
        li.textContent = dasar;
        listElement.appendChild(li);
    });
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideFullDasarModal() {
    const modal = document.getElementById('full-dasar-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.getElementById('delete-confirm-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close full text modal when clicking outside
document.addEventListener('click', function(e) {
    const fullTextModal = document.getElementById('full-text-modal');
    if (fullTextModal && e.target === fullTextModal) {
        hideFullTextModal();
    }
    
    const fullDasarModal = document.getElementById('full-dasar-modal');
    if (fullDasarModal && e.target === fullDasarModal) {
        hideFullDasarModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideFullTextModal();
        hideFullDasarModal();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/spt.blade.php ENDPATH**/ ?>