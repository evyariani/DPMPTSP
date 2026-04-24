<?php $__env->startSection('title', 'Laporan Hasil Perjalanan Dinas (LHPD)'); ?>

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

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Fixed width untuk kolom */
.fixed-col-tujuan {
    min-width: 200px;
    max-width: 300px;
}

.fixed-col-tanggal {
    min-width: 130px;
    max-width: 160px;
}

.fixed-col-daerah {
    min-width: 150px;
    max-width: 200px;
}

.fixed-col-hasil {
    min-width: 200px;
    max-width: 350px;
}

.fixed-col-tempat {
    min-width: 150px;
    max-width: 200px;
}

/* Badge status */
.status-badge {
    @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium;
}

.status-complete {
    @apply bg-green-100 text-green-800;
}

.status-incomplete {
    @apply bg-yellow-100 text-yellow-800;
}

.status-empty {
    @apply bg-gray-100 text-gray-800;
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

/* Button styling */
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background-color: #2563eb;
    color: white;
}

.btn-primary:hover {
    background-color: #1d4ed8;
}

.btn-secondary {
    background-color: #9ca3af;
    color: white;
}

.btn-secondary:hover {
    background-color: #6b7280;
}

.btn-danger {
    background-color: #dc2626;
    color: white;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

/* Image preview */
.image-preview {
    cursor: pointer;
    transition: transform 0.2s;
}

.image-preview:hover {
    transform: scale(1.05);
}

/* Modal image */
.modal-image {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

/* Galeri foto */
.gallery-thumb {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 0.5rem;
}

.gallery-thumb img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    transition: transform 0.3s;
}

.gallery-thumb:hover img {
    transform: scale(1.1);
}

.gallery-thumb .badge-count {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 20px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: bold;
}

/* Foto grid di modal galeri */
.foto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.foto-item {
    position: relative;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.foto-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s;
}

.foto-item img:hover {
    transform: scale(1.05);
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Laporan Hasil Perjalanan Dinas (LHPD)</h2>
            <p class="text-gray-500">Kelola data Laporan Hasil Perjalanan Dinas</p>
        </div>
    </div>
</div>

<!-- Notifikasi Toast -->
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

<!-- Notifikasi Hapus -->
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
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Konfirmasi Hapus</h3>
                <div class="mb-6 text-left">
                    <p class="text-gray-600 mb-3">Anda akan menghapus data LHPD:</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-gray-800 text-lg" id="delete-tujuan"></p>
                        <p class="text-gray-600 text-sm mt-1" id="delete-tanggal"></p>
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
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideDeleteModal()" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <form id="delete-form" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[120px]">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-transparent max-w-5xl mx-auto">
            <button type="button" onclick="hideImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-3xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <div class="relative">
                <img id="modal-image" src="" alt="Preview" class="max-w-full max-h-[90vh] mx-auto rounded-lg shadow-2xl">
                <div id="image-navigation" class="absolute inset-y-0 left-0 right-0 flex justify-between items-center px-4">
                    <button onclick="prevImage()" class="bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                        <i class="fas fa-chevron-left text-2xl"></i>
                    </button>
                    <button onclick="nextImage()" class="bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                        <i class="fas fa-chevron-right text-2xl"></i>
                    </button>
                </div>
                <div id="image-counter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                    1 / 1
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Galeri Foto -->
<div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen p-4">
        <div class="sticky top-0 z-10 flex justify-end mb-4">
            <button type="button" onclick="hideGalleryModal()" class="text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="container mx-auto">
            <h3 class="text-white text-xl font-semibold mb-4 text-center">Galeri Foto LHPD</h3>
            <div id="gallery-container" class="foto-grid">
                <!-- Dynamic gallery content -->
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo e(route('lhpd.index')); ?>" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari tujuan, hasil LHPD, atau daerah..."
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

                <select name="id_daerah" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Daerah Tujuan</option>
                    <?php $__currentLoopData = $daerahList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daerah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($daerah->id); ?>" <?php echo e(request('id_daerah') == $daerah->id ? 'selected' : ''); ?>>
                            <?php echo e($daerah->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>

                <?php if(request()->has('search') || request()->has('bulan') || request()->has('tahun') || request()->has('id_daerah')): ?>
                    <a href="<?php echo e(route('lhpd.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                <?php endif; ?>
                
                <!-- Tombol Export Excel -->
                <button type="button" 
                        onclick="exportData()"
                        id="btn-export"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabel LHPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tujuan">Tujuan Perjalanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal Berangkat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-daerah">Daerah Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-hasil">Hasil LHPD</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tempat">Tempat Dikeluarkan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider fixed-col-tanggal">Tanggal LHPD</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Foto</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $lhpdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lhpd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        <?php echo e($lhpdList->firstItem() + $index); ?>

                    </td>

                    <!-- Kolom Tujuan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tujuan">
                        <div class="text-sm font-medium text-gray-900" title="<?php echo e($lhpd->tujuan); ?>">
                            <?php echo e(Str::limit($lhpd->tujuan, 50)); ?>

                        </div>
                        <?php if(!$lhpd->hasil): ?>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium status-incomplete">
                                <i class="fas fa-clock mr-1 text-xs"></i> Belum diisi
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Tanggal Berangkat -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tanggal">
                        <?php if($lhpd->tanggal_berangkat): ?>
                            <div class="text-sm text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d/m/Y')); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Daerah Tujuan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-daerah">
                        <?php if($lhpd->daerahTujuan): ?>
                            <div class="text-sm text-gray-900">
                                <?php echo e($lhpd->daerahTujuan->nama); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Hasil LHPD -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-hasil">
                        <?php if($lhpd->hasil): ?>
                            <div class="text-sm text-gray-900" title="<?php echo e($lhpd->hasil); ?>">
                                <?php echo e(Str::limit($lhpd->hasil, 80)); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm italic">Belum diisi</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Tempat Dikeluarkan -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tempat">
                        <?php if($lhpd->tempatDikeluarkan): ?>
                            <div class="text-sm text-gray-900">
                                <?php echo e($lhpd->tempatDikeluarkan->nama); ?>

                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Tanggal LHPD -->
                    <td class="px-6 py-4 text-wrap-cell fixed-col-tanggal">
                        <?php if($lhpd->tanggal_lhpd): ?>
                            <div class="text-sm text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($lhpd->tanggal_lhpd)->format('d/m/Y')); ?>

                            </div>
                            <?php if($lhpd->hasil): ?>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium status-complete">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Lengkap
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Foto (Multiple) -->
                    <td class="px-6 py-4 text-center">
                        <?php
                            $fotoCount = $lhpd->foto_count;
                        ?>
                        <?php if($fotoCount > 0): ?>
                            <button type="button" 
                                    onclick="showGallery(<?php echo e($lhpd->id_lhpd); ?>)"
                                    class="gallery-thumb inline-flex flex-col items-center text-blue-600 hover:text-blue-800">
                                <div class="relative">
                                    <?php if($lhpd->first_foto_url): ?>
                                        <img src="<?php echo e($lhpd->first_foto_url); ?>" alt="Thumbnail" 
                                             class="w-12 h-12 object-cover rounded-lg border">
                                    <?php else: ?>
                                        <i class="fas fa-images text-3xl"></i>
                                    <?php endif; ?>
                                    <span class="badge-count"><?php echo e($fotoCount); ?></span>
                                </div>
                                <span class="text-xs mt-1"><?php echo e($fotoCount); ?> foto</span>
                            </button>
                        <?php else: ?>
                            <span class="text-gray-400">
                                <i class="fas fa-image text-2xl opacity-50"></i>
                                <span class="text-xs block">0 foto</span>
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center gap-2">
                            <a href="<?php echo e(route('lhpd.edit', $lhpd->id_lhpd)); ?>"
                               class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150 tooltip"
                               title="Edit LHPD">
                                <i class="fas fa-edit"></i>
                            </a>

                            

                            <a href="<?php echo e(route('lhpd.print', $lhpd->id_lhpd)); ?>"
                               target="_blank"
                               class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition duration-150 tooltip"
                               title="Download PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            <a href="<?php echo e(route('lhpd.preview-pdf', $lhpd->id_lhpd)); ?>"
                               target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition duration-150 tooltip"
                               title="Preview PDF">
                                <i class="fas fa-eye"></i>
                            </a>

                            <button type="button"
                                    onclick="showDeleteConfirmation(
                                        <?php echo e($lhpd->id_lhpd); ?>,
                                        '<?php echo e(addslashes(Str::limit($lhpd->tujuan, 50))); ?>',
                                        '<?php echo e(addslashes($lhpd->tanggal_berangkat ? \Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d/m/Y') : '-')); ?>'
                                    )"
                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150 tooltip"
                                    title="Hapus LHPD">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Tidak ada data LHPD</p>
                            <p class="text-sm mt-1">LHPD akan dibuat otomatis saat membuat SPT/SPD</p>
                            <a href="<?php echo e(route('spt.index')); ?>" class="mt-3 btn-primary btn">
                                <i class="fas fa-plus"></i> Buat SPT
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
<?php if($lhpdList->hasPages()): ?>
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan
        <span class="font-medium"><?php echo e($lhpdList->firstItem() ?: 0); ?></span>
        sampai
        <span class="font-medium"><?php echo e($lhpdList->lastItem() ?: 0); ?></span>
        dari
        <span class="font-medium"><?php echo e($lhpdList->total()); ?></span>
        Laporan Hasil Perjalanan Dinas
    </div>

    <div class="flex items-center space-x-1">
        <?php if($lhpdList->onFirstPage()): ?>
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        <?php else: ?>
            <a href="<?php echo e($lhpdList->previousPageUrl()); ?>" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        <?php endif; ?>

        <?php
            $current = $lhpdList->currentPage();
            $last = $lhpdList->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        ?>

        <?php if($start > 1): ?>
            <a href="<?php echo e($lhpdList->url(1)); ?>" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            <?php if($start > 2): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for($page = $start; $page <= $end; $page++): ?>
            <?php if($page == $current): ?>
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($lhpdList->url($page)); ?>" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if($end < $last): ?>
            <?php if($end < $last - 1): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
            <a href="<?php echo e($lhpdList->url($last)); ?>" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($last); ?></a>
        <?php endif; ?>

        <?php if($lhpdList->hasMorePages()): ?>
            <a href="<?php echo e($lhpdList->nextPageUrl()); ?>" class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
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
// Variabel global untuk galeri
let currentGalleryImages = [];
let currentImageIndex = 0;

