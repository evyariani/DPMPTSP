<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
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
        .nav-link-active {
            background-color: #f0fdfa;
            color: #0f766e;
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .submenu-active {
            background-color: white;
            color: #0f766e;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #99f6e4;
        }
        /* Warna tema Teal */
        .bg-teal-50 {
            background-color: #f0fdfa;
        }
        .bg-teal-100 {
            background-color: #ccfbf1;
        }
        .text-teal-600 {
            color: #0d9488;
        }
        .text-teal-700 {
            color: #0f766e;
        }
        .text-teal-800 {
            color: #115e59;
        }
        .border-teal-200 {
            border-color: #99f6e4;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ 
    isMobile: window.innerWidth <= 768,
    isProfileOpen: false
}">
    <!-- Mobile Warning -->
    <div x-show="isMobile" class="bg-gradient-to-r from-teal-600 to-emerald-600 min-h-screen text-white flex justify-center items-center text-lg text-center p-20">
        <div>
            <i class="fas fa-desktop text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold mb-2">Halaman Admin</h1>
            <p class="text-lg">Hanya dapat diakses melalui layar desktop</p>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div x-show="!isMobile" class="min-h-screen flex flex-col">
        <!-- TOP NAVBAR - MENU UTAMA -->
        <nav class="bg-gradient-to-r from-white to-teal-50 shadow-lg sticky top-0 z-50 border-b border-teal-100">
            <div class="px-6 py-3">
                <div class="flex items-center justify-between">
                    <!-- LOGO & BRAND - UKURAN DIPERBESAR -->
                    <div class="flex items-center gap-4">
                        <!-- Logo DPMPTSP - UKURAN BESAR -->
                        <div class="flex items-center">
                            <img src="{{ asset('image/dpm.png') }}" 
                                 alt="Logo DPMPTSP" 
                                 class="h-20 w-auto object-contain drop-shadow-md">
                        </div>
                        <div class="border-l border-teal-200 pl-4">
                            <h1 class="text-xl font-bold text-teal-800">DPMPTSP</h1>
                            <p class="text-sm text-teal-700 font-medium">Management System</p>
                        </div>
                    </div>

                    <!-- NAVIGATION MENUS UTAMA -->
                    <div class="flex items-center gap-2">
                        <!-- DATA MASTER -->
                        <a href="/user" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('user*', 'pegawai*', 'transportasi*', 'penyedia*', 'data-stok*', 'plat*', 'rekening*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-database text-teal-600 text-lg"></i>
                            <span class="font-medium">Data Master</span>
                        </a>

                        <!-- SPPD -->
                        <a href="/sppd" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('sppd*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-file-invoice-dollar text-teal-600 text-lg"></i>
                            <span class="font-medium">SPT</span>
                        </a>

                        <!-- TRANSAKSI -->
                        <a href="/transaksi" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('transaksi*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-exchange-alt text-teal-600 text-lg"></i>
                            <span class="font-medium">Spd Depan</span>
                        </a>

                        <!-- LAPORAN -->
                        <a href="/laporan" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('laporan*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-chart-bar text-teal-600 text-lg"></i>
                            <span class="font-medium">Spd Belakang</span>
                        </a>

                        <!-- PENGATURAN -->
                        <a href="/pengaturan" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('pengaturan*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-cog text-teal-600 text-lg"></i>
                            <span class="font-medium">Rincian Bidang</span>
                        </a>

                                                <!-- PENGATURAN -->
                        <a href="/pengaturan" 
                           class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200
                                  {{ request()->is('pengaturan*') ? 'nav-link-active bg-teal-50 text-teal-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700 hover:shadow-sm' }}">
                            <i class="fas fa-cog text-teal-600 text-lg"></i>
                            <span class="font-medium">Kwitansi Bidang</span>
                        </a>
                    </div>

                    <!-- RIGHT SIDE: PROFILE -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <button class="relative p-3 rounded-lg hover:bg-teal-50 transition-colors group">
                            <i class="fas fa-bell text-teal-600 text-xl"></i>
                            <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-red-500 rounded-full border border-white"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" 
                                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-teal-50 transition-all duration-200 group">
                                <div class="text-right">
                                    <div class="font-semibold text-teal-800">{{ session('user')['username'] ?? 'Admin' }}</div>
                                    <div class="text-xs text-teal-700 capitalize font-medium">{{ session('user')['level'] ?? 'Admin' }}</div>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-400 rounded-full flex items-center justify-center shadow-sm group-hover:shadow">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <i class="fas fa-chevron-down text-sm text-teal-600" 
                                   :class="{'rotate-180': open}"></i>
                            </button>
                            
                            <!-- Profile Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg dropdown-shadow py-2 z-50 border border-teal-100">
                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-teal-50">
                                    <div class="font-semibold text-teal-800">{{ session('user')['username'] ?? 'Admin' }}</div>
                                    <div class="text-xs text-teal-700 font-medium">{{ session('user')['level'] ?? 'Admin' }}</div>
                                </div>

                                <!-- Menu Items -->
                                <a href="/profile" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition duration-150">
                                    <i class="fas fa-user-circle mr-3 text-teal-500 w-5 text-center"></i> Profil Saya
                                </a>
                                <a href="/settings" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition duration-150">
                                    <i class="fas fa-cog mr-3 text-teal-500 w-5 text-center"></i> Pengaturan
                                </a>
                                
                                <div class="border-t border-teal-50 my-1"></div>
                                
                                <!-- Logout Button -->
                                <a href="/logout" 
                                   class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition duration-150">
                                    <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUBMENU BAR - TAMPIL DI BAWAH MENU UTAMA -->
            <!-- Submenu Data Master - RATA TENGAH -->
            <div x-show="{{ request()->is('user*', 'pegawai*', 'transportasi*', 'penyedia*', 'data-stok*', 'plat*', 'rekening*') }}"
                 class="bg-gradient-to-r from-teal-50 to-emerald-50 border-t border-teal-100 px-6 py-3">
                <div class="max-w-7xl mx-auto">
                    <div class="flex justify-center items-center">
                        <div class="flex flex-wrap justify-center gap-3">
                            <!-- Data User -->
                            <a href="/user" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('user*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-users text-teal-500"></i> User
                            </a>
                            
                            <!-- Data pegawai -->
                            <a href="/pegawai" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('pegawai*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-building text-teal-500"></i> Pegawai
                            </a>
                            
                            <!-- Data transportasi -->
                            <a href="/transportasi" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('transportasi*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-box text-teal-500"></i> Transportasi
                            </a>
                            
                            <!-- Data Penyedia -->
                            <a href="/penyedia" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('penyedia*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-address-book text-teal-500"></i> Rekening
                            </a>
                            
                            <!-- Data Stok -->
                            <a href="/data-stok" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('data-stok*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-boxes text-teal-500"></i> Program
                            </a>
                            
                            <!-- Plat -->
                            <a href="/plat" 
                               class="px-4 py-2.5 rounded-lg text-sm transition-all duration-200 flex items-center gap-2
                                      {{ request()->is('plat*') ? 'submenu-active bg-white text-teal-700 shadow-sm' : 'text-gray-700 hover:bg-white hover:text-teal-700 hover:shadow-sm border border-transparent hover:border-teal-100' }}">
                                <i class="fas fa-car text-teal-500"></i> Dana
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto">
            <!-- PAGE HEADER -->
            <div class="bg-gradient-to-r from-white to-teal-50 border-b border-teal-100 px-6 py-5 shadow-sm">
                <div class="max-w-7xl mx-auto">
                    {{-- <h1 class="text-2xl font-bold text-teal-800 flex items-center gap-3">
                        <i class="fas @yield('icon', 'fa-cog') text-teal-600"></i>
                        @yield('title')
                    </h1> --}}
                    @hasSection('subtitle')
                        <p class="text-gray-600 mt-2 text-sm bg-white/50 inline-block px-3 py-1.5 rounded-lg border border-teal-100">
                            <i class="fas fa-info-circle text-teal-500 mr-2"></i>@yield('subtitle')
                        </p>
                    @endif
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-6">
                {{-- // NOTIFIKASI DIATAS DIMATIKAN --}}
                {{-- // KARENA SUDAH ADA NOTIFIKASI KUSTOM DI BAWAH DI FILE pegawai.blade.php --}}
                {{-- // JADI HAPUS AJA BLOK INI: --}}
                {{--
                @if(session('success'))
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                --}}
                
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Deteksi mobile
        window.addEventListener('resize', function() {
            Alpine.reactive({ isMobile: window.innerWidth <= 768 });
        });
    </script>
    
    @yield('scripts')
</body>
</html>