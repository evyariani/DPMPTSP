<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Perjalanan Dinas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #e0e0e0;
            padding: 20px;
        }
        
        .kwitansi-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* KOP SURAT DENGAN LOGO */
        .kop-surat {
            margin-bottom: 15px;
        }
        
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .kop-table td {
            vertical-align: middle;
            padding: 5px 0;
        }
        
        .logo-cell {
            width: 100px;
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: auto;
        }
        
        .logo img {
            max-width: 100%;
            height: auto;
        }
        
        .header-text {
            text-align: center;
        }
        
        .header-text .pemkab {
            font-size: 14px;
            font-weight: normal;
            letter-spacing: 2px;
        }
        
        .header-text .dinas {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .header-text .alamat {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .header-text .kontak {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .garis-kop {
            border-bottom: 2px solid #000;
            margin-top: 8px;
            margin-bottom: 15px;
        }
        
        /* Tabel Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .info-table td {
            padding: 4px 5px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 110px;
            font-weight: bold;
        }
        
        .info-table td:nth-child(2) {
            width: 15px;
        }
        
        .info-table td:nth-child(3) {
            width: 35%;
        }
        
        .info-table td:nth-child(4) {
            width: 130px;
            font-weight: bold;
        }
        
        .info-table td:nth-child(5) {
            width: 15px;
        }
        
        .info-table td:nth-child(6) {
            width: 35%;
        }
        
        /* Judul Kwitansi */
        .judul-kwitansi {
            text-align: center;
            margin: 25px 0 20px 0;
        }
        
        .judul-kwitansi h1 {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            text-decoration: underline;
        }
        
        /* Body Kwitansi */
        .body-kwitansi {
            margin: 20px 0 25px 0;
        }
        
        .body-row {
            margin-bottom: 12px;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .body-label {
            font-weight: bold;
            display: inline-block;
            width: 160px;
            vertical-align: top;
        }
        
        .body-value {
            display: inline-block;
            width: calc(100% - 170px);
            vertical-align: top;
        }
        
        /* Terbilang */
        .terbilang-box {
            margin: 20px 0 30px 0;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        
        .terbilang-box .label {
            font-weight: bold;
            font-size: 12px;
        }
        
        .terbilang-box .value {
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Tabel Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 11px;
            text-align: center;
        }
        
        .ttd-table td {
            width: 33.33%;
            padding: 15px 10px;
            vertical-align: top;
        }
        
        .ttd-table .ttd-title {
            font-weight: bold;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        
        .ttd-table .ttd-name {
            font-weight: bold;
            margin-top: 45px;
            margin-bottom: 3px;
            text-decoration: underline;
            font-size: 12px;
        }
        
        .ttd-table .ttd-nip {
            font-size: 10px;
            margin-top: 3px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
            .kwitansi-container {
                padding: 10px;
                box-shadow: none;
            }
            .no-print {
                display: none;
            }
            button {
                display: none;
            }
        }
        
        button {
            margin: 20px auto;
            display: block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        
        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="kwitansi-container">
        <!-- KOP SURAT DENGAN LOGO -->
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="logo-cell">
                        <div class="logo">
                            <img src="<?php echo e(public_path('image/Logo_Tala-removebg-preview (2).png')); ?>" alt="Logo Kabupaten Tanah Laut">
                        </div>
                    </td>
                    <td class="header-text">
                        <div class="pemkab">PEMERINTAH KABUPATEN TANAH LAUT</div>
                        <div class="dinas">DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU</div>
                        <div class="alamat">Jalan H. Boejasin, Pelaihari, Kab. Tanah Laut, Kalimantan Selatan 70814</div>
                        <div class="kontak">Laman https://dpmptsp.tanahlautkab.go.id | Pos-el dpmptsptanahlautkab@gmail.com</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="garis-kop"></div>

        <!-- TABEL INFORMASI - DATA DARI DATABASE -->
        <table class="info-table">
            <tr>
                <td>TAHUN ANGGARAN</td>
                <td>:</td>
                <td><?php echo e($kwitansi->tahun_anggaran ?? ''); ?></td>
                <td>NO. BUKU KAS UMUM</td>
                <td>:</td>
                <td><?php echo e($kwitansi->no_bku ?? ''); ?></td>
            </tr>
            <tr>
                <td>KODE REKENING</td>
                <td>:</td>
                <td><?php echo e($kwitansi->kode_rekening ?? ''); ?></td>
                <td>NO. BRPP</td>
                <td>:</td>
                <td><?php echo e($kwitansi->no_brpp ?? ''); ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td>:</td>
                <td colspan="4"><?php echo e($kwitansi->sub_kegiatan ?? ''); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="4">Asli - I - II - III - IV</td>
            </tr>
        </table>

        <!-- JUDUL KWITANSI -->
        <div class="judul-kwitansi">
            <h1>KWITANSI</h1>
        </div>

        <!-- BODY KWITANSI - DATA DARI DATABASE -->
        <div class="body-kwitansi">
            <div class="body-row">
                <span class="body-label">SUDAH TERIMA DARI :</span>
                <span class="body-value">BENDAHARA PENGELUARAN DINAS PENANAMAN MODAL DAN PTSP KAB. TANAH LAUT</span>
            </div>
            <div class="body-row">
                <span class="body-label">UANG SEBANYAK :</span>
                <span class="body-value"><?php echo e($kwitansi->terbilang); ?></span>
            </div>
            <div class="body-row">
                <span class="body-label">UNTUK PEMBAYARAN :</span>
                <span class="body-value"><?php echo e($kwitansi->untuk_pembayaran); ?></span>
            </div>
        </div>

        <!-- TERBILANG -->
        <div class="terbilang-box">
            <span class="label">Terbilang Rp. :</span>
            <span class="value">Rp <?php echo e(number_format($kwitansi->nominal, 0, ',', '.')); ?></span>
        </div>

        <!-- TABEL TANDA TANGAN - 3 KOLOM SEJAJAR -->
        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-title">Setuju dibayar</div>
                    <div class="ttd-title">Pengguna Anggaran</div>
                    <div class="ttd-name"><?php echo e($kwitansi->pengguna_anggaran ?: '-'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_pengguna_anggaran ?: '-'); ?></div>
                </td>
                <td>
                    <div class="ttd-title">Telah dibayar lunas pada tanggal</div>
                    <div class="ttd-title"><?php echo e($kwitansi->tanggal_kwitansi ? \Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('d F Y') : '-'); ?></div>
                    <div class="ttd-title" style="margin-top: 15px;">Bendahara Pengeluaran</div>
                    <div class="ttd-name"><?php echo e($kwitansi->bendahara_pengeluaran ?: '-'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_bendahara ?: '-'); ?></div>
                </td>
                <td>
                    <div class="ttd-title">Pelaihari,</div>
                    <div class="ttd-title"><?php echo e($kwitansi->tanggal_kwitansi ? \Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('d F Y') : '-'); ?></div>
                    <div class="ttd-title" style="margin-top: 15px;">Yang menerima,</div>
                    <div class="ttd-name"><?php echo e($kwitansi->penerima ?: '-'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_penerima ?: '-'); ?></div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        // Script untuk cetak otomatis jika diperlukan
        // window.print();
    </script>
</body>
</html><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-pdf.blade.php ENDPATH**/ ?>