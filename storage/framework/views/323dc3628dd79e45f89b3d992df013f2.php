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
            overflow: hidden;
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
        
        /* PAGE BREAK */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <?php
        // ========== AMBIL DATA DARI DATABASE VIA $rincian ==========
        
        // Nomor SPPD (dari tb_rincianbidang.nomor_sppd)
        $nomorSppd = $rincian->nomor_sppd ?? '-';
        
        // Tanggal (gunakan created_at atau tanggal_berangkat)
        $tanggal = $rincian->created_at ?? $rincian->tanggal_berangkat ?? now();
        if ($tanggal instanceof \Carbon\Carbon) {
            $tanggal = \Carbon\Carbon::parse($tanggal);
        } else {
            $tanggal = \Carbon\Carbon::now();
        }
        
        // Tujuan (dari tb_rincianbidang.tempat_tujuan)
        $tujuan = $rincian->tempat_tujuan ?? '-';
        
        // Data pegawai dari JSON
        $pegawaiList = is_array($rincian->pegawai) ? $rincian->pegawai : [];
        
        // Uang harian per hari (dari tb_rincianbidang.uang_harian)
        $uangHarian = $rincian->uang_harian ?? 0;
        
        // Lama perjalanan (dari tb_rincianbidang.lama_perjadin)
        $lamaPerjadin = $rincian->lama_perjadin ?? 1;
        
        // Total (dari tb_rincianbidang.total)
        $total = $rincian->total_keseluruhan ?? $rincian->total ?? 0;
        
        // Terbilang (dari tb_rincianbidang.terbilang)
        $terbilang = $rincian->terbilang ?? 'Nol Rupiah';
        
        // Bendahara (dari snapshot)
        $bendaharaNama = $rincian->bendahara_nama ?? '-';
        $bendaharaNip = $rincian->bendahara_nip ?? '-';
        $bendaharaJabatan = $rincian->bendahara_jabatan ?? '-';
        
        // ========== FALLBACK jika data kosong ==========
        if (empty($pegawaiList)) {
            // Coba ambil dari relasi spd jika ada
            if ($rincian->spd && $rincian->spd->pelaksanaPerjadin) {
                foreach ($rincian->spd->pelaksanaPerjadin as $peg) {
                    $pegawaiList[] = [
                        'id_pegawai' => $peg->id_pegawai,
                        'nama' => $peg->nama,
                        'nip' => $peg->nip ?? '-',
                        'jabatan' => $peg->jabatan ?? '-',
                        'gol' => $peg->gol ?? '-',
                        'nominal' => $uangHarian,
                        'hari' => $lamaPerjadin,
                    ];
                }
            }
        }
        
        // Hitung total uang harian per pegawai
        foreach($pegawaiList as &$p) {
            $p['nominal'] = $p['nominal'] ?? $uangHarian;
            $p['hari'] = $p['hari'] ?? $lamaPerjadin;
            $p['subtotal'] = ($p['nominal'] ?? 0) * ($p['hari'] ?? 1);
        }
        
        // Nama bulan Indonesia
        $bulanIndonesia = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        $tanggalFormat = $tanggal->format('d') . ' ' . $bulanIndonesia[$tanggal->format('F')] . ' ' . $tanggal->format('Y');
        
        // Format tempat tujuan untuk uang harian
        $tujuanText = $tujuan;
    ?>

    <!-- JUDUL -->
    <div class="judul">RINCIAN BIAYA PERJALANAN DINAS</div>

    <!-- LAMPIRAN SPPD NOMOR & TANGGAL -->
    <table class="info-table">
        <tr><td class="bold">Lampiran SPPD Nomor</td><td>:</td><td class="bold"><?php echo e($nomorSppd); ?></td></tr>
        <tr><td>Tanggal</td><td>:</td><td><?php echo e($tanggalFormat); ?></td></tr>
    </table>

    <!-- TABEL RINCIAN BIAYA -->
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
                    Uang Harian Ke : <?php echo e($tujuanText); ?>

                </td>
                <?php else: ?>
                <td style="vertical-align: middle;">
                </td>
                <?php endif; ?>
                <td class="text-right" style="vertical-align: middle;">
                    <?php echo e($pegawai['nama'] ?? '-'); ?><br>
                    Rp <?php echo e(number_format($pegawai['nominal'] ?? 0, 0, ',', '.')); ?> x <?php echo e($pegawai['hari'] ?? 1); ?> (hari)
                </td>
                <td class="text-right" style="vertical-align: middle;">
                    Rp <?php echo e(number_format($pegawai['subtotal'] ?? 0, 0, ',', '.')); ?>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <!-- CATATAN: UANG TRANSPORT TIDAK DIMUNCULKAN (KWITANSI TERPISAH) -->
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
            <div>Pelaihari, <?php echo e($tanggalFormat); ?></div>
            <div style="margin-top: 8px;">Telah dibayar sejumlah:</div>
            <div class="bold" style="margin: 4px 0;">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></div>
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
            <div class="bold" style="margin: 4px 0;">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></div>
            <div style="margin-top: 8px;">Yang Menerima,</div>
            <div class="sign-space">
                <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="sign-line"></div>
                    <div class="bold" style="margin-top: 5px;"><?php echo e($pegawai['nama'] ?? '-'); ?></div>
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
            <span>: Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
        </div>
        <div class="calculation-item">
            <span class="calculation-label">Yang telah dibayar semula</span>
            <span>: Rp 0</span>
        </div>
        <div class="calculation-item">
            <span class="calculation-label">Sisa kurang / lebih</span>
            <span>: Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
        </div>
    </div>

    <!-- KEPALA DINAS -->
    <div class="kepala-box">
        <div>Kepala Dinas</div>
        <div>Kab. Tanah Laut</div>
        <div class="ttd-space">
            <div class="sign-line" style="width: 250px;"></div>
            <div class="bold" style="margin-top: 5px;"><?php echo e($kepalaDinasNama ?? '_________________'); ?></div>
            <div class="nip">NIP. <?php echo e($kepalaDinasNip ?? '_________________'); ?></div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/rincian-pdf.blade.php ENDPATH**/ ?>