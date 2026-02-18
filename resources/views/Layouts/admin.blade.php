<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            background-color: #f0fdfa;
            color: #0f766e;
            border-left: 4px solid #0d9488;
            font-weight: 600;
        }
        .submenu-active {
            background-color: #ccfbf1;
            color: #0f766e;
            font-weight: 600;
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
    isProfileOpen: false,
    sidebarOpen: true
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
    <div x-show="!isMobile" class="min-h-screen flex">
        <!-- SIDEBAR -->
        <aside class="w-72 bg-gradient-to-b from-white to-teal-50 shadow-lg border-r border-teal-100 flex flex-col h-screen sticky top-0"
               :class="{'w-20': !sidebarOpen}">

            <!-- Logo Area - Collapsible -->
            <div class="p-4 border-b border-teal-100" :class="{'p-2': !sidebarOpen}">
                <div class="flex items-center gap-3" :class="{'justify-center': !sidebarOpen}">
                    <img src="{{ asset('image/dpm.png') }}"
                         alt="Logo DPMPTSP"
                         class="h-12 w-auto object-contain drop-shadow-md">
                    <div x-show="sidebarOpen" class="overflow-hidden transition-all">
                        <h1 class="text-lg font-bold text-teal-800 leading-tight">DPMPTSP</h1>
                        <p class="text-xs text-teal-700 font-medium">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Toggle Sidebar Button -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="absolute -right-3 top-20 bg-white border border-teal-200 rounded-full p-1.5 shadow-md hover:bg-teal-50 transition-colors z-10">
                <i class="fas fa-chevron-left text-teal-600 text-sm"
                   :class="{'rotate-180': !sidebarOpen}"></i>
            </button>

            <!-- User Profile in Sidebar -->
            <div class="p-4 border-b border-teal-100" :class="{'p-2': !sidebarOpen}">
                <div class="flex items-center gap-3" :class="{'justify-center': !sidebarOpen}">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-emerald-400 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <div class="font-semibold text-teal-800 text-sm truncate">{{ session('user')['username'] ?? 'Admin' }}</div>
                        <div class="text-xs text-teal-700 capitalize">{{ session('user')['level'] ?? 'Admin' }}</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-4">
                <ul class="space-y-1 px-3">
                    <!-- DATA MASTER -->
                    <li>
                        <a href="/user"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('user*', 'pegawai*', 'program*', 'uang-harian*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-database text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Data Master</span>
                        </a>

                        <!-- Submenu Data Master -->
                        <div x-show="sidebarOpen && {{ request()->is('user*', 'pegawai*', 'program*', 'uang-harian*') ? 1 : 0 }}"
                             class="ml-9 mt-1 space-y-1">
                            <a href="/user"
                               class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->is('user*') ? 'submenu-active' : 'text-gray-600 hover:bg-teal-50' }}">
                                <i class="fas fa-users text-teal-500 mr-2 w-4"></i> User
                            </a>
                            <a href="/pegawai"
                               class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->is('pegawai*') ? 'submenu-active' : 'text-gray-600 hover:bg-teal-50' }}">
                                <i class="fas fa-building text-teal-500 mr-2 w-4"></i> Pegawai
                            </a>
                            <a href="/program"
                               class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->is('program*') ? 'submenu-active' : 'text-gray-600 hover:bg-teal-50' }}">
                                <i class="fas fa-boxes text-teal-500 mr-2 w-4"></i> Program
                            </a>
                            <a href="/uang-harian"
                               class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->is('uang-harian*') ? 'submenu-active' : 'text-gray-600 hover:bg-teal-50' }}">
                                <i class="fas fa-money-bill-wave text-teal-500 mr-2 w-4"></i> Uang Harian
                            </a>
                        </div>
                    </li>

                    <!-- SPT -->
                    <li>
                        <a href="/spt"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('spt*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-file-invoice-dollar text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">SPT</span>
                        </a>
                    </li>

                    <!-- SPD DEPAN -->
                    <li>
                        <a href="/transaksi"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('transaksi*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-exchange-alt text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Spd Depan</span>
                        </a>
                    </li>

                    <!-- SPD BELAKANG -->
                    <li>
                        <a href="/laporan"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('laporan*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-chart-bar text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Spd Belakang</span>
                        </a>
                    </li>

                    <!-- RINCIAN BIDANG -->
                    <li>
                        <a href="/pengaturan"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('pengaturan*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-cog text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Rincian Bidang</span>
                        </a>
                    </li>

                    <!-- KWITANSI BIDANG -->
                    <li>
                        <a href="/kwitansi"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                                  {{ request()->is('kwitansi*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}"
                           :class="{'justify-center': !sidebarOpen}">
                            <i class="fas fa-file-invoice text-teal-600 text-lg w-6"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Kwitansi Bidang</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout Button at Bottom -->
            <div class="p-4 border-t border-teal-100" :class="{'p-2': !sidebarOpen}">
                <a href="/logout"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200"
                   :class="{'justify-center': !sidebarOpen}">
                    <i class="fas fa-sign-out-alt text-lg w-6"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOP NAVBAR (simplified) -->
            <header class="bg-white shadow-sm border-b border-teal-100 sticky top-0 z-40">
                <div class="px-6 py-3">
                    <div class="flex items-center justify-between">
                        <!-- Page Title -->
                        <h2 class="text-lg font-semibold text-teal-800">@yield('title')</h2>

                        <!-- Notifications -->
                        <div class="flex items-center gap-3">
                            <button class="relative p-2 rounded-lg hover:bg-teal-50 transition-colors">
                                <i class="fas fa-bell text-teal-600 text-lg"></i>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <!-- PAGE HEADER (if needed) -->
                @hasSection('subtitle')
                <div class="bg-gradient-to-r from-white to-teal-50 border-b border-teal-100 px-6 py-4">
                    <div class="max-w-7xl mx-auto">
                        <p class="text-gray-600 text-sm bg-white/50 inline-block px-3 py-1.5 rounded-lg border border-teal-100">
                            <i class="fas fa-info-circle text-teal-500 mr-2"></i>@yield('subtitle')
                        </p>
                    </div>
                </div>
                @endif

                <!-- CONTENT -->
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</div>

@yield('scripts')

</body>
</html>
