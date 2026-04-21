{{-- spd-pdf-belakang.blade.php --}}
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
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 1.5cm;
        }

        .container {
            max-width: 21cm;
            margin: 0 auto;
        }

        /* Format perjalanan dinas */
        .perjalanan-dinas {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11pt;
        }

        .perjalanan-dinas th,
        .perjalanan-dinas td {
            border: 1px solid #000;
            padding: 8px 5px;
            vertical-align: top;
        }

        .perjalanan-dinas th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .perjalanan-dinas .no-col {
            width: 5%;
            text-align: center;
        }

        .perjalanan-dinas .rincian-col {
            width: 95%;
        }

        .rincian-perjalanan {
            margin: 0;
            padding-left: 15px;
        }

        .rincian-perjalanan li {
            margin-bottom: 8px;
            list-style: none;
            position: relative;
        }

        .rincian-perjalanan li strong {
            display: inline-block;
            min-width: 120px;
        }

        /* Tanda tangan */
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

        /* Keterangan */
        .keterangan {
            margin-top: 30px;
            font-size: 10pt;
            font-style: italic;
            text-align: justify;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        /* Print optimization */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .perjalanan-dinas th,
            .perjalanan-dinas td {
                border-color: #000;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- TABEL RINCIAN PERJALANAN DINAS --}}
        <table class="perjalanan-dinas">
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th class="rincian-col">Rincian Perjalanan Dinas</th>
                </tr>
            </thead>
            <tbody>
                {{-- I. Berangkat dari Pelaihari --}}
                <tr>
                    <td class="no-col">I.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Berangkat dari :</strong> {{ $spd->tempat_berangkat ?? 'Pelaihari (tempat kedudukan)' }}</li>
                            <li><strong>Ke :</strong> {{ $spd->tempatTujuan->nama ?? 'Kota Banjarbaru' }}</li>
                            <li><strong>Pada Tanggal :</strong> {{ $spd->tanggal_berangkat ? $spd->tanggal_berangkat->format('d F Y') : '08 Juli 2024' }}</li>
                            <li><strong>Kasubbag Umum dan Kepegawaian</strong> Selaku Pejabat Pelaksana Teknis Kegiatan</li>
                            <li style="margin-top: 15px;">
                                <strong>{{ $spd->pejabatTeknisPegawai->nama ?? 'LASMIATI, S. Tr' }}</strong><br>
                                NIP. {{ $spd->pejabatTeknisPegawai->nip ?? '19860412 201001 2 001' }}
                            </li>
                        </ul>
                    </td>
                </tr>

                {{-- II. Tiba di Banjarbaru --}}
                <tr>
                    <td class="no-col">II.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Tiba :</strong> {{ $spd->tempatTujuan->nama ?? 'Kota Banjarbaru' }}</li>
                            <li><strong>Pada tanggal :</strong> {{ $spd->tanggal_berangkat ? $spd->tanggal_berangkat->format('d F Y') : '08 Juli 2024' }}</li>
                            <li><strong>Kepala :</strong> _________________</li>
                            <li style="margin-top: 10px;">
                                <strong>Berangkat dari :</strong> {{ $spd->tempatTujuan->nama ?? 'Kota Banjarbaru' }}
                            </li>
                            <li><strong>Pada tanggal :</strong> {{ $spd->tanggal_kembali ? $spd->tanggal_kembali->format('d F Y') : '09 Juli 2024' }}</li>
                            <li><strong>Kepala :</strong> _________________</li>
                        </ul>
                    </td>
                </tr>

                {{-- III. (Kosong sesuai format) --}}
                <tr>
                    <td class="no-col">III.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Tiba :</strong> _________________</li>
                            <li><strong>Pada tanggal :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                            <li style="margin-top: 10px;">
                                <strong>Berangkat dari :</strong> _________________
                            </li>
                            <li><strong>Pada tanggal :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                        </ul>
                    </td>
                </tr>

                {{-- IV. (Kosong) --}}
                <tr>
                    <td class="no-col">IV.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Tiba :</strong> _________________</li>
                            <li><strong>Pada tanggal :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                            <li style="margin-top: 10px;">
                                <strong>Berangkat dari :</strong> _________________
                            </li>
                            <li><strong>Untuk :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                        </ul>
                    </td>
                </tr>

                {{-- V. (Kosong) --}}
                <tr>
                    <td class="no-col">V.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Tiba :</strong> _________________</li>
                            <li><strong>Pada tanggal :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                            <li style="margin-top: 10px;">
                                <strong>Berangkat dari :</strong> _________________
                            </li>
                            <li><strong>Pada tanggal :</strong> _________________</li>
                            <li><strong>Kepala :</strong> _________________</li>
                        </ul>
                    </td>
                </tr>

                {{-- VI. Kembali ke Pelaihari --}}
                <tr>
                    <td class="no-col">VI.</td>
                    <td>
                        <ul class="rincian-perjalanan">
                            <li><strong>Tiba :</strong> {{ $spd->tempat_berangkat ?? 'Pelaihari, Kab. Tanah Laut' }}</li>
                            <li><strong>Pada tanggal :</strong> {{ $spd->tanggal_kembali ? $spd->tanggal_kembali->format('d F Y') : '09 Juli 2024' }}</li>
                            <li><strong>Kepala :</strong> Pengguna Anggaran</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- KETERANGAN TELAH DIPERIKSA --}}
        <div class="keterangan">
            Telah diperiksa, dengan keterangan bahwa perjalanan tersebut di atas dilakukan atas perintahnya
            dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
        </div>

        {{-- TANDA TANGAN (kiri: Pejabat Teknis, kanan: Penanda Tangan Tujuan) --}}
        <div class="ttd-wrapper">
            <div class="ttd-box">
                <p class="jabatan">Pejabat Pelaksana Teknis Kegiatan,</p>
                <br><br><br>
                <p class="nama">{{ $spd->pejabatTeknisPegawai->nama ?? 'LASMIATI, S. Tr' }}</p>
                <p class="nip">NIP. {{ $spd->pejabatTeknisPegawai->nip ?? '19860412 201001 2 001' }}</p>
            </div>
            <div class="ttd-box">
                <p class="jabatan">
                    {{ $spd->penanda_tangan_jabatan ?? 'Kepala' }},
                </p>
                <br><br><br>
                <p class="nama">{{ $spd->penanda_tangan_nama ?? '_________________' }}</p>
                <p class="nip">NIP. {{ $spd->penanda_tangan_nip ?? '_________________' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
