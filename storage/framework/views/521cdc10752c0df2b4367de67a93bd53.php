<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Perjalanan Dinas</title>
    <style>
        /* RESET & GLOBAL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: white;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 2.5cm;
            color: #000000;
            line-height: 1.3;
        }

        /* SEMUA BORDER DIHIDE - tidak ada garis tabel */
        table, tr, td, th, tbody, thead {
            border: none !important;
            border-collapse: collapse;
        }

        /* JUDUL PERSIS SEPERTI TEMPLATE WORD */
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }

        /* TABEL UTAMA UNTUK BLOK DASAR, TANGGAL, DLL */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .content-table td {
            padding: 2px 2px;
            vertical-align: top;
            border: none;
            font-size: 12pt;
            font-family: 'Times New Roman', Times, serif;
        }

        /* LEBAR KOLOM DISESUAIKAN DENGAN TEMPLATE WORD */
        .label-col {
            width: 35px;
            font-weight: normal;
            vertical-align: top;
        }

        .ket-col {
            width: 105px;
            font-weight: normal;
            vertical-align: top;
        }

        .titikdua-col {
            width: 20px;
            text-align: center;
            vertical-align: top;
        }

        .nomor-col {
            width: 35px;
            text-align: right;
            vertical-align: top;
            padding-right: 8px;
        }

        .content-col {
            text-align: justify;
            vertical-align: top;
            word-break: break-word;
        }

        .hasil-text {
            text-align: justify;
            line-height: 1.4;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* SPACER ANTAR BAGIAN */
        .section-spacer {
            height: 6px;
        }

        /* TABEL FOTO 3 KOLOM SEPERTI TEMPLATE WORD */
        .foto-table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .foto-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 8px 10px;
            width: 33.33%;
        }

        .foto-item {
            display: block;
            text-align: center;
        }

        .foto-item img {
            width: 100%;
            max-width: 170px;
            height: auto;
            max-height: 140px;
            object-fit: cover;
            background: #f9f9f9;
            border: 1px solid #ccc;
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
            font-size: 9pt;
            margin-top: 5px;
            font-style: italic;
        }

        /* TANDA TANGAN - PERSIS SEPERTI TEMPLATE WORD */
        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            margin-top: 35px;
            font-size: 12pt;
        }

        .tempat-tanggal {
            font-size: 12pt;
            margin-bottom: 5px;
        }

        .nama-ttd {
            font-size: 12pt;
            text-decoration: underline;
            margin-top: 45px;
            margin-bottom: 2px;
        }

        .nip-ttd {
            font-size: 12pt;
        }

        .clearfix {
            clear: both;
        }

        strong {
            font-weight: bold;
        }

        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        @media print {
            body {
                margin: 2.5cm;
            }
            .foto-item img {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>

<div class="judul-surat">
    <h1>LAPORAN HASIL PERJALANAN DINAS</h1>
</div>

<?php
    // ======================== DATA DINAMIS =========================
    // DASAR HUKUM (Dasar) - ambil dari SPT snapshot
    $dasarList = [];
    
    // Coba ambil dari property dasar_list (jika ada)
    if (isset($dasarList) && is_array($dasarList) && count($dasarList) > 0) {
        // sudah ada
    } elseif (isset($lhpd->dasar_list) && is_array($lhpd->dasar_list) && count($lhpd->dasar_list) > 0) {
        $dasarList = $lhpd->dasar_list;
    } elseif (isset($lhpd->dasar)) {
        if (is_array($lhpd->dasar)) {
            $dasarList = $lhpd->dasar;
        } elseif (is_string($lhpd->dasar)) {
            // Coba parse sebagai JSON
            $decoded = json_decode($lhpd->dasar, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $dasarList = $decoded;
            } else {
                // Pisahkan berdasarkan baris
                $lines = explode("\n", $lhpd->dasar);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        // Hapus nomor urut di awal jika ada
                        $cleaned = preg_replace('/^\d+\.\s*/', '', $line);
                        $dasarList[] = $cleaned;
                    }
                }
            }
        }
    }
    
    // Fallback jika masih kosong
    if (!is_array($dasarList)) $dasarList = [];
    if (empty($dasarList)) {
        $dasarList = ['Data dasar tidak tersedia'];
    }

    // Format tanggal Indonesia
    $bulanIndonesia = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];

    // Tanggal berangkat
    $tanggalBerangkatStr = '';
    if (isset($lhpd->tanggal_berangkat) && $lhpd->tanggal_berangkat) {
        try {
            $tgl = \Carbon\Carbon::parse($lhpd->tanggal_berangkat);
            $tanggalBerangkatStr = $tgl->format('d') . ' ' . $bulanIndonesia[$tgl->format('F')] . ' ' . $tgl->format('Y');
        } catch (\Exception $e) {
            $tanggalBerangkatStr = $lhpd->tanggal_berangkat;
        }
    }

    // Tujuan daerah
    $tujuanDaerah = '';
    if (isset($lhpd->daerahTujuan) && $lhpd->daerahTujuan) {
        $tujuanDaerah = is_string($lhpd->daerahTujuan) ? $lhpd->daerahTujuan : ($lhpd->daerahTujuan->nama ?? '');
    } elseif (isset($lhpd->tempat_tujuan_snapshot) && $lhpd->tempat_tujuan_snapshot) {
        $tujuanDaerah = $lhpd->tempat_tujuan_snapshot;
    } elseif (isset($lhpd->tujuan_kota)) {
        $tujuanDaerah = $lhpd->tujuan_kota;
    }

    // Keperluan
    $keperluan = isset($lhpd->tujuan) ? $lhpd->tujuan : '';
    
    // Hasil
    $hasilTeks = isset($lhpd->hasil) ? $lhpd->hasil : '';

    // Tempat dan tanggal laporan
    $tempat = '';
    if (isset($lhpd->tempatDikeluarkan) && $lhpd->tempatDikeluarkan) {
        $tempat = is_string($lhpd->tempatDikeluarkan) ? $lhpd->tempatDikeluarkan : ($lhpd->tempatDikeluarkan->nama ?? '');
    } elseif (isset($lhpd->tempat_dikeluarkan_snapshot) && $lhpd->tempat_dikeluarkan_snapshot) {
        $tempat = $lhpd->tempat_dikeluarkan_snapshot;
    }

    $tanggalLaporanStr = '';
    if (isset($lhpd->tanggal_lhpd) && $lhpd->tanggal_lhpd) {
        try {
            $tglLaporan = \Carbon\Carbon::parse($lhpd->tanggal_lhpd);
            $tanggalLaporanStr = $tglLaporan->format('d') . ' ' . $bulanIndonesia[$tglLaporan->format('F')] . ' ' . $tglLaporan->format('Y');
        } catch (\Exception $e) {
            $tanggalLaporanStr = $lhpd->tanggal_lhpd;
        }
    }

    // FOTO
    $fotoBase64List = [];
    $fotoPaths = [];
    
    if (isset($fotoUrls) && is_array($fotoUrls) && count($fotoUrls) > 0) {
        foreach ($fotoUrls as $url) {
            $fotoBase64List[] = $url;
        }
    } elseif (isset($lhpd->foto)) {
        $rawFoto = $lhpd->foto;
        if (is_array($rawFoto)) {
            $fotoPaths = $rawFoto;
        } elseif (is_string($rawFoto)) {
            $decoded = json_decode($rawFoto, true);
            if (is_array($decoded)) {
                $fotoPaths = $decoded;
            }
        }
        
        foreach ($fotoPaths as $path) {
            $cleanPath = trim($path, '"');
            $fullPath = storage_path('app/public/' . $cleanPath);
            if (!empty($cleanPath) && file_exists($fullPath)) {
                try {
                    $imageContent = file_get_contents($fullPath);
                    if ($imageContent !== false) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $fullPath);
                        finfo_close($finfo);
                        $fotoBase64List[] = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                    }
                } catch (\Exception $e) {}
            }
        }
    }
    
    // Susun grid foto 3 kolom
    $fotoGrid = [];
    $jumlahFoto = count($fotoBase64List);
    if ($jumlahFoto > 0) {
        for ($i = 0; $i < $jumlahFoto; $i += 3) {
            $fotoGrid[] = array_slice($fotoBase64List, $i, 3);
        }
    }

    // PEGAWAI PENANDATANGAN
    $pegawaiCollection = collect();
    if (isset($pegawaiList) && $pegawaiList instanceof \Illuminate\Support\Collection) {
        $pegawaiCollection = $pegawaiList;
    } elseif (isset($lhpd->pegawai_list_from_snapshot) && $lhpd->pegawai_list_from_snapshot instanceof \Illuminate\Support\Collection) {
        $pegawaiCollection = $lhpd->pegawai_list_from_snapshot;
    } elseif (isset($lhpd->pegawai_snapshot) && is_array($lhpd->pegawai_snapshot)) {
        $pegawaiCollection = collect($lhpd->pegawai_snapshot);
    }
