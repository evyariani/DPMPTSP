<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Gagal - DPMPTSP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center">
            <div class="bg-red-600 p-6">
                <i class="fas fa-exclamation-triangle text-white text-5xl"></i>
            </div>
            <div class="p-6">
                <h1 class="text-2xl font-bold text-red-600 mb-4">Verifikasi Gagal</h1>
                <p class="text-gray-600 mb-6">{{ $message ?? 'Kode verifikasi tidak valid atau surat tidak ditemukan.' }}</p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Jika Anda menerima surat ini, harap segera melaporkan ke pihak DPMPTSP Kabupaten Tanah Laut untuk verifikasi lebih lanjut.
                    </p>
                </div>
                <a href="{{ url('/') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>