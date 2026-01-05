<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h3 class="text-xl font-bold mb-2">ADMIN PANEL</h3>
                <p class="text-blue-200 text-sm mb-6">Selamat datang, {{ session('user')['username'] ?? 'Admin' }}</p>
                
                <nav class="space-y-1">
                    <a href="/user" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->is('user') ? 'bg-blue-900' : '' }}">
                        <i class="fas fa-users mr-3"></i> Data User
                    </a>
                    <a href="/unit" class="flex items-center py-2 px-4 rounded hover:bg-blue-700 {{ request()->is('unit') ? 'bg-blue-900' : '' }}">
                        <i class="fas fa-building mr-3"></i> Data Unit
                    </a>
                    <a href="/data-meter" class="flex items-center py-2 px-4 rounded hover:bg-blue-700">
                        <i class="fas fa-tachometer-alt mr-3"></i> Data Meter
                    </a>
                    <a href="/material" class="flex items-center py-2 px-4 rounded hover:bg-blue-700">
                        <i class="fas fa-boxes mr-3"></i> Material
                    </a>
                    <!-- Menu lainnya sesuai gambar -->
                </nav>
                
                <div class="pt-6 mt-6 border-t border-blue-700">
                    <a href="/logout" class="flex items-center py-2 px-4 rounded bg-red-600 hover:bg-red-700">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-white border-b px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
            </div>
            
            <!-- Konten -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    @yield('scripts')
</body>
</html>