// ========== EXPORT FUNCTION ==========
function exportData() {
    const btn = document.getElementById('btn-export');
    const originalHtml = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    btn.classList.add('btn-loading');
    btn.disabled = true;
    
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value && value !== '') {
            params.append(key, value);
        }
    }
    
    const exportUrl = "<?php echo e(route('lhpd.export')); ?>?" + params.toString();
    window.location.href = exportUrl;
    
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
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
    }, 5000);
});

// ========== DELETE CONFIRMATION FUNCTIONS ==========
let currentDeleteId = null;

function showDeleteConfirmation(id, tujuan, tanggal) {
    currentDeleteId = id;
    document.getElementById('delete-tujuan').textContent = tujuan;
    document.getElementById('delete-tanggal').textContent = `Tanggal Berangkat: ${tanggal}`;
    const form = document.getElementById('delete-form');
    form.action = `/lhpd/${id}`;
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

document.getElementById('delete-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDeleteSuccess(currentDeleteId);
            hideDeleteModal();
            setTimeout(() => { window.location.reload(); }, 2000);
        } else {
            throw new Error(data.message || 'Gagal menghapus data');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat menghapus data: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showDeleteSuccess(id) {
    const notification = document.getElementById('delete-notification');
    const message = document.getElementById('delete-message');
    message.textContent = `Data LHPD berhasil dihapus.`;
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    setTimeout(() => { hideNotification('delete'); }, 5000);
}

// ========== GALERI FOTO FUNCTIONS ==========
async function showGallery(lhpdId) {
    try {
        const response = await fetch(`/lhpd/api/get-fotos/${lhpdId}`);
        const data = await response.json();
        
        if (data.success && data.fotos.length > 0) {
            currentGalleryImages = data.fotos;
            renderGallery();
            showGalleryModal();
        } else {
            alert('Tidak ada foto untuk ditampilkan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal memuat foto');
    }
}

function renderGallery() {
    const container = document.getElementById('gallery-container');
    container.innerHTML = '';
    
    currentGalleryImages.forEach((foto, index) => {
        const fotoItem = document.createElement('div');
        fotoItem.className = 'foto-item';
        fotoItem.innerHTML = `
            <img src="${foto.url}" alt="Foto ${index + 1}" 
                 onclick="openImageViewer(${index})">
        `;
        container.appendChild(fotoItem);
    });
}

function showGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function hideGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ========== IMAGE VIEWER FUNCTIONS ==========
function openImageViewer(index) {
    currentImageIndex = index;
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const counter = document.getElementById('image-counter');
    
    modalImage.src = currentGalleryImages[currentImageIndex].url;
    counter.textContent = `${currentImageIndex + 1} / ${currentGalleryImages.length}`;
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Sembunyikan galeri modal
    hideGalleryModal();
}

function hideImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function prevImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        const modalImage = document.getElementById('modal-image');
        const counter = document.getElementById('image-counter');
        modalImage.src = currentGalleryImages[currentImageIndex].url;
        counter.textContent = `${currentImageIndex + 1} / ${currentGalleryImages.length}`;
    }
}

function nextImage() {
    if (currentImageIndex < currentGalleryImages.length - 1) {
        currentImageIndex++;
        const modalImage = document.getElementById('modal-image');
        const counter = document.getElementById('image-counter');
        modalImage.src = currentGalleryImages[currentImageIndex].url;
        counter.textContent = `${currentImageIndex + 1} / ${currentGalleryImages.length}`;
    }
}

// ========== SINGLE IMAGE PREVIEW (untuk edit/create) ==========
function showImagePreview(imageUrl) {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const counter = document.getElementById('image-counter');
    
    modalImage.src = imageUrl;
    counter.textContent = '1 / 1';
    currentGalleryImages = [{ url: imageUrl }];
    currentImageIndex = 0;
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
        hideImageModal();
        hideGalleryModal();
    }
    
    // Navigasi kiri/kanan untuk image viewer
    if (e.key === 'ArrowLeft' && document.getElementById('image-modal').style.display === 'block') {
        prevImage();
    }
    if (e.key === 'ArrowRight' && document.getElementById('image-modal').style.display === 'block') {
        nextImage();
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('delete-confirm-modal');
    const imageModal = document.getElementById('image-modal');
    const galleryModal = document.getElementById('gallery-modal');
    
    if (event.target === deleteModal) {
        hideDeleteModal();
    }
    if (event.target === imageModal) {
        hideImageModal();
    }
    if (event.target === galleryModal) {
        hideGalleryModal();
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/lhpd.blade.php ENDPATH**/ ?>