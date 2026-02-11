<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50"
      x-data="{ isMobile: window.innerWidth <= 768 }"
      x-init="window.addEventListener('resize', () => isMobile = window.innerWidth <= 768)">

<!-- MOBILE WARNING -->
<div x-show="isMobile"
     class="fixed inset-0 bg-gradient-to-r from-teal-600 to-emerald-600 flex items-center justify-center text-white z-50">
    <div class="text-center p-10">
        <i class="fas fa-desktop text-5xl mb-4"></i>
        <h1 class="text-2xl font-bold">Halaman Admin</h1>
        <p class="mt-2">Hanya dapat diakses melalui layar desktop</p>
    </div>
</div>

<!-- DESKTOP -->
<div x-show="!isMobile" class="flex h-screen overflow-hidden">

    <!-- ============ SIDEBAR ============ -->
    <aside class="w-72 bg-gradient-to-b from-white to-teal-50 border-r border-teal-100 shadow-lg
                  flex flex-col overflow-hidden">

        <!-- LOGO (TIDAK IKUT SCROLL) -->
        <div class="px-6 py-6 flex items-center gap-3 border-b border-teal-100 shrink-0">
            <img src="{{ asset('image/dpm.png') }}" class="h-14">
            <div>
                <h1 class="text-lg font-bold text-teal-800">DPMPTSP</h1>
                <p class="text-sm text-teal-600">Management System</p>
            </div>
        </div>

        <!-- MENU (YANG SCROLL) -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm overflow-y-auto">

            <a href="/user"
               class="flex items-center gap-3 px-4 py-3 rounded-lg
               {{ request()->is('user*','pegawai*','transportasi*','penyedia*','data-stok*','plat*','rekening*')
                    ? 'bg-teal-50 text-teal-700 font-semibold'
                    : 'hover:bg-teal-50' }}">
                <i class="fas fa-database text-teal-500"></i> Data Master
            </a>

            @if(request()->is('user*','pegawai*','transportasi*','penyedia*','data-stok*','plat*','rekening*'))
            <div class="ml-10 space-y-1">
                <a href="/user" class="block px-3 py-2 rounded {{ request()->is('user*') ? 'bg-white shadow text-teal-700 font-semibold' : 'hover:bg-white' }}">User</a>
                <a href="/pegawai" class="block px-3 py-2 rounded {{ request()->is('pegawai*') ? 'bg-white shadow text-teal-700 font-semibold' : 'hover:bg-white' }}">Pegawai</a>
                <a href="/transportasi" class="block px-3 py-2 rounded hover:bg-white">Transportasi</a>
                <a href="/penyedia" class="block px-3 py-2 rounded hover:bg-white">Rekening</a>
                <a href="/data-stok" class="block px-3 py-2 rounded hover:bg-white">Program</a>
                <a href="/plat" class="block px-3 py-2 rounded hover:bg-white">Dana</a>
            </div>
            @endif

            <a href="/sppd" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-teal-50">
                <i class="fas fa-file-invoice text-teal-500"></i> SPT
            </a>

            <a href="/transaksi" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-teal-50">
                <i class="fas fa-exchange-alt text-teal-500"></i> SPD Depan
            </a>

            <a href="/laporan" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-teal-50">
                <i class="fas fa-chart-bar text-teal-500"></i> SPD Belakang
            </a>

        </nav>

        <!-- PROFILE (TETAP DI BAWAH) -->
        <div class="border-t border-teal-100 p-4 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="font-semibold text-teal-800">{{ session('user')['username'] ?? 'Admin' }}</div>
                    <div class="text-xs text-teal-600">{{ session('user')['level'] ?? 'Admin' }}</div>
                </div>
            </div>

            <a href="/logout"
               class="mt-4 flex items-center gap-2 px-3 py-2 rounded text-red-600 hover:bg-red-50">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- HEADER (TIDAK SCROLL) -->
        <div class="bg-white border-b border-teal-100 px-6 py-4 shrink-0">
            <h1 class="text-xl font-bold text-teal-800">@yield('title')</h1>
            @hasSection('subtitle')
                <p class="text-sm text-gray-600 mt-1">@yield('subtitle')</p>
            @endif
        </div>

        <!-- CONTENT (YANG SCROLL) -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

@yield('scripts')

</body>
</html>
