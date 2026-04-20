<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perjalanan Dinas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 1cm 1.5cm 1.5cm 1.5cm;
            color: #000;
            line-height: 1.25;
        }

        .spd-page {
            page-break-after: always;
            margin-bottom: 0;
        }

        .spd-page:last-child {
            page-break-after: auto;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: none;
        }

        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 65px;
            padding-right: 8px;
        }

        .logo {
            width: 60px;
            height: 60px;
        }

        .logo img {
            width: 100%;
            height: auto;
            display: block;
        }

        .header-text {
            width: 100%;
            text-align: center;
        }

        .header-text .pemkab {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header-text .dinas {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header-text .alamat {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .header-text .kontak {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .garis-kop {
            width: 100%;
            margin: 0 0 12px 0;
            clear: both;
            border-top: 1px solid #000;
            border-bottom: 2px solid #000;
            height: 2px;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }

        .judul-surat .nomor {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            margin-top: 5px;
            font-weight: normal;
        }

        .spd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 25px;
            border: 1px solid #000;
        }

        .spd-table td, .spd-table th {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 11pt;
        }

        .col-nomor {
            width: 5%;
            text-align: center;
            vertical-align: middle;
        }

        .col-label {
            width: 25%;
        }

        .col-tgl-lahir {
            width: 20%;
        }

        .col-keterangan {
            width: 50%;
        }

        .align-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .align-label {
            display: table-cell;
            width: 45px;
            white-space: nowrap;
        }

        .align-colon {
            display: table-cell;
            width: 12px;
            white-space: nowrap;
        }

        .align-value {
            display: table-cell;
        }

        .tanda-tangan {
            float: right;
            width: 280px;
            text-align: center;
            margin-top: 20px;
            font-size: 11.5pt;
        }

        .tempat-tanggal {
            font-size: 11.5pt;
            margin-bottom: 5px;
        }

        .jabatan-ttd {
            font-size: 11.5pt;
            margin-bottom: 25px;
        }

        .ttd-space {
            height: 50px;
        }

        .nama-ttd {
            font-size: 11.5pt;
            text-decoration: underline;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .pangkat-ttd {
            font-size: 10.5pt;
            margin-bottom: 2px;
        }

        .nip-ttd {
            font-size: 10.5pt;
        }

        .clearfix {
            clear: both;
        }

        .list-pengikut {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>

    @php
        $pelaksanaList = $spd->pelaksanaPerjadin ?? collect();

        if($pelaksanaList->count() == 0) {
            $pelaksanaList = collect([(object)['nama' => '-', 'nip' => '-', 'pangkat' => '-', 'gol' => '-', 'jabatan' => '-']]);
        }
    @endphp

    @foreach($pelaksanaList as $index => $pegawai)
        <div class="spd-page" @if(!$loop->last) style="page-break-after: always;" @endif>
            <table class="kop-table">
                <tr>
                    <td class="logo-cell">
                        <div class="logo">
                            <img src="{{ public_path('image/Logo_Tala-removebg-preview (2).png') }}" alt="Logo Kabupaten Tanah Laut">
                        </div>
                    </td>
                    <td class="header-text">
                        <div class="pemkab">PEMERINTAH KABUPATEN TANAH LAUT</div>
                        <div class="dinas">DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU</div>
                        <div class="alamat">Jalan H. Boejasin, Pelaihari, Kab. Tanah Laut, Kalimantan Selatan 70814</div>
                        <div class="kontak">Laman https://dpmptsp.tanahlautkab.go.id Pos-el dpmptsptanahlautkab@gmail.com</div>
                    </td>
                </tr>
            </table>

            <div class="garis-kop"></div>

            <div class="judul-surat">
                <h1>SURAT PERJALANAN DINAS (SPD)</h1>
                <div class="nomor">{{ $spd->nomor_surat ?? '-' }}</div>
            </div>

            <table class="spd-table">
                <!-- ROW 1 -->
                <tr>
                    <td class="col-nomor">1.</td>
                    <td class="col-label">Pengguna Anggaran</td>
                    <td colspan="2">
                        <div class="align-row">
                            <span class="align-label">Nama</span>
                            <span class="align-colon">:</span>
                            <span class="align-value">{{ $spd->penggunaAnggaran->nama ?? '-' }}</span>
                        </div>
                        <div class="align-row">
                            <span class="align-label">NIP</span>
                            <span class="align-colon">:</span>
                            <span class="align-value">{{ $spd->penggunaAnggaran->nip ?? '-' }}</span>
                        </div>
                    </td>
                </tr>

                <!-- ROW 2 -->
                <tr>
                    <td class="col-nomor">2.</td>
                    <td class="col-label">Nama pejabat/personil yang melaksanakan perjalanan dinas</td>
                    <td colspan="2">
                        <div class="align-row">
                            <span class="align-label">Nama</span>
                            <span class="align-colon">:</span>
                            <span class="align-value">{{ $pegawai->nama ?? '-' }}</span>
                        </div>
                        <div class="align-row">
                            <span class="align-label">NIP</span>
                            <span class="align-colon">:</span>
                            <span class="align-value">{{ $pegawai->nip ?? '-' }}</span>
                        </div>
                    </td>
                </tr>

                <!-- ROW 3 -->
                <tr>
                    <td class="col-nomor">3.</td>
                    <td class="col-label">a. Pangkat dan Golongan<br>b. Jabatan / Instansi<br>c. Tingkat biaya perjalanan dinas</td>
                    <td colspan="2">
                        a. {{ $pegawai->pangkat ?? '-' }} ({{ $pegawai->gol ?? '-' }})<br>
                        b. {{ $pegawai->jabatan ?? '-' }} / {{ $spd->skpd ?? 'DPMPTSP Kab. Tanah Laut' }}<br>
                        c. D
                    </td>
                </tr>

                <!-- ROW 4 -->
                <tr>
                    <td class="col-nomor">4.</td>
                    <td class="col-label">Maksud perjalanan dinas</td>
                    <td colspan="2">{{ $spd->maksud_perjadin ?? '-' }}</td>
                </tr>

                <!-- ROW 5 -->
                <tr>
                    <td class="col-nomor">5.</td>
                    <td class="col-label">Alat angkut yang digunakan</td>
                    <td colspan="2">{{ $spd->label_alat_transportasi ?? '-' }}</td>
                </tr>

                <!-- ROW 6 -->
                <tr>
                    <td class="col-nomor">6.</td>
                    <td class="col-label">a. Tempat berangkat<br>b. Tempat tujuan</td>
                    <td colspan="2">
                        a. {{ $spd->tempat_berangkat ?? 'Pelaihari' }}<br>
                        b. {{ $spd->nama_tempat_tujuan ?? '-' }}
                    </td>
                </tr>

                <!-- ROW 7 -->
                <tr>
                    <td class="col-nomor">7.</td>
                    <td class="col-label">a. Lamanya perjalanan dinas<br>b. Tanggal berangkat<br>c. Tanggal harus kembali / tiba di tempat baru</td>
                    <td colspan="2">
                        @php
                            $lama = $spd->lama_perjadin ?? 1;
                            $lamaTerbilang = '';
                            if($lama == 1) $lamaTerbilang = 'satu';
                            elseif($lama == 2) $lamaTerbilang = 'dua';
                            elseif($lama == 3) $lamaTerbilang = 'tiga';
                            elseif($lama == 4) $lamaTerbilang = 'empat';
                            elseif($lama == 5) $lamaTerbilang = 'lima';
                            else $lamaTerbilang = $lama;
                        @endphp
                        a. {{ $lama }} ({{ $lamaTerbilang }}) hari<br>
                        b. {{ $spd->tanggal_berangkat ? \Carbon\Carbon::parse($spd->tanggal_berangkat)->translatedFormat('d F Y') : '-' }}<br>
                        c. {{ $spd->tanggal_kembali ? \Carbon\Carbon::parse($spd->tanggal_kembali)->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>

                <!-- ROW 8 : NAMA PENGIKUT - TANGGAL LAHIR - KETERANGAN (3 KOLOM TERPISAH) -->
                <tr>
                    <td class="col-nomor">8.</td>
                    <td class="col-label">Nama Pengikut</td>
                    <td class="col-tgl-lahir">Tanggal lahir</td>
                    <td class="col-keterangan">Keterangan</td>
                </tr>
                @php
                    $pengikutList = $spd->pengikut ?? [];
                    if(count($pengikutList) == 0) {
                        $pengikutList = [
                            (object)['nama' => '', 'tgl_lahir' => '', 'keterangan' => ''],
                            (object)['nama' => '', 'tgl_lahir' => '', 'keterangan' => ''],
                            (object)['nama' => '', 'tgl_lahir' => '', 'keterangan' => ''],
                            (object)['nama' => '', 'tgl_lahir' => '', 'keterangan' => ''],
                            (object)['nama' => '', 'tgl_lahir' => '', 'keterangan' => '']
                        ];
                    }
                @endphp
                @foreach($pengikutList as $idx => $pengikut)
                <tr>
                    <td class="col-nomor"></td>
                    <td class="col-label">{{ $idx + 1 }}. {{ $pengikut->nama ?? '-' }}</td>
                    <td class="col-tgl-lahir">{{ $pengikut->tgl_lahir ?? '-' }}</td>
                    <td class="col-keterangan">{{ $pengikut->keterangan ?? '-' }}</td>
                </tr>
                @endforeach

                <!-- ROW 9 -->
                <tr>
                    <td class="col-nomor">9.</td>
                    <td class="col-label">a. SKPD<br>b. Kode rekening</td>
                    <td colspan="2">
                        a. {{ $spd->skpd ?? 'Dinas Penanaman Modal dan PTSP Kab. Tanah Laut' }}<br>
                        b. {{ $spd->kode_rek ?? $spd->pejabat_teknis_kode_rekening ?? '-' }}
                    </td>
                </tr>

                <!-- ROW 10 -->
                <tr>
                    <td class="col-nomor">10.</td>
                    <td class="col-label">Keterangan lain-lain</td>
                    <td colspan="2">{{ $spd->keterangan ?? '-' }}</td>
                </tr>
            </table>

            <div class="tanda-tangan">
                @php
                    $bulanIndonesia = [
                        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                    ];

                    $tglDikeluarkan = $spd->tanggal_dikeluarkan ?? $spd->tanggal_berangkat ?? now();
                    $tanggal = \Carbon\Carbon::parse($tglDikeluarkan)->format('d') . ' ' .
                               $bulanIndonesia[\Carbon\Carbon::parse($tglDikeluarkan)->format('F')] . ' ' .
                               \Carbon\Carbon::parse($tglDikeluarkan)->format('Y');
                    $tempatDikeluarkan = $spd->tempat_dikeluarkan ?? 'Pelaihari';

                    $ttdNama = $spd->penggunaAnggaran->nama ?? '-';
                    $ttdNip = $spd->penggunaAnggaran->nip ?? '-';
                    $ttdPangkat = $spd->penggunaAnggaran->pangkat ?? '';
                    $ttdJabatan = $spd->penggunaAnggaran->jabatan ?? 'Pengguna Anggaran';
                @endphp

                <div class="tempat-tanggal">Dikeluarkan di : {{ $tempatDikeluarkan }}</div>
                <div class="tempat-tanggal">Pada Tanggal : {{ $tanggal }}</div>

                <div class="ttd-space"></div>

                <div class="jabatan-ttd">{{ $ttdJabatan }}</div>
                <div class="nama-ttd">{{ $ttdNama }}</div>
                <div class="nip-ttd">NIP. {{ $ttdNip }}</div>
            </div>

            <div class="clearfix"></div>
        </div>
    @endforeach

</body>
</html>