?>

<!-- I. DASAR -->
<table class="content-table">
    <?php $__currentLoopData = $dasarList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dasarItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($index == 0): ?>
        <tr>
            <td class="label-col">I.</td>
            <td class="ket-col">Dasar</td>
            <td class="titikdua-col">:</td>
            <td class="nomor-col"><?php echo e($index + 1); ?>.</td>
            <td class="content-col" colspan="2"><?php echo e($dasarItem); ?></td>
        </tr>
        <?php else: ?>
        <tr>
            <td class="label-col"></td>
            <td class="ket-col"></td>
            <td class="titikdua-col"></td>
            <td class="nomor-col"><?php echo e($index + 1); ?>.</td>
            <td class="content-col" colspan="2"><?php echo e($dasarItem); ?></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

<div class="section-spacer"></div>

<!-- II. TANGGAL/TUJUAN -->
<table class="content-table">
    <tr>
        <td class="label-col">II.</td>
        <td class="ket-col">Tanggal/Tujuan</td>
        <td class="titikdua-col">:</td>
        <td class="content-col" colspan="3">
            Perjalanan Dinas dilaksanakan pada tanggal <strong><?php echo e($tanggalBerangkatStr ?: '________'); ?></strong> dengan tujuan <strong><?php echo e($tujuanDaerah ?: '________'); ?></strong>
        </td>
    </tr>
