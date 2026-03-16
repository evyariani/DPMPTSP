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
            font-size: 12pt;
            margin: 1cm 2cm 2cm 2cm; /* Atas: 1cm, Kanan: 2cm, Bawah: 2cm, Kiri: 2cm */
            color: #000;
            line-height: 1.2;
        }
        
        /* KOP SURAT MENGGUNAKAN TABEL YANG DIHIDE BORDERNYA */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px; /* Space dengan garis kop */
            border: none; /* Sembunyikan border tabel */
        }
        
        .kop-table td {
            border: none; /* Sembunyikan border sel */
            padding: 0;
            vertical-align: middle; /* Center vertikal */
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
            text-align: center; /* Center teks horizontal */
        }
        
        .header-text .pemkab {
            font-family: Arial, sans-serif;
            font-size: 16px;
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
            font-size: 21px;
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
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .header-text .kontak {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        /* GARIS KOP - TIPIS ATAS TEBAL BAWAH */
        .garis-kop {
            width: 100%;
            margin: 0 0 15px 0; /* Hapus margin top, biarkan margin bottom saja */
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
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        
        .judul-surat .nomor {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* DASAR */
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .section-subtitle {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 15px 0 10px 0;
        }
        
        .dasar-item {
            margin-bottom: 5px;
            overflow: hidden;
        }
        
        .dasar-nomor {
            float: left;
            width: 25px;
            text-align: right;
            margin-right: 5px;
        }
        
        .dasar-teks {
            margin-left: 30px;
            display: block;
        }
        
        /* PEGAWAI */
        .pegawai-item {
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .pegawai-header {
            margin-bottom: 2px;
            overflow: hidden;
        }
        
        .kepada-label {
            float: left;
            width: 70px;
            font-weight: normal;
        }
        
        .pegawai-nomor {
            float: left;
            width: 25px;
            text-align: right;
            margin-right: 5px;
        }
        
        .pegawai-detail {
            margin-left: 30px;
        }
        
        .detail-row {
            margin-bottom: 2px;
            overflow: hidden;
        }
        
        .detail-label {
            float: left;
            width: 100px;
        }
        
        .detail-titikdua {
            float: left;
            width: 15px;
            text-align: center;
        }
        
        .detail-value {
            margin-left: 115px;
            display: block;
        }
        
        /* UNTUK */
        .untuk-item {
            margin-bottom: 5px;
            overflow: hidden;
        }
        
        .untuk-label {
            float: left;
            width: 70px;
        }
        
        .untuk-nomor {
            float: left;
            width: 25px;
            text-align: right;
            margin-right: 5px;
        }
        
        .untuk-teks {
            margin-left: 100px;
            display: block;
        }
        
        /* TANDA TANGAN */
        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 40px;
        }
        
        .tempat-tanggal {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .jabatan-ttd {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .nama-ttd {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        
        .pangkat-ttd {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin-bottom: 2px;
        }
        
        .nip-ttd {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
        }
        
        .clearfix {
            clear: both;
        }
        
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT MENGGUNAKAN TABEL TERSEMBUNYI UNTUK KERAPIHAN -->
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <div class="logo">
                    <img src="{{ public_path('image/Logo_Tala-removebg-preview (2).png') }}" alt="Logo">
                </div>
            </td>
            <td class="header-text">
                <div class="pemkab">PEMERINTAH KABUPATEN TANAH LAUT</div>
                <div class="dinas">DINAS PENANAMAN MODAL DAN PTSP</div>
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

    <!-- DASAR -->
    <div class="section">
        <div class="section-title">DASAR :</div>
        @foreach($dasarList as $index => $dasar)
        <div class="dasar-item">
            <span class="dasar-nomor">{{ $index + 1 }}.</span>
            <span class="dasar-teks">{{ $dasar }}</span>
        </div>
        @endforeach
    </div>

    <!-- MEMERINTAHKAN -->
    <div class="section">
        <div class="section-subtitle">MEMERINTAHKAN</div>
        
        @foreach($pegawaiList as $index => $pegawai)
        <div class="pegawai-item">
            <span class="pegawai-nomor">{{ $index + 1 }}.</span>
            <div class="pegawai-detail">
                <div class="detail-row">
                    <span class="detail-label">Nama</span>
                    <span class="detail-titikdua">:</span>
                    <span class="detail-value">{{ $pegawai->nama }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pangkat/gol</span>
                    <span class="detail-titikdua">:</span>
                    <span class="detail-value">
                        @if($pegawai->pangkat && $pegawai->gol)
                            {{ $pegawai->pangkat }} ({{ $pegawai->gol }})
                        @elseif($pegawai->pangkat)
                            {{ $pegawai->pangkat }}
                        @elseif($pegawai->gol)
                            Gol. {{ $pegawai->gol }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">NIP</span>
                    <span class="detail-titikdua">:</span>
                    <span class="detail-value">{{ $pegawai->nip ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jabatan</span>
                    <span class="detail-titikdua">:</span>
                    <span class="detail-value">{{ $pegawai->jabatan ?? '-' }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- UNTUK -->
    <div class="section">
        <div class="untuk-item">
            <span class="untuk-label">Untuk :</span>
            <span class="untuk-nomor">1.</span>
            <span class="untuk-teks">{{ $spt->tujuan }}</span>
        </div>
    </div>

    <!-- TANDA TANGAN -->
    <div class="tanda-tangan">
        <div class="tempat-tanggal">Pelaihari, {{ $spt->tanggal->format('d F Y') }}</div>
        <div class="jabatan-ttd">{{ $spt->penandaTangan->jabatan ?? 'Kepala Dinas' }}</div>
        
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