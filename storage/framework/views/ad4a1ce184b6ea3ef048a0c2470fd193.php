<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Dinas - Halaman Depan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            margin: 1cm 1.5cm 1.5cm 1.5cm;
            color: #000;
            line-height: 1.25;
        }
        
        /* KOP SURAT */
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
        
        /* GARIS KOP */
        .garis-kop {
            width: 100%;
            margin: 0 0 12px 0;
            clear: both;
            border-top: 1px solid #000;
            border-bottom: 2px solid #000;
            height: 2px;
        }
        
        /* JUDUL SURAT */
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
        
        /* TABEL UTAMA SPD - SEPERTI CONTOH */
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
            font-size: 11.5pt;
        }
        
        /* Kolom nomor (lebar kecil) */
        .col-nomor {
            width: 8%;
            text-align: center;
            vertical-align: middle;
        }
        
        /* Kolom label/poin */
        .col-label {
            width: 22%;
        }
        
        /* Kolom isi */
        .col-content {
            width: 70%;
        }
        
        /* Sub tabel untuk daftar pengikut */
        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .sub-table td {
            border: none;
            padding: 2px 0;
        }
        
        .sub-table .no-col {
            width: 25px;
            text-align: right;
            padding-right: 8px;
        }
        
        .pelaksana-item {
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        
        .pelaksana-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .pelaksana-divider {
            margin: 5px 0;
            border-top: 1px dashed #ccc;
        }
        
        /* TANDA TANGAN */
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
        
        .footer-catatan {
            margin-top: 20px;
            font-size: 9pt;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
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
                <div class="kontak">Laman https://dpmptsp.tanahlautkab.go.id Pos-el dpmptsptanahlautkab@gmail.com</div>
            </td>
        </tr>
    </table>
    
    <div class="garis-kop"></div>

    <!-- JUDUL SURAT -->
    <div class="judul-surat">
        <h1>SURAT PERJALANAN DINAS (SPD)</h1>
        <div class="nomor"><?php echo e($spd->nomor_surat ?? '-'); ?></div>
    </div>

    <!-- TABEL UTAMA SPD - FORMAT SEPERTI CONTOH -->
    <table class="spd-table">
        <!-- ROW 1 -->
        <tr>
            <td class="col-nomor">1.</td>
            <td class="col-label">Pengguna Anggaran</td>
            <td class="col-content">
                <?php
                    $paNama = $spd->penggunaAnggaran->nama ?? '-';
                    $paNip = $spd->penggunaAnggaran->nip ?? '';
                ?>
                Nama : <?php echo e($paNama); ?><br>
                NIP : <?php echo e($paNip); ?>

            </td>
        </tr>
        
        <!-- ROW 2 -->
        <tr>
            <td class="col-nomor">2.</td>
            <td class="col-label">Nama/NIP Pegawai yang melaksanakan perjalanan dinas</td>
            <td class="col-content">
                <?php
                    $pelaksana = $spd->pelaksanaPerjadin;
                ?>
                <?php if($pelaksana && $pelaksana->count() > 0): ?>
                    <?php $__currentLoopData = $pelaksana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $peg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="pelaksana-item">
                            Nama : <?php echo e($peg->nama ?? '-'); ?><br>
                            NIP : <?php echo e($peg->nip ?? '-'); ?>

                        </div>
                        <?php if(!$loop->last): ?>
                            <div class="pelaksana-divider"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    Nama : -<br>
                    NIP : -
                <?php endif; ?>
            </td>
        </tr>
        
        <!-- ROW 3 - Dengan sub poin a,b,c (berbeda baris) -->
        <tr>
            <td class="col-nomor">3.</td>
            <td class="col-label">
                a. Pangkat dan Golongan<br>
                b. Jabatan / Instansi<br>
                c. Tingkat biaya perjalanan dinas
            </td>
            <td class="col-content">
                <?php
                    $firstPelaksana = $pelaksana && $pelaksana->count() > 0 ? $pelaksana->first() : null;
                    $pangkatGol = '';
                    if($firstPelaksana) {
                        if($firstPelaksana->pangkat && $firstPelaksana->gol) {
                            $pangkatGol = $firstPelaksana->pangkat . ' (' . $firstPelaksana->gol . ')';
                        } elseif($firstPelaksana->pangkat) {
                            $pangkatGol = $firstPelaksana->pangkat;
                        } elseif($firstPelaksana->gol) {
                            $pangkatGol = 'Gol. ' . $firstPelaksana->gol;
                        } else {
                            $pangkatGol = '-';
                        }
                    } else {
                        $pangkatGol = '-';
                    }
                    $jabatan = $firstPelaksana->jabatan ?? '-';
                    $instansi = $spd->skpd ?? 'DPMPTSP Kab. Tanah Laut';
                ?>
                a. <?php echo e($pangkatGol); ?><br>
                b. <?php echo e($jabatan); ?> / <?php echo e($instansi); ?><br>
                c. D
            </td>
        </tr>
        
        <!-- ROW 4 -->
        <tr>
            <td class="col-nomor">4.</td>
            <td class="col-label">Maksud perjalanan dinas</td>
            <td class="col-content">
                <?php echo e($spd->maksud_perjadin ?? '-'); ?>

            </td>
        </tr>
        
        <!-- ROW 5 -->
        <tr>
            <td class="col-nomor">5.</td>
            <td class="col-label">Alat angkut yang digunakan</td>
            <td class="col-content">
                <?php echo e($spd->label_alat_transportasi ?? '-'); ?>

            </td>
        </tr>
        
        <!-- ROW 6 - Dengan sub poin a,b -->
        <tr>
            <td class="col-nomor">6.</td>
            <td class="col-label">
                a. Tempat berangkat<br>
                b. Tempat tujuan
            </td>
            <td class="col-content">
                a. <?php echo e($spd->tempat_berangkat ?? 'Pelaihari'); ?><br>
                b. <?php echo e($spd->nama_tempat_tujuan ?? '-'); ?>

            </td>
        </tr>
        
        <!-- ROW 7 - Dengan sub poin a,b,c -->
        <tr>
            <td class="col-nomor">7.</td>
            <td class="col-label">
                a. Lamanya perjalanan dinas<br>
                b. Tanggal berangkat<br>
                c. Tanggal harus kembali / tiba di tempat baru *)
            </td>
            <td class="col-content">
                <?php
                    $tglBerangkat = $spd->tanggal_berangkat ? \Carbon\Carbon::parse($spd->tanggal_berangkat)->translatedFormat('d F Y') : '-';
                    $tglKembali = $spd->tanggal_kembali ? \Carbon\Carbon::parse($spd->tanggal_kembali)->translatedFormat('d F Y') : '-';
                    $lama = $spd->lama_perjadin ?? 1;
                    $lamaTerbilang = '';
                    if($lama == 1) $lamaTerbilang = 'satu';
                    elseif($lama == 2) $lamaTerbilang = 'dua';
                    elseif($lama == 3) $lamaTerbilang = 'tiga';
                    elseif($lama == 4) $lamaTerbilang = 'empat';
                    elseif($lama == 5) $lamaTerbilang = 'lima';
                    elseif($lama == 6) $lamaTerbilang = 'enam';
                    elseif($lama == 7) $lamaTerbilang = 'tujuh';
                    elseif($lama == 8) $lamaTerbilang = 'delapan';
                    elseif($lama == 9) $lamaTerbilang = 'sembilan';
                    elseif($lama == 10) $lamaTerbilang = 'sepuluh';
                    else $lamaTerbilang = $lama;
                ?>
                a. <?php echo e($lama); ?> ( <?php echo e($lamaTerbilang); ?> ) hari<br>
                b. <?php echo e($tglBerangkat); ?><br>
                c. <?php echo e($tglKembali); ?>

            </td>
        </tr>
        
        <!-- ROW 8 -->
        <tr>
            <td class="col-nomor">8.</td>
            <td class="col-label">Nama Pengikut</td>
            <td class="col-content">
                <table class="sub-table">
                    <tr><td class="no-col">1.</td><td>-</td></tr>
                    <tr><td class="no-col">2.</td><td>-</td></tr>
                    <tr><td class="no-col">3.</td><td>-</td></tr>
                    <tr><td class="no-col">4.</td><td>-</td></tr>
                    <tr><td class="no-col">5.</td><td>-</td></tr>
                </table>
            </td>
        </tr>
        
        <!-- ROW 9 -->
        <tr>
            <td class="col-nomor">9.</td>
            <td class="col-label">Pembebanan anggaran</td>
            <td class="col-content">
                a. Instansi : <?php echo e($spd->skpd ?? 'Dinas Penanaman Modal dan PTSP'); ?><br>
                b. Akun : <?php echo e($spd->kode_rek ?? $spd->pejabat_teknis_kode_rekening ?? '-'); ?>

            </td>
        </tr>
        
        <!-- ROW 10 -->
        <tr>
            <td class="col-nomor">10.</td>
            <td class="col-label">Keterangan lain-lain</td>
            <td class="col-content">
                <?php echo e($spd->keterangan ?? '-'); ?>

            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN - PENGGUNA ANGGARAN -->
    <div class="tanda-tangan">
        <?php
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
            
            $tglDikeluarkan = $spd->tanggal_dikeluarkan ?? $spd->tanggal_berangkat ?? now();
            $tanggal = \Carbon\Carbon::parse($tglDikeluarkan)->format('d') . ' ' . 
                       $bulanIndonesia[\Carbon\Carbon::parse($tglDikeluarkan)->format('F')] . ' ' . 
                       \Carbon\Carbon::parse($tglDikeluarkan)->format('Y');
            $tempatDikeluarkan = $spd->tempat_dikeluarkan ?? 'Pelaihari';
            
            // PENANDA TANGAN = PENGGUNA ANGGARAN
            $ttdNama = $spd->penggunaAnggaran->nama ?? '-';
            $ttdNip = $spd->penggunaAnggaran->nip ?? '-';
            $ttdPangkat = $spd->penggunaAnggaran->pangkat ?? '';
            $ttdGol = $spd->penggunaAnggaran->gol ?? '';
            $ttdJabatan = $spd->penggunaAnggaran->jabatan ?? 'Pengguna Anggaran';
        ?>
        
        <div class="tempat-tanggal">Dikeluarkan di : <?php echo e($tempatDikeluarkan); ?></div>
        <div class="tempat-tanggal">Pada Tanggal : <?php echo e($tanggal); ?></div>
        
        <div class="ttd-space"></div>
        
        <div class="jabatan-ttd"><?php echo e($ttdJabatan); ?></div>
        <div class="nama-ttd"><?php echo e($ttdNama); ?></div>
        <div class="pangkat-ttd">
            <?php if($ttdPangkat): ?>
                <?php echo e($ttdPangkat); ?>

                <?php if($ttdGol): ?>
                    (<?php echo e($ttdGol); ?>)
                <?php endif; ?>
            <?php elseif($ttdGol): ?>
                Gol. <?php echo e($ttdGol); ?>

            <?php endif; ?>
        </div>
        <div class="nip-ttd">NIP. <?php echo e($ttdNip); ?></div>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="footer-catatan">
        *) Coret yang tidak perlu
    </div>
</body>
</html><?php /**PATH C:\POLITALA\PKL\dpmptsp\resources\views/admin/spd-pdf-depan.blade.php ENDPATH**/ ?>