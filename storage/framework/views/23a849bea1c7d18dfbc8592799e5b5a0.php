<?php $__env->startSection('title', 'Tambah Pegawai'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Pegawai Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data pegawai</p>
        </div>
    </div>
</div>

<!-- Notifikasi Error -->
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

<!-- Form Tambah Pegawai -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="<?php echo e(route('pegawai.store')); ?>" method="POST" id="formPegawai">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama" 
                               name="nama" 
                               value="<?php echo e(old('nama')); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Drs. Ahmad Fauzi, M.Si">
                        <p class="mt-1 text-sm text-gray-500">Masukkan nama lengkap dengan gelar (jika ada)</p>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="nip" 
                               name="nip" 
                               value="<?php echo e(old('nip')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: 197501012005011002"
                               maxlength="25">
                        <p class="mt-1 text-sm text-gray-500">Nomor Induk Pegawai (18 digit)</p>
                    </div>

                    <!-- Pangkat -->
                    <div>
                        <label for="pangkat" class="block text-sm font-medium text-gray-700 mb-2">
                            Pangkat <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="pangkat" 
                               name="pangkat" 
                               value="<?php echo e(old('pangkat')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Pembina Utama Muda">
                        <p class="mt-1 text-sm text-gray-500">Pangkat/golongan ruang pegawai</p>
                    </div>

                    <!-- Golongan -->
                    <div>
                        <label for="gol" class="block text-sm font-medium text-gray-700 mb-2">
                            Golongan <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="gol" 
                               name="gol" 
                               value="<?php echo e(old('gol')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: IV/d">
                        <p class="mt-1 text-sm text-gray-500">Golongan ruang (contoh: III/a, IV/b, dll)</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Jabatan dengan Dropdown + Lainnya -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select id="jabatan" 
                                name="jabatan" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Jabatan</option>
                            <?php $__currentLoopData = $jabatanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($jabatan); ?>" <?php echo e(old('jabatan') == $jabatan ? 'selected' : ''); ?>>
                                    <?php echo e($jabatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pilih jabatan, jika tidak ada pilih "Lainnya"</p>
                    </div>

                    <!-- Input Jabatan Lainnya (hidden by default) -->
                    <div id="jabatan_lainnya_container" class="hidden">
                        <label for="jabatan_lainnya" class="block text-sm font-medium text-gray-700 mb-2">
                            Jabatan Lainnya <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="jabatan_lainnya" 
                               name="jabatan_lainnya" 
                               value="<?php echo e(old('jabatan_lainnya')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan jabatan">
                        <p class="mt-1 text-sm text-gray-500">Masukkan jabatan yang tidak ada dalam daftar</p>
                    </div>

                    <!-- TK Jalan -->
                    <div>
                        <label for="tk_jalan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tunjangan Kinerja Jalan <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="tk_jalan" 
                               name="tk_jalan" 
                               value="<?php echo e(old('tk_jalan')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: A, B, C, D, E, atau F">
                        <p class="mt-1 text-sm text-gray-500">Kelas jabatan untuk tunjangan kinerja (A/B/C/D/E/F)</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?php echo e(route('pegawai.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jabatanSelect = document.getElementById('jabatan');
    const jabatanLainnyaContainer = document.getElementById('jabatan_lainnya_container');
    const jabatanLainnyaInput = document.getElementById('jabatan_lainnya');
    
    // Fungsi untuk toggle input jabatan lainnya
    function toggleJabatanLainnya() {
        if (jabatanSelect.value === 'Lainnya') {
            jabatanLainnyaContainer.classList.remove('hidden');
            jabatanLainnyaInput.setAttribute('required', 'required');
            jabatanLainnyaInput.focus();
        } else {
            jabatanLainnyaContainer.classList.add('hidden');
            jabatanLainnyaInput.removeAttribute('required');
            jabatanLainnyaInput.value = '';
        }
    }
    
    // Event listener untuk perubahan dropdown
    jabatanSelect.addEventListener('change', toggleJabatanLainnya);
    
    // Cek saat halaman dimuat (untuk old value)
    toggleJabatanLainnya();
    
    // Validasi form sebelum submit
    document.getElementById('formPegawai').addEventListener('submit', function(e) {
        if (jabatanSelect.value === 'Lainnya' && !jabatanLainnyaInput.value.trim()) {
            alert('Jabatan lainnya harus diisi');
            e.preventDefault();
            jabatanLainnyaInput.focus();
            return false;
        }
        
        if (!jabatanSelect.value) {
            alert('Jabatan harus dipilih');
            e.preventDefault();
            jabatanSelect.focus();
            return false;
        }
        
        return true;
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/create-pegawai.blade.php ENDPATH**/ ?>