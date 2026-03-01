<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>

    {{-- Tailwind CSS v3 (template menggunakan v3) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Google Font: Poppins (sesuai template) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
        /* Scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #e5e7eb;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 9999px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        /* Sidebar transition */
        .sidebar-transition {
            transition: width 0.3s ease;
        }
        /* Menu item active style */
        .menu-item-active {
            @apply bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 border-r-4 border-indigo-500;
        }
        .dark .menu-item-active {
            @apply from-indigo-900/50 to-indigo-800/50 text-indigo-300 border-indigo-400;
        }
        /* Submenu active style */
        .submenu-active {
            @apply bg-indigo-50 text-indigo-700 font-medium;
        }
        .dark .submenu-active {
            @apply bg-indigo-900/30 text-indigo-300;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
      x-data="{
        isMobile: window.innerWidth <= 1024,
        sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' ? true : (window.innerWidth > 1024 ? true : false),
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
      }"
      x-init="
        window.addEventListener('resize', () => { isMobile = window.innerWidth <= 1024; });
        if (isMobile) sidebarOpen = false;
      ">

    {{-- Layout Flex --}}
    <div class="flex h-screen overflow-hidden">

        {{-- ========== SIDEBAR ========== --}}
        <aside class="sidebar-transition bg-gradient-to-b from-indigo-800 to-indigo-900 text-white flex flex-col h-screen sticky top-0 shadow-xl"
               :style="{ width: sidebarOpen ? '280px' : '80px' }">

            {{-- Logo Area with Toggle Button --}}
            <div class="flex items-center justify-between h-20 px-4 border-b border-indigo-700">
                <div class="flex items-center gap-3 overflow-hidden" x-show="sidebarOpen" x-cloak>
                    <img src="{{ asset('image/dpm.png') }}" alt="Logo" class="h-10 w-auto brightness-0 invert">
                    <span class="font-bold text-lg tracking-wide">DPMPTSP</span>
                </div>
                <div x-show="!sidebarOpen" x-cloak class="w-full flex justify-center">
                    <img src="{{ asset('image/dpm.png') }}" alt="Logo" class="h-8 w-auto brightness-0 invert">
                </div>

                {{-- Toggle Button --}}
                <button @click="toggleSidebar"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-700 hover:bg-indigo-600 transition-colors"
                        :class="{ 'rotate-180': !sidebarOpen }">
                    <i class="fas fa-chevron-left text-white text-sm"></i>
                </button>
            </div>

            {{-- User Profile --}}
            <div class="px-4 py-5 border-b border-indigo-700">
                <div class="flex items-center gap-3" :class="{ 'justify-center': !sidebarOpen }">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-pink-500 flex items-center justify-center text-white shadow-lg flex-shrink-0">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden" x-cloak>
                        <div class="font-semibold text-base truncate">{{ session('user')['username'] ?? 'Admin' }}</div>
                        <div class="text-xs text-indigo-200 capitalize truncate mt-0.5">{{ session('user')['level'] ?? 'Admin' }}</div>
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
                <a href="/transaksi"
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

                {{-- Menu: Kwitansi Bidang --}}
                <a href="/kwitansi"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-indigo-700 transition-colors
                          {{ request()->is('kwitansi*') ? 'menu-item-active bg-indigo-700' : '' }}"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-file-invoice w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>Kwitansi Bidang</span>
                </a>
            </nav>

            {{-- Footer Sidebar: Logout --}}
            <div class="p-4 border-t border-indigo-700">
                <a href="/logout"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-indigo-100 hover:bg-red-600 hover:text-white transition-colors"
                   :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fas fa-sign-out-alt w-6 text-xl"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium" x-cloak>Logout</span>
                </a>
            </div>
        </aside>

        {{-- ========== MAIN CONTENT AREA ========== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- TOP NAVBAR --}}
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-6 py-4 flex items-center justify-between">
                    {{-- Page Title with Mobile Toggle --}}
                    <div class="flex items-center gap-3">
                        <button @click="toggleSidebar" class="p-2 rounded-lg hover:bg-gray-100 lg:hidden">
                            <i class="fas fa-bars text-gray-600 text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
                    </div>

                    {{-- Right Icons --}}
                    <div class="flex items-center gap-3">
                        <button class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bell text-gray-600 text-lg"></i>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                        </button>
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-y-auto scrollbar-thin bg-gray-50">
                @hasSection('subtitle')
                <div class="bg-white border-b px-6 py-3">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-indigo-500 mr-2"></i> @yield('subtitle')
                    </p>
                </div>
                @endif

                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- X-cloak style --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Alpine.js Collapse plugin --}}
    <script src="//unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>
