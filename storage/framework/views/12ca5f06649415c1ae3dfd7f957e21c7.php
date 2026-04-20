<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $__env->yieldContent('title'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
        }
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .dropdown-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .sidebar-link-active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -1px rgba(37, 99, 235, 0.1);
        }
        .sidebar-link-active i {
            color: white !important;
        }
        /* Warna tema Biru */
        .bg-blue-50 {
            background-color: #eff6ff;
        }
        .bg-blue-100 {
            background-color: #dbeafe;
        }
        .text-blue-600 {
            color: #2563eb;
        }
        .text-blue-700 {
            color: #1d4ed8;
        }
        .text-blue-800 {
            color: #1e40af;
        }
        .border-blue-200 {
            border-color: #bfdbfe;
        }
        .hover-bg-blue-50:hover {
            background-color: #eff6ff;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ 
    isMobile: window.innerWidth <= 768,
    sidebarOpen: true
}">
    <!-- Mobile Warning -->
    <div x-show="isMobile" class="bg-gradient-to-r from-blue-600 to-blue-700 min-h-screen text-white flex justify-center items-center text-lg text-center p-20">
        <div>
            <i class="fas fa-desktop text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold mb-2">Halaman Admin</h1>
            <p class="text-lg">Hanya dapat diakses melalui layar desktop</p>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div x-show="!isMobile" class="min-h-screen flex">
        <!-- SIDEBAR dengan tema biru -->
        <aside class="w-72 bg-gradient-to-br from-blue-50 via-white to-blue-50 shadow-2xl border-r border-blue-100 flex flex-col h-screen sticky top-0 transition-all duration-300" 
               :class="{'w-20': !sidebarOpen}">
            
            <!-- Logo Area dengan background PUTIH agar logo terlihat jelas -->
            <div class="relative overflow-hidden bg-white border-b border-blue-100">
                <div class="p-4" :class="{'px-3 py-4': !sidebarOpen}">
                    <div class="flex items-center gap-3" :class="{'justify-center': !sidebarOpen}">
                        <img src="<?php echo e(asset('image/dpm.png')); ?>" 
                             alt="Logo DPMPTSP" 
                             class="h-16 w-auto object-contain drop-shadow-md">
                        <div x-show="sidebarOpen" x-transition.opacity.duration.200>
                            <h1 class="text-gray-800 font-bold text-xl tracking-tight">DPMPTSP</h1>
                            <p class="text-gray-500 text-xs font-medium">Management System</p>
                        </div>
                    </div>
                </div>
                
                <!-- Toggle Sidebar Button - DIPERBESAR -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="absolute -right-4 top-8 bg-white border-2 border-blue-300 rounded-full p-2 shadow-lg hover:bg-blue-50 transition-all z-20 hover:scale-110">
                    <i class="fas fa-chevron-left text-blue-600 text-base transition-transform duration-300" 
                       :class="{'rotate-180': !sidebarOpen}"></i>
                </button>
            </div>

            <!-- User Profile in Sidebar - TANPA DROPDOWN -->
            <div class="p-4 border-b border-blue-100" :class="{'px-2': !sidebarOpen}">
                <div class="flex items-center gap-3 rounded-xl p-2" :class="{'justify-center': !sidebarOpen}">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <div class="font-semibold text-gray-800 text-sm truncate"><?php echo e(session('user')['username'] ?? 'Admin'); ?></div>
                        <div class="text-xs text-blue-600 font-medium capitalize"><?php echo e(session('user')['level'] ?? 'Admin'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu dengan tema biru -->
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-4">
                <ul class="space-y-1 px-3">
                    <?php
                        $userLevel = session('user')['level'] ?? 'guest';
                    ?>
                    
                    <!-- MENU UNTUK ADMIN (HANYA DATA MASTER) -->
                    <?php if($userLevel == 'admin'): ?>
                        <!-- User -->
                        <li>
                            <a href="/user" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('user*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-users text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">User</span>
                            </a>
                        </li>
                        
                        <!-- Pegawai -->
                        <li>
                            <a href="/pegawai" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('pegawai*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-building text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Pegawai</span>
                            </a>
                        </li>
                        
                        <!-- Program -->
                        <li>
                            <a href="/program" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('program*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-boxes text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Program</span>
                            </a>
                        </li>
                        
                        <!-- Uang Harian -->
                        <li>
                            <a href="/uang-harian" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('uang-harian*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-money-bill-wave text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Uang Harian</span>
                            </a>
                        </li>
                        
                    <!-- MENU UNTUK PEGAWAI (SPT, SPD, RINCIAN BIDANG, KWITANSI BIDANG, LHPD) -->
                    <?php elseif($userLevel == 'pegawai'): ?>
                        <!-- SPT -->
                        <li>
                            <a href="/spt" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('spt*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-file-invoice-dollar text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">SPT</span>
                                <span x-show="sidebarOpen" class="ml-auto bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Surat</span>
                            </a>
                        </li>

                        <!-- SPD DEPAN -->
                        <li>
                            <a href="/spd" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('spd*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-exchange-alt text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">SPD</span>
                            </a>
                        </li>

                        <!-- RINCIAN BIDANG -->
                        <li>
                            <a href="/rincian" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('rincian*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-cog text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Rincian Bidang</span>
                            </a>
                        </li>

                        <!-- KWITANSI BIDANG -->
                        <li>
                            <a href="/kwitansi" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('kwitansi*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-file-invoice text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Kwitansi Bidang</span>
                            </a>
                        </li>

                        <!-- LHPD (Laporan Hasil Perjalanan Dinas) - MENU TERAKHIR UNTUK PEGAWAI -->
                        <li>
                            <a href="/lhpd" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('lhpd*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-clipboard-list text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">LHPD</span>
                                <span x-show="sidebarOpen" class="ml-auto bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Laporan</span>
                            </a>
                        </li>
                        
                    <!-- MENU UNTUK KADIS (SEMUA MENU) - TANPA LHPD -->
                    <?php elseif($userLevel == 'kadis'): ?>
                        <!-- Data Master Section -->
                        <li class="mb-2">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2" x-show="sidebarOpen">
                                <i class="fas fa-database mr-2"></i> Data Master
                            </div>
                        </li>
                        
                        <!-- User -->
                        <li>
                            <a href="/user" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('user*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-users text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">User</span>
                            </a>
                        </li>
                        
                        <!-- Pegawai -->
                        <li>
                            <a href="/pegawai" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('pegawai*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-building text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Pegawai</span>
                            </a>
                        </li>
                        
                        <!-- Program -->
                        <li>
                            <a href="/program" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('program*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-boxes text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Program</span>
                            </a>
                        </li>
                        
                        <!-- Uang Harian -->
                        <li>
                            <a href="/uang-harian" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('uang-harian*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-money-bill-wave text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Uang Harian</span>
                            </a>
                        </li>
                        
                        <!-- Divider -->
                        <li class="my-3">
                            <div class="border-t border-blue-100" x-show="sidebarOpen"></div>
                        </li>
                        
                        <li class="mb-2">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2" x-show="sidebarOpen">
                                <i class="fas fa-file-alt mr-2"></i> Perjalanan Dinas
                            </div>
                        </li>
                        
                        <!-- SPT -->
                        <li>
                            <a href="/spt" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('spt*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-file-invoice-dollar text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">SPT</span>
                                <span x-show="sidebarOpen" class="ml-auto bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Surat</span>
                            </a>
                        </li>

                        <!-- SPD DEPAN -->
                        <li>
                            <a href="/spd" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('spd*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-exchange-alt text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">SPD Depan</span>
                            </a>
                        </li>

                        <!-- RINCIAN BIDANG -->
                        <li>
                            <a href="/rincian" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('rincian*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-cog text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Rincian Bidang</span>
                            </a>
                        </li>

                        <!-- KWITANSI BIDANG -->
                        <li>
                            <a href="/kwitansi" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      <?php echo e(request()->is('kwitansi*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm'); ?>"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-file-invoice text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Kwitansi Bidang</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Logout Button at Bottom -->
            <div class="p-4 border-t border-blue-100" :class="{'px-2': !sidebarOpen}">
                <a href="/logout" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 group"
                   :class="{'justify-center': !sidebarOpen}">
                    <i class="fas fa-sign-out-alt text-lg w-6"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOP NAVBAR dengan tema biru -->
            <header class="bg-white shadow-md border-b border-blue-100 sticky top-0 z-40">
                <div class="px-6 py-3">
                    <div class="flex items-center justify-between">
                        <!-- Page Title dengan gradien biru -->
                        <h2 class="text-lg font-semibold bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent"><?php echo $__env->yieldContent('title'); ?></h2>

                        <!-- Notifications -->
                        <div class="flex items-center gap-3">
                            <button class="relative p-2 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-bell text-blue-500 text-lg"></i>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white animate-pulse"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <!-- PAGE HEADER (if needed) -->
                <?php if (! empty(trim($__env->yieldContent('subtitle')))): ?>
                <div class="bg-gradient-to-r from-white to-blue-50 border-b border-blue-100 px-6 py-4">
                    <div class="max-w-7xl mx-auto">
                        <p class="text-gray-600 text-sm bg-white/50 inline-block px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i><?php echo $__env->yieldContent('subtitle'); ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- CONTENT -->
                <div class="p-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Deteksi mobile
        window.addEventListener('resize', function() {
            Alpine.reactive({ isMobile: window.innerWidth <= 768 });
        });
    </script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/layouts/admin.blade.php ENDPATH**/ ?>