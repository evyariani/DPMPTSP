@extends('layouts.admin')

@section('title', 'Edit Surat Perintah Dinas (SPD)')

@section('content')
<style>
    /* Animasi untuk notifikasi */
    @keyframes slideInFromBottom {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideOutToBottom {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }

    .animate-slide-in-bottom {
        animation: slideInFromBottom 0.3s ease-out forwards;
    }

    .animate-slide-out-bottom {
        animation: slideOutToBottom 0.3s ease-out forwards;
    }

    /* Card styling */
    .info-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .info-card-header {
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
    }

    /* Notifikasi floating */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInFromBottom 0.3s ease-out forwards;
    }

    .notification.hide {
        animation: slideOutToBottom 0.3s ease-out forwards;
    }

    /* Form styling */
    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        ring: 2px solid #3b82f6;
        border-color: transparent;
    }

    .required-field::after {
        content: "*";
        color: #dc2626;
        margin-left: 4px;
    }

    .btn {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: #2563eb;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }

    .btn-secondary {
        background-color: #9ca3af;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #6b7280;
    }

    .readonly-field {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .info-badge-blue {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .info-badge-green {
        background-color: #d1fae5;
        color: #065f46;
    }

    .info-badge-yellow {
        background-color: #fef3c7;
        color: #92400e;
    }
</style>

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit Surat Perintah Dinas (SPD)</h2>
            <p class="text-gray-500">Edit data halaman depan SPD</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('spd.belakang', $spd->id_spd) }}" class="btn-primary btn">
                <i class="fas fa-signature"></i> Halaman Belakang
            </a>
        </div>
    </div>
</div>

<!-- Notifikasi Toast -->
@if(session('success'))
<div id="success-notification" class="notification">
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Berhasil!</p>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="hideNotification('success')" class="ml-4 text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-green-200 rounded-full h-1">
            <div id="success-progress" class="bg-green-500 h-1 rounded-full" style="width: 100%; animation: progressBar 5s linear forwards;"></div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div id="error-notification" class="notification">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Terjadi Kesalahan!</p>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="hideNotification('error')" class="ml-4 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-2 w-full bg-red-200 rounded-full h-1">
            <div id="error-progress" class="bg-red-500 h-1 rounded-full" style="width: 100%; animation: progressBar 5s linear forwards;"></div>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div id="error-notification" class="notification">
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-medium">Validasi Gagal!</p>
                <ul class="text-sm mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="hideNotification('error')" class="ml-4 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Form Edit SPD -->
<form action="{{ route('spd.update', $spd->id_spd) }}" method="POST" id="formEditSpd">
    @csrf
    @method('PUT')

  <!-- CARD 1: NOMOR SURAT (READONLY - DARI SPT) -->
<div class="info-card">
    <div class="info-card-header">
        <h3 class="info-card-title">
            <i class="fas fa-hashtag text-blue-500 mr-2"></i>
            Nomor Surat
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Surat Lengkap
                </label>
                <input type="text" 
                       value="{{ $spd->nomor_surat }}"
                       class="form-input readonly-field bg-gray-100 font-mono"
                       readonly
                       disabled>
                
                <!-- ✅ TAMBAHKAN HIDDEN INPUT UNTUK NOMOR URUT -->
                @php
                    // Extract nomor urut dari nomor surat
                    // Format: 000.1.2.3/{nomor_urut}/DPMPTSP/{tahun}
                    $nomorUrut = null;
                    if ($spd->nomor_surat) {
                        preg_match('/000\.1\.2\.3\/(\d+)\/DPMPTSP\/\d{4}/', $spd->nomor_surat, $matches);
                        if (isset($matches[1])) {
                            $nomorUrut = (int)$matches[1];
                        }
                    }
                @endphp
                <input type="hidden" name="nomor_urut" value="{{ $nomorUrut }}">
                
                @if($spd->spt_id)
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-link mr-1"></i> 
                        Berasal dari SPT: <strong>{{ $spd->spt?->nomor_surat ?? '-' }}</strong>
                        ({{ $spd->spt?->tanggal ? \Carbon\Carbon::parse($spd->spt->tanggal)->format('d/m/Y') : '-' }})
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
    <!-- CARD 2: PROGRAM (HANYA BISA DIPILIH, KODE REKENING OTOMATIS) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-folder-open text-purple-500 mr-2"></i>
                Program & Kode Rekening
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                        Pilih Program
                    </label>
                    <select name="pejabat_teknis_id" 
                            id="pejabat_teknis_id"
                            class="form-input"
                            required>
                        <option value="">Pilih Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id_program }}" 
                                data-program="{{ $program->program }}"
                                data-kegiatan="{{ $program->kegiatan }}"
                                data-sub-kegiatan="{{ $program->sub_kegiatan }}"
                                data-kode-rekening="{{ $program->kode_rekening }}"
                                data-pegawai-id="{{ $program->pegawai?->id_pegawai }}"
                                data-pegawai-nama="{{ $program->pegawai?->nama }}"
                                data-pegawai-nip="{{ $program->pegawai?->nip }}"
                                data-pegawai-jabatan="{{ $program->pegawai?->jabatan }}"
                                {{ old('pejabat_teknis_id', $spd->pejabat_teknis_id) == $program->id_program ? 'selected' : '' }}>
                                {{ $program->program }} - {{ $program->kegiatan }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Pilih program, maka kode rekening akan terisi otomatis</p>
                </div>
            </div>

            <!-- Detail Program (READONLY - OTOMATIS) -->
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Detail Program (Otomatis dari pilihan di atas)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-600">Program:</span>
                        <p class="text-sm font-medium text-gray-800" id="detail_program">{{ $spd->pejabat_teknis_program ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-600">Kegiatan:</span>
                        <p class="text-sm font-medium text-gray-800" id="detail_kegiatan">{{ $spd->pejabat_teknis_kegiatan ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-600">Sub Kegiatan:</span>
                        <p class="text-sm font-medium text-gray-800" id="detail_sub_kegiatan">{{ $spd->pejabat_teknis_sub_kegiatan ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-600">Kode Rekening:</span>
                        <p class="text-sm font-mono font-medium text-gray-800 bg-white p-1 rounded" id="detail_kode_rekening">{{ $spd->pejabat_teknis_kode_rekening ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-xs text-gray-600">Pejabat Teknis:</span>
                        <p class="text-sm font-medium text-gray-800" id="detail_pegawai_nama">{{ $spd->pejabatTeknisPegawai?->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500" id="detail_pegawai_nip">NIP: {{ $spd->pejabatTeknisPegawai?->nip ?? '-' }}</p>
                        <p class="text-xs text-gray-500" id="detail_pegawai_jabatan">Jabatan: {{ $spd->pejabatTeknisPegawai?->jabatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kode Rekening Manual (Optional) -->
            {{-- <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Rekening
                </label>
                <input type="text" 
                       name="kode_rek" 
                       id="kode_rek_manual"
                       value="{{ old('kode_rek', $spd->kode_rek) }}"
                       class="form-input"
                       readonly
                       placeholder="Kosongkan jika sama dengan kode rekening program">
                <p class="mt-1 text-xs text-gray-500">Isi hanya jika kode rekening berbeda dari program yang dipilih</p>
            </div> --}}
        </div>
    </div>

    <!-- CARD 3: PERJALANAN DINAS (BISA DIUBAH) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-plane text-green-500 mr-2"></i>
                Perjalanan Dinas
            </h3>
            {{-- <span class="info-badge info-badge-green ml-3">Dapat diubah</span> --}}
        </div>
        <div class="p-6">
            <!-- Maksud Perjalanan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                    Maksud Perjalanan Dinas
                </label>
                <textarea name="maksud_perjadin" 
                          rows="3"
                          class="form-input"
                          required>{{ old('maksud_perjadin', $spd->maksud_perjadin) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Jelaskan maksud dan tujuan perjalanan dinas secara jelas</p>
            </div>
            
            <!-- Alat Transportasi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                    Alat Transportasi
                </label>
                <select name="alat_transportasi" 
                        class="form-input"
                        required>
                    <option value="">Pilih Alat Transportasi</option>
                    @foreach($alatTransportasiList as $value => $label)
                        <option value="{{ $value }}" {{ old('alat_transportasi', $spd->alat_transportasi) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Tempat Berangkat dan Tujuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                        Tempat Berangkat
                    </label>
                    <input type="text" 
                           name="tempat_berangkat" 
                           value="{{ old('tempat_berangkat', $spd->tempat_berangkat) }}"
                           class="form-input"
                           placeholder="Contoh: Pelaihari"
                           readonly
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                        Tempat Tujuan
                    </label>
                    <select name="tempat_tujuan" 
                            class="form-input"
                            required>
                        <option value="">Pilih Tempat Tujuan</option>
                        @foreach($daerahs as $daerah)
                            <option value="{{ $daerah->id }}" 
                                {{ old('tempat_tujuan', $spd->tempat_tujuan) == $daerah->id ? 'selected' : '' }}>
                                {{ $daerah->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Tanggal Perjalanan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                        Tanggal Berangkat
                    </label>
                    <input type="date" 
                           name="tanggal_berangkat" 
                           id="tanggal_berangkat"
                           value="{{ old('tanggal_berangkat', $spd->tanggal_berangkat ? $spd->tanggal_berangkat->format('Y-m-d') : '') }}"
                           class="form-input"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                        Tanggal Kembali
                    </label>
                    <input type="date" 
                           name="tanggal_kembali" 
                           id="tanggal_kembali"
                           value="{{ old('tanggal_kembali', $spd->tanggal_kembali ? $spd->tanggal_kembali->format('Y-m-d') : '') }}"
                           class="form-input"
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lama Perjalanan (Hari)
                    </label>
                    <input type="number" 
                           name="lama_perjadin" 
                           id="lama_perjadin"
                           value="{{ old('lama_perjadin', $spd->lama_perjadin) }}"
                           class="form-input readonly-field bg-gray-100"
                           readonly>
                    <p class="mt-1 text-xs text-gray-500">Akan dihitung otomatis</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 4: PENGGUNA ANGGARAN & SKPD -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-user-tie text-indigo-500 mr-2"></i>
                Pengguna Anggaran & SKPD
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
    <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
        Pengguna Anggaran (Kepala Dinas)
    </label>
    
    @php
        // Cari data Kepala Dinas dari koleksi $semuaPegawai
        // Sesuaikan kondisi pencarian Kadis dengan database Anda
        $kadis = $semuaPegawai->firstWhere('jabatan', 'Kepala Dinas'); 
        // Atau bisa pakai: $semuaPegawai->where('is_kadis', true)->first();
        
        $idKadis = $kadis->id_pegawai ?? '';
        $namaKadis = $kadis ? $kadis->nama . ' - ' . $kadis->nip . ' (' . $kadis->jabatan . ')' : 'Kepala Dinas tidak ditemukan';
    @endphp
    
    <input type="text" 
           class="form-input bg-gray-100" 
           value="{{ $namaKadis }}"
           readonly
           disabled>
    
    <input type="hidden" name="pengguna_anggaran" value="{{ $idKadis }}">
</div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SKPD
                    </label>
                    <input type="text" 
                           name="skpd" 
                           value="{{ old('skpd', $spd->skpd) }}"
                           class="form-input"
                           readonly
                           placeholder="Contoh: Dinas Penanaman Modal dan PTSP">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Dikeluarkan
                    </label>
                    <input type="text" 
                           name="tempat_dikeluarkan" 
                           value="{{ old('tempat_dikeluarkan', $spd->tempat_dikeluarkan) }}"
                           class="form-input"
                           readonly
                           placeholder="Contoh: Pelaihari">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Dikeluarkan
                    </label>
                    <input type="date" 
                           name="tanggal_dikeluarkan" 
                           value="{{ old('tanggal_dikeluarkan', $spd->tanggal_dikeluarkan ? $spd->tanggal_dikeluarkan->format('Y-m-d') : '') }}"
                           class="form-input"
                           readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 5: PELAKSANA PERJALANAN DINAS (READONLY - DARI SPT) -->
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-users text-teal-500 mr-2"></i>
                Pelaksana Perjalanan Dinas
            </h3>
        </div>
        <div class="p-6">
            @if($spd->spt_id)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                        <p class="text-sm text-blue-700">
                            Pelaksana perjalanan dinas diambil dari <strong>SPT asal</strong> dan tidak dapat diubah di sini.
                            @if($spd->spt_id)
                                <br>Untuk mengubah pelaksana, silakan edit <strong>SPT Nomor: {{ $spd->spt?->nomor_surat ?? '-' }}</strong>.
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">
                @if($selectedPelaksana && count($selectedPelaksana) > 0)
                    <div class="space-y-2">
                        @foreach($semuaPegawai as $pegawai)
                            @if(in_array($pegawai->id_pegawai, $selectedPelaksana))
                                <div class="flex items-center space-x-3 p-2 bg-white rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-teal-100 flex items-center justify-center">
                                        <i class="fas fa-user-check text-teal-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-800">{{ $pegawai->nama }}</span>
                                        <div class="text-xs text-gray-500">
                                            NIP: {{ $pegawai->nip ?? '-' }} | Jabatan: {{ $pegawai->jabatan ?? '-' }}
                                        </div>
                                    </div>
                                    <span class="info-badge info-badge-blue text-xs">dari SPT</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-4">Tidak ada data pelaksana dari SPT</p>
                @endif
            </div>
            
            <!-- Hidden input untuk menyimpan pelaksana dari SPT (tidak bisa diubah) -->
            @foreach($selectedPelaksana as $pelaksanaId)
                <input type="hidden" name="pelaksana_perjadin[]" value="{{ $pelaksanaId }}">
            @endforeach
            
            <p class="mt-3 text-xs text-gray-500 text-center">
                <i class="fas fa-lock mr-1"></i> Pelaksana perjalanan dinas diambil dari SPT dan tidak dapat diubah
            </p>
        </div>
    </div>

    <!-- CARD 6: INFORMASI SPT ASAL -->
    @if($spd->spt_id)
    <div class="info-card">
        <div class="info-card-header">
            <h3 class="info-card-title">
                <i class="fas fa-link text-blue-500 mr-2"></i>
                Informasi SPT Asal
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-500">Nomor SPT</span>
                        <p class="text-sm font-medium text-gray-800">{{ $spd->spt?->nomor_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Tanggal SPT</span>
                        <p class="text-sm font-medium text-gray-800">{{ $spd->spt?->tanggal ? \Carbon\Carbon::parse($spd->spt->tanggal)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-xs text-gray-500">Tujuan SPT</span>
                        <p class="text-sm text-gray-700">{{ $spd->spt?->tujuan ?? '-' }}</p>
                    </div>
                </div>
                <div class="mt-3 text-right">
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- CARD 7: TOMBOL ACTION -->
    <div class="info-card">
        <div class="p-6">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('spd.index') }}" class="btn-secondary btn">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-primary btn">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<style>
@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.required-field::after {
    content: "*";
    color: #dc2626;
    margin-left: 4px;
}

.readonly-field {
    background-color: #f3f4f6;
    cursor: not-allowed;
}
</style>
@endsection

@section('scripts')
<script>
function hideNotification(type) {
    const notification = document.getElementById(`${type}-notification`);
    if (notification) {
        notification.classList.add('animate-slide-out-bottom');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }
}

// Auto hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const successNotif = document.getElementById('success-notification');
        const errorNotif = document.getElementById('error-notification');
        if (successNotif) hideNotification('success');
        if (errorNotif) hideNotification('error');
    }, 5000);
});

// Hitung Lama Perjalanan Otomatis
const tanggalBerangkat = document.getElementById('tanggal_berangkat');
const tanggalKembali = document.getElementById('tanggal_kembali');
const lamaPerjadin = document.getElementById('lama_perjadin');

function hitungLamaPerjadin() {
    if (tanggalBerangkat.value && tanggalKembali.value) {
        const start = new Date(tanggalBerangkat.value);
        const end = new Date(tanggalKembali.value);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        lamaPerjadin.value = diffDays;
    } else {
        lamaPerjadin.value = '';
    }
}

tanggalBerangkat.addEventListener('change', hitungLamaPerjadin);
tanggalKembali.addEventListener('change', hitungLamaPerjadin);

// Load Detail Program saat memilih program
const programSelect = document.getElementById('pejabat_teknis_id');
const kodeRekManual = document.getElementById('kode_rek_manual');

function updateDetailProgram() {
    const selectedOption = programSelect.options[programSelect.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        document.getElementById('detail_program').textContent = selectedOption.dataset.program || '-';
        document.getElementById('detail_kegiatan').textContent = selectedOption.dataset.kegiatan || '-';
        document.getElementById('detail_sub_kegiatan').textContent = selectedOption.dataset.subKegiatan || '-';
        document.getElementById('detail_kode_rekening').textContent = selectedOption.dataset.kodeRekening || '-';
        document.getElementById('detail_pegawai_nama').textContent = selectedOption.dataset.pegawaiNama || '-';
        document.getElementById('detail_pegawai_nip').textContent = selectedOption.dataset.pegawaiNip ? `NIP: ${selectedOption.dataset.pegawaiNip}` : 'NIP: -';
        document.getElementById('detail_pegawai_jabatan').textContent = selectedOption.dataset.pegawaiJabatan ? `Jabatan: ${selectedOption.dataset.pegawaiJabatan}` : 'Jabatan: -';
        
        // Jika kode rekening manual kosong, isi dengan kode rekening dari program
        if (!kodeRekManual.value) {
            kodeRekManual.value = selectedOption.dataset.kodeRekening || '';
        }
    } else {
        document.getElementById('detail_program').textContent = '-';
        document.getElementById('detail_kegiatan').textContent = '-';
        document.getElementById('detail_sub_kegiatan').textContent = '-';
        document.getElementById('detail_kode_rekening').textContent = '-';
        document.getElementById('detail_pegawai_nama').textContent = '-';
        document.getElementById('detail_pegawai_nip').textContent = 'NIP: -';
        document.getElementById('detail_pegawai_jabatan').textContent = 'Jabatan: -';
    }
}

programSelect.addEventListener('change', updateDetailProgram);

// Inisialisasi
hitungLamaPerjadin();
</script>
@endsection