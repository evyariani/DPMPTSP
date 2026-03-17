<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Tugas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt; /* SEMUA FONT ISI 12PT */
            margin: 1cm 2cm 2cm 2cm;
            color: #000;
            line-height: 1.2;
        }
        
        /* KOP SURAT MENGGUNAKAN TABEL YANG DIHIDE BORDERNYA */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: none;
        }
        
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        
        .logo-cell {
            width: 55px;
            padding-right: 5px;
        }
        
        .logo {
            width: 55px;
            height: 55px;
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
            font-size: 16px; /* KOP TETAP 16 */
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .header-text .dinas {
            font-family: Arial, sans-serif;
            font-size: 16px; /* KOP TETAP 16 */
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
            letter-spacing: -0.2px;
        }
        
        .header-text .alamat {
            font-family: Arial, sans-serif;
            font-size: 11px; /* KOP TETAP 11 */
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .header-text .kontak {
            font-family: Arial, sans-serif;
            font-size: 11px; /* KOP TETAP 11 */
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        /* GARIS KOP */
        .garis-kop {
            width: 100%;
            margin: 0 0 15px 0;
            clear: both;
            border-top: 1px solid #000;
            border-bottom: 3px solid #000;
            height: 2px;
        }
        
        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .judul-surat h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px; /* JUDUL 16 */
            font-weight: normal;
            text-transform: uppercase;
            margin: 0;
        }
        
        .judul-surat .nomor {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px; /* NOMOR 14 */
            margin-top: 5px;
        }
        
        /* MEMERINTAHKAN */
        .memerintahkan {
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt; /* DIUBAH JADI 12PT */
            font-weight: normal;
            text-transform: uppercase;
            margin: 15px 0 10px 0;
        }
        
        /* TABEL UNTUK DASAR/KEPADA/UNTUK */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .content-table td {
            padding: 1px 2px;
            vertical-align: top;
            border: none;
            font-size: 12pt; /* SEMUA FONT ISI 12PT */
            font-family: 'Times New Roman', Times, serif;
        }
        
        .label-col {
            width: 60px;
            font-weight: normal;
        }
        
        .titikdua-col {
            width: 15px;
            text-align: center;
        }
        
        .nomor-col {
            width: 25px;
            text-align: right;
        }
        
        .sub-label-col {
            width: 80px;
            padding-left: 5px;
        }
        
        .sub-titikdua-col {
            width: 15px;
            text-align: center;
        }
        
        .content-col {
            text-align: left;
        }
        
        .spacer-row td {
            height: 10px;
        }
        
        .pegawai-spacer td {
            height: 15px; /* Space enter setelah setiap pegawai */
        }
        
        /* SPACE SETELAH KEPADA DAN SETELAH UNTUK */
        .section-spacer {
            height: 20px; /* Space enter setelah Kepada dan setelah Untuk */
        }
        
        /* TANDA TANGAN */
        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 40px;
            font-size: 12pt; /* FONT TANDA TANGAN 12PT */
            font-family: 'Times New Roman', Times, serif;
        }
        
        .tempat-tanggal {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .jabatan-ttd {
            font-size: 12pt;
            margin-bottom: 15px;
        }
        
        .enter-dua-kali {
            height: 30px;
        }
        
        .nama-ttd {
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        
        .pangkat-ttd {
            font-size: 12pt;
            margin-bottom: 2px;
        }
        
        .nip-ttd {
            font-size: 12pt;
        }
        
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <div class="logo">
                    <img src="{{ public_path('image/Logo_Tala-removebg-preview (2).png') }}" alt="Logo">
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
    
    <!-- GARIS KOP -->
    <div class="garis-kop"></div>

    <!-- JUDUL SURAT -->
    <div class="judul-surat">
        <h1>SURAT PERINTAH TUGAS</h1>
        <div class="nomor">Nomor : {{ $spt->nomor_surat }}</div>
    </div>

    <!-- CONTENT TABLE - DASAR -->
    <table class="content-table">
        <!-- DASAR -->
        <tr>
            <td class="label-col">Dasar</td>
            <td class="titikdua-col">:</td>
            <td class="nomor-col">1.</td>
            <td colspan="3">{{ $dasarList[0] ?? '' }}</td>
        </tr>
        @if(isset($dasarList[1]))
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">2.</td>
            <td colspan="3">{{ $dasarList[1] }}</td>
        </tr>
        @endif
        @if(isset($dasarList[2]))
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">3.</td>
            <td colspan="3">{{ $dasarList[2] }}</td>
        </tr>
        @endif
        
        <!-- SPACER -->
        <tr class="spacer-row"><td colspan="6"></td></tr>
    </table>

    <!-- MEMERINTAHKAN -->
    <div class="memerintahkan">MEMERINTAHKAN</div>

    <!-- LANJUTAN TABEL UNTUK KEPADA DAN UNTUK -->
    <table class="content-table">
        <!-- KEPADA -->
        @foreach($pegawaiList as $index => $pegawai)
        <tr>
            @if($index == 0)
            <td class="label-col">Kepada</td>
            <td class="titikdua-col">:</td>
            @else
            <td></td>
            <td></td>
            @endif
            <td class="nomor-col">{{ $index + 1 }}.</td>
            <td class="sub-label-col">Nama</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->nama }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">Pangkat/gol</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">
                @if($pegawai->pangkat && $pegawai->gol)
                    {{ $pegawai->pangkat }} ({{ $pegawai->gol }})
                @elseif($pegawai->pangkat)
                    {{ $pegawai->pangkat }}
                @elseif($pegawai->gol)
                    Gol. {{ $pegawai->gol }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">NIP</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">Jabatan</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->jabatan ?? '-' }}</td>
        </tr>
        @if(!$loop->last)
        <tr class="pegawai-spacer"><td colspan="6"></td></tr>
        @endif
        @endforeach
        
        <!-- SPACE ENTER SETELAH KEPADA (SEBELUM UNTUK) -->
        <tr class="section-spacer"><td colspan="6"></td></tr>
        
        <!-- UNTUK -->
        <tr>
            <td class="label-col">Untuk</td>
            <td class="titikdua-col">:</td>
            <td class="nomor-col">1.</td>
            <td colspan="3">{{ $spt->tujuan }}</td>
        </tr>
        @if(isset($spt->tujuan_lain) && $spt->tujuan_lain)
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">2.</td>
            <td colspan="3">{{ $spt->tujuan_lain }}</td>
        </tr>
        @endif
        
        <!-- SPACE ENTER SETELAH UNTUK -->
        <tr class="section-spacer"><td colspan="6"></td></tr>
    </table>

    <!-- TANDA TANGAN -->
    <div class="tanda-tangan">
        @php
            // Array bulan dalam bahasa Indonesia
            $bulanIndonesia = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];
            
            // Format tanggal dengan bulan Indonesia
            $tanggal = $spt->tanggal->format('d') . ' ' . 
                       $bulanIndonesia[$spt->tanggal->format('F')] . ' ' . 
                       $spt->tanggal->format('Y');
        @endphp
        
        <div class="tempat-tanggal">Pelaihari, {{ $tanggal }}</div>
        <div class="jabatan-ttd">{{ $spt->penandaTangan->jabatan ?? 'Kepala Dinas' }}</div>
        
        <!-- ENTER 2 KALI -->
        <div style="height: 50px;"></div>
        
        <div class="nama-ttd">{{ $spt->penandaTangan->nama ?? '-' }}</div>
        <div class="pangkat-ttd">
            @if($spt->penandaTangan->pangkat && $spt->penandaTangan->gol)
                {{ $spt->penandaTangan->pangkat }} ({{ $spt->penandaTangan->gol }})
            @elseif($spt->penandaTangan->pangkat)
                {{ $spt->penandaTangan->pangkat }}
            @elseif($spt->penandaTangan->gol)
                Gol. {{ $spt->penandaTangan->gol }}
            @endif
        </div>
        <div class="nip-ttd">NIP. {{ $spt->penandaTangan->nip ?? '-' }}</div>
    </div>
    
    <div class="clearfix"></div>
</body>
</html>