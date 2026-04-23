
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Perintah Dinas (SPD) - Halaman Belakang</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.4;
      color: #000;
      background: #fff;
      padding: 1cm;
    }

    .container {
      max-width: 21cm;
      margin: 0 auto;
    }

    /* Tabel Utama */
    .perjalanan-dinas {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    .perjalanan-dinas th,
    .perjalanan-dinas td {
      border: 1px solid #000;
      padding: 0;
      vertical-align: top;
    }

    .perjalanan-dinas th {
      background-color: #f0f0f0;
      font-weight: bold;
      text-align: center;
      padding: 6px;
    }

    .no-col {
      width: 5%;
      text-align: center;
      vertical-align: top;
      padding: 6px !important;
    }

    .rincian-col {
      width: 95%;
      padding: 0 !important;
    }

    /* Inner table: 2 kolom 50%:50% dengan garis vertikal */
    .inner-table {
      width: 100%;
      border-collapse: collapse;
      border: none;
      margin: 0;
      table-layout: fixed;
    }

    .inner-table td {
      border: none;
      padding: 8px 10px;
      vertical-align: top;
    }

    .inner-table .col-left {
      width: 50%;
      border-right: 1px solid #000;
      padding-right: 10px;
    }

    .inner-table .col-right {
      width: 50%;
      padding-left: 10px;
    }

    /* Format teks */
    .row-group {
      margin-bottom: 6px;
      line-height: 1.4;
    }

    .row-label {
      display: inline-block;
      width: 115px;
      font-weight: normal;
    }

    /* Tanda Tangan */
    .ttd-wrapper {
      margin-top: 40px;
      display: flex;
      justify-content: space-between;
    }

    .ttd-box {
      width: 45%;
    }

    .ttd-box p {
      margin: 5px 0;
    }

    .ttd-box .jabatan {
      margin-bottom: 50px;
    }

    .ttd-box .nama {
      font-weight: bold;
      text-decoration: underline;
    }

    .ttd-box .nip {
      font-size: 10pt;
    }

    .keterangan {
      margin-top: 30px;
      font-size: 10pt;
      font-style: italic;
      text-align: justify;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    @media print {
      body { padding: 0; margin: 0; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>
  <div class="container">
    <table class="perjalanan-dinas">
      <thead>
        <tr>
          <th class="no-col">No</th>
          <th class="rincian-col">Rincian Perjalanan Dinas</th>
        </tr>
      </thead>
      <tbody>

        
        <tr>
          <td class="no-col">I.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left"></td>
                <td class="col-right">
                  <div class="row-group">
                    <span class="row-label">Berangkat dari :</span>
                    <?php echo e($spd->tempat_berangkat ?? 'Pelaihari'); ?>

                  </div>
                  <div class="row-group">
                    <span class="row-label">Ke :</span>
                    <?php echo e($spd->tempatTujuan->nama ?? 'BANJARBARU'); ?>

                  </div>
                  <div class="row-group">
                    <span class="row-label">Pada Tanggal :</span>
                    <?php echo e($spd->tanggal_berangkat ? $spd->tanggal_berangkat->isoFormat('D MMMM Y') : '22 April 2026'); ?>

                  </div>
                  <div class="row-group">
                    <span class="row-label"></span>
                    Kasubbag Umum dan Kepegawaian Selaku Pejabat Pelaksana Teknis Kegiatan
                  </div>
                  <div class="row-group" style="margin-top: 8px;">
                    <span class="row-label"></span>
                    <?php echo e($spd->pejabatTeknisPegawai->nama ?? 'M. HAYAT, S. Sos'); ?><br>
                    NIP. <?php echo e($spd->pejabatTeknisPegawai->nip ?? '19701013 199203 1 006'); ?>

                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        
        <tr>
          <td class="no-col">II.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left">
                  <strong>Tiba di :</strong> <?php echo e($spd->tempatTujuan->nama ?? 'BANJARBARU'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_berangkat ? $spd->tanggal_berangkat->isoFormat('D MMMM Y') : '22 April 2026'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_tujuan_kedatangan ?? '_________________'); ?>

                </td>
                <td class="col-right">
                  <strong>Berangkat dari :</strong> <?php echo e($spd->tempatTujuan->nama ?? 'BANJARBARU'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_kembali ? $spd->tanggal_kembali->isoFormat('D MMMM Y') : '22 April 2026'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_tujuan_keberangkatan ?? '_________________'); ?>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        
        <tr>
          <td class="no-col">III.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left">
                  <strong>Tiba di :</strong> <?php echo e($spd->tempat_lain_1_tiba ?? '_________________'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_lain_1_tiba ? \Carbon\Carbon::parse($spd->tanggal_lain_1_tiba)->isoFormat('D MMMM Y') : '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_1_tiba ?? '_________________'); ?>

                </td>
                <td class="col-right">
                  <strong>Berangkat dari :</strong> <?php echo e($spd->tempat_lain_1_berangkat ?? '_________________'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_lain_1_berangkat ? \Carbon\Carbon::parse($spd->tanggal_lain_1_berangkat)->isoFormat('D MMMM Y') : '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_1_berangkat ?? '_________________'); ?>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        
        <tr>
          <td class="no-col">IV.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left">
                  <strong>Tiba di :</strong> <?php echo e($spd->tempat_lain_2_tiba ?? '_________________'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_lain_2_tiba ? \Carbon\Carbon::parse($spd->tanggal_lain_2_tiba)->isoFormat('D MMMM Y') : '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_2_tiba ?? '_________________'); ?>

                </td>
                <td class="col-right">
                  <strong>Berangkat dari :</strong> <?php echo e($spd->tempat_lain_2_berangkat ?? '_________________'); ?><br>
                  <strong>Untuk :</strong> <?php echo e($spd->tempat_lain_2_tujuan ?? '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_2_berangkat ?? '_________________'); ?>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        
        <tr>
          <td class="no-col">V.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left">
                  <strong>Tiba di :</strong> <?php echo e($spd->tempat_lain_3_tiba ?? '_________________'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_lain_3_tiba ? \Carbon\Carbon::parse($spd->tanggal_lain_3_tiba)->isoFormat('D MMMM Y') : '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_3_tiba ?? '_________________'); ?>

                </td>
                <td class="col-right">
                  <strong>Berangkat dari :</strong> <?php echo e($spd->tempat_lain_3_berangkat ?? '_________________'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_lain_3_berangkat ? \Carbon\Carbon::parse($spd->tanggal_lain_3_berangkat)->isoFormat('D MMMM Y') : '_________________'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_lain_3_berangkat ?? '_________________'); ?>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        
        <tr>
          <td class="no-col">VI.</td>
          <td class="rincian-col">
            <table class="inner-table">
              <tr>
                <td class="col-left">
                  <strong>Tiba di :</strong> <?php echo e($spd->tempat_berangkat ?? 'Pelaihari'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_kembali ? $spd->tanggal_kembali->isoFormat('D MMMM Y') : '22 April 2026'); ?><br>
                  <strong>Kepala :</strong> Pengguna Anggaran
                </td>
                <td class="col-right">
                  <strong>Berangkat dari :</strong> <?php echo e($spd->tempat_terakhir ?? $spd->tempatTujuan->nama ?? 'BANJARBARU'); ?><br>
                  <strong>Pada tanggal :</strong> <?php echo e($spd->tanggal_kembali ? $spd->tanggal_kembali->isoFormat('D MMMM Y') : '22 April 2026'); ?><br>
                  <strong>Kepala :</strong> <?php echo e($spd->kepala_kembali ?? '_________________'); ?>

                </td>
              </tr>
            </table>
          </td>
        </tr>

      </tbody>
    </table>

    
    <div class="keterangan">
      Telah diperiksa, dengan keterangan bahwa perjalanan tersebut di atas dilakukan atas perintahnya
      dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
    </div>

    
    <div class="ttd-wrapper">
      <div class="ttd-box">
        <p class="jabatan">Pejabat Pelaksana Teknis Kegiatan,</p>
        <br><br><br>
        <p class="nama"><?php echo e($spd->pejabatTeknisPegawai->nama ?? 'M. HAYAT, S. Sos'); ?></p>
        <p class="nip">NIP. <?php echo e($spd->pejabatTeknisPegawai->nip ?? '19701013 199203 1 006'); ?></p>
      </div>
      <div class="ttd-box">
        <p class="jabatan"><?php echo e($spd->penanda_tangan_jabatan ?? 'Kepala'); ?>,</p>
        <br><br><br>
        <p class="nama"><?php echo e($spd->penanda_tangan_nama ?? '_________________'); ?></p>
        <p class="nip">NIP. <?php echo e($spd->penanda_tangan_nip ?? '_________________'); ?></p>
      </div>
    </div>
  </div>
</body>
</html>
<?php /**PATH C:\PKL POLITALA\dpmptsp\resources\views/admin/spd-pdf-belakang.blade.php ENDPATH**/ ?>