<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Perjalanan Dinas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 2cm;
            color: #000;
            line-height: 1.25;
        }
        
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .judul-surat h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .content-table td {
            padding: 2px 2px;
            vertical-align: top;
            border: none;
            font-size: 12pt;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .label-col {
            width: 35px;
            font-weight: normal;
            vertical-align: top;
        }
        
        .ket-col {
            width: 110px;
            font-weight: normal;
            vertical-align: top;
        }
        
        .titikdua-col {
            width: 15px;
            text-align: center;
            vertical-align: top;
        }
        
        /* Kolom nomor untuk dasar - lebar tetap agar sejajar */
        .nomor-col {
            width: 30px;
            text-align: right;
            vertical-align: top;
            padding-right: 8px;
        }
        
        .content-col {
            text-align: justify;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
            text-align-last: left;
        }
        
        /* Baris dasar - pastikan teks justify */
        .dasar-row {
            text-align: justify;
        }
        
        .section-spacer {
            height: 24px;
        }
        
        .hasil-text {
            text-align: justify;
            line-height: 1.3;
            white-space: pre-wrap;
            word-wrap: break-word;
            text-align-last: left;
        }
        
        /* Informasi tanggal/tujuan dan keperluan tetap justify */
        .info-text {
            text-align: justify;
        }
        
        .foto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        
        .foto-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 8px 6px;
            width: 33.33%;
        }
        
        .foto-item {
            display: block;
            text-align: center;
            margin: 0 auto;
        }
        
        .foto-item img {
            width: 100%;
            max-width: 160px;
            height: auto;
            min-height: 110px;
            max-height: 130px;
            object-fit: cover;
            border: 1px solid #aaa;
            background: #f9f9f9;
        }
        
        .foto-placeholder {
            width: 100%;
            max-width: 160px;
            height: 120px;
            background: #f0f0f0;
            border: 1px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 10px;
            text-align: center;
            margin: 0 auto;
        }
        
        .foto-caption {
            font-size: 9pt;
            margin-top: 5px;
            font-style: italic;
        }
        
        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 30px;
            font-size: 12pt;
        }
        
        .tempat-tanggal {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .nama-ttd {
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 2px;
            margin-top: 35px;
        }
        
        .nip-ttd {
            font-size: 12pt;
        }
        
        .clearfix {
            clear: both;
        }
        
        /* Justify untuk semua teks dalam content */
        .text-justify {
            text-align: justify;
        }
        
        @media print {
            body {
                margin: 2cm;
            }
        }
    </style>
</head>
<body>

    <div class="judul-surat">
        <h1>LAPORAN HASIL PERJALANAN DINAS (LHPD)</h1>
    </div>

    @php
        // =====================================================
        // AMBIL DATA DASAR DARI LHPD
        // =====================================================
        $dasarList = [];
        
        // Cara 1: Coba ambil dari atribut dasar_list (accessor di model Lhpd)
        if (isset($lhpd->dasar_list) && is_array($lhpd->dasar_list) && count($lhpd->dasar_list) > 0) {
            $dasarList = $lhpd->dasar_list;
        }
        // Cara 2: Jika kosong, coba dari properti dasar (json string atau array)
        elseif (isset($lhpd->dasar)) {
            if (is_array($lhpd->dasar)) {
                $dasarList = $lhpd->dasar;
            } elseif (is_string($lhpd->dasar)) {
                $decoded = json_decode($lhpd->dasar, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $dasarList = $decoded;
                } else {
                    $lines = explode("\n", $lhpd->dasar);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            $cleaned = preg_replace('/^\d+\.\s*/', '', $line);
                            $dasarList[] = $cleaned;
                        }
                    }
                }
            }
        }
        
        // Cara 3: Jika masih kosong, coba dari relasi SPT (jika ada)
        if (empty($dasarList) && isset($lhpd->spt) && $lhpd->spt) {
            if (isset($lhpd->spt->dasar_list) && is_array($lhpd->spt->dasar_list)) {
                $dasarList = $lhpd->spt->dasar_list;
            } elseif (isset($lhpd->spt->dasar)) {
                $decoded = json_decode($lhpd->spt->dasar, true);
                if (is_array($decoded)) {
                    $dasarList = $decoded;
                }
            }
        }
        
        if (!is_array($dasarList)) {
            $dasarList = [];
        }
        
        $bulanIndonesia = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        
        $tanggalBerangkatStr = '';
        if (isset($lhpd->tanggal_berangkat) && $lhpd->tanggal_berangkat) {
            $tgl = \Carbon\Carbon::parse($lhpd->tanggal_berangkat);
            $tanggalBerangkatStr = $tgl->format('d') . ' ' . $bulanIndonesia[$tgl->format('F')] . ' ' . $tgl->format('Y');
        }
        
        $tujuanDaerah = '';
        if (isset($lhpd->daerahTujuan) && $lhpd->daerahTujuan) {
            $tujuanDaerah = $lhpd->daerahTujuan->nama;
        }
        
        $keperluan = isset($lhpd->tujuan) ? $lhpd->tujuan : '';
        $hasilTeks = isset($lhpd->hasil) ? $lhpd->hasil : '';
        
        $tempat = '';
        if (isset($lhpd->tempatDikeluarkan) && $lhpd->tempatDikeluarkan) {
            $tempat = ucfirst(strtolower($lhpd->tempatDikeluarkan->nama));
        }
        
        $tanggalLaporanStr = '';
        if (isset($lhpd->tanggal_lhpd) && $lhpd->tanggal_lhpd) {
            $tglLaporan = \Carbon\Carbon::parse($lhpd->tanggal_lhpd);
            $tanggalLaporanStr = $tglLaporan->format('d') . ' ' . $bulanIndonesia[$tglLaporan->format('F')] . ' ' . $tglLaporan->format('Y');
        }
        
        $fotoBase64List = [];
        $rawFoto = isset($lhpd->foto) ? $lhpd->foto : null;
        $fotoPaths = [];
        if ($rawFoto) {
            if (is_array($rawFoto)) {
                $fotoPaths = $rawFoto;
            } elseif (is_string($rawFoto)) {
                $decoded = json_decode($rawFoto, true);
                if (is_array($decoded)) {
                    $fotoPaths = $decoded;
                }
            }
        }
        
        foreach ($fotoPaths as $path) {
            $cleanPath = trim($path, '"');
            $cleanPath = str_replace('"', '', $cleanPath);
            $fullPath = storage_path('app/public/' . $cleanPath);
            $imageData = '';
            if (!empty($cleanPath) && file_exists($fullPath)) {
                try {
                    $imageContent = file_get_contents($fullPath);
                    if ($imageContent !== false) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $fullPath);
                        finfo_close($finfo);
                        $imageData = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                    }
                } catch (\Exception $e) {}
            }
            $fotoBase64List[] = $imageData;
        }
        
        $fotoGrid = [];
        $jumlahFoto = count($fotoBase64List);
        if ($jumlahFoto > 0) {
            for ($i = 0; $i < $jumlahFoto; $i += 3) {
                $fotoGrid[] = array_slice($fotoBase64List, $i, 3);
            }
        }
        
        $pegawaiList = isset($lhpd->pegawai_list) ? $lhpd->pegawai_list : collect([]);
    @endphp

    {{-- I. DASAR --}}
    <table class="content-table">
        @if(count($dasarList) > 0)
            @foreach($dasarList as $index => $dasar)
                @if($index == 0)
                <tr>
                    <td class="label-col">I.</td>
                    <td class="ket-col">Dasar</td>
                    <td class="titikdua-col">:</td>
                    <td class="nomor-col">{{ $index + 1 }}.</td>
                    <td class="content-col" colspan="2">{{ $dasar }}</td>
                </tr>
                @else
                <tr>
                    <td class="label-col"></td>
                    <td class="ket-col"></td>
                    <td class="titikdua-col"></td>
                    <td class="nomor-col">{{ $index + 1 }}.</td>
                    <td class="content-col" colspan="2">{{ $dasar }}</td>
                </tr>
                @endif
            @endforeach
        @else
        <tr>
            <td class="label-col">I.</td>
            <td class="ket-col">Dasar</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">-</td>
        </tr>
        @endif
    </table>

    <div class="section-spacer"></div>

    {{-- II. TANGGAL/TUJUAN --}}
    <table class="content-table">
        <tr>
            <td class="label-col">II.</td>
            <td class="ket-col">Tanggal/Tujuan</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">
                Perjalanan Dinas dilaksanakan pada tanggal 
                <strong>{{ $tanggalBerangkatStr }}</strong> 
                dengan tujuan 
                <strong>{{ $tujuanDaerah }}</strong>
            </td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    {{-- III. KEPERLUAN --}}
    <table class="content-table">
        <tr>
            <td class="label-col">III.</td>
            <td class="ket-col">Keperluan</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">{{ $keperluan }}</td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    {{-- IV. HASIL --}}
    <table class="content-table">
        <tr>
            <td class="label-col">IV.</td>
            <td class="ket-col">Hasil</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">
                <div class="hasil-text">{!! nl2br(e($hasilTeks)) !!}</div>
            </td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    {{-- PENUTUP --}}
    <table class="content-table">
        <tr>
            <td class="label-col"></td>
            <td class="ket-col"></td>
            <td class="titikdua-col"></td>
            <td class="content-col" colspan="3">
                Demikian laporan hasil perjalanan dinas ini dibuat dan disampaikan, untuk diketahui dan menjadi bahan selanjutnya.
            </td>
        </tr>
    </table>

    {{-- FOTO --}}
    @if(count($fotoGrid) > 0)
    <table class="foto-table">
        @foreach($fotoGrid as $rowIdx => $rowFoto)
        <tr>
            @for($col = 0; $col < 3; $col++)
                @php
                    $fotoData = isset($rowFoto[$col]) ? $rowFoto[$col] : null;
                    $fotoIndex = ($rowIdx * 3) + $col + 1;
                @endphp
                <td>
                    <div class="foto-item">
                        @if($fotoData)
                            <img src="{{ $fotoData }}" alt="Dokumentasi {{ $fotoIndex }}">
                        @else
                            <div class="foto-placeholder">
                                📷 Foto {{ $fotoIndex }}<br>
                                <span style="font-size:8px;">(belum tersedia)</span>
                            </div>
                        @endif
                        <div class="foto-caption">Foto {{ $fotoIndex }}</div>
                    </div>
                </td>
            @endfor
        </tr>
        @endforeach
    </table>
    @endif

    {{-- TANDA TANGAN --}}
    <div class="tanda-tangan">
        <div class="tempat-tanggal">
            {{ $tempat }}@if($tempat && $tanggalLaporanStr), @endif{{ $tanggalLaporanStr }}
        </div>
        <div>Yang Membuat Laporan,</div>
        
        <div style="height: 35px;"></div>
        
        @if($pegawaiList && $pegawaiList->count() > 0)
            @foreach($pegawaiList as $index => $pegawai)
                @if($index > 0)
                    <div style="margin-top: 15px;"></div>
                @endif
                <div class="nama-ttd">{{ $pegawai->nama ?? '' }}</div>
                <div class="nip-ttd">@if($pegawai->nip)NIP. {{ $pegawai->nip }}@endif</div>
            @endforeach
        @else
            <div class="nama-ttd">_________________________</div>
            <div class="nip-ttd">NIP. _________________</div>
        @endif
    </div>
    <div class="clearfix"></div>

</body>
</html>