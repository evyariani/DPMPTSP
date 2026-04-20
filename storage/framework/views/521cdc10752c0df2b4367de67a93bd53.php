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
            line-height: 1.4;
        }
        
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .judul-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .content-table td {
            padding: 4px 2px;
            vertical-align: top;
            border: none;
            font-size: 12pt;
        }
        
        /* Kolom I., II., III., IV. */
        .label-col {
            width: 35px;
            font-weight: bold;
            vertical-align: top;
        }
        
        /* Kolom Dasar, Tanggal/Tujuan, dll */
        .ket-col {
            width: 120px;
            font-weight: normal;
            vertical-align: top;
        }
        
        /* Kolom titik dua */
        .titikdua-col {
            width: 15px;
            text-align: center;
            vertical-align: top;
        }
        
        /* Kolom nomor untuk dasar (1., 2., 3.) */
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
        }
        
        .hasil-text {
            text-align: justify;
            line-height: 1.4;
            white-space: pre-wrap;
        }
        
        .section-spacer {
            height: 10px;
        }
        
        /* Tabel foto */
        .foto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        .foto-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 10px 8px;
            width: 33.33%;
        }
        
        .foto-item {
            display: block;
            text-align: center;
            margin: 0 auto;
        }
        
        .foto-item img {
            width: 100%;
            max-width: 170px;
            height: auto;
            min-height: 120px;
            max-height: 140px;
            object-fit: cover;
            border: 1px solid #aaa;
            background: #f9f9f9;
        }
        
        .foto-placeholder {
            width: 100%;
            max-width: 170px;
            height: 130px;
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
            font-size: 10pt;
            margin-top: 6px;
            font-style: italic;
        }
        
        /* Tanda tangan */
        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 30px;
        }
        
        .tempat-tanggal {
            font-size: 12pt;
            margin-bottom: 10px;
        }
        
        .ttd-label {
            margin-top: 15px;
            margin-bottom: 5px;
        }
        
        .ttd-space {
            height: 40px;
        }
        
        .nama-ttd {
            font-size: 12pt;
            text-decoration: underline;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .nip-ttd {
            font-size: 11pt;
        }
        
        .clearfix {
            clear: both;
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

    <?php
        // =====================================================
        // AMBIL DATA DARI DATABASE
        // =====================================================
        
        // 1. DASAR (dari SPT)
        $dasarList = [];
        if (isset($lhpd->dasar_list) && is_array($lhpd->dasar_list) && count($lhpd->dasar_list) > 0) {
            $dasarList = $lhpd->dasar_list;
        } elseif (isset($lhpd->dasar)) {
            if (is_array($lhpd->dasar)) {
                $dasarList = $lhpd->dasar;
            } elseif (is_string($lhpd->dasar)) {
                $decoded = json_decode($lhpd->dasar, true);
                if (is_array($decoded)) {
                    $dasarList = $decoded;
                }
            }
        }
        
        // 2. TANGGAL BERANGKAT & TUJUAN DAERAH
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
        
        // Prioritas: snapshot dulu, baru relasi
        $tujuanDaerah = $lhpd->tempat_tujuan_snapshot ?? ($lhpd->daerahTujuan?->nama ?? '-');
        
        // 3. KEPERLUAN (tujuan dari SPT)
        $keperluan = isset($lhpd->tujuan) ? $lhpd->tujuan : '-';
        
        // 4. HASIL
        $hasilTeks = isset($lhpd->hasil) ? $lhpd->hasil : '-';
        
        // 5. TEMPAT & TANGGAL LAPORAN
        $tempat = $lhpd->tempat_dikeluarkan_snapshot ?? ($lhpd->tempatDikeluarkan?->nama ?? 'Pelaihari');
        $tanggalLaporanStr = '';
        if (isset($lhpd->tanggal_lhpd) && $lhpd->tanggal_lhpd) {
            $tglLaporan = \Carbon\Carbon::parse($lhpd->tanggal_lhpd);
            $tanggalLaporanStr = $tglLaporan->format('d') . ' ' . $bulanIndonesia[$tglLaporan->format('F')] . ' ' . $tglLaporan->format('Y');
        } else {
            $tanggalLaporanStr = date('d') . ' ' . $bulanIndonesia[date('F')] . ' ' . date('Y');
        }
        
        // 6. FOTO
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
        
        // 7. PEGAWAI (tanda tangan)
        $pegawaiList = isset($lhpd->pegawai_list_from_snapshot) ? $lhpd->pegawai_list_from_snapshot : collect([]);
        if ($pegawaiList->isEmpty() && isset($lhpd->pegawai_list)) {
            $pegawaiList = $lhpd->pegawai_list;
        }
    ?>

    
    <table class="content-table">
        <?php if(count($dasarList) > 0): ?>
            <?php $__currentLoopData = $dasarList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dasar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php if($index == 0): ?>
                        <td class="label-col" rowspan="<?php echo e(count($dasarList)); ?>">I.</td>
                        <td class="ket-col" rowspan="<?php echo e(count($dasarList)); ?>">Dasar</td>
                        <td class="titikdua-col" rowspan="<?php echo e(count($dasarList)); ?>">:</td>
                    <?php endif; ?>
                    <td class="nomor-col"><?php echo e($index + 1); ?>.</td>
                    <td class="content-col" colspan="2"><?php echo e($dasar); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <tr>
                <td class="label-col">I.</td>
                <td class="ket-col">Dasar</td>
                <td class="titikdua-col">:</td>
                <td class="content-col" colspan="3">-</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="section-spacer"></div>

    
    <table class="content-table">
        <tr>
            <td class="label-col">II.</td>
            <td class="ket-col">Tanggal/Tujuan</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">
                Perjalanan Dinas dilaksanakan pada tanggal 
                <strong><?php echo e($tanggalBerangkatStr); ?></strong> 
                dengan tujuan 
                <strong><?php echo e($tujuanDaerah); ?></strong>
            </td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    
    <table class="content-table">
        <tr>
            <td class="label-col">III.</td>
            <td class="ket-col">Keperluan</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3"><?php echo e($keperluan); ?></td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    
    <table class="content-table">
        <tr>
            <td class="label-col">IV.</td>
            <td class="ket-col">Hasil</td>
            <td class="titikdua-col">:</td>
            <td class="content-col" colspan="3">
                <div class="hasil-text"><?php echo nl2br(e($hasilTeks)); ?></div>
            </td>
        </tr>
    </table>

    <div class="section-spacer"></div>

    
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

    
    <?php if(count($fotoGrid) > 0): ?>
    <table class="foto-table">
        <?php $__currentLoopData = $fotoGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowIdx => $rowFoto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <?php for($col = 0; $col < 3; $col++): ?>
                <?php
                    $fotoData = isset($rowFoto[$col]) ? $rowFoto[$col] : null;
                    $fotoIndex = ($rowIdx * 3) + $col + 1;
                ?>
                <td>
                    <div class="foto-item">
                        <?php if($fotoData): ?>
                            <img src="<?php echo e($fotoData); ?>" alt="Dokumentasi <?php echo e($fotoIndex); ?>">
                        <?php else: ?>
                            <div class="foto-placeholder">
                                📷 Foto <?php echo e($fotoIndex); ?><br>
                                <span style="font-size:8px;">(belum tersedia)</span>
                            </div>
                        <?php endif; ?>
                        <div class="foto-caption">Foto <?php echo e($fotoIndex); ?></div>
                    </div>
                </td>
            <?php endfor; ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <?php endif; ?>

    
    <div class="tanda-tangan">
        <div class="tempat-tanggal">
            <?php echo e($tempat); ?>, <?php echo e($tanggalLaporanStr); ?>

        </div>
        <div>Yang Membuat Laporan,</div>
        
        <div class="ttd-space"></div>
        
        <?php if($pegawaiList && $pegawaiList->count() > 0): ?>
            <?php $__currentLoopData = $pegawaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($index > 0): ?>
                    <div style="margin-top: 20px;"></div>
                <?php endif; ?>
                <?php
                    $nama = is_object($pegawai) ? ($pegawai->nama ?? '') : ($pegawai['nama'] ?? '');
                    $nip = is_object($pegawai) ? ($pegawai->nip ?? '') : ($pegawai['nip'] ?? '');
                ?>
                <div class="nama-ttd"><?php echo e($nama); ?></div>
                <?php if($nip): ?>
                    <div class="nip-ttd">NIP. <?php echo e($nip); ?></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="nama-ttd">_________________________</div>
            <div class="nip-ttd">NIP. _________________</div>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>

</body>
</html><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/lhpd-pdf.blade.php ENDPATH**/ ?>