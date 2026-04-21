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
                        <img src="{{ asset('image/dpm.png') }}"
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
                        <div class="font-semibold text-gray-800 text-sm truncate">{{ session('user')['username'] ?? 'Admin' }}</div>
                        <div class="text-xs text-blue-600 font-medium capitalize">{{ session('user')['level'] ?? 'Admin' }}</div>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-5 px-3 space-y-1">

                {{-- Menu: Dashboard (contoh) --}}
                {{-- <a href="/dashboard"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors group
                          {{ request()->is('dashboard') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-tachometer-alt w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>Dashboard</span>
                </a> --}}

                {{-- Menu: Data Master (dengan submenu) --}}
                <div x-data="{ open: {{ request()->is('user*', 'pegawai*', 'program*', 'uang-harian*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors"
                            :class="{ 'justify-center': !sidebarOpen }">
                        <i class="fas fa-database w-6 text-xl"></i>
                        <span x-show="sidebarOpen" class="text-sm font-medium flex-1 text-left" x-cloak>Data Master</span>
                        <i x-show="sidebarOpen" class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }" x-cloak></i>
                    </button>

                    {{-- Submenu --}}
                    <div x-show="sidebarOpen && open" x-collapse class="mt-1 ml-11 space-y-1" x-cloak>
                        <a href="/user"
                           class="block px-4 py-2 rounded-lg text-sm {{ request()->is('user*') ? 'submenu-active' : 'text-indigo-200 hover:bg-indigo-700' }}">
                            <i class="fas fa-users mr-2 w-4"></i> User
                        </a>
                        <a href="/pegawai"
                           class="block px-4 py-2 rounded-lg text-sm {{ request()->is('pegawai*') ? 'submenu-active' : 'text-indigo-200 hover:bg-indigo-700' }}">
                            <i class="fas fa-building mr-2 w-4"></i> Pegawai
                        </a>
                        <a href="/program"
                           class="block px-4 py-2 rounded-lg text-sm {{ request()->is('program*') ? 'submenu-active' : 'text-indigo-200 hover:bg-indigo-700' }}">
                            <i class="fas fa-boxes mr-2 w-4"></i> Program
                        </a>
                        <a href="/uang-harian"
                           class="block px-4 py-2 rounded-lg text-sm {{ request()->is('uang-harian*') ? 'submenu-active' : 'text-indigo-200 hover:bg-indigo-700' }}">
                            <i class="fas fa-money-bill-wave mr-2 w-4"></i> Uang Harian
                        </a>
                    </div>
                </div>

                {{-- Menu: SPT --}}
                <a href="/spt"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors
                          {{ request()->is('spt*') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-file-invoice-dollar w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>SPT</span>
                </a>

                {{-- Menu: SPD Depan --}}
                <a href="/spd"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors
                          {{ request()->is('transaksi*') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-exchange-alt w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>SPD Depan</span>
                </a>

                {{-- Menu: SPD Belakang --}}
                <a href="/laporan"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors
                          {{ request()->is('laporan*') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-chart-bar w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>SPD Belakang</span>
                </a>

                {{-- Menu: Rincian Bidang --}}
                <a href="/pengaturan"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors
                          {{ request()->is('pengaturan*') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-cog w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>Rincian Bidang</span>
                </a>

                        <!-- KWITANSI BIDANG -->
                        <li>
                            <a href="/kwitansi"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                                      {{ request()->is('kwitansi*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm' }}"
                               :class="{'justify-center': !sidebarOpen}">
                                <i class="fas fa-file-invoice text-blue-500 text-lg w-6 group-hover:text-blue-600 transition-colors"></i>
                                <span x-show="sidebarOpen" class="text-sm font-medium">Kwitansi Bidang</span>
                            </a>
                        </li>
                    @endif
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
                        <h2 class="text-lg font-semibold bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">@yield('title')</h2>

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
                @hasSection('subtitle')
                <div class="bg-gradient-to-r from-white to-blue-50 border-b border-blue-100 px-6 py-4">
                    <div class="max-w-7xl mx-auto">
                        <p class="text-gray-600 text-sm bg-white/50 inline-block px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>@yield('subtitle')
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

    <script>
        // Deteksi mobile
        window.addEventListener('resize', function() {
            Alpine.reactive({ isMobile: window.innerWidth <= 768 });
        });
    </script>

    @yield('scripts')
</body>
</html>
