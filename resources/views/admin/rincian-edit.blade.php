@extends('layouts.admin')

@section('title', 'Edit Rincian Biaya Perjalanan Dinas')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <!-- HEADER SECTION -->
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Edit Rincian Biaya</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah form rincian biaya perjalanan dinas</p>
        </div>
        <a href="{{ route('rincian.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('rincian.update', $rincian->id) }}" id="formRincian">
        @csrf
        @method('PUT')

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
                    </div>
                </div>
            </div>

            <!-- RINGKASAN PERHITUNGAN -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
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
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500">Uang Transport</div>
                        <div class="text-xl font-bold text-orange-600" id="totalTransport">Rp 0</div>
                        <div class="text-xs text-gray-400">masukkan nominal transport</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-blue-800">TOTAL KESELURUHAN</div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
</script>

@endsection