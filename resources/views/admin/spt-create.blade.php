{{-- resources/views/admin/spt-create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Buat SPT Baru')

@section('subtitle', 'Lengkapi form untuk membuat Surat Perintah Tugas')

@section('content')
<div class="space-y-6">
    {{-- Header dengan Tombol Kembali --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('spt.index') }}" 
               class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-indigo-600"></i>
                    Buat SPT Baru
                </h2>
                <p class="text-gray-500 text-sm mt-1">
                    Isi formulir di bawah untuk membuat Surat Perintah Tugas baru
                </p>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-lg shadow-sm flex items-start gap-3" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-rose-600 hover:text-rose-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-500 text-xs"></i>
                Formulir Surat Perintah Tugas
            </h3>
        </div>
        
        <div class="p-6">
            <form action="{{ route('spt.store') }}" method="POST" id="sptForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        {{-- Nomor Surat --}}
                        <div>
                            <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Surat <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="nomor_surat"
                                       name="nomor_surat" 
                                       value="{{ old('nomor_surat', $nomorSurat ?? '') }}" 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm @error('nomor_surat') border-rose-500 @enderror"
                                       placeholder="Contoh: 001/SPT/01/2024">
                            </div>
                            @error('nomor_surat')
                                <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tanggal Surat --}}
                        <div>
                            <label for="tanggal_surat_dibuat" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Surat <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                                </div>
                                <input type="date" 
                                       id="tanggal_surat_dibuat"
                                       name="tanggal_surat_dibuat" 
                                       value="{{ old('tanggal_surat_dibuat', date('Y-m-d')) }}" 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm @error('tanggal_surat_dibuat') border-rose-500 @enderror">
                            </div>
                            @error('tanggal_surat_dibuat')
                                <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="kota"
                                       name="kota" 
                                       value="{{ old('kota', 'Pelaihari') }}" 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm @error('kota') border-rose-500 @enderror"
                                       placeholder="Contoh: Pelaihari">
                            </div>
                            @error('kota')
                                <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        {{-- Penandatangan Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Penandatangan Surat <span class="text-rose-500">*</span>
                            </label>
                            
                            {{-- Grid 4 Kolom untuk Dropdown --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                {{-- Dropdown Nama --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user-tie text-gray-400 text-xs"></i>
                                        </div>
                                        <select id="penandatangan_nama" 
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm penandatangan-selector"
                                                data-field="nama">
                                            <option value="">Pilih Nama</option>
                                            @foreach($penandatangan as $pegawai)
                                                <option value="{{ $pegawai->id_pegawai }}" 
                                                        data-nama="{{ $pegawai->nama }}"
                                                        data-nip="{{ $pegawai->nip }}"
                                                        data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                        data-gol="{{ $pegawai->gol ?? '' }}"
                                                        data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                    {{ $pegawai->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Dropdown NIP --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">NIP</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400 text-xs"></i>
                                        </div>
                                        <select id="penandatangan_nip" 
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm penandatangan-selector"
                                                data-field="nip">
                                            <option value="">Pilih NIP</option>
                                            @foreach($penandatangan as $pegawai)
                                                <option value="{{ $pegawai->id_pegawai }}" 
                                                        data-nama="{{ $pegawai->nama }}"
                                                        data-nip="{{ $pegawai->nip }}"
                                                        data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                        data-gol="{{ $pegawai->gol ?? '' }}"
                                                        data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                    {{ $pegawai->nip }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Dropdown Pangkat/Gol (Gabungan) --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Pangkat/Gol</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-circle-up text-gray-400 text-xs"></i>
                                        </div>
                                        <select id="penandatangan_pangkat_gol" 
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm penandatangan-selector"
                                                data-field="pangkat_gol">
                                            <option value="">Pilih Pangkat/Gol</option>
                                            @foreach($penandatangan as $pegawai)
                                                @php
                                                    $pangkatGol = ($pegawai->pangkat ?? '') . ($pegawai->gol ? '/' . $pegawai->gol : '');
                                                @endphp
                                                @if(!empty($pangkatGol))
                                                <option value="{{ $pegawai->id_pegawai }}" 
                                                        data-nama="{{ $pegawai->nama }}"
                                                        data-nip="{{ $pegawai->nip }}"
                                                        data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                        data-gol="{{ $pegawai->gol ?? '' }}"
                                                        data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                    {{ $pangkatGol }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Dropdown Jabatan --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Jabatan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-briefcase text-gray-400 text-xs"></i>
                                        </div>
                                        <select id="penandatangan_jabatan" 
                                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm penandatangan-selector"
                                                data-field="jabatan">
                                            <option value="">Pilih Jabatan</option>
                                            @foreach($penandatangan as $pegawai)
                                                <option value="{{ $pegawai->id_pegawai }}" 
                                                        data-nama="{{ $pegawai->nama }}"
                                                        data-nip="{{ $pegawai->nip }}"
                                                        data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                        data-gol="{{ $pegawai->gol ?? '' }}"
                                                        data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                    {{ $pegawai->jabatan ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Hidden input untuk menyimpan ID yang dipilih --}}
                            <input type="hidden" name="penandatangan_surat" id="penandatangan_surat" value="{{ old('penandatangan_surat') }}">
                            
                            @error('penandatangan_surat')
                                <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Dasar Surat (Full Width) --}}
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dasar Surat <span class="text-rose-500">*</span>
                        </label>
                        
                        <div id="dasar-poin-container" class="space-y-2">
                            @php
                                $oldDasarPoins = old('dasar_poins', ['']);
                            @endphp
                            
                            @foreach($oldDasarPoins as $index => $poin)
                            <div class="flex items-center gap-2 dasar-poin-item">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-circle text-indigo-300 text-[6px]"></i>
                                    </div>
                                    <input type="text" 
                                           name="dasar_poins[]" 
                                           value="{{ $poin }}"
                                           class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                                           placeholder="Poin dasar surat...">
                                </div>
                                
                                @if($loop->first)
                                <button type="button" 
                                        onclick="addDasarPoin()" 
                                        class="w-10 h-10 flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors duration-200"
                                        title="Tambah poin">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @else
                                <button type="button" 
                                        onclick="removeDasarPoin(this)" 
                                        class="w-10 h-10 flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors duration-200"
                                        title="Hapus poin">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        
                        <input type="hidden" name="dasar_surat" id="dasar_surat_hidden" value="{{ old('dasar_surat') }}">
                        
                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle text-indigo-400"></i>
                            <span>Klik tombol <span class="bg-indigo-600 text-white px-1.5 py-0.5 rounded text-[10px]">+</span> untuk menambah poin dasar surat</span>
                        </div>
                        @error('dasar_surat')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Pegawai Yang Diperintahkan (Full Width) --}}
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pegawai Yang Diperintahkan <span class="text-rose-500">*</span>
                        </label>
                        
                        <div id="pegawai-container" class="space-y-4">
                            @php
                                $oldPegawai = old('pegawai_diperintahkan', [['id' => '']]);
                            @endphp
                            
                            @foreach($oldPegawai as $index => $pegawaiData)
                            @php
                                $selectedPegawai = null;
                                if (isset($pegawaiData['id']) && !empty($pegawaiData['id'])) {
                                    $selectedPegawai = $pegawais->firstWhere('id_pegawai', $pegawaiData['id']);
                                }
                            @endphp
                            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50 pegawai-item" data-index="{{ $index }}">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <span>Data Pegawai</span>
                                    </h4>
                                    @if(!$loop->first)
                                    <button type="button" 
                                            onclick="removePegawai(this)" 
                                            class="text-rose-600 hover:text-rose-800 bg-white hover:bg-rose-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors duration-200"
                                            title="Hapus pegawai">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                {{-- Grid 4 Kolom untuk Dropdown --}}
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    {{-- Dropdown Nama --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400 text-xs"></i>
                                            </div>
                                            <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                                                    data-index="{{ $index }}"
                                                    data-field="nama">
                                                <option value="">Pilih Nama</option>
                                                @foreach($pegawais as $pegawai)
                                                    <option value="{{ $pegawai->id_pegawai }}" 
                                                            data-nama="{{ $pegawai->nama }}"
                                                            data-nip="{{ $pegawai->nip }}"
                                                            data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                            data-gol="{{ $pegawai->gol ?? '' }}"
                                                            data-jabatan="{{ $pegawai->jabatan ?? '' }}"
                                                            {{ isset($pegawaiData['id']) && $pegawaiData['id'] == $pegawai->id_pegawai ? 'selected' : '' }}>
                                                        {{ $pegawai->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Dropdown NIP --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">NIP</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-id-card text-gray-400 text-xs"></i>
                                            </div>
                                            <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                                                    data-index="{{ $index }}"
                                                    data-field="nip">
                                                <option value="">Pilih NIP</option>
                                                @foreach($pegawais as $pegawai)
                                                    <option value="{{ $pegawai->id_pegawai }}" 
                                                            data-nama="{{ $pegawai->nama }}"
                                                            data-nip="{{ $pegawai->nip }}"
                                                            data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                            data-gol="{{ $pegawai->gol ?? '' }}"
                                                            data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                        {{ $pegawai->nip }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Dropdown Pangkat/Gol (Gabungan) --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Pangkat/Gol</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-chevron-circle-up text-gray-400 text-xs"></i>
                                            </div>
                                            <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                                                    data-index="{{ $index }}"
                                                    data-field="pangkat_gol">
                                                <option value="">Pilih Pangkat/Gol</option>
                                                @foreach($pegawais as $pegawai)
                                                    @php
                                                        $pangkatGol = ($pegawai->pangkat ?? '') . ($pegawai->gol ? '/' . $pegawai->gol : '');
                                                    @endphp
                                                    @if(!empty($pangkatGol))
                                                    <option value="{{ $pegawai->id_pegawai }}" 
                                                            data-nama="{{ $pegawai->nama }}"
                                                            data-nip="{{ $pegawai->nip }}"
                                                            data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                            data-gol="{{ $pegawai->gol ?? '' }}"
                                                            data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                        {{ $pangkatGol }}
                                                    </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Dropdown Jabatan --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Jabatan</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-briefcase text-gray-400 text-xs"></i>
                                            </div>
                                            <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                                                    data-index="{{ $index }}"
                                                    data-field="jabatan">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($pegawais as $pegawai)
                                                    <option value="{{ $pegawai->id_pegawai }}" 
                                                            data-nama="{{ $pegawai->nama }}"
                                                            data-nip="{{ $pegawai->nip }}"
                                                            data-pangkat="{{ $pegawai->pangkat ?? '' }}"
                                                            data-gol="{{ $pegawai->gol ?? '' }}"
                                                            data-jabatan="{{ $pegawai->jabatan ?? '' }}">
                                                        {{ $pegawai->jabatan ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Hidden input untuk menyimpan ID yang dipilih --}}
                                <input type="hidden" name="pegawai_diperintahkan[{{ $index }}][id]" class="pegawai-id" value="{{ $pegawaiData['id'] ?? '' }}">
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" 
                                onclick="addPegawai()" 
                                class="mt-3 inline-flex items-center px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg transition-colors duration-200 border border-indigo-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pegawai
                        </button>
                        
                        @error('pegawai_diperintahkan')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle text-indigo-400"></i>
                            <span>Pilih minimal 1 pegawai</span>
                        </div>
                    </div>

                    {{-- Untuk Keperluan (Full Width) --}}
                    <div class="lg:col-span-2">
                        <label for="untuk_keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                            Untuk Keperluan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fas fa-info-circle text-gray-400"></i>
                            </div>
                            <textarea name="untuk_keperluan" 
                                      id="untuk_keperluan"
                                      rows="4" 
                                      class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm @error('untuk_keperluan') border-rose-500 @enderror"
                                      placeholder="Contoh: Melaksanakan verifikasi data di Kantor Kecamatan...">{{ old('untuk_keperluan') }}</textarea>
                        </div>
                        @error('untuk_keperluan')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="mt-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-indigo-600 mt-0.5"></i>
                        <div class="text-sm text-indigo-800">
                            <span class="font-medium">Informasi:</span> Kolom bertanda <span class="text-rose-500 font-medium">*</span> wajib diisi. Anda cukup memilih salah satu dari 4 pilihan (Nama, NIP, Pangkat/Gol, atau Jabatan), data lainnya akan terisi otomatis.
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('spt.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan SPT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ============ DATA DARI SERVER ============
const pegawaisData = @json($pegawais);
const penandatanganData = @json($penandatangan);

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi untuk penandatangan
    initPenandatanganSelectors();
    
    // Inisialisasi untuk setiap pegawai
    initPegawaiSelectors();
});

// ============ FUNGSI PENANDATANGAN ============

function initPenandatanganSelectors() {
    const selectors = document.querySelectorAll('.penandatangan-selector');
    
    selectors.forEach(selector => {
        selector.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                // Update semua dropdown penandatangan
                updatePenandatanganDropdowns(this.value);
                
                // Set hidden input
                document.getElementById('penandatangan_surat').value = this.value;
            } else {
                // Reset semua dropdown
                resetPenandatanganDropdowns();
                document.getElementById('penandatangan_surat').value = '';
            }
        });
    });
}

function updatePenandatanganDropdowns(selectedId) {
    const dropdowns = {
        nama: document.getElementById('penandatangan_nama'),
        nip: document.getElementById('penandatangan_nip'),
        pangkat_gol: document.getElementById('penandatangan_pangkat_gol'),
        jabatan: document.getElementById('penandatangan_jabatan')
    };
    
    // Update setiap dropdown
    Object.keys(dropdowns).forEach(key => {
        const dropdown = dropdowns[key];
        Array.from(dropdown.options).forEach(option => {
            if (option.value == selectedId) {
                option.selected = true;
            } else {
                option.selected = false;
            }
        });
    });
}

function resetPenandatanganDropdowns() {
    const dropdowns = document.querySelectorAll('.penandatangan-selector');
    dropdowns.forEach(dropdown => {
        dropdown.value = '';
    });
}

// ============ FUNGSI DASAR SURAT ============

function addDasarPoin() {
    const container = document.getElementById('dasar-poin-container');
    const newItem = document.createElement('div');
    newItem.className = 'flex items-center gap-2 dasar-poin-item';
    newItem.innerHTML = `
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-circle text-indigo-300 text-[6px]"></i>
            </div>
            <input type="text" 
                   name="dasar_poins[]" 
                   value=""
                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm"
                   placeholder="Poin dasar surat...">
        </div>
        <button type="button" 
                onclick="removeDasarPoin(this)" 
                class="w-10 h-10 flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors duration-200"
                title="Hapus poin">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(newItem);
    
    // Fokus ke input baru
    const newInput = newItem.querySelector('input');
    if (newInput) newInput.focus();
}

function removeDasarPoin(button) {
    const item = button.closest('.dasar-poin-item');
    if (document.querySelectorAll('.dasar-poin-item').length > 1) {
        item.remove();
    } else {
        alert('Minimal harus ada 1 poin dasar surat');
    }
}

// ============ FUNGSI PEGAWAI ============

function initPegawaiSelectors() {
    document.querySelectorAll('.pegawai-selector').forEach(selector => {
        selector.addEventListener('change', function() {
            const pegawaiItem = this.closest('.pegawai-item');
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Update semua dropdown di item yang sama
                updatePegawaiDropdowns(pegawaiItem, this.value);
                
                // Update hidden input
                const hiddenInput = pegawaiItem.querySelector('.pegawai-id');
                if (hiddenInput) hiddenInput.value = this.value;
            } else {
                // Reset dropdown di item yang sama
                resetPegawaiDropdowns(pegawaiItem);
                
                // Reset hidden input
                const hiddenInput = pegawaiItem.querySelector('.pegawai-id');
                if (hiddenInput) hiddenInput.value = '';
            }
        });
    });
}

function updatePegawaiDropdowns(pegawaiItem, selectedId) {
    const dropdowns = pegawaiItem.querySelectorAll('.pegawai-selector');
    
    dropdowns.forEach(dropdown => {
        Array.from(dropdown.options).forEach(option => {
            if (option.value == selectedId) {
                option.selected = true;
            } else {
                option.selected = false;
            }
        });
    });
}

function resetPegawaiDropdowns(pegawaiItem) {
    const dropdowns = pegawaiItem.querySelectorAll('.pegawai-selector');
    dropdowns.forEach(dropdown => {
        dropdown.value = '';
    });
}

function addPegawai() {
    const container = document.getElementById('pegawai-container');
    const index = document.querySelectorAll('.pegawai-item').length;
    
    const newItem = document.createElement('div');
    newItem.className = 'border border-gray-200 rounded-xl p-5 bg-gray-50 pegawai-item';
    newItem.setAttribute('data-index', index);
    
    // Generate options untuk semua dropdown
    let namaOptions = '<option value="">Pilih Nama</option>';
    let nipOptions = '<option value="">Pilih NIP</option>';
    let pangkatGolOptions = '<option value="">Pilih Pangkat/Gol</option>';
    let jabatanOptions = '<option value="">Pilih Jabatan</option>';
    
    pegawaisData.forEach(pegawai => {
        // Options untuk Nama
        namaOptions += `<option value="${pegawai.id_pegawai}" 
            data-nama="${pegawai.nama}"
            data-nip="${pegawai.nip}"
            data-pangkat="${pegawai.pangkat || ''}"
            data-gol="${pegawai.gol || ''}"
            data-jabatan="${pegawai.jabatan || ''}">
            ${pegawai.nama}
        </option>`;
        
        // Options untuk NIP
        nipOptions += `<option value="${pegawai.id_pegawai}" 
            data-nama="${pegawai.nama}"
            data-nip="${pegawai.nip}"
            data-pangkat="${pegawai.pangkat || ''}"
            data-gol="${pegawai.gol || ''}"
            data-jabatan="${pegawai.jabatan || ''}">
            ${pegawai.nip}
        </option>`;
        
        // Options untuk Pangkat/Gol
        const pangkatGol = (pegawai.pangkat || '') + (pegawai.gol ? '/' + pegawai.gol : '');
        if (pangkatGol) {
            pangkatGolOptions += `<option value="${pegawai.id_pegawai}" 
                data-nama="${pegawai.nama}"
                data-nip="${pegawai.nip}"
                data-pangkat="${pegawai.pangkat || ''}"
                data-gol="${pegawai.gol || ''}"
                data-jabatan="${pegawai.jabatan || ''}">
                ${pangkatGol}
            </option>`;
        }
        
        // Options untuk Jabatan
        if (pegawai.jabatan) {
            jabatanOptions += `<option value="${pegawai.id_pegawai}" 
                data-nama="${pegawai.nama}"
                data-nip="${pegawai.nip}"
                data-pangkat="${pegawai.pangkat || ''}"
                data-gol="${pegawai.gol || ''}"
                data-jabatan="${pegawai.jabatan || ''}">
                ${pegawai.jabatan}
            </option>`;
        }
    });
    
    newItem.innerHTML = `
        <div class="flex justify-between items-start mb-4">
            <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                    ${index + 1}
                </span>
                <span>Data Pegawai</span>
            </h4>
            <button type="button" 
                    onclick="removePegawai(this)" 
                    class="text-rose-600 hover:text-rose-800 bg-white hover:bg-rose-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors duration-200"
                    title="Hapus pegawai">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            {{-- Dropdown Nama --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Nama</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400 text-xs"></i>
                    </div>
                    <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                            data-index="${index}"
                            data-field="nama">
                        ${namaOptions}
                    </select>
                </div>
            </div>

            {{-- Dropdown NIP --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">NIP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400 text-xs"></i>
                    </div>
                    <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                            data-index="${index}"
                            data-field="nip">
                        ${nipOptions}
                    </select>
                </div>
            </div>

            {{-- Dropdown Pangkat/Gol (Gabungan) --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Pangkat/Gol</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-circle-up text-gray-400 text-xs"></i>
                    </div>
                    <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                            data-index="${index}"
                            data-field="pangkat_gol">
                        ${pangkatGolOptions}
                    </select>
                </div>
            </div>

            {{-- Dropdown Jabatan --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Jabatan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-400 text-xs"></i>
                    </div>
                    <select class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow text-sm pegawai-selector"
                            data-index="${index}"
                            data-field="jabatan">
                        ${jabatanOptions}
                    </select>
                </div>
            </div>
        </div>
        
        <input type="hidden" name="pegawai_diperintahkan[${index}][id]" class="pegawai-id" value="">
    `;
    
    container.appendChild(newItem);
    
    // Inisialisasi event listener untuk dropdown baru
    initPegawaiSelectorsForItem(newItem);
}

function initPegawaiSelectorsForItem(item) {
    item.querySelectorAll('.pegawai-selector').forEach(selector => {
        selector.addEventListener('change', function() {
            const pegawaiItem = this.closest('.pegawai-item');
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                updatePegawaiDropdowns(pegawaiItem, this.value);
                
                const hiddenInput = pegawaiItem.querySelector('.pegawai-id');
                if (hiddenInput) hiddenInput.value = this.value;
            } else {
                resetPegawaiDropdowns(pegawaiItem);
                
                const hiddenInput = pegawaiItem.querySelector('.pegawai-id');
                if (hiddenInput) hiddenInput.value = '';
            }
        });
    });
}

function removePegawai(button) {
    const items = document.querySelectorAll('.pegawai-item');
    if (items.length > 1) {
        const itemToRemove = button.closest('.pegawai-item');
        itemToRemove.remove();
        
        // Update nomor urut dan data-index
        document.querySelectorAll('.pegawai-item').forEach((item, idx) => {
            const numberSpan = item.querySelector('.w-6.h-6.rounded-full');
            if (numberSpan) {
                numberSpan.textContent = idx + 1;
            }
            
            item.setAttribute('data-index', idx);
            
            // Update data-index pada semua dropdown di item ini
            item.querySelectorAll('.pegawai-selector').forEach(selector => {
                selector.setAttribute('data-index', idx);
            });
        });
    } else {
        alert('Minimal harus ada 1 pegawai');
    }
}

// ============ VALIDASI FORM ============

document.getElementById('sptForm').addEventListener('submit', function(e) {
    // Validasi dasar surat
    const dasarPoins = document.querySelectorAll('input[name="dasar_poins[]"]');
    let hasEmptyPoin = false;
    let validPoins = [];
    
    dasarPoins.forEach(input => {
        if (!input.value.trim()) {
            hasEmptyPoin = true;
            input.classList.add('border-rose-500');
        } else {
            input.classList.remove('border-rose-500');
            validPoins.push(input.value.trim());
        }
    });
    
    if (hasEmptyPoin) {
        e.preventDefault();
        alert('Semua poin dasar surat harus diisi');
        return false;
    }
    
    if (validPoins.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 poin dasar surat');
        return false;
    }
    
    // Gabungkan poin dasar surat
    const formattedDasar = validPoins.map((poin, index) => {
        return `${index + 1}. ${poin}`;
    }).join('\n');
    
    document.getElementById('dasar_surat_hidden').value = formattedDasar;
    
    // Validasi pegawai
    const pegawaiItems = document.querySelectorAll('.pegawai-item');
    let hasEmptyPegawai = false;
    
    pegawaiItems.forEach(item => {
        const hiddenInput = item.querySelector('.pegawai-id');
        if (!hiddenInput || !hiddenInput.value) {
            hasEmptyPegawai = true;
        }
    });
    
    if (hasEmptyPegawai) {
        e.preventDefault();
        alert('Semua pegawai harus dipilih');
        return false;
    }
    
    // Validasi penandatangan
    const penandatangan = document.getElementById('penandatangan_surat');
    if (!penandatangan.value) {
        e.preventDefault();
        alert('Penandatangan surat harus dipilih');
        return false;
    }
    
    return true;
});
</script>
@endsection 