</table>

<div class="section-spacer"></div>

<!-- III. KEPERLUAN -->
<table class="content-table">
    <tr>
        <td class="label-col">III.</td>
        <td class="ket-col">Keperluan</td>
        <td class="titikdua-col">:</td>
        <td class="content-col" colspan="3"><?php echo e($keperluan ?: '-'); ?></td>
    </tr>
</table>

<div class="section-spacer"></div>

<!-- IV. HASIL -->
<table class="content-table">
    <tr>
        <td class="label-col">IV.</td>
        <td class="ket-col">Hasil</td>
        <td class="titikdua-col">:</td>
        <td class="content-col" colspan="3">
            <div class="hasil-text"><?php echo nl2br(e($hasilTeks)) ?: '___'; ?></div>
        </td>
    </tr>
</table>

<div class="section-spacer"></div>

<!-- KALIMAT PENUTUP - LANGSUNG DI BAWAH HASIL -->
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

<!-- BAGIAN FOTO - 3 KOLOM -->
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
                    <?php if($fotoData && $fotoData !== ''): ?>
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

<!-- TANDA TANGAN -->
<div class="tanda-tangan">
    <div class="tempat-tanggal">
        <?php echo e($tempat ?: '________'); ?><?php if($tempat && $tanggalLaporanStr): ?>, <?php endif; ?><?php echo e($tanggalLaporanStr ?: '________'); ?>

    </div>
    <div>Yang Membuat Laporan,</div>
    <div style="height: 30px;"></div>

    <?php if($pegawaiCollection && $pegawaiCollection->count() > 0): ?>
        <?php $__currentLoopData = $pegawaiCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $pegawai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($idx > 0): ?>
                <div style="margin-top: 15px;"></div>
            <?php endif; ?>
            <div class="nama-ttd"><?php echo e($pegawai->nama ?? ($pegawai['nama'] ?? '_________________________')); ?></div>
            <div class="nip-ttd">
                <?php
                    $nipVal = $pegawai->nip ?? ($pegawai['nip'] ?? '');
                ?>
                <?php if($nipVal): ?>
                    NIP. <?php echo e($nipVal); ?>

                <?php else: ?>
                    NIP. _________________
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="nama-ttd">_________________________</div>
        <div class="nip-ttd">NIP. _________________</div>
    <?php endif; ?>
</div>
<div class="clearfix"></div>

</body>
</html>  <?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/lhpd-pdf.blade.php ENDPATH**/ ?>