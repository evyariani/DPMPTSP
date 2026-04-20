<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rincian Biaya Perjalanan Dinas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            background: white;
            color: black;
            margin: 0;
            padding: 1.2cm;
            line-height: 1.3;
        }

        /* JUDUL */
        .judul {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* TABEL INFO LAMPIRAN */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            border: none;
            padding: 3px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 140px;
        }
        .info-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        /* TABEL RINCIAN BIAYA - BORDER HITAM */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .bordered-table th,
        .bordered-table td {
            border: 1px solid black;
            padding: 6px 5px;
            vertical-align: top;
        }
        .bordered-table th {
            text-align: center;
            font-weight: bold;
            background: white;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }

        /* GARIS PEMISAH */
        .hr-line {
            border-top: 1px solid black;
            margin: 12px 0;
        }

        /* TANDA TANGAN 2 KOLOM (KIRI & KANAN) */
        .signature-wrapper {
            width: 100%;
            margin: 15px 0;
        }
        .sign-left {
            width: 48%;
            float: left;
            text-align: center;
        }
        .sign-right {
            width: 48%;
            float: right;
            text-align: center;
        }
        .sign-space {
            margin-top: 30px;
        }
        .nip {
            margin-top: 3px;
        }
        .clearfix {
            clear: both;
        }

        /* PERHITUNGAN SPPD RAMPUNG */
        .calculation-box {
            margin-top: 15px;
            width: 100%;
        }
        .calculation-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        .calculation-item {
            margin: 4px 0;
        }
        .calculation-label {
            display: inline-block;
            width: 180px;
        }

        /* KEPALA DINAS */
        .kepala-box {
            text-align: center;
            margin-top: 30px;
        }
        .ttd-space {
            margin-top: 40px;
        }

        /* GARIS BAWAH UNTUK TANDA TANGAN */
        .sign-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid black;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php
        // Data dari database
        $nomorSppd = $rincian->nomor ?? '000.1.2.3/DPMPTSP/2025';
        $tanggal = isset($rincian->tanggal) ? \Carbon\Carbon::parse($rincian->tanggal) : \Carbon\Carbon::now();
        $tujuan = $rincian->tujuan ?? 'Kota Banjarbaru';
        $transport = $rincian->transport ?? 250000;
        $total = $rincian->total ?? 850000;
        $terbilang = $rincian->terbilang ?? 'Delapan Ratus Lima Puluh Ribu Rupiah';
        
        $pegawaiList = $rincian->pegawai ?? [];
        if (empty($pegawaiList)) {
            $pegawaiList = [
                ['nama' => 'LIDIA MIRANTI MAYASARI, SE', 'nip' => '19840817 200903 2 022', 'nominal' => 300000, 'hari' => 1],
                ['nama' => 'NURLITA FEBRIANA PRATWI, A.Md', 'nip' => '19980208 202012 2 007', 'nominal' => 300000, 'hari' => 1]
            ];
        }
        
        // Hitung total uang harian
        $totalUangHarian = 0;
        foreach($pegawaiList as $p) {
            $totalUangHarian += ($p['nominal'] * $p['hari']);
        }
        
        $bendaharaNama = $rincian->bendahara['nama'] ?? 'NURLITA FEBRIANA PRATWI, A.Md';
        $bendaharaNip = $rincian->bendahara['nip'] ?? '19980208 202012 2 007';
        $kepalaDinasNama = $rincian->kepala_dinas['nama'] ?? 'BUDI ANDRIAN SUTANTO, S. Sos., MM';
        $kepalaDinasNip = $rincian->kepala_dinas['nip'] ?? '19760218 200701 1 006';
        
        $bulanIndonesia = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        $tanggalFormat = $tanggal->format('d') . ' ' . $bulanIndonesia[$tanggal->format('F')] . ' ' . $tanggal->format('Y');
    ?>

    <!-- JUDUL -->
    <div class="judul">RINCIAN BIAYA PERJALANAN DINAS</div>

    <!-- LAMPIRAN SPPD NOMOR & TANGGAL -->
    <table class="info-table">
        <tr><td class="bold">Lampiran SPPD Nomor</td><td>:</td><td class="bold"><?php echo e($nomorSppd); ?></td></tr>
        <tr><td>Tanggal</td><td>:</td><td><?php echo e($tanggalFormat); ?></td></tr>
    </table>

    <!-- TABEL RINCIAN BIAYA (PERSIS SEPERTI GAMBAR) -->
    <table class="bordered-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 44%;">Perincian Biaya</th>
                <th style="width: 24%;">Jumlah (Rp)</th>
                <th style="width: 24%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- BARIS UANG HARIAN -->
            <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <?php if($index == 0): ?>
                <td class="text-center" style="vertical-align: middle;" rowspan="<?php echo e(count($pegawaiList)); ?>">1</td>
                <td style="vertical-align: middle;">
                    Uang Harian Ke : <?php echo e($tujuan); ?><br>
                </td>
                <?php else: ?>
                <td style="vertical-align: middle;">
                </td>
                <?php endif; ?>
                <td class="text-right" style="vertical-align: middle;">
                    Rp <?php echo e(number_format($pegawai['nominal'])); ?> x <?php echo e($pegawai['hari']); ?> (satu hari)
                </td>
                <td class="text-right" style="vertical-align: middle;">
                    Rp <?php echo e(number_format($pegawai['nominal'] * $pegawai['hari'])); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <!-- BARIS UANG TRANSPORT -->
            <tr>
                <td class="text-center">2</td>
                <td>Uang Transport</td>
                <td class="text-right">Rp <?php echo e(number_format($transport)); ?></td>
                <td class="text-right">Rp <?php echo e(number_format($total)); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- TERBILANG -->
    <div class="italic" style="margin: 8px 0 12px 0;">Terbilang : <?php echo e($terbilang); ?></div>

    <!-- GARIS PEMISAH -->
    <div class="hr-line"></div>

    <!-- TANDA TANGAN: 2 KOLOM (BENDAHARA KIRI, YANG MENERIMA KANAN) -->
    <div class="signature-wrapper">
        <!-- KOLOM KIRI - BENDAHARA PENGELUARAN -->
        <div class="sign-left">
            <div>Pelaihari, <?php echo e($tanggal->format('Y')); ?></div>
            <div style="margin-top: 8px;">Telah dibayar sejumlah:</div>
            <div class="bold" style="margin: 4px 0;">Rp <?php echo e(number_format($total)); ?></div>
            <div style="margin-top: 8px;">Bendahara Pengeluaran,</div>
            <div class="sign-space">
                <div class="sign-line"></div>
                <div class="bold" style="margin-top: 5px;"><?php echo e($bendaharaNama); ?></div>
                <div class="nip">NIP. <?php echo e($bendaharaNip); ?></div>
            </div>
        </div>
        
        <!-- KOLOM KANAN - YANG MENERIMA -->
        <div class="sign-right">
            <div>&nbsp;</div>
            <div style="margin-top: 8px;">Telah menerima jumlah uang sebesar:</div>
            <div class="bold" style="margin: 4px 0;">Rp <?php echo e(number_format($total)); ?></div>
            <div style="margin-top: 8px;">Yang Menerima,</div>
            <div class="sign-space">
                <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="sign-line"></div>
                    <div class="bold" style="margin-top: 5px;"><?php echo e($pegawai['nama']); ?></div>
                    <div class="nip">NIP. <?php echo e($pegawai['nip'] ?? '-'); ?></div>
                    <?php if(!$loop->last): ?>
                        <div style="margin-top: 15px;"></div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <!-- GARIS PEMISAH -->
    <div class="hr-line"></div>

    <!-- PERHITUNGAN SPPD RAMPUNG -->
    <div class="calculation-box">
        <div class="calculation-title">PERHITUNGAN SPPD RAMPUNG</div>
        <div class="calculation-item">
            <span class="calculation-label">Ditetapkan sejumlah</span>
            <span>: Rp <?php echo e(number_format($total)); ?></span>
        </div>
        <div class="calculation-item">
            <span class="calculation-label">Yang telah dibayar semula</span>
            <span>: Rp -</span>
        </div>
        <div class="calculation-item">
            <span class="calculation-label">Sisa kurang / lebih</span>
            <span>: Rp <?php echo e(number_format($total)); ?></span>
        </div>
    </div>

    <!-- KEPALA DINAS -->
    <div class="kepala-box">
        <div>Kepala Dinas</div>
        <div>Kab. Tanah Laut</div>
        <div class="ttd-space">
            <div class="sign-line" style="width: 250px;"></div>
            <div class="bold" style="margin-top: 5px;"><?php echo e($kepalaDinasNama); ?></div>
            <div class="nip">NIP. <?php echo e($kepalaDinasNip); ?></div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\POLITALA\DPMPTSP\DPMPTSP\resources\views/admin/rincian-pdf.blade.php ENDPATH**/ ?>