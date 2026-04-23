<?php $__env->startSection('title', 'Edit Laporan Hasil Perjalanan Dinas (LHPD)'); ?>

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

    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
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

    /* Form styling */
    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        ring: 2px solid #3b82f6;
        border-color: transparent;
    }

    .required-field::after {
        content: "*";
        color: #dc2626;
        margin-left: 4px;
    }

    .btn {
        padding: 0.5rem 1.5rem;
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

    .btn-success {
        background-color: #10b981;
        color: white;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .readonly-field {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
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

    .info-badge-yellow {
        background-color: #fef3c7;
        color: #92400e;
    }

    .info-badge-purple {
        background-color: #e9d5ff;
        color: #6b21a5;
    }

    /* Dropzone styling */
    .dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    /* Foto grid */
    .foto-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .foto-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
    }

    .foto-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        cursor: pointer;
    }

    .foto-item .delete-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background-color: #ef4444;
        color: white;
        border-radius: 9999px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        opacity: 0;
        border: none;
    }

    .foto-item:hover .delete-btn {
        opacity: 1;
    }

    .foto-item .delete-btn:hover {
        background-color: #dc2626;
    }

    .foto-item.marked-delete {
        opacity: 0.5;
        filter: grayscale(100%);
    }

    .foto-item .marked-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0,0,0,0.7);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    /* Progress bar */
    .progress-bar {
        animation: progressBar 5s linear forwards;
    }

    /* Fixed location info */
    .location-info {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Laporan Hasil Perjalanan Dinas (LHPD)</h2>
            <p class="text-gray-500">Edit data Laporan Hasil Perjalanan Dinas</p>
        </div>
        <div>
            <a href="<?php echo e(route('lhpd.index')); ?>" class="btn-secondary btn">
                <i class="fas fa-arrow-left"></i> Kembali
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
            <div class="bg-green-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
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
            <div class="bg-red-500 h-1 rounded-full progress-bar" style="width: 100%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div id="error-notification" class="notification">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Validasi Gagal!</p>
                <ul class="text-sm mt-1 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <button type="button" onclick="hideNotification('error')" class="ml-4 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Form Edit LHPD -->
<form action="<?php echo e(route('lhpd.update', $lhpd->id_lhpd)); ?>" method="POST" enctype="multipart/form-data" id="formEditLhpd">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <!-- CARD 1: INFORMASI PERJALANAN (READONLY - DARI SPT/SPD) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Informasi Perjalanan
            </h3>
            <span class="info-badge info-badge-blue ml-3">Otomatis dari SPT/SPD</span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan / Tujuan
                    </label>
                    <textarea class="form-input readonly-field" rows="2" readonly disabled><?php echo e($lhpd->tujuan); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Berangkat
                    </label>
                    <input type="text" 
                           value="<?php echo e($lhpd->tanggal_berangkat ? \Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d/m/Y') : '-'); ?>"
                           class="form-input readonly-field"
                           readonly disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Daerah Tujuan
                    </label>
                    <input type="text" 
                           value="<?php echo e($lhpd->tempat_tujuan); ?>"
                           class="form-input readonly-field"
                           readonly disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pegawai yang Ditugaskan
                    </label>
                    <div class="bg-gray-50 rounded-lg p-3 max-h-32 overflow-y-auto">
                        <?php
                            $pegawaiList = $lhpd->pegawai_list;
                        ?>
                        <?php if($pegawaiList && $pegawaiList->count() > 0): ?>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($pegawai->nama); ?> (<?php echo e($pegawai->nip ?? '-'); ?>)</li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm">-</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 2: DASAR PERJALANAN (INPUT MANUAL - BISA LEBIH DARI SATU) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-gavel text-yellow-500 mr-2"></i>
                Dasar Perjalanan Dinas
            </h3>
            <span class="info-badge info-badge-yellow ml-3">Input Manual (bisa lebih dari satu)</span>
        </div>
        <div class="p-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Dasar / Landasan Perjalanan <span class="text-red-500">*</span>
            </label>
            <div id="dasar-container" class="space-y-3">
                <?php
                    $dasarList = $lhpd->dasar_list;
                    if ($dasarList->isEmpty()) {
                        $dasarList = collect(['']);
                    }
                ?>
                <?php $__currentLoopData = $dasarList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dasar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start space-x-2 dasar-item">
                    <div class="flex-grow">
                        <input type="text" 
                               name="dasar[]" 
                               value="<?php echo e($dasar); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Surat dari Sekretariat Daerah Nomor ...">
                    </div>
                    <button type="button" 
                            class="remove-dasar bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                            title="Hapus dasar"
                            <?php echo e($loop->first && $dasarList->count() == 1 ? 'disabled style="opacity:0.5;cursor:not-allowed;display:none;"' : ''); ?>>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <button type="button" id="tambah-dasar" class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Dasar Lainnya
            </button>
            <p class="mt-2 text-xs text-gray-500">Isi dasar/landasan perjalanan dinas (bisa lebih dari satu, misal: Surat Tugas, Nota Dinas, Peraturan, dll)</p>
        </div>
    </div>

    <!-- CARD 3: HASIL LHPD (DAPAT DIUBAH) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-file-alt text-green-500 mr-2"></i>
                Hasil Laporan
            </h3>
            <span class="info-badge info-badge-green ml-3">Dapat diubah</span>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                    Hasil Perjalanan Dinas
                </label>
                <textarea name="hasil" 
                          rows="5"
                          class="form-input"
                          required><?php echo e(old('hasil', $lhpd->hasil)); ?></textarea>
                <p class="mt-1 text-xs text-gray-500">Jelaskan hasil yang dicapai selama perjalanan dinas</p>
            </div>
        </div>
    </div>

 <!-- CARD 4: TEMPAT & TANGGAL LHPD -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
            Tempat & Tanggal LHPD
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                    Tempat LHPD Dikeluarkan
                </label>
                <input type="text" 
                       value="Pelaihari" 
                       class="form-input readonly-field"
                       readonly
                       disabled>
                <input type="hidden" name="tempat_dikeluarkan" value="<?php echo e($pelaihariId ?? ''); ?>">
                <?php if(isset($pelaihariId) && $pelaihariId): ?>
                    
                <?php else: ?>
                    <p class="mt-1 text-xs text-yellow-600">
                        <i class="fas fa-exclamation-triangle"></i> Data Pelaihari sedang disinkronkan. Simpan tetap akan berfungsi.
                    </p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                    Tanggal LHPD
                </label>
                <input type="date" 
                       name="tanggal_lhpd" 
                       value="<?php echo e(old('tanggal_lhpd', $lhpd->tanggal_lhpd ? $lhpd->tanggal_lhpd->format('Y-m-d') : date('Y-m-d'))); ?>"
                       class="form-input"
                       required>
            </div>
        </div>
    </div>
</div>

    <!-- CARD 5: DOKUMENTASI FOTO (MULTIPLE) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-images text-pink-500 mr-2"></i>
                Dokumentasi Foto
            </h3>
            <span class="info-badge info-badge-green ml-3">Dapat diubah</span>
        </div>
        <div class="p-6">
            <!-- Foto yang sudah ada -->
            <?php if(isset($existingFotos) && count($existingFotos) > 0): ?>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Foto yang sudah ada (<?php echo e(count($existingFotos)); ?>)
                </label>
                <div class="foto-grid" id="existing-fotos-container">
                    <?php $__currentLoopData = $existingFotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fotoPath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="foto-item" data-path="<?php echo e($fotoPath); ?>">
                        <img src="<?php echo e(Storage::url($fotoPath)); ?>" alt="Foto <?php echo e($index + 1); ?>" 
                             onclick="showImagePreview('<?php echo e(Storage::url($fotoPath)); ?>')"
                             class="cursor-pointer">
                        <button type="button" 
                                class="delete-btn"
                                onclick="markForDelete(this, '<?php echo e($fotoPath); ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <input type="hidden" name="delete_fotos" id="delete-fotos-input" value="">
                <p class="mt-2 text-xs text-gray-500">Klik foto untuk preview, klik tombol hapus untuk menandai foto yang akan dihapus</p>
            </div>
            <?php endif; ?>

            <!-- Upload foto baru -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Foto Baru
                </label>
                <div class="dropzone">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Klik tombol di bawah untuk memilih foto</p>
                    <p class="text-gray-400 text-sm mt-1">Maksimal 10 foto (JPG, PNG, maks 10MB per foto)</p>
                    <input type="file" name="fotos[]" id="fotos-input" class="hidden" multiple accept="image/jpeg,image/png,image/jpg">
                    <button type="button" id="select-foto-btn" 
                            class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-plus"></i> Pilih Foto
                    </button>
                </div>
            </div>

            <!-- Preview foto baru -->
            <div id="new-fotos-preview" class="foto-grid"></div>
        </div>
    </div>

    <!-- CARD 6: TOMBOL ACTION -->
    <div class="info-card">
        <div class="p-6">
            <div class="flex justify-end space-x-3">
                <a href="<?php echo e(route('lhpd.index')); ?>" class="btn-secondary btn">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-primary btn" id="submit-btn">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Modal Preview Foto -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-transparent max-w-4xl mx-auto">
            <button type="button" onclick="hideImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-3xl">
                <i class="fas fa-times"></i>
            </button>
            <img id="modal-image" src="" alt="Preview" class="max-w-full max-h-[90vh] mx-auto rounded-lg shadow-2xl">
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Variabel global
let selectedFiles = [];
let filesToDelete = [];

// ========== DASAR DYNAMIC FIELDS (KONSISTEN DENGAN SPT) ==========
function setupDasarFields() {
    const container = document.getElementById('dasar-container');
    const tambahBtn = document.getElementById('tambah-dasar');
    
    if (!container) return;
    
    // Update tombol remove dasar
    function updateDasarButtons() {
        const items = document.querySelectorAll('.dasar-item');
        
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-dasar');
            if (items.length === 1) {
                if (removeBtn) {
                    removeBtn.style.display = 'none';
                    removeBtn.disabled = true;
                }
            } else {
                if (removeBtn) {
                    removeBtn.style.display = 'block';
                    removeBtn.disabled = false;
                }
            }
        });
        
        // Event listener untuk remove
        document.querySelectorAll('.remove-dasar').forEach(btn => {
            btn.removeEventListener('click', removeDasar);
            btn.addEventListener('click', removeDasar);
        });
    }
    
    function removeDasar(e) {
        const item = e.currentTarget.closest('.dasar-item');
        const container = document.getElementById('dasar-container');
        
        if (container.children.length > 1) {
            item.remove();
            updateDasarButtons();
        }
    }
    
    // Tambah dasar baru
    if (tambahBtn) {
        // Hapus event listener lama jika ada
        const newTambahBtn = tambahBtn.cloneNode(true);
        tambahBtn.parentNode.replaceChild(newTambahBtn, tambahBtn);
        
        newTambahBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const newItem = document.createElement('div');
            newItem.className = 'flex items-start space-x-2 dasar-item';
            newItem.innerHTML = `
                <div class="flex-grow">
                    <input type="text" 
                           name="dasar[]" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: Surat dari Sekretariat Daerah Nomor ...">
                </div>
                <button type="button" 
                        class="remove-dasar bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                        title="Hapus dasar">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
            
            // Focus ke input baru
            newItem.querySelector('input').focus();
            
            // Update tombol remove
            updateDasarButtons();
        });
    }
    
    // Initial update buttons
    updateDasarButtons();
}

// ========== NOTIFICATION FUNCTIONS ==========
function hideNotification(type) {
    const notification = document.getElementById(`${type}-notification`);
    if (notification) {
        notification.classList.add('animate-slide-out-bottom');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }
}

// Auto hide notifications after 5 seconds
setTimeout(() => {
    const successNotif = document.getElementById('success-notification');
    const errorNotif = document.getElementById('error-notification');
    if (successNotif) hideNotification('success');
    if (errorNotif) hideNotification('error');
}, 5000);

// ========== IMAGE PREVIEW ==========
function showImagePreview(imageUrl) {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    modalImage.src = imageUrl;
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function hideImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hideImageModal();
});

window.onclick = function(event) {
    const imageModal = document.getElementById('image-modal');
    if (event.target === imageModal) hideImageModal();
}

// ========== FILE UPLOAD ==========
function initializeFileUpload() {
    const selectBtn = document.getElementById('select-foto-btn');
    const fileInput = document.getElementById('fotos-input');
    const previewContainer = document.getElementById('new-fotos-preview');
    
    if (selectBtn) {
        selectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileInput.click();
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                handleFiles(files);
            }
            // Reset value agar bisa upload file yang sama lagi
            this.value = '';
        });
    }
}

function handleFiles(files) {
    const previewContainer = document.getElementById('new-fotos-preview');
    const maxFiles = 10;
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    // Hitung existing foto yang belum dihapus
    const existingContainer = document.getElementById('existing-fotos-container');
    let existingCount = 0;
    if (existingContainer) {
        existingCount = existingContainer.querySelectorAll('.foto-item:not(.marked-delete)').length;
    }
    
    if (selectedFiles.length + files.length + existingCount > maxFiles) {
        alert(`Maksimal ${maxFiles} foto! Saat ini sudah ada ${existingCount + selectedFiles.length} foto.`);
        return;
    }
    
    files.forEach(file => {
        // Validasi tipe file
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            return;
        }
        
        // Validasi ukuran file
        if (file.size > maxSize) {
            alert(`Ukuran file ${file.name} melebihi 10MB!`);
            return;
        }
        
        // Cek duplikat
        const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (isDuplicate) {
            alert(`File ${file.name} sudah dipilih!`);
            return;
        }
        
        selectedFiles.push(file);
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'foto-item';
            previewDiv.innerHTML = `
                <img src="${e.target.result}" onclick="showImagePreview('${e.target.result}')" style="cursor:pointer; width:100%; height:120px; object-fit:cover;">
                <button type="button" class="delete-btn" onclick="removeNewFile(this, '${file.name}')">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            previewContainer.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    });
}

