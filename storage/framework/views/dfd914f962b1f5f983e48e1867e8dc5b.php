<<<<<<< HEAD:storage/framework/views/dfd914f962b1f5f983e48e1867e8dc5b.php
<?php $__env->startSection('title', 'Surat Perintah Dinas (SPD) - Halaman Depan'); ?>
=======
<?php $__env->startSection('title', 'Surat Perintah Dinas (SPD)'); ?>
>>>>>>> 1f6d5892f27f21c66f85cde1faf6f091fce20555:storage/framework/views/6ac85d76b0af24c0f65cb6eea9f24d83.php

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

/* Badge untuk SPD */
.spd-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    font-family: monospace;
    background-color: #e0e7ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
}

/* Wrapping untuk teks panjang */
.text-wrap-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Fixed width untuk kolom */
.fixed-col-nomor {
    min-width: 180px;
    max-width: 220px;
}

.fixed-col-pengguna {
    min-width: 180px;
    max-width: 220px;
}

.fixed-col-pelaksana {
    min-width: 160px;
    max-width: 200px;
}

.fixed-col-maksud {
    min-width: 250px;
    max-width: 350px;
}

.fixed-col-tanggal {
    min-width: 160px;
    max-width: 200px;
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

/* Line clamp untuk teks panjang */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Surat Perintah Dinas (SPD)</h2>
            <p class="text-gray-500">Kelola data Surat Perintah Dinas - SPD dibuat otomatis dari SPT</p>
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

<?php if(session('info')): ?>
<div id="info-notification" class="fixed bottom-6 right-6 z-50 w-96 animate-slide-in-bottom">
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Informasi!</p>
                <p class="text-sm mt-1"><?php echo e(session('info')); ?></p>
            </div>
            <button type="button" onclick="hideNotification('info')" class="ml-4 text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1">
            <div id="info-progress" class="bg-blue-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="<?php echo e(route('spd.index')); ?>" id="filter-form">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Cari nomor surat, maksud perjadin, SKPD, atau nama pegawai..."
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

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>

                <?php if(request()->hasAny(['search', 'bulan', 'tahun'])): ?>
                    <a href="<?php echo e(route('spd.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                <?php endif; ?>

                <button type="button"
                        onclick="exportData()"
                        id="btn-export"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- ACTIVE FILTERS -->
        <?php if(request()->hasAny(['search', 'bulan', 'tahun'])): ?>
        <div class="mt-4 pt-3 border-t border-gray-200">
            <div class="flex items-center flex-wrap gap-2">
                <span class="text-sm text-gray-600">Filter aktif:</span>
                
                <?php if(request('search')): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-search mr-1"></i> <?php echo e(request('search')); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null])); ?>" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                <?php endif; ?>
                
                <?php if(request('bulan')): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-calendar mr-1"></i> 
                    <?php
                        $bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    ?>
                    <?php echo e($bulanNames[request('bulan')-1] ?? request('bulan')); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['bulan' => null])); ?>" class="ml-2 text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                <?php endif; ?>
                
                <?php if(request('tahun')): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i class="fas fa-calendar-year mr-1"></i> <?php echo e(request('tahun')); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['tahun' => null])); ?>" class="ml-2 text-purple-600 hover:text-purple-800">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel SPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelaksana</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maksud Perjadin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $spds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $spd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <!-- No - menggunakan padding konsisten -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        <?php echo e(($spds->currentPage() - 1) * $spds->perPage() + $index + 1); ?>

                    </td>

                    <!-- Kolom Nomor Surat - px-6 py-4 konsisten -->
                    <td class="px-6 py-4">
                        <span class="spd-badge"><i class="fas fa-file-alt mr-1 text-xs"></i> <?php echo e(Str::limit($spd->nomor_surat, 25)); ?></span>
                        <?php if(strlen($spd->nomor_surat) > 25): ?>
                            <button onclick="showFullText(this, '<?php echo e(addslashes($spd->nomor_surat)); ?>', 'Nomor Surat')" class="text-blue-500 text-xs mt-1 block hover:underline">Lihat selengkapnya</button>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Pengguna Anggaran -->
                    <td class="px-6 py-4">
                        <?php if($spd->penggunaAnggaran): ?>
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-xs">
                                        <?php echo e(strtoupper(substr($spd->penggunaAnggaran->nama, 0, 1))); ?>

                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-gray-900 tooltip" title="<?php echo e($spd->penggunaAnggaran->nama); ?>"><?php echo e(Str::limit($spd->penggunaAnggaran->nama, 20)); ?></div>
                                    <div class="text-xs text-gray-500 tooltip" title="<?php echo e($spd->penggunaAnggaran->jabatan ?? '-'); ?>"><?php echo e(Str::limit($spd->penggunaAnggaran->jabatan ?? '-', 20)); ?></div>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Pelaksana -->
                    <td class="px-6 py-4">
                        <?php
                            $pelaksanaList = [];
                            if ($spd->pelaksana_snapshot && count($spd->pelaksana_snapshot) > 0) {
                                $pelaksanaList = $spd->pelaksana_snapshot;
                            } elseif ($spd->pelaksanaPerjadin && $spd->pelaksanaPerjadin->count() > 0) {
                                foreach ($spd->pelaksanaPerjadin as $p) {
                                    $pelaksanaList[] = ['nama' => $p->nama, 'nip' => $p->nip, 'jabatan' => $p->jabatan];
                                }
                            }
                        ?>
                        <?php if(count($pelaksanaList) > 0): ?>
                            <div class="flex items-center gap-1">
                                <div class="flex -space-x-2">
                                    <?php $__currentLoopData = array_slice($pelaksanaList, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-xs font-medium text-indigo-600 tooltip" 
                                             title="<?php echo e($pel['nama'] ?? ''); ?><?php echo e(isset($pel['nip']) && $pel['nip'] ? ' - ' . $pel['nip'] : ''); ?>">
                                            <?php echo e(strtoupper(substr($pel['nama'] ?? '?', 0, 1))); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <span class="text-xs text-gray-500 ml-1"><?php echo e(count($pelaksanaList)); ?> orang</span>
                                <?php if(count($pelaksanaList) > 3): ?>
                                    <button type="button" onclick="showPelaksanaDetail(<?php echo e(json_encode($pelaksanaList)); ?>)" class="text-blue-500 text-xs hover:underline">detail</button>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Maksud Perjadin -->
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700 line-clamp-2 tooltip" title="<?php echo e($spd->maksud_perjadin); ?>">
                            <?php echo e(Str::limit($spd->maksud_perjadin, 50)); ?>

                        </div>
                        <?php if(strlen($spd->maksud_perjadin) > 50): ?>
                            <button type="button" onclick="showFullText(this, '<?php echo e(addslashes($spd->maksud_perjadin)); ?>', 'Maksud Perjalanan Dinas')" class="text-blue-500 text-xs mt-1 hover:underline">Lihat selengkapnya</button>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Tanggal Perjadin -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($spd->tanggal_berangkat && $spd->tanggal_kembali): ?>
                            <div class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d/m/Y')); ?> → <?php echo e(\Carbon\Carbon::parse($spd->tanggal_kembali)->format('d/m/Y')); ?></div>
                            <div class="text-xs text-blue-600 mt-1"><?php echo e($spd->lama_perjadin); ?> Hari</div>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">-</span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?php echo e(route('spd.edit', $spd->id_spd)); ?>" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-150 tooltip" title="Edit SPD">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit SPD</span>
                            </a>
                            <a href="<?php echo e(route('spd.belakang', $spd->id_spd)); ?>" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition duration-150 tooltip" title="Halaman Belakang">
                                <i class="fas fa-file-alt"></i>
                                <span class="tooltip-text">Halaman Belakang</span>
                            </a>
                            <a href="<?php echo e(route('spd.preview-depan', $spd->id_spd)); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-150 tooltip" title="Preview PDF Depan">
                                <i class="fas fa-print"></i>
                                <span class="tooltip-text">Preview PDF Depan</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                            <p class="text-lg">Tidak ada data SPD</p>
                            <p class="text-sm mt-1">SPD akan dibuat otomatis saat membuat SPT</p>
                            <a href="<?php echo e(route('spt.index')); ?>" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition duration-200">
                                <i class="fas fa-plus mr-2"></i> Buat SPT Baru
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<?php if($spds->count() > 0): ?>
<div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-700">
        Menampilkan 
        <span class="font-medium"><?php echo e($spds->firstItem() ?: 0); ?></span> 
        sampai 
        <span class="font-medium"><?php echo e($spds->lastItem() ?: 0); ?></span> 
        dari 
        <span class="font-medium"><?php echo e($spds->total()); ?></span> 
        Surat Perintah Dinas
    </div>
    
    <div class="flex items-center space-x-1">
        <?php if($spds->onFirstPage()): ?>
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        <?php else: ?>
            <a href="<?php echo e($spds->previousPageUrl()); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        <?php endif; ?>
        
        <?php
            $current = $spds->currentPage();
            $last = $spds->lastPage();
            $start = max($current - 2, 1);
            $end = min($current + 2, $last);
        ?>
        
        <?php if($start > 1): ?>
            <a href="<?php echo e($spds->url(1)); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">1</a>
            <?php if($start > 2): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php for($page = $start; $page <= $end; $page++): ?>
            <?php if($page == $current): ?>
                <span class="px-3 py-1.5 border rounded bg-blue-600 text-white"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($spds->url($page)); ?>" 
                   class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if($end < $last): ?>
            <?php if($end < $last - 1): ?>
                <span class="px-3 py-1.5 text-gray-500">...</span>
            <?php endif; ?>
            <a href="<?php echo e($spds->url($last)); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150"><?php echo e($last); ?></a>
        <?php endif; ?>
        
        <?php if($spds->hasMorePages()): ?>
            <a href="<?php echo e($spds->nextPageUrl()); ?>" 
               class="px-3 py-1.5 border rounded hover:bg-gray-100 transition duration-150">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        <?php else: ?>
            <span class="px-3 py-1.5 border rounded text-gray-400 cursor-not-allowed bg-gray-100">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Modal Detail Pelaksana -->
<div id="pelaksana-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pelaksana</h3>
                    <button type="button" onclick="hidePelaksanaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="pelaksana-list" class="space-y-2 max-h-96 overflow-y-auto"></div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="hidePelaksanaModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Full Text -->
<div id="full-text-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="full-text-title"></h3>
                    <button type="button" onclick="hideFullTextModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-96 overflow-y-auto">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap" id="full-text-content"></pre>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="hideFullTextModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
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
        ['success', 'error', 'info'].forEach(t => hideNotification(t));
    }, 5000);
});

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
        if (value && value !== '') params.append(key, value);
    }
    
    window.location.href = "<?php echo e(route('spd.export')); ?>?" + params.toString();
    
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.remove('btn-loading');
        btn.disabled = false;
    }, 2000);
}

function showFullText(element, text, title) {
    const modal = document.getElementById('full-text-modal');
    document.getElementById('full-text-title').textContent = title;
    document.getElementById('full-text-content').textContent = text;
    modal.classList.remove('hidden');
}

function hideFullTextModal() {
    const modal = document.getElementById('full-text-modal');
    modal.classList.add('hidden');
}

function showPelaksanaDetail(pelaksanaList) {
    const modal = document.getElementById('pelaksana-modal');
    const listContainer = document.getElementById('pelaksana-list');
    listContainer.innerHTML = '';
    
    if (pelaksanaList && pelaksanaList.length > 0) {
        pelaksanaList.forEach(p => {
            const item = document.createElement('div');
            item.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg mb-2';
            item.innerHTML = `
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${escapeHtml(p.nama || '-')}</p>
                    <p class="text-xs text-gray-500">NIP: ${escapeHtml(p.nip || '-')}</p>
                    <p class="text-xs text-gray-500">Jabatan: ${escapeHtml(p.jabatan || '-')}</p>
                </div>
            `;
            listContainer.appendChild(item);
        });
    } else {
        listContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada data pelaksana</p>';
    }
    
    modal.classList.remove('hidden');
}

function hidePelaksanaModal() {
    const modal = document.getElementById('pelaksana-modal');
    modal.classList.add('hidden');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hidePelaksanaModal();
        hideFullTextModal();
    }
});

window.onclick = function(event) {
    const pelaksanaModal = document.getElementById('pelaksana-modal');
    const fullTextModal = document.getElementById('full-text-modal');
    
    if (event.target === pelaksanaModal) hidePelaksanaModal();
    if (event.target === fullTextModal) hideFullTextModal();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/spd.blade.php ENDPATH**/ ?>