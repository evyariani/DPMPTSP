<?php $__env->startSection('title', 'Edit Surat Perintah Tugas (SPT)'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Surat Perintah Tugas</h2>
            <p class="text-gray-500">Ubah data Surat Perintah Tugas</p>
        </div>
        <a href="<?php echo e(route('spt.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Notifikasi -->
<?php if(session('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">Terjadi kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Form Edit SPT -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="<?php echo e(route('spt.update', $spt->id_spt)); ?>" method="POST" id="formSPT">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Grid 2 kolom untuk informasi dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Nomor Surat - INPUT NOMOR URUT SAJA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1">
                                    <input type="number" 
                                           name="nomor_urut" 
                                           id="nomor_urut"
                                           value="<?php echo e(old('nomor_urut', $nomorUrut ?? '')); ?>"
                                           min="1" 
                                           max="999"
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                           placeholder="Masukkan nomor urut (contoh: 1, 2, 3, ...)">
                                </div>
                                <button type="button"
                                        id="btn-auto-nomor"
                                        onclick="getNextNomorUrut()"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-magic mr-1"></i> Auto
                                </button>
                            </div>
                            
                            <!-- Preview Nomor Surat -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-file-alt text-blue-500 mt-0.5"></i>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600 mb-1">Preview format lengkap:</p>
                                        <p class="font-mono text-md font-semibold text-blue-700 break-all" id="preview-nomor-surat">
                                            800.1.11.1/<span id="preview-nomor-urut" class="text-gray-400">___</span>/DPMPTSP/<span id="preview-tahun"><?php echo e(date('Y', strtotime($spt->tanggal))); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="<?php echo e(old('tanggal', $spt->tanggal->format('Y-m-d'))); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal pembuatan surat (tahun akan digunakan untuk format nomor surat)</p>
                    </div>

                    <!-- Lokasi - DEFAULT PELAIHARI DAN DISABLE -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="lokasi" 
                               name="lokasi" 
                               value="Pelaihari"
                               required
                               disabled
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none cursor-not-allowed text-gray-600">
                        <input type="hidden" name="lokasi" value="Pelaihari">
                        <p class="mt-1 text-sm text-gray-500">Lokasi default kantor (Pelaihari)</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Penanda Tangan -->
                    <div>
                        <label for="penanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Penanda Tangan <span class="text-red-500">*</span>
                        </label>
                        <select id="penanda_tangan" 
                                name="penanda_tangan" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Penanda Tangan</option>
                            <?php $__currentLoopData = $penandaTangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pegawai->id_pegawai); ?>" 
                                    <?php echo e(old('penanda_tangan', $spt->penanda_tangan) == $pegawai->id_pegawai ? 'selected' : ''); ?>>
                                    <?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->jabatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih pejabat yang menandatangani surat</p>
                    </div>
                </div>
            </div>

            <!-- Dasar (Dynamic Fields) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dasar <span class="text-red-500">*</span>
                </label>
                <div id="dasar-container" class="space-y-3">
                    <?php
                        $dasarList = old('dasar', is_array($spt->dasar) ? $spt->dasar : (json_decode($spt->dasar ?? '[]', true) ?: ['']));
                        if (!is_array($dasarList)) {
                            $dasarList = ['']; // Fallback jika bukan array
                        }
                    ?>
                    <?php $__currentLoopData = $dasarList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start space-x-2 dasar-item">
                        <div class="flex-grow">
                            <input type="text" 
                                   name="dasar[]" 
                                   value="<?php echo e($value); ?>"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Contoh: Surat dari Sekretariat Daerah Nomor ...">
                        </div>
                        <button type="button" 
                                class="remove-dasar bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                                title="Hapus dasar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" id="tambah-dasar" class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Dasar Lainnya
                </button>
            </div>

            <!-- PEGAWAI YANG DITUGASKAN (Dynamic Fields) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pegawai yang Ditugaskan <span class="text-red-500">*</span>
                </label>
                <div id="pegawai-container" class="space-y-3">
                    <?php
                        $pegawaiList = old('pegawai', is_array($spt->pegawai) ? $spt->pegawai : (json_decode($spt->pegawai ?? '[]', true) ?: ['']));
                        if (!is_array($pegawaiList)) {
                            $pegawaiList = ['']; // Fallback jika bukan array
                        }
                    ?>
                    <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start space-x-2 pegawai-item">
                        <div class="flex-grow">
                            <select name="pegawai[]" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pegawai-select">
                                <option value="">Pilih Pegawai</option>
                                <?php $__currentLoopData = $semuaPegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($pegawai->id_pegawai); ?>" 
                                        data-nama="<?php echo e($pegawai->nama); ?>"
                                        data-nip="<?php echo e($pegawai->nip); ?>"
                                        data-jabatan="<?php echo e($pegawai->jabatan); ?>"
                                        <?php echo e($value == $pegawai->id_pegawai ? 'selected' : ''); ?>>
                                        <?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?> (<?php echo e($pegawai->jabatan); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="button" 
                                class="remove-pegawai bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                                title="Hapus pegawai">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" id="tambah-pegawai" class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pegawai Lainnya
                </button>
                
                <!-- Preview Pegawai yang Dipilih -->
                <div id="selected-pegawai-preview" class="mt-3 flex flex-wrap gap-2"></div>
            </div>

            <!-- Tujuan -->
            <div class="mb-6">
                <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                    Tujuan <span class="text-red-500">*</span>
                </label>
                <textarea id="tujuan" 
                          name="tujuan" 
                          rows="4"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Jelaskan tujuan penugasan..."><?php echo e(old('tujuan', $spt->tujuan)); ?></textarea>
                <p class="mt-1 text-sm text-gray-500">Uraikan maksud dan tujuan pelaksanaan tugas</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?php echo e(route('spt.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update SPT
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// ========== FUNGSI PREVIEW NOMOR SURAT ==========
function updatePreviewNomorSurat() {
    const nomorUrut = document.getElementById('nomor_urut').value;
    const tanggalInput = document.getElementById('tanggal');
    let tahun = new Date().getFullYear();
    
    if (tanggalInput && tanggalInput.value) {
        tahun = new Date(tanggalInput.value).getFullYear();
    }
    
    const previewNomor = document.getElementById('preview-nomor-urut');
    const previewTahun = document.getElementById('preview-tahun');
    
    if (nomorUrut && nomorUrut !== '') {
        previewNomor.textContent = String(nomorUrut).padStart(3, '0');
        previewNomor.className = 'font-bold text-blue-700';
    } else {
        previewNomor.textContent = '___';
        previewNomor.className = 'text-gray-400';
    }
    
    previewTahun.textContent = tahun;
}

// ========== FUNGSI GET NOMOR URUT OTOMATIS ==========
function getNextNomorUrut() {
    const btn = document.getElementById('btn-auto-nomor');
    const originalHtml = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
    btn.disabled = true;
    
    // Ambil tahun dari input tanggal atau gunakan tahun saat ini
    let tahun = new Date().getFullYear();
    const tanggalInput = document.getElementById('tanggal');
    if (tanggalInput && tanggalInput.value) {
        tahun = new Date(tanggalInput.value).getFullYear();
    }
    
    fetch(`<?php echo e(route('spt.api-get-next-nomor-urut')); ?>?tahun=${tahun}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('nomor_urut').value = data.nomor_urut;
                updatePreviewNomorSurat();
                showNotification('success', `Nomor urut otomatis: ${data.nomor_urut}`);
            } else {
                showNotification('error', 'Gagal mendapatkan nomor urut otomatis');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Terjadi kesalahan saat mengambil nomor urut');
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
}

// ========== NOTIFIKASI SEDERHANA ==========
function showNotification(type, message) {
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.className = `fixed bottom-6 right-6 z-50 bg-${type === 'success' ? 'green' : 'red'}-500 text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-y-0 opacity-100`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// ========== DASAR DYNAMIC FIELDS ==========
document.addEventListener('DOMContentLoaded', function() {
    // Focus ke input nomor urut
    document.getElementById('nomor_urut').focus();
    
    // Setup dasar fields
    updateDasarButtons();
    setupPegawaiFields();
    updatePegawaiPreview();
    updatePreviewNomorSurat();
    
    // Event listener untuk update preview saat nomor urut berubah
    document.getElementById('nomor_urut').addEventListener('input', updatePreviewNomorSurat);
    document.getElementById('tanggal').addEventListener('change', updatePreviewNomorSurat);
    
    // Tambah dasar baru
    document.getElementById('tambah-dasar').addEventListener('click', function() {
        const container = document.getElementById('dasar-container');
        const newItem = document.createElement('div');
        newItem.className = 'flex items-start space-x-2 dasar-item';
        newItem.innerHTML = `
            <div class="flex-grow">
                <input type="text" 
                       name="dasar[]" 
                       required
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
    
    // Fungsi untuk update tombol remove dasar
    function updateDasarButtons() {
        const items = document.querySelectorAll('.dasar-item');
        
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-dasar');
            if (items.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
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
});

// ========== PEGAWAI DYNAMIC FIELDS ==========
function setupPegawaiFields() {
    // Tambah pegawai baru
    const tambahBtn = document.getElementById('tambah-pegawai');
    if (tambahBtn) {
        tambahBtn.addEventListener('click', function() {
            const container = document.getElementById('pegawai-container');
            const newItem = document.createElement('div');
            newItem.className = 'flex items-start space-x-2 pegawai-item';
            
            // Clone select options dari item pertama
            const firstSelect = document.querySelector('.pegawai-select');
            let options = '';
            if (firstSelect) {
                options = firstSelect.innerHTML;
            } else {
                // Generate options dari data semuaPegawai
                options = `<option value="">Pilih Pegawai</option>`;
                <?php $__currentLoopData = $semuaPegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    options += `<option value="<?php echo e($pegawai->id_pegawai); ?>" 
                        data-nama="<?php echo e($pegawai->nama); ?>"
                        data-nip="<?php echo e($pegawai->nip); ?>"
                        data-jabatan="<?php echo e($pegawai->jabatan); ?>">
                        <?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?> (<?php echo e($pegawai->jabatan); ?>)
                    </option>`;
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            }
            
            newItem.innerHTML = `
                <div class="flex-grow">
                    <select name="pegawai[]" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pegawai-select">
                        ${options}
                    </select>
                </div>
                <button type="button" 
                        class="remove-pegawai bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded-lg transition duration-200"
                        title="Hapus pegawai">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
            
            // Focus ke select baru
            newItem.querySelector('select').focus();
            
            // Update event listener untuk select baru
            newItem.querySelector('select').addEventListener('change', updatePegawaiPreview);
            newItem.querySelector('select').addEventListener('change', function() {
                disableSelectedPegawaiOptions();
            });
            
            // Update tombol remove
            updatePegawaiButtons();
            disableSelectedPegawaiOptions();
        });
    }
    
    // Update tombol remove pegawai
    updatePegawaiButtons();
    
    // Event listener untuk semua select pegawai
    document.querySelectorAll('.pegawai-select').forEach(select => {
        select.addEventListener('change', updatePegawaiPreview);
        select.addEventListener('change', function() {
            disableSelectedPegawaiOptions();
        });
    });
    
    // Initial disable options
    disableSelectedPegawaiOptions();
}

function updatePegawaiButtons() {
    const items = document.querySelectorAll('.pegawai-item');
    
    items.forEach((item, index) => {
        const removeBtn = item.querySelector('.remove-pegawai');
        if (items.length === 1) {
            removeBtn.style.display = 'none';
        } else {
            removeBtn.style.display = 'block';
        }
    });
    
    // Event listener untuk remove
    document.querySelectorAll('.remove-pegawai').forEach(btn => {
        btn.removeEventListener('click', removePegawai);
        btn.addEventListener('click', removePegawai);
    });
}

function removePegawai(e) {
    const item = e.currentTarget.closest('.pegawai-item');
    const container = document.getElementById('pegawai-container');
    
    if (container.children.length > 1) {
        item.remove();
        updatePegawaiButtons();
        updatePegawaiPreview();
        disableSelectedPegawaiOptions();
    }
}

function updatePegawaiPreview() {
    const preview = document.getElementById('selected-pegawai-preview');
    if (!preview) return;
    
    const selects = document.querySelectorAll('.pegawai-select');
    const selectedData = [];
    
    preview.innerHTML = '';
    
    selects.forEach(select => {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const nama = selectedOption.getAttribute('data-nama') || selectedOption.text.split(' - ')[0];
            const nip = selectedOption.getAttribute('data-nip') || '';
            const jabatan = selectedOption.getAttribute('data-jabatan') || '';
            
            selectedData.push({
                nama: nama,
                nip: nip,
                jabatan: jabatan
            });
            
            const badge = document.createElement('span');
            badge.className = 'bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded flex items-center';
            badge.title = `${nip} - ${jabatan}`;
            badge.innerHTML = `
                <i class="fas fa-user mr-1"></i>
                ${nama.length > 20 ? nama.substring(0, 20) + '...' : nama}
            `;
            preview.appendChild(badge);
        }
    });
    
    // Jika tidak ada pegawai dipilih
    if (selectedData.length === 0) {
        const emptyMsg = document.createElement('span');
        emptyMsg.className = 'text-gray-400 text-sm';
        emptyMsg.innerHTML = '<i class="fas fa-user-slash mr-1"></i> Belum ada pegawai dipilih';
        preview.appendChild(emptyMsg);
    }
}

// Fungsi untuk menonaktifkan option yang sudah dipilih di select lain
function disableSelectedPegawaiOptions() {
    const selects = document.querySelectorAll('.pegawai-select');
    const selectedValues = [];
    
    // Kumpulkan semua nilai yang sudah dipilih
    selects.forEach(select => {
        if (select.value) {
            selectedValues.push(select.value);
        }
    });
    
    // Loop semua select
    selects.forEach(select => {
        // Loop semua option dalam select
        Array.from(select.options).forEach(option => {
            if (option.value) {
                // Jika option ada di selectedValues DAN bukan option yang sedang dipilih di select ini
                if (selectedValues.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            }
        });
    });
}

// ========== VALIDASI FORM ==========
const form = document.getElementById('formSPT');
if (form) {
    form.addEventListener('submit', function(e) {
        // Validasi Nomor Urut
        const nomorUrut = document.getElementById('nomor_urut');
        if (!nomorUrut.value || nomorUrut.value < 1 || nomorUrut.value > 999) {
            alert('Nomor urut surat wajib diisi dengan angka 1-999');
            e.preventDefault();
            nomorUrut.focus();
            return false;
        }
        
        // Validasi Dasar (minimal 1)
        const dasarInputs = document.querySelectorAll('input[name="dasar[]"]');
        let dasarValid = false;
        dasarInputs.forEach(input => {
            if (input.value.trim()) dasarValid = true;
        });
        
        if (!dasarValid) {
            alert('Minimal 1 dasar harus diisi');
            e.preventDefault();
            document.querySelector('input[name="dasar[]"]').focus();
            return false;
        }
        
        // Validasi Pegawai (minimal 1)
        const pegawaiSelects = document.querySelectorAll('select[name="pegawai[]"]');
        let pegawaiValid = false;
        pegawaiSelects.forEach(select => {
            if (select.value) pegawaiValid = true;
        });
        
        if (!pegawaiValid) {
            alert('Minimal 1 pegawai harus dipilih');
            e.preventDefault();
            document.querySelector('select[name="pegawai[]"]').focus();
            return false;
        }
        
        // Validasi Tujuan
        const tujuan = document.getElementById('tujuan');
        if (!tujuan.value.trim()) {
            alert('Tujuan wajib diisi');
            e.preventDefault();
            tujuan.focus();
            return false;
        }
        
        // Validasi Tanggal
        const tanggal = document.getElementById('tanggal');
        if (!tanggal.value) {
            alert('Tanggal wajib diisi');
            e.preventDefault();
            tanggal.focus();
            return false;
        }
        
        // Validasi Penanda Tangan
        const penandaTangan = document.getElementById('penanda_tangan');
        if (!penandaTangan.value) {
            alert('Penanda Tangan wajib dipilih');
            e.preventDefault();
            penandaTangan.focus();
            return false;
        }
        
        return true;
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/spt-edit.blade.php ENDPATH**/ ?>