function removeNewFile(button, fileName) {
    const index = selectedFiles.findIndex(f => f.name === fileName);
    if (index !== -1) {
        selectedFiles.splice(index, 1);
    }
    const fotoCard = button.closest('.foto-item');
    if (fotoCard) fotoCard.remove();
}

// ========== DELETE EXISTING FOTO ==========
function markForDelete(button, fotoPath) {
    const fotoItem = button.closest('.foto-item');
    
    if (fotoItem.classList.contains('marked-delete')) {
        // Batalkan hapus
        const index = filesToDelete.indexOf(fotoPath);
        if (index !== -1) filesToDelete.splice(index, 1);
        fotoItem.classList.remove('marked-delete');
        const badge = fotoItem.querySelector('.marked-badge');
        if (badge) badge.remove();
    } else {
        // Tandai untuk dihapus
        filesToDelete.push(fotoPath);
        fotoItem.classList.add('marked-delete');
        
        // Tambah badge "Akan dihapus"
        const badge = document.createElement('div');
        badge.className = 'marked-badge';
        badge.innerHTML = '<i class="fas fa-trash mr-1"></i> Akan dihapus';
        fotoItem.appendChild(badge);
    }
    
    updateDeleteFotosInput();
}

function updateDeleteFotosInput() {
    const input = document.getElementById('delete-fotos-input');
    if (input) {
        input.value = JSON.stringify(filesToDelete);
    }
}

// ========== FORM SUBMIT ==========
document.getElementById('formEditLhpd')?.addEventListener('submit', function(e) {
    const form = this;
    const formData = new FormData(form);
    
    // Hapus existing fotos[] yang kosong
    formData.delete('fotos[]');
    
    // Append new files
    selectedFiles.forEach((file) => {
        formData.append('fotos[]', file);
    });
    
    // Append delete_fotos as JSON
    formData.append('delete_fotos', JSON.stringify(filesToDelete));
    
    // Tampilkan loading
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    submitBtn.disabled = true;
    
    // Submit form dengan FormData
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && !data.success && data.message) {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
    
    e.preventDefault();
    return false;
});

// ========== INITIALIZE ON PAGE LOAD ==========
document.addEventListener('DOMContentLoaded', function() {
    setupDasarFields();
    initializeFileUpload();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/lhpd-edit.blade.php ENDPATH**/ ?>