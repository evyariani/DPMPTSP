<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Perintah Tugas - DPMPTSP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .verification-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .badge-authentic {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-fake {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-block bg-white rounded-full p-3 shadow-md mb-4">
                <i class="fas fa-shield-alt text-green-600 text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Verifikasi Keaslian Surat</h1>
            <p class="text-gray-600 mt-2">DPMPTSP Kabupaten Tanah Laut</p>
        </div>
        
        <!-- Status Badge -->
        <div class="text-center mb-6">
            @if($isAuthentic)
                <span class="verification-badge badge-authentic">
                    <i class="fas fa-check-circle mr-2"></i> 
                    SURAT ASLI - TERVERIFIKASI
                </span>
            @else
                <span class="verification-badge badge-fake">
                    <i class="fas fa-exclamation-triangle mr-2"></i> 
                    PERINGATAN: DOKUMEN TIDAK ASLI
                </span>
            @endif
        </div>
        
        <!-- Kartu Informasi Surat -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-blue-700 text-white px-6 py-4">
                <h2 class="text-xl font-bold">
                    <i class="fas fa-file-alt mr-2"></i> 
                    Surat Perintah Tugas (SPT)
                </h2>
                <p class="text-blue-100 text-sm mt-1">Nomor: {{ $spt->nomor_surat }}</p>
            </div>
            
            <div class="p-6">
                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal Surat
                        </label>
                        <p class="text-gray-800">{{ $spt->tanggal->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">
                            <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
                        </label>
                        <p class="text-gray-800">{{ $spt->lokasi }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">
                            <i class="fas fa-bullseye mr-1"></i> Tujuan
                        </label>
                        <p class="text-gray-800">{{ $spt->tujuan }}</p>
                    </div>
                </div>
                
                <!-- Dasar -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        <i class="fas fa-gavel mr-1"></i> Dasar
                    </label>
                    <ul class="list-disc list-inside space-y-1 bg-gray-50 p-3 rounded-lg">
                        @foreach($dasarList as $dasar)
                            <li class="text-gray-700 text-sm">{{ $dasar }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Pegawai yang Ditugaskan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        <i class="fas fa-users mr-1"></i> Pegawai yang Ditugaskan
                    </label>
                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                        @foreach($pegawaiList as $pegawai)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-semibold text-sm">
                                        {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $pegawai->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $pegawai->jabatan }} | NIP: {{ $pegawai->nip ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Penanda Tangan -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        <i class="fas fa-pen mr-1"></i> Penanda Tangan
                    </label>
                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold text-lg">
                                {{ strtoupper(substr($spt->penandaTangan->nama ?? '-', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $spt->penandaTangan->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $spt->penandaTangan->jabatan ?? '-' }}</p>
                            <p class="text-xs text-gray-500">NIP: {{ $spt->penandaTangan->nip ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Approval -->
                <div class="border-t pt-4 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Disetujui pada:</span>
                            <span class="text-gray-800 font-medium ml-2">
                                {{ $spt->approved_at ? $spt->approved_at->format('d/m/Y H:i:s') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Diverifikasi pada:</span>
                            <span class="text-gray-800 font-medium ml-2">
                                {{ $spt->verified_at ? $spt->verified_at->format('d/m/Y H:i:s') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah verifikasi:</span>
                            <span class="text-gray-800 font-medium ml-2">
                                {{ $spt->verification_count }} kali
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Kode verifikasi:</span>
                            <span class="text-gray-800 font-mono text-xs ml-2">
                                {{ $spt->verification_code }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Tanda Tangan Digital -->
                @if($spt->ttd_stempel_path)
                <div class="border-t pt-4 mt-4 text-center">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">
                        <i class="fas fa-stamp mr-1"></i> Tanda Tangan Digital & Stempel
                    </label>
                    <img src="{{ asset($spt->ttd_stempel_path) }}" alt="Tanda Tangan Digital" class="mx-auto max-w-xs">
                </div>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="text-center text-sm text-gray-500">
                    <i class="fas fa-lock mr-1"></i> 
                    Dokumen ini diverifikasi secara digital. 
                    Keaslian dokumen dapat dicek melalui QR Code pada surat.
                </div>
            </div>
        </div>
        
        <!-- Tombol Kembali -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>