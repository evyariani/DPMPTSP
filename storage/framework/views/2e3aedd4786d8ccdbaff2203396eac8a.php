<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit User</h2>
            <p class="text-gray-500">Edit informasi user <?php echo e($user->username); ?></p>
        </div>
        <a href="<?php echo e(route('user.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
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

<!-- Form Edit User -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="<?php echo e(route('user.update', $user->id)); ?>" method="POST" id="formUser" autocomplete="off">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Grid 2 kolom untuk informasi user -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username"
                               name="username" 
                               value="<?php echo e(old('username', $user->username)); ?>"
                               required
                               autocomplete="off"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Masukkan username"
                               autofocus>
                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="mt-1 text-sm text-gray-500">Username harus unik dan minimal 3 karakter</p>
                    </div>

                    <!-- Password (Opsional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-gray-500 text-xs">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan password baru (minimal 3 karakter)">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Level -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                            Level User <span class="text-red-500">*</span>
                        </label>
                        
                        <?php
                            $adminExists = \App\Models\User::where('level', 'admin')
                                ->where('id', '!=', $user->id)
                                ->exists();
                            $kadisExists = \App\Models\User::where('level', 'kadis')
                                ->where('id', '!=', $user->id)
                                ->exists();
                        ?>
                        
                        <?php if($user->level == 'pegawai' && $adminExists && $kadisExists): ?>
                            
                            <input type="hidden" name="level" value="pegawai">
                            <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                                Pegawai (Otomatis - Admin & Kadis sudah ada)
                            </div>
                        <?php else: ?>
                            
                            <select id="level" 
                                    name="level" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih Level User</option>
                                
                                <?php if(!$adminExists || $user->level == 'admin'): ?>
                                    <option value="admin" <?php echo e(old('level', $user->level) == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <?php endif; ?>
                                
                                <option value="pegawai" <?php echo e(old('level', $user->level) == 'pegawai' ? 'selected' : ''); ?>>Pegawai</option>
                                
                                <?php if(!$kadisExists || $user->level == 'kadis'): ?>
                                    <option value="kadis" <?php echo e(old('level', $user->level) == 'kadis' ? 'selected' : ''); ?>>Kepala Dinas (Kadis)</option>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                        
                        <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?php echo e(route('user.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Clear password field on page load untuk mencegah cache
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus ke input username
    document.getElementById('username').focus();
    
    // Clear password field
    document.getElementById('password').value = '';
});

// Validasi form sebelum submit
document.getElementById('formUser').addEventListener('submit', function(e) {
    const username = document.getElementById('username');
    
    // Validasi Username
    if (!username.value.trim()) {
        alert('Username wajib diisi!');
        e.preventDefault();
        username.focus();
        return false;
    }
    
    if (username.value.trim().length < 3) {
        alert('Username minimal 3 karakter!');
        e.preventDefault();
        username.focus();
        return false;
    }
    
    return true;
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/user-edit.blade.php ENDPATH**/ ?>