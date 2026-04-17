<?php $__env->startSection('title', 'Tambah Surat Perintah Dinas (SPD)'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Tambah Surat Perintah Dinas Baru</h2>
            <p class="text-gray-500">Isi formulir untuk menambahkan data SPD baru (Lengkap Depan & Belakang)</p>
        </div>
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

<!-- Form Tambah SPD -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="<?php echo e(route('spd.store')); ?>" method="POST" id="formSPD">
            <?php echo csrf_field(); ?>

            <!-- ============================================================ -->
            <!-- BAGIAN 1: HALAMAN DEPAN SPD                                   -->
            <!-- ============================================================ -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    A. DATA SPD - HALAMAN DEPAN
                </h3>
                <p class="text-sm text-gray-500 mt-1">Informasi utama Surat Perintah Dinas</p>
            </div>

            <!-- Grid 2 kolom untuk informasi dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Nomor Surat -->
                    <div>
                        <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nomor_surat"
                               name="nomor_surat"
                               value="<?php echo e(old('nomor_surat')); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: SPD-001/2024">
                        <p class="mt-1 text-sm text-gray-500">Masukkan nomor surat dengan format yang sesuai</p>
                    </div>

                    <!-- Tanggal Berangkat -->
                    <div>
                        <label for="tanggal_berangkat" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_berangkat"
                               name="tanggal_berangkat"
                               value="<?php echo e(old('tanggal_berangkat', date('Y-m-d'))); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal mulai perjalanan dinas</p>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_kembali"
                               name="tanggal_kembali"
                               value="<?php echo e(old('tanggal_kembali', date('Y-m-d'))); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal selesai perjalanan dinas</p>
                    </div>

                    <!-- Lama Perjadin (Auto Calculate) -->
                    <div>
                        <label for="lama_perjadin" class="block text-sm font-medium text-gray-700 mb-2">
                            Lama Perjalanan Dinas
                        </label>
                        <input type="number"
                               id="lama_perjadin"
                               name="lama_perjadin"
                               value="<?php echo e(old('lama_perjadin')); ?>"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none cursor-not-allowed text-gray-600">
                        <p class="mt-1 text-sm text-gray-500">Otomatis dihitung berdasarkan tanggal berangkat dan kembali</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Pengguna Anggaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pengguna Anggaran <span class="text-red-500">*</span>
                        </label>
                        <?php
                        $kepalaDinas = $pegawais->firstWhere('jabatan', 'Kepala Dinas');
                        if(!$kepalaDinas) {
                            $kepalaDinas = $pegawais->firstWhere('nama', 'like', '%Budi%');
                        }
                        ?>

                        <input type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                            value="<?php echo e($kepalaDinas ? $kepalaDinas->nama . ' - ' . $kepalaDinas->nip . ' (' . $kepalaDinas->jabatan . ')' : 'Tidak ditemukan'); ?>"
                            readonly
                            disabled>

                        <?php if($kepalaDinas): ?>
                        <input type="hidden" name="pengguna_anggaran" value="<?php echo e($kepalaDinas->id_pegawai); ?>">
                        <?php endif; ?>

                        <p class="mt-1 text-sm text-gray-500">Pengguna anggaran ditetapkan kepada Kepala Dinas</p>
                    </div>

                    <!-- Pejabat Pelaksana Teknis (PPTK) -->
                    <div>
                        <label for="pptk_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pejabat Pelaksana Teknis Kegiatan (PPTK) <span class="text-red-500">*</span>
                        </label>
                        <select id="pptk_id"
                                name="pptk_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih PPTK</option>
                            <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pegawai->id_pegawai); ?>" <?php echo e(old('pptk_id') == $pegawai->id_pegawai ? 'selected' : ''); ?>>
                                    <?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?> (<?php echo e($pegawai->jabatan); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Pejabat yang melaksanakan teknis kegiatan</p>
                    </div>

                    <!-- Tempat Dikeluarkan -->
                    <div>
                        <label for="tempat_dikeluarkan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Dikeluarkan
                        </label>
                        <input type="text"
                               id="tempat_dikeluarkan"
                               name="tempat_dikeluarkan"
                               value="<?php echo e(old('tempat_dikeluarkan', 'Pelaihari')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Pelaihari">
                        <p class="mt-1 text-sm text-gray-500">Tempat surat dikeluarkan</p>
                    </div>

                    <!-- Tanggal Dikeluarkan -->
                    <div>
                        <label for="tanggal_dikeluarkan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Dikeluarkan
                        </label>
                        <input type="date"
                               id="tanggal_dikeluarkan"
                               name="tanggal_dikeluarkan"
                               value="<?php echo e(old('tanggal_dikeluarkan', date('Y-m-d'))); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">Tanggal surat dikeluarkan</p>
                    </div>
                </div>
            </div>

            <!-- Tempat Berangkat dan Tempat Tujuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tempat Berangkat -->
                <div>
                    <label for="tempat_berangkat" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Berangkat <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="tempat_berangkat"
                        name="tempat_berangkat"
                        value="<?php echo e(old('tempat_berangkat', 'Pelaihari')); ?>"
                        required
                        readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none cursor-not-allowed text-gray-600">
                    <p class="mt-1 text-sm text-gray-500">Tempat asal keberangkatan (Kantor/Dinas)</p>
                </div>

                <!-- Tempat Tujuan -->
                <div>
                    <label for="tempat_tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select id="tempat_tujuan"
                            name="tempat_tujuan"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">Pilih Tempat Tujuan</option>
                        <?php $__currentLoopData = $daerahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daerah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($daerah->id); ?>"
                                    data-nama="<?php echo e($daerah->nama); ?>"
                                    data-kode="<?php echo e($daerah->kode_daerah ?? ''); ?>"
                                    <?php echo e(old('tempat_tujuan') == $daerah->id ? 'selected' : ''); ?>>
                                <?php echo e($daerah->nama); ?> <?php if($daerah->kode_daerah): ?> (<?php echo e($daerah->kode_daerah); ?>) <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Pilih daerah tujuan perjalanan dinas</p>
                </div>
            </div>

            <!-- Maksud Perjadin -->
            <div class="mb-6">
                <label for="maksud_perjadin" class="block text-sm font-medium text-gray-700 mb-2">
                    Maksud Perjalanan Dinas <span class="text-red-500">*</span>
                </label>
                <textarea id="maksud_perjadin"
                          name="maksud_perjadin"
                          rows="3"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Jelaskan maksud perjalanan dinas..."><?php echo e(old('maksud_perjadin')); ?></textarea>
                <p class="mt-1 text-sm text-gray-500">Uraikan maksud dan tujuan pelaksanaan perjalanan dinas</p>
            </div>

            <!-- Alat Transportasi -->
            <div class="mb-6">
                <label for="alat_transportasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Alat Transportasi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php $__currentLoopData = $alatTransportasiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200">
                        <input type="radio"
                               name="alat_transportasi"
                               value="<?php echo e($key); ?>"
                               <?php echo e(old('alat_transportasi') == $key ? 'checked' : ''); ?>

                               required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            <?php
                                $icon = match($key) {
                                    'transportasi_darat' => 'fa-bus',
                                    'transportasi_udara' => 'fa-plane',
                                    'transportasi_darat_udara' => 'fa-exchange-alt',
                                    'angkutan_darat' => 'fa-truck',
                                    'kendaraan_dinas' => 'fa-car',
                                    'angkutan_umum' => 'fa-taxi',
                                    default => 'fa-road'
                                };
                            ?>
                            <i class="fas <?php echo e($icon); ?> text-gray-500 mr-1"></i>
                            <?php echo e($label); ?>

                        </span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="mt-2 text-sm text-gray-500">Pilih jenis transportasi yang digunakan</p>
            </div>

            <!-- SKPD dan Kode Rekening -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="skpd" class="block text-sm font-medium text-gray-700 mb-2">
                        SKPD
                    </label>
                    <input type="text"
                           id="skpd"
                           name="skpd"
                           value="Dinas Penanaman Modal dan PTSP"
                           readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none cursor-not-allowed text-gray-600">
                    <p class="mt-1 text-sm text-gray-500">Satuan Kerja Perangkat Daerah (Tetap)</p>
                </div>

                <div>
                    <label for="kode_rek" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Rekening
                    </label>
                    <input type="text"
                           id="kode_rek"
                           name="kode_rek"
                           value="<?php echo e(old('kode_rek')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Contoh: 5.2.1.01.01">
                    <p class="mt-1 text-sm text-gray-500">Kode rekening anggaran</p>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- BAGIAN 2: HALAMAN BELAKANG SPD (SESUAI GAMBAR)               -->
            <!-- ============================================================ -->
            <div class="border-b border-gray-200 pb-4 mb-6 mt-8">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-receipt text-green-600 mr-2"></i>
                    B. DATA SPD - HALAMAN BELAKANG
                </h3>
                <p class="text-sm text-gray-500 mt-1">Rincian perjalanan dinas, pengesahan, dan pertanggungjawaban</p>
            </div>

            <!-- I. RINCIAN PERJALANAN DINAS -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-route text-blue-600 mr-2"></i>
                    I. RINCIAN PERJALANAN DINAS
                </h4>

                <!-- Bagian Keberangkatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-2">A. Keberangkatan</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sempat Kedudukan</label>
                                <input type="text" name="sempat_kedudukan" value="Pelaihari" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ke Kota</label>
                                <input type="text" name="ke_kota" id="ke_kota" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pada Tanggal</label>
                                <input type="date" name="tanggal_berangkat_spd" id="tanggal_berangkat_spd"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-2">B. Kembali</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tiba Kota</label>
                                <input type="text" name="tiba_kota" id="tiba_kota" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pada Tanggal</label>
                                <input type="date" name="tanggal_kembali_spd" id="tanggal_kembali_spd"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Berangkat dari Kota</label>
                                <input type="text" name="berangkat_dari_kota" id="berangkat_dari_kota" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dasar Perjalanan Dinas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dasar Perjalanan Dinas</label>
                    <textarea name="dasar_perjalanan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                              placeholder="Sebutkan dasar perjalanan dinas (Surat Perintah, SK, dll)"></textarea>
                </div>
            </div>

            <!-- II. PENGESAHAN -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-stamp text-purple-600 mr-2"></i>
                    II. PENGESAHAN
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pejabat Berwenang -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-3">Pejabat yang Berwenang Mengesahkan</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <select name="pejabat_pengesah_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih Pejabat</option>
                                    <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pegawai->id_pegawai); ?>"><?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" name="nip_pengesah" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <input type="text" name="jabatan_pengesah" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengesahan</label>
                                <input type="date" name="tanggal_pengesahan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Yang Mengesahkan -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-3">Yang Mengesahkan</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="nama_mengesahkan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" name="nip_mengesahkan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <input type="text" name="jabatan_mengesahkan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- III. PERTANGGUNGJAWABAN -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-file-invoice-dollar text-yellow-600 mr-2"></i>
                    III. PERTANGGUNGJAWABAN
                </h4>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat (Real)</label>
                            <input type="date" name="tanggal_berangkat_real" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali (Real)</label>
                            <input type="date" name="tanggal_kembali_real" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pertanggungjawaban</label>
                        <textarea name="catatan_pertanggungjawaban" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                  placeholder="Catatan terkait pertanggungjawaban perjalanan dinas..."></textarea>
                    </div>
                </div>
            </div>

            <!-- IV. PEJABAT PELAKSANA TEKNIS KEGIATAN -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-user-tie text-indigo-600 mr-2"></i>
                    IV. PEJABAT PELAKSANA TEKNIS KEGIATAN
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama PPTK</label>
                                <select name="pptk_spd_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih PPTK</option>
                                    <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pegawai->id_pegawai); ?>"><?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" name="nip_pptk_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <input type="text" name="jabatan_pptk_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal_pptk_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Tempat & Tanggal Dikeluarkan -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dikeluarkan di Tempat</label>
                                <input type="text" name="dikeluarkan_di_tempat" value="Pelaihari" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pada Tanggal</label>
                                <input type="date" name="dikeluarkan_pada_tanggal" value="<?php echo e(date('Y-m-d')); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- V. PENGESAHAN ATAS BEBAN ANGGARAN -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    V. PENGESAHAN ATAS BEBAN ANGGARAN
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKPD</label>
                                <input type="text" name="skpd_spd" value="Dinas Penanaman Modal dan PTSP" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Rekening</label>
                                <input type="text" name="kode_rekening_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Program/Kegiatan</label>
                                <input type="text" name="program_kegiatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beban Anggaran</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-600">Biaya Transport</label>
                                        <input type="number" name="biaya_transport_spd" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600">Biaya Penginapan</label>
                                        <input type="number" name="biaya_penginapan_spd" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600">Uang Harian</label>
                                        <input type="number" name="biaya_harian_spd" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600">Tiket/Retribusi</label>
                                        <input type="number" name="biaya_tiket_spd" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Anggaran</label>
                                <input type="text" name="total_anggaran_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengesahan Bendahara dan PPK -->
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-3">Bendahara Pengeluaran</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <select name="bendahara_spd_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih Bendahara</option>
                                    <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pegawai->id_pegawai); ?>"><?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" name="nip_bendahara_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-800 mb-3">Pejabat Pembuat Komitmen (PPK)</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <select name="ppk_spd_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Pilih PPK</option>
                                    <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pegawai->id_pegawai); ?>"><?php echo e($pegawai->nama); ?> - <?php echo e($pegawai->nip); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" name="nip_ppk_spd" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Lain -->
            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan Lain
                </label>
                <textarea id="keterangan"
                          name="keterangan"
                          rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                          placeholder="Keterangan tambahan (jika ada)..."><?php echo e(old('keterangan')); ?></textarea>
            </div>

            <!-- Preview Informasi -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Ringkasan Informasi SPD
                </h4>
                <div id="info-preview" class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <div><span class="font-medium">Pengguna Anggaran:</span> <span id="preview-pengguna">-</span></div>
                    <div><span class="font-medium">PPTK:</span> <span id="preview-pptK">-</span></div>
                    <div><span class="font-medium">Tempat Tujuan:</span> <span id="preview-tujuan">-</span></div>
                    <div><span class="font-medium">Lama Perjalanan:</span> <span id="preview-lama">-</span></div>
                    <div><span class="font-medium">Transportasi:</span> <span id="preview-transportasi">-</span></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?php echo e(route('spd.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan SPD
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== AUTO CALCULATE LAMA PERJADIN ==========
    function calculateLamaPerjadin() {
        const tglBerangkat = document.getElementById('tanggal_berangkat').value;
        const tglKembali = document.getElementById('tanggal_kembali').value;
        const lamaInput = document.getElementById('lama_perjadin');

        if (tglBerangkat && tglKembali) {
            const start = new Date(tglBerangkat);
            const end = new Date(tglKembali);
            start.setHours(0,0,0,0);
            end.setHours(0,0,0,0);

            if (end >= start) {
                const diffTime = end - start;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                lamaInput.value = diffDays;
                document.getElementById('preview-lama').textContent = diffDays + ' Hari';
            } else {
                lamaInput.value = '';
                document.getElementById('preview-lama').textContent = '-';
                alert('Tanggal kembali harus setelah atau sama dengan tanggal berangkat');
            }
        } else {
            lamaInput.value = '';
            document.getElementById('preview-lama').textContent = '-';
        }
    }

    // ========== AUTO FILL KOTA TUJUAN ==========
    function fillKotaTujuan() {
        const select = document.getElementById('tempat_tujuan');
        const keKotaInput = document.getElementById('ke_kota');
        const tibaKotaInput = document.getElementById('tiba_kota');
        const berangkatDariKotaInput = document.getElementById('berangkat_dari_kota');

        if (select && keKotaInput) {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const namaKota = selectedOption.getAttribute('data-nama') || selectedOption.text;
                keKotaInput.value = namaKota;
                if (tibaKotaInput) tibaKotaInput.value = namaKota;
                if (berangkatDariKotaInput) berangkatDariKotaInput.value = namaKota;
            } else {
                keKotaInput.value = '';
                if (tibaKotaInput) tibaKotaInput.value = '';
                if (berangkatDariKotaInput) berangkatDariKotaInput.value = '';
            }
        }
    }

    // ========== AUTO FILL TANGGAL SPD ==========
    function fillTanggalSPD() {
        const tglBerangkat = document.getElementById('tanggal_berangkat');
        const tglKembali = document.getElementById('tanggal_kembali');
        const tglBerangkatSPD = document.getElementById('tanggal_berangkat_spd');
        const tglKembaliSPD = document.getElementById('tanggal_kembali_spd');

        if (tglBerangkat && tglBerangkatSPD) {
            tglBerangkatSPD.value = tglBerangkat.value;
        }
        if (tglKembali && tglKembaliSPD) {
            tglKembaliSPD.value = tglKembali.value;
        }
    }

    // ========== AUTO FILL PEGAWAI DATA ==========
    function setupPegawaiAutoFill(selectId, namaTargetId, nipTargetId, jabatanTargetId) {
        const select = document.getElementById(selectId);
        const namaTarget = document.getElementById(namaTargetId);
        const nipTarget = document.getElementById(nipTargetId);
        const jabatanTarget = document.getElementById(jabatanTargetId);

        if (!select) return;

        // Store pegawai data
        const pegawaiData = {};
        <?php $__currentLoopData = $pegawais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            pegawaiData[<?php echo e($pegawai->id_pegawai); ?>] = {
                nama: '<?php echo e(addslashes($pegawai->nama)); ?>',
                nip: '<?php echo e($pegawai->nip); ?>',
                jabatan: '<?php echo e(addslashes($pegawai->jabatan)); ?>'
            };
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        select.addEventListener('change', function() {
            const id = this.value;
            if (id && pegawaiData[id]) {
                if (namaTarget) namaTarget.value = pegawaiData[id].nama;
                if (nipTarget) nipTarget.value = pegawaiData[id].nip;
                if (jabatanTarget) jabatanTarget.value = pegawaiData[id].jabatan;
            } else {
                if (namaTarget) namaTarget.value = '';
                if (nipTarget) nipTarget.value = '';
                if (jabatanTarget) jabatanTarget.value = '';
            }
        });
    }

    // ========== HITUNG TOTAL ANGGARAN ==========
    function calculateTotalAnggaran() {
        const transport = parseFloat(document.querySelector('input[name="biaya_transport_spd"]')?.value || 0);
        const penginapan = parseFloat(document.querySelector('input[name="biaya_penginapan_spd"]')?.value || 0);
        const harian = parseFloat(document.querySelector('input[name="biaya_harian_spd"]')?.value || 0);
        const tiket = parseFloat(document.querySelector('input[name="biaya_tiket_spd"]')?.value || 0);

        const total = transport + penginapan + harian + tiket;
        const totalInput = document.querySelector('input[name="total_anggaran_spd"]');

        if (totalInput) {
            totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
        }
    }

    // ========== PREVIEW FUNCTIONS ==========
    function updatePreviewTempatTujuan() {
        const select = document.getElementById('tempat_tujuan');
        const preview = document.getElementById('preview-tujuan');
        if (select && preview) {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const nama = selectedOption.getAttribute('data-nama') || selectedOption.text;
                preview.textContent = nama;
            } else {
                preview.textContent = '-';
            }
        }
    }

    function updatePreviewTransportasi() {
        const radios = document.querySelectorAll('input[name="alat_transportasi"]');
        const preview = document.getElementById('preview-transportasi');
        if (!preview) return;

        let selectedLabel = '';
        for (const radio of radios) {
            if (radio.checked) {
                const labelSpan = radio.nextElementSibling;
                if (labelSpan) {
                    selectedLabel = labelSpan.innerText.trim();
                }
                break;
            }
        }
        preview.textContent = selectedLabel || '-';
    }

    function updatePreviewPengguna() {
        const preview = document.getElementById('preview-pengguna');
        if (preview) {
            <?php
                $kadis = $pegawais->firstWhere('jabatan', 'Kepala Dinas');
                if(!$kadis) $kadis = $pegawais->firstWhere('nama', 'like', '%Budi%');
            ?>
            preview.textContent = '<?php echo e($kadis ? $kadis->nama : "-"); ?>';
        }
    }

    function updatePreviewPPTK() {
        const select = document.getElementById('pptk_id');
        const preview = document.getElementById('preview-pptK');
        if (select && preview) {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                preview.textContent = selectedOption.text.split(' - ')[0];
            } else {
                preview.textContent = '-';
            }
        }
    }

    // ========== INITIALIZE ==========
    const tglBerangkat = document.getElementById('tanggal_berangkat');
    const tglKembali = document.getElementById('tanggal_kembali');
    if (tglBerangkat) tglBerangkat.addEventListener('change', function() {
        calculateLamaPerjadin();
        fillTanggalSPD();
    });
    if (tglKembali) tglKembali.addEventListener('change', function() {
        calculateLamaPerjadin();
        fillTanggalSPD();
    });

    const tempatTujuanSelect = document.getElementById('tempat_tujuan');
    if (tempatTujuanSelect) {
        tempatTujuanSelect.addEventListener('change', function() {
            updatePreviewTempatTujuan();
            fillKotaTujuan();
        });
        updatePreviewTempatTujuan();
        fillKotaTujuan();
    }

    const transportRadios = document.querySelectorAll('input[name="alat_transportasi"]');
    transportRadios.forEach(radio => {
        radio.addEventListener('change', updatePreviewTransportasi);
    });
    updatePreviewTransportasi();
    updatePreviewPengguna();

    const pptkSelect = document.getElementById('pptk_id');
    if (pptkSelect) {
        pptkSelect.addEventListener('change', updatePreviewPPTK);
        updatePreviewPPTK();
    }

    calculateLamaPerjadin();
    fillTanggalSPD();

    // Setup auto fill for pegawai selects
    setupPegawaiAutoFill('pejabat_pengesah_id', 'nama_mengesahkan', 'nip_mengesahkan', 'jabatan_mengesahkan');
    setupPegawaiAutoFill('pptk_spd_id', null, 'nip_pptk_spd', 'jabatan_pptk_spd');
    setupPegawaiAutoFill('bendahara_spd_id', null, 'nip_bendahara_spd', null);
    setupPegawaiAutoFill('ppk_spd_id', null, 'nip_ppk_spd', null);

    // Setup total anggaran calculation
    const anggaranInputs = ['biaya_transport_spd', 'biaya_penginapan_spd', 'biaya_harian_spd', 'biaya_tiket_spd'];
    anggaranInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', calculateTotalAnggaran);
            input.addEventListener('change', calculateTotalAnggaran);
        }
    });
    calculateTotalAnggaran();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL POLITALA\dpmptsp\resources\views/admin/spd-create.blade.php ENDPATH**/ ?>