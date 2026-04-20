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
            background: white;
            padding: 20px;
        }
        
        .kwitansi-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
        
        /* Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .kop-surat h2 {
            font-size: 14px;
            font-weight: normal;
            letter-spacing: 2px;
        }
        
        .kop-surat h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .kop-surat p {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .kop-surat .website {
            font-size: 10px;
        }
        
        /* Tabel Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 130px;
            font-weight: bold;
        }
        
        .info-table td:nth-child(2) {
            width: 10px;
        }
        
        /* Judul Kwitansi */
        .judul-kwitansi {
            text-align: center;
            margin: 20px 0;
        }
        
        .judul-kwitansi h1 {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            text-decoration: underline;
        }
        
        /* Body Kwitansi */
        .body-kwitansi {
            margin: 20px 0;
        }
        
        .body-row {
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .body-label {
            font-weight: bold;
            display: inline-block;
            width: 130px;
        }
        
        .body-value {
            display: inline;
        }
        
        /* Terbilang */
        .terbilang-box {
            margin: 15px 0;
        }
        
        .terbilang-box .label {
            font-weight: bold;
            font-size: 12px;
        }
        
        .terbilang-box .value {
            font-size: 12px;
        }
        
        .nominal-box {
            margin: 10px 0;
        }
        
        .nominal-box .label {
            font-weight: bold;
            font-size: 12px;
        }
        
        .nominal-box .value {
            font-size: 14px;
            font-weight: bold;
        }
        
        /* Tabel Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 11px;
            text-align: center;
        }
        
        .ttd-table td {
            padding: 20px 10px;
            vertical-align: top;
        }
        
        .ttd-table .ttd-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .ttd-table .ttd-name {
            font-weight: bold;
            margin-top: 40px;
            text-decoration: underline;
        }
        
        .ttd-table .ttd-nip {
            font-size: 10px;
        }
        
        /* Border untuk kwitansi */
        .kwitansi-border {
            border: 1px solid #000;
            padding: 15px;
        }
        
        /* Garis pembatas */
        .garis-bawah {
            border-bottom: 1px solid #000;
            margin: 10px 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .kwitansi-container {
                padding: 10px;
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
        <!-- KOP SURAT -->
        <div class="kop-surat">
            <h2>PEMERINTAH KABUPATEN TANAH LAUT</h2>
            <h1>DINAS PENANAMAN MODAL DAN PTSP</h1>
            <p>Jalan H. Boejasin, Pelaihari, Kab. Tanah Laut, Kalimantan Selatan 70814</p>
            <p class="website">Laman https://dpmpts.p.tanahlautkab.go.id Pos-el dpmpts.ptanahlautkab@gmail.com</p>
        </div>

        <!-- TABEL INFORMASI -->
        <table class="info-table">
            <tr>
                <td>TAHUN ANGGARAN</td>
                <td>:</td>
                <td><?php echo e($kwitansi->tahun_anggaran ?? '2025'); ?></td>
                <td style="width: 50px;"></td>
                <td>NO. BUKU KAS UMUM</td>
                <td>:</td>
                <td><?php echo e($kwitansi->no_bku ?? '...../SRJ/GU-/2025'); ?></td>
            </tr>
            <tr>
                <td>KODE REKENING</td>
                <td>:</td>
                <td><?php echo e($kwitansi->kode_rekening ?? '5.1.02.04.01.0001'); ?></td>
                <td></td>
                <td>NO. BRPP</td>
                <td>:</td>
                <td><?php echo e($kwitansi->no_brpp ?? ''); ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td>:</td>
                <td colspan="5"><?php echo e($kwitansi->sub_kegiatan ?? 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="5">Asli - I - II - II - IV</td>
            </tr>
        </table>

        <!-- JUDUL KWITANSI -->
        <div class="judul-kwitansi">
            <h1>KWITANSI</h1>
        </div>

        <!-- BODY KWITANSI -->
        <div class="body-kwitansi">
            <div class="body-row">
                <span class="body-label">SUDAH TERIMA DARI :</span>
                <span class="body-value">BENDAHARA PENGELUARAN DINAS PENANAMAN MODAL DAN PTSP KAB. TANAH LAUT</span>
            </div>
            <div class="body-row">
                <span class="body-label">UANG SEBANYAK :</span>
                <span class="body-value"><?php echo e($kwitansi->terbilang ?? 'Enam Ratus Ribu Rupiah'); ?></span>
            </div>
            <div class="body-row">
                <span class="body-label">UNTUK PEMBAYARAN :</span>
                <span class="body-value"><?php echo e($kwitansi->untuk_pembayaran ?? 'Biaya perjalanan dinas ke Kota Banjarbaru pada tanggal 14 Oktober 2025 Dalam rangka Mengikuti Kegiatan Peningkatan Kapabilitas Verifikator dan PPK (Sekretaris) SKPD an. LIDIA MIRANTI MAYASARI, SE, NIK 6301035708840013 Rek Bank Kalsel 2001337508 Alamat Jl. Bougenvil No. 25 Blok. A Raya Kel. Pabahanan, SPT dan SPD terlampir'); ?></span>
            </div>
        </div>

        <!-- TERBILANG DAN NOMINAL -->
        <div class="terbilang-box">
            <span class="label">Terbilang Rp. :</span>
            <span class="value">Rp <?php echo e(number_format($kwitansi->nominal ?? 600000, 0, ',', '.')); ?></span>
        </div>

        <!-- TABEL TANDA TANGAN -->
        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-title">Setuju dibayar</div>
                    <div class="ttd-title">Pengguna Anggaran</div>
                    <div class="ttd-name"><?php echo e($kwitansi->pengguna_anggaran ?? 'BUDI ANDRIAN SUTANTO, S. Sos., MM'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_pengguna_anggaran ?? '19760218 200701 1 006'); ?></div>
                </td>
                <td>
                    <div class="ttd-title">Telah dibayar lunas pada tanggal</div>
                    <div class="ttd-title">Bendahara Pengeluaran</div>
                    <div class="ttd-name"><?php echo e($kwitansi->bendahara_pengeluaran ?? 'NURLITA FEBRANA PRATWI, A.Md'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_bendahara ?? '19980208 202012 2 007'); ?></div>
                </td>
                <td>
                    <div class="ttd-title">Pelaihari, <?php echo e($kwitansi->tanggal_kwitansi ? \Carbon\Carbon::parse($kwitansi->tanggal_kwitansi)->format('d F Y') : '...... 2025'); ?></div>
                    <div class="ttd-title">Yang menerima,</div>
                    <div class="ttd-name"><?php echo e($kwitansi->penerima ?? 'LIDIA MIRANTI MAYASARI, SE'); ?></div>
                    <div class="ttd-nip">NIP. <?php echo e($kwitansi->nip_penerima ?? '19840817 200903 2 022'); ?></div>
                </td>
            </tr>
        </table>
    </div>

    <button onclick="window.print()" class="no-print">🖨️ Cetak Kwitansi</button>

    <script>
        // Auto print when page loads (optional)
        // window.print();
    </script>
</body>
</html><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/kwitansi-pdf.blade.php ENDPATH**/ ?>