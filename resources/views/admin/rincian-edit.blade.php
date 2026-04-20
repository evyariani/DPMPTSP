@extends('layouts.admin')

@section('title', 'Edit Rincian Biaya Perjalanan Dinas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Edit Rincian Biaya</h2>
<<<<<<< HEAD
            <p class="text-sm text-gray-500 mt-1">Ubah form rincian biaya perjalanan dinas</p>
        </div>
        <a href="{{ route('rincian.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
=======
            <p class="text-sm text-gray-500 mt-1">Edit rincian biaya perjalanan dinas (berasal dari SPD)</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rincian.cetak', $rincian->id) }}" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak PDF
            </a>
            {{-- <a href="{{ route('rincian.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a> --}}
        </div>
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    </div>

    <form method="POST" action="{{ route('rincian.update', $rincian->id) }}" id="formRincian">
        @csrf
        @method('PUT')

<<<<<<< HEAD
        <div class="p-6 space-y-6">
            <!-- FORM DATA DASAR -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SPPD <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="nomor" 
                           value="{{ old('nomor', $rincian->nomor) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nomor') border-red-500 @enderror"
                           placeholder="Contoh: 000.1.2.3/DPMPTSP/2025"
                           required>
                    @error('nomor')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="tanggal" 
                           value="{{ old('tanggal', $rincian->tanggal instanceof \Carbon\Carbon ? $rincian->tanggal->format('Y-m-d') : $rincian->tanggal) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror"
                           required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Perjalanan <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="tujuan" 
                           value="{{ old('tujuan', $rincian->tujuan) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tujuan') border-red-500 @enderror"
                           placeholder="Contoh: Kota Banjarbaru / Dinas Luar Kota"
                           required>
                    @error('tujuan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- INPUT DATA PEGAWAI -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Pegawai</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Input manual jumlah dan nominal pegawai</div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jumlah Pegawai</label>
                                <input type="number" 
                                       id="jumlahPegawai"
                                       name="jumlah_pegawai"
                                       value="{{ old('jumlah_pegawai', count($rincian->pegawai)) }}"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nominal per Pegawai (Rp)</label>
                                <input type="number" 
                                       id="nominalPerPegawai"
                                       name="nominal_per_pegawai"
                                       value="{{ old('nominal_per_pegawai', $rincian->pegawai[0]['nominal'] ?? 300000) }}"
                                       min="0"
                                       step="50000"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Hari</label>
                                <input type="number" 
                                       id="hari"
                                       name="hari"
                                       value="{{ old('hari', $rincian->pegawai[0]['hari'] ?? 1) }}"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 bg-blue-50 p-2 rounded">
                            <span>💡 Informasi: Total Uang Harian = Jumlah Pegawai × Nominal per Pegawai × Hari</span>
                        </div>
=======
        <!-- Hidden input untuk spd_id -->
        <input type="hidden" name="spd_id" value="{{ $rincian->spd_id }}">

        <div class="p-6 space-y-6">
            <!-- INFORMASI DARI SPD (READONLY) -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-semibold text-blue-800">Informasi dari SPD (Tidak dapat diubah)</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <div class="text-xs text-blue-600">Nomor SPPD</div>
                        <div class="font-medium text-gray-800">{{ $rincian->nomor_sppd ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tanggal Berangkat</div>
                        <div class="font-medium text-gray-800">{{ $rincian->tanggal_berangkat ? $rincian->tanggal_berangkat->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tanggal Kembali</div>
                        <div class="font-medium text-gray-800">{{ $rincian->tanggal_kembali ? $rincian->tanggal_kembali->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Lama Perjadin</div>
                        <div class="font-medium text-gray-800">{{ $rincian->lama_perjadin ?? 0 }} Hari</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Tempat Tujuan</div>
                        <div class="font-medium text-gray-800">{{ $rincian->tempat_tujuan ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Uang Harian per Hari</div>
                        <div class="font-medium text-gray-800">Rp {{ number_format($rincian->uang_harian ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">Uang Transport per Orang</div>
                        <div class="font-medium text-gray-800">Rp {{ number_format($rincian->uang_transport ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-blue-600">SPD ID</div>
                        <div class="font-medium text-gray-800">#{{ $rincian->spd_id }}</div>
                    </div>
                </div>
            </div>

            <!-- DATA PEGAWAI (READONLY) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Pegawai Pelaksana</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Data dari SPD (Tidak dapat diubah)</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">No</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">Nama Pegawai</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">NIP</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500">Jabatan</th>
                                    <th class="px-4 py-2 text-right text-xs text-gray-500">Uang Harian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php
                                    $pegawaiList = is_array($rincian->pegawai) ? $rincian->pegawai : [];
                                @endphp
                                @forelse($pegawaiList as $index => $peg)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $peg['nama'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $peg['nip'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $peg['jabatan'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-600">
                                        Rp {{ number_format($peg['nominal'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                                        Tidak ada data pegawai
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">
                                        Total Uang Harian Keseluruhan:
                                    </td>
                                    <td class="px-4 py-2 text-sm font-bold text-blue-600 text-right">
                                        Rp {{ number_format($rincian->total_uang_harian_keseluruhan ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    * Data pegawai diambil dari SPD. Untuk mengubah, silakan edit SPD terlebih dahulu.
                </p>
            </div>

            <!-- BENDAHARA PENGELUARAN (READONLY - TIDAK BISA DIUBAH) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Bendahara Pengeluaran
                </label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Data dari sistem (Tidak dapat diubah)</div>
                    </div>
                    <div class="p-4">
                        @php
                            $bendahara = $rincian->bendaharaPengeluaran;
                        @endphp
                        @if($bendahara)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $bendahara->nama }}</div>
                                    <div class="text-xs text-gray-500">
                                        NIP: {{ $bendahara->nip ?? '-' }} | Jabatan: {{ $bendahara->jabatan ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-yellow-800">Belum ditentukan</div>
                                    <div class="text-xs text-yellow-600">Bendahara pengeluaran belum dipilih</div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Hidden input untuk mengirim bendahara yang sudah ada -->
                        <input type="hidden" name="bendahara_pengeluaran_id" value="{{ $rincian->bendahara_pengeluaran_id }}">
                        
                        <p class="text-xs text-gray-500 mt-3">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Bendahara pengeluaran diambil dari data pegawai dengan jabatan "Bendahara Pengeluaran" dan tidak dapat diubah di sini.
                        </p>
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
                    </div>
                </div>
            </div>

            <!-- RINGKASAN PERHITUNGAN -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
<<<<<<< HEAD
                <h3 class="font-semibold text-gray-800 mb-3">Ringkasan Perhitungan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Jumlah Pegawai</div>
                        <div class="text-xl font-bold text-blue-600" id="totalPegawai">0</div>
                        <div class="text-xs text-gray-400">orang</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Nominal per Pegawai</div>
                        <div class="text-xl font-bold text-green-600" id="nominalDisplay">Rp 0</div>
                        <div class="text-xs text-gray-400">per hari</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Total Uang Harian</div>
                        <div class="text-xl font-bold text-purple-600" id="totalUangHarian">Rp 0</div>
                        <div class="text-xs text-gray-400">(pegawai × nominal × hari)</div>
=======
                <h3 class="font-semibold text-gray-800 mb-3">Ringkasan Perhitungan Biaya</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Jumlah Pegawai</div>
                        <div class="text-xl font-bold text-blue-600">
                            {{ count($pegawaiList) }} orang
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Uang Harian per Hari</div>
                        <div class="text-xl font-bold text-green-600">
                            Rp {{ number_format($rincian->uang_harian ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400">per orang</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Uang Transport per Orang</div>
                        <div class="text-xl font-bold text-orange-600">
                            Rp {{ number_format($rincian->uang_transport ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Lama Perjalanan</div>
                        <div class="text-xl font-bold text-purple-600">
                            {{ $rincian->lama_perjadin ?? 0 }} hari
                        </div>
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
<<<<<<< HEAD
                        <div class="text-xs text-gray-500">Uang Transport</div>
                        <div class="text-xl font-bold text-orange-600" id="totalTransport">Rp 0</div>
                        <div class="text-xs text-gray-400">masukkan nominal transport</div>
=======
                        <div class="text-xs text-gray-500">Transport Tambahan (Darat/Udara)</div>
                        <input type="number" 
                               name="transport" 
                               id="transportInput"
                               value="{{ old('transport', $rincian->transport ?? 0) }}"
                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Biaya transport tambahan">
                        <div class="text-xs text-gray-400 mt-1">
                            * Biaya transportasi selain uang transport per orang
                        </div>
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-blue-800">TOTAL KESELURUHAN</div>
<<<<<<< HEAD
                                <div class="text-xs text-blue-600">(Uang Harian + Transport)</div>
                            </div>
                            <div class="text-2xl font-bold text-blue-800" id="grandTotal">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR NAMA PEGAWAI -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Nama Pegawai</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Masukkan nama pegawai yang berangkat</div>
                    </div>
                    <div class="p-4" id="namaPegawaiContainer"></div>
                </div>
            </div>

            <!-- BENDAHARA PENGELUARAN -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara Pengeluaran</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Masukkan data bendahara pengeluaran</div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Bendahara</label>
                                <input type="text" 
                                       id="bendaharaNama"
                                       name="bendahara_nama"
                                       value="{{ old('bendahara_nama', $rincian->bendahara['nama'] ?? 'NURLITA FEBRIANA PRATIWI, A.Md') }}"
                                       placeholder="Nama bendahara"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP Bendahara</label>
                                <input type="text" 
                                       id="bendaharaNip"
                                       name="bendahara_nip"
                                       value="{{ old('bendahara_nip', $rincian->bendahara['nip'] ?? '19980208 202012 2 007') }}"
                                       placeholder="NIP bendahara"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
=======
                                <div class="text-[10px] text-blue-600">
                                    (Uang Harian × Pegawai × Hari) + (Uang Transport × Pegawai) + Transport Tambahan
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-blue-800" id="grandTotal">
                                Rp {{ number_format($rincian->total_keseluruhan ?? $rincian->total, 0, ',', '.') }}
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<<<<<<< HEAD
            <!-- KEPALA DINAS -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Dinas</label>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="text-sm font-medium text-gray-700">Masukkan data kepala dinas</div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Kepala Dinas</label>
                                <input type="text" 
                                       id="kepalaDinasNama"
                                       name="kepala_dinas_nama"
                                       value="{{ old('kepala_dinas_nama', $rincian->kepala_dinas['nama'] ?? 'BUDI ANDRIAN SUTANTO, S. Sos., M.M') }}"
                                       placeholder="Nama kepala dinas"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIP Kepala Dinas</label>
                                <input type="text" 
                                       id="kepalaDinasNip"
                                       name="kepala_dinas_nip"
                                       value="{{ old('kepala_dinas_nip', $rincian->kepala_dinas['nip'] ?? '19760218 200701 1 006') }}"
                                       placeholder="NIP kepala dinas"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- UANG TRANSPORT & TERBILANG -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uang Transport (Rp)</label>
                    <input type="number" 
                           name="transport" 
                           id="transportInput"
                           value="{{ old('transport', $rincian->transport) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Biaya transportasi perjalanan dinas</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang</label>
                    <textarea name="terbilang" 
                              id="terbilangInput"
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              readonly
                              style="background-color: #f9fafb;"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Terbilang akan otomatis terisi saat nominal berubah</p>
                </div>
            </div>

            <!-- HIDDEN INPUT -->
            <input type="hidden" name="pegawai" id="pegawaiJson">
=======
            <!-- TERBILANG -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang</label>
                <textarea name="terbilang" 
                          id="terbilangInput"
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          readonly
                          style="background-color: #f9fafb;">{{ $rincian->terbilang ?? '' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Terbilang akan otomatis terisi saat nominal berubah</p>
            </div>
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        </div>

        <!-- BUTTON SUBMIT -->
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('rincian.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Update Rincian
            </button>
        </div>
    </form>
</div>

<script>
    // DOM Elements
<<<<<<< HEAD
    const jumlahPegawaiInput = document.getElementById('jumlahPegawai');
    const nominalPerPegawaiInput = document.getElementById('nominalPerPegawai');
    const hariInput = document.getElementById('hari');
    const transportInput = document.getElementById('transportInput');
    const terbilangInput = document.getElementById('terbilangInput');
    const namaContainer = document.getElementById('namaPegawaiContainer');
    
    const totalPegawaiSpan = document.getElementById('totalPegawai');
    const nominalDisplaySpan = document.getElementById('nominalDisplay');
    const totalUangHarianSpan = document.getElementById('totalUangHarian');
    const totalTransportSpan = document.getElementById('totalTransport');
    const grandTotalSpan = document.getElementById('grandTotal');
    
    // Data pegawai dari server (yang sudah ada)
    const existingPegawai = JSON.parse('{!! addslashes(json_encode($rincian->pegawai ?? [])) !!}');
    
    function updateAll() {
        const jumlah = parseInt(jumlahPegawaiInput.value) || 0;
        const nominal = parseInt(nominalPerPegawaiInput.value) || 0;
        const hari = parseInt(hariInput.value) || 1;
        const transport = parseInt(transportInput.value) || 0;
        
        const totalHarian = jumlah * nominal * hari;
        const grandTotal = totalHarian + transport;
        
        totalPegawaiSpan.textContent = jumlah;
        nominalDisplaySpan.textContent = 'Rp ' + formatNumber(nominal);
        totalUangHarianSpan.textContent = 'Rp ' + formatNumber(totalHarian);
        totalTransportSpan.textContent = 'Rp ' + formatNumber(transport);
        grandTotalSpan.textContent = 'Rp ' + formatNumber(grandTotal);
        
        terbilangInput.value = convertToTerbilang(grandTotal);
        updateNamaPegawaiForms(jumlah);
        updatePegawaiJson();
    }
    
    function updateNamaPegawaiForms(jumlah) {
        namaContainer.innerHTML = '';
        
        for (let i = 0; i < jumlah; i++) {
            const existingData = existingPegawai[i] || { nama: '', nip: '' };
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 w-8">${i+1}.</span>
                    <input type="text" 
                           name="nama_pegawai[]" 
                           value="${escapeHtml(existingData.nama || '')}"
                           placeholder="Nama Pegawai ${i+1}"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="text" 
                           name="nip_pegawai[]" 
                           value="${escapeHtml(existingData.nip || '')}"
                           placeholder="NIP"
                           class="w-48 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            `;
            namaContainer.appendChild(div);
        }
    }
    
    function updatePegawaiJson() {
        const jumlah = parseInt(jumlahPegawaiInput.value) || 0;
        const nominal = parseInt(nominalPerPegawaiInput.value) || 0;
        const hari = parseInt(hariInput.value) || 1;
        
        const namaInputs = document.querySelectorAll('input[name="nama_pegawai[]"]');
        const nipInputs = document.querySelectorAll('input[name="nip_pegawai[]"]');
        
        const pegawaiArray = [];
        
        for (let i = 0; i < jumlah; i++) {
            pegawaiArray.push({
                id_pegawai: i + 1,
                nama: namaInputs[i]?.value || `Pegawai ${i+1}`,
                nip: nipInputs[i]?.value || '-',
                nominal: nominal,
                hari: hari
            });
        }
        
        document.getElementById('pegawaiJson').value = JSON.stringify(pegawaiArray);
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
=======
    const transportInput = document.getElementById('transportInput');
    const terbilangInput = document.getElementById('terbilangInput');
    const grandTotalSpan = document.getElementById('grandTotal');
    
    // Data dari server (PHP variables to JavaScript)
    const uangHarianPerOrang = {{ $rincian->uang_harian ?? 0 }};
    const uangTransportPerOrang = {{ $rincian->uang_transport ?? 0 }};
    const jumlahPegawai = {{ count(is_array($rincian->pegawai) ? $rincian->pegawai : []) }};
    const lamaPerjadin = {{ $rincian->lama_perjadin ?? 0 }};
    const currentTotal = {{ $rincian->total_keseluruhan ?? $rincian->total ?? 0 }};
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function convertToTerbilang(angka) {
        if (angka === 0 || isNaN(angka)) return 'Nol Rupiah';
        
        const satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        
        function terbilangRibuan(n) {
            if (n === 0) return '';
            if (n < 10) return satuan[n];
            if (n < 20) {
                if (n === 10) return 'Sepuluh';
                if (n === 11) return 'Sebelas';
                return satuan[n - 10] + ' Belas';
            }
            if (n < 100) {
                const puluh = Math.floor(n / 10);
                const sisa = n % 10;
                if (sisa === 0) return satuan[puluh] + ' Puluh';
                return satuan[puluh] + ' Puluh ' + satuan[sisa];
            }
            if (n < 1000) {
                const ratus = Math.floor(n / 100);
                const sisa = n % 100;
                if (ratus === 1) return 'Seratus ' + terbilangRibuan(sisa);
                if (sisa === 0) return satuan[ratus] + ' Ratus';
                return satuan[ratus] + ' Ratus ' + terbilangRibuan(sisa);
            }
            return '';
        }
        
        let result = '';
        let i = 0;
        let tempAngka = angka;
        
        while (tempAngka > 0) {
            const segment = tempAngka % 1000;
            if (segment > 0) {
                let segmentText = terbilangRibuan(segment);
                if (segment === 1 && i === 1) segmentText = 'Seribu';
                else if (segment === 1 && i > 0) segmentText = 'Satu';
                result = segmentText + ' ' + ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'][i] + ' ' + result;
            }
            tempAngka = Math.floor(tempAngka / 1000);
            i++;
        }
        
        result = result.trim().replace(/\s+/g, ' ');
        result = result.charAt(0).toUpperCase() + result.slice(1).toLowerCase();
        
        return result + ' Rupiah';
    }
    
<<<<<<< HEAD
    // Event Listeners
    jumlahPegawaiInput.addEventListener('input', updateAll);
    nominalPerPegawaiInput.addEventListener('input', updateAll);
    hariInput.addEventListener('input', updateAll);
    transportInput.addEventListener('input', updateAll);
    
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name="nama_pegawai[]"], input[name="nip_pegawai[]"]')) {
            updatePegawaiJson();
        }
    });
    
    // Initial update
    updateAll();
=======
    function calculateTotal() {
        const transportTambahan = parseInt(transportInput.value) || 0;
        
        const totalUangHarian = uangHarianPerOrang * jumlahPegawai * lamaPerjadin;
        const totalUangTransport = uangTransportPerOrang * jumlahPegawai;
        const grandTotal = totalUangHarian + totalUangTransport + transportTambahan;
        
        grandTotalSpan.innerHTML = 'Rp ' + formatNumber(grandTotal);
        terbilangInput.value = convertToTerbilang(grandTotal);
        
        return grandTotal;
    }
    
    // Event Listeners
    transportInput.addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
</script>

@endsection