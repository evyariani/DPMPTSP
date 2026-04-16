{{-- resources/views/spd/export-spd-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPD - {{ $spd->nomor_surat ?? 'Surat Perintah Dinas' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        /* Container untuk setiap halaman */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px auto;
            padding: 20mm;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            page-break-after: always;
            position: relative;
        }

        /* Header surat */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header .logo {
            display: inline-block;
            vertical-align: middle;
        }

        .header .logo img {
            max-height: 70px;
            width: auto;
        }

        .header .title {
            display: inline-block;
            vertical-align: middle;
            margin-left: 15px;
            text-align: center;
        }

        .header .title h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
        }

        .header .title h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }

        .header .title p {
            font-size: 10pt;
            margin: 2px 0;
        }

        /* Nomor surat */
        .nomor-surat {
            text-align: center;
            margin: 15px 0;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Table styles */
        .table-spd {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .table-spd td, .table-spd th {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .table-spd td.label {
            width: 35%;
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .table-borderless {
            width: 100%;
            border-collapse: collapse;
        }

        .table-borderless td {
            padding: 5px;
            vertical-align: top;
        }

        /* Tanda tangan */
        .ttd-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .ttd-box {
            width: 45%;
            text-align: center;
        }

        .ttd-box p {
            margin: 5px 0;
        }

        .ttd-space {
            margin-top: 60px;
        }

        .ttd-line {
            margin-top: 30px;
        }

        /* Rincian perjalanan */
        .subtitle {
            font-size: 13pt;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        /* Print styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
                padding: 15mm;
                page-break-after: always;
            }
            .no-print {
                display: none;
            }
            .page:last-child {
                page-break-after: auto;
            }
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        /* Checklist */
        .checklist {
            display: inline-block;
            width: 20px;
            text-align: center;
        }

        .checklist.checked {
            font-weight: bold;
        }

        /* Rincian biaya */
        .rincian-biaya {
            margin: 15px 0;
        }

        .rincian-biaya table {
            width: 100%;
            border-collapse: collapse;
        }

        .rincian-biaya th, .rincian-biaya td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .rincian-biaya th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        hr {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    @php
        // Helper function untuk format mata uang
        function formatRupiah($number) {
            return 'Rp ' . number_format($number, 0, ',', '.');
        }
    @endphp

    {{-- HALAMAN DEPAN SPD --}}
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="logo" style="float: left; width: 80px;">
                {{-- Logo bisa ditambahkan di sini --}}
                <div style="width: 70px; height: 70px; border: 1px solid #ccc; text-align: center; line-height: 70px; font-size: 10px;">LOGO</div>
            </div>
            <div class="title" style="margin-left: 90px;">
                <h1>PEMERINTAH KABUPATEN TANAH LAUT</h1>
                <h2>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU</h2>
                <p>Jl. Jend. Sudirman No. 1 Pelaihari Kode Pos 70815</p>
                <p>Email: dpmptsp@tanahlautkab.go.id Telp. (0512) 21000</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Nomor Surat -->
        <div class="nomor-surat">
            SURAT PERINTAH DINAS (SPD) <br>
            NOMOR : {{ $spd->nomor_surat ?? '______________' }}
        </div>

        <!-- Data SPD -->
        <table class="table-spd">
            <tr>
                <td class="label">1. Pejabat yang berwenang mengeluarkan perintah</td>
                <td>:
                    {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nama : '-' }}<br>
                    NIP. {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nip : '-' }}<br>
                    Jabatan {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->jabatan : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">2. Nama/NIP Pegawai yang diperintah</td>
                <td>:
                    @if($spd->pegawais && $spd->pegawais->count())
                        @foreach($spd->pegawais as $index => $pegawai)
                            {{ $index+1 }}. {{ $pegawai->nama }} (NIP. {{ $pegawai->nip }})<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">3. Pangkat dan Golongan Ruang</td>
                <td>:
                    @if($spd->pegawais && $spd->pegawais->count())
                        @foreach($spd->pegawais as $index => $pegawai)
                            {{ $index+1 }}. {{ $pegawai->pangkat ?? '-' }} / {{ $pegawai->golongan ?? '-' }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">4. Jabatan</td>
                <td>:
                    @if($spd->pegawais && $spd->pegawais->count())
                        @foreach($spd->pegawais as $index => $pegawai)
                            {{ $index+1 }}. {{ $pegawai->jabatan ?? '-' }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">5. Maksud Perjalanan Dinas</td>
                <td>: {{ $spd->maksud_perjadin ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">6. Alat Transportasi</td>
                <td>: {{ $spd->alat_transportasi_label ?? $spd->alat_transportasi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">7. Tempat Berangkat</td>
                <td>: {{ $spd->tempat_berangkat ?? 'Pelaihari' }}</td>
            </tr>
            <tr>
                <td class="label">8. Tempat Tujuan</td>
                <td>: {{ $spd->tempatTujuan ? $spd->tempatTujuan->nama : ($spd->tempat_tujuan ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">9. Lamanya Perjalanan Dinas</td>
                <td>: {{ $spd->lama_perjadin ?? $spd->lama ?? '-' }} ({{ $spd->lama_perjadin ?? $spd->lama ?? '-' }} hari)</td>
            </tr>
            <tr>
                <td class="label">10. Tanggal Berangkat</td>
                <td>: {{ $spd->tanggal_berangkat ? \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">11. Tanggal Kembali</td>
                <td>: {{ $spd->tanggal_kembali ? \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">12. Pengikut</td>
                <td>: {{ $spd->pengikut ?? '-' }} Orang</td>
            </tr>
            <tr>
                <td class="label">13. Tempat Dikeluarkan</td>
                <td>: {{ $spd->tempat_dikeluarkan ?? 'Pelaihari' }}</td>
            </tr>
            <tr>
                <td class="label">14. Tanggal Dikeluarkan</td>
                <td>: {{ $spd->tanggal_dikeluarkan ? \Carbon\Carbon::parse($spd->tanggal_dikeluarkan)->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">15. Keterangan Lain</td>
                <td>: {{ $spd->keterangan ?? '-' }}</td>
            </tr>
        </table>

        <!-- Catatan -->
        <div style="margin-top: 20px; font-size: 10pt;">
            <p><strong>Catatan:</strong></p>
            <p>1. SPD ini berlaku untuk perjalanan dinas yang bersangkutan.</p>
            <p>2. SPD ini harus dibawa selama melaksanakan perjalanan dinas.</p>
            <p>3. Setelah selesai melaksanakan perjalanan dinas, SPD ini harus diserahkan kepada pejabat yang berwenang.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd-container" style="margin-top: 40px;">
            <div class="ttd-box">
                <p>Dikeluarkan di : {{ $spd->tempat_dikeluarkan ?? 'Pelaihari' }}</p>
                <p>Pada Tanggal : {{ $spd->tanggal_dikeluarkan ? \Carbon\Carbon::parse($spd->tanggal_dikeluarkan)->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}</p>
                <br><br><br>
                <p>Pejabat yang mengeluarkan perintah,</p>
                <br><br><br>
                <p><strong><u>{{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nama : '____________________' }}</u></strong></p>
                <p>NIP. {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nip : '____________________' }}</p>
                <p>Jabatan {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->jabatan : 'Kepala Dinas' }}</p>
            </div>
            <div class="ttd-box">
                <p>&nbsp;</p>
                <br><br><br>
                <p>Telah diterima dengan jelas perintah tersebut di atas,</p>
                <p>Pada Tanggal : _______________</p>
                <br><br><br>
                <p><strong><u>____________________</u></strong></p>
                <p>NIP. ____________________</p>
                <p>(Yang Menerima Perintah)</p>
            </div>
        </div>
    </div>

    {{-- HALAMAN BELAKANG SPD --}}
    <div class="page">
        <div class="header">
            <div class="logo" style="float: left; width: 80px;">
                <div style="width: 70px; height: 70px; border: 1px solid #ccc; text-align: center; line-height: 70px; font-size: 10px;">LOGO</div>
            </div>
            <div class="title" style="margin-left: 90px;">
                <h1>PEMERINTAH KABUPATEN TANAH LAUT</h1>
                <h2>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU</h2>
                <p>Jl. Jend. Sudirman No. 1 Pelaihari Kode Pos 70815</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="text-align: center; margin: 15px 0;">
            <strong>SURAT PERINTAH DINAS (SPD)</strong><br>
            <strong>NOMOR : {{ $spd->nomor_surat ?? '______________' }}</strong>
        </div>

        <!-- I. RINCIAN PERJALANAN DINAS -->
        <div class="subtitle">I. RINCIAN PERJALANAN DINAS</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    <strong>A. Keberangkatan</strong><br>
                    Sempat Kedudukan : {{ $spd->tempat_berangkat ?? 'Pelaihari' }}<br>
                    Ke Kota : {{ $spd->tempatTujuan ? $spd->tempatTujuan->nama : ($spd->tempat_tujuan ?? '-') }}<br>
                    Pada Tanggal : {{ $spd->tanggal_berangkat ? \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d F Y') : '-' }}
                </td>
                <td width="50%">
                    <strong>B. Kembali</strong><br>
                    Tiba Kota : {{ $spd->tempat_berangkat ?? 'Pelaihari' }}<br>
                    Pada Tanggal : {{ $spd->tanggal_kembali ? \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d F Y') : '-' }}<br>
                    Berangkat dari Kota : {{ $spd->tempatTujuan ? $spd->tempatTujuan->nama : ($spd->tempat_tujuan ?? '-') }}
                </td>
            </tr>
        </table>

        <div style="margin-top: 15px;">
            <strong>Dasar Perjalanan Dinas</strong><br>
            {{ $spd->dasar_perjalanan ?? '_______________________________________________' }}
        </div>

        <!-- II. PENGESAHAN -->
        <div class="subtitle">II. PENGESAHAN</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    <strong>Pejabat yang Berwenang Mengesahkan</strong><br>
                    Nama : {{ $spd->pejabatPengesah ? $spd->pejabatPengesah->nama : ($spd->nama_mengesahkan ?? '-') }}<br>
                    NIP : {{ $spd->pejabatPengesah ? $spd->pejabatPengesah->nip : ($spd->nip_mengesahkan ?? '-') }}<br>
                    Jabatan : {{ $spd->pejabatPengesah ? $spd->pejabatPengesah->jabatan : ($spd->jabatan_mengesahkan ?? '-') }}<br>
                    Tanggal Pengesahan : {{ $spd->tanggal_pengesahan ? \Carbon\Carbon::parse($spd->tanggal_pengesahan)->format('d F Y') : '-' }}
                </td>
                <td width="50%">
                    <strong>Yang Mengesahkan</strong><br>
                    Nama : {{ $spd->nama_mengesahkan ?? '-' }}<br>
                    NIP : {{ $spd->nip_mengesahkan ?? '-' }}<br>
                    Jabatan : {{ $spd->jabatan_mengesahkan ?? '-' }}
                </td>
            </tr>
        </table>

        <!-- III. PERTANGGUNGJAWABAN -->
        <div class="subtitle">III. PERTANGGUNGJAWABAN</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    Tanggal Berangkat (Real) : {{ $spd->tanggal_berangkat_real ? \Carbon\Carbon::parse($spd->tanggal_berangkat_real)->format('d F Y') : '-' }}
                </td>
                <td width="50%">
                    Tanggal Kembali (Real) : {{ $spd->tanggal_kembali_real ? \Carbon\Carbon::parse($spd->tanggal_kembali_real)->format('d F Y') : '-' }}
                </td>
            </tr>
        </table>

        <div style="margin-top: 10px;">
            <strong>Catatan Pertanggungjawaban:</strong><br>
            {{ $spd->catatan_pertanggungjawaban ?? '-' }}
        </div>

        <!-- IV. PEJABAT PELAKSANA TEKNIS KEGIATAN -->
        <div class="subtitle">IV. PEJABAT PELAKSANA TEKNIS KEGIATAN</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    Nama PPTK : {{ $spd->pptk ? $spd->pptk->nama : ($spd->nama_pptk_spd ?? '-') }}<br>
                    NIP : {{ $spd->pptk ? $spd->pptk->nip : ($spd->nip_pptk_spd ?? '-') }}<br>
                    Jabatan : {{ $spd->pptk ? $spd->pptk->jabatan : ($spd->jabatan_pptk_spd ?? '-') }}<br>
                    Tanggal : {{ $spd->tanggal_pptk_spd ? \Carbon\Carbon::parse($spd->tanggal_pptk_spd)->format('d F Y') : '-' }}
                </td>
                <td width="50%">
                    Dikeluarkan di Tempat : {{ $spd->dikeluarkan_di_tempat ?? 'Pelaihari' }}<br>
                    Pada Tanggal : {{ $spd->dikeluarkan_pada_tanggal ? \Carbon\Carbon::parse($spd->dikeluarkan_pada_tanggal)->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- V. PENGESAHAN ATAS BEBAN ANGGARAN -->
        <div class="subtitle">V. PENGESAHAN ATAS BEBAN ANGGARAN</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    SKPD : {{ $spd->skpd_spd ?? 'Dinas Penanaman Modal dan PTSP' }}<br>
                    Kode Rekening : {{ $spd->kode_rekening_spd ?? ($spd->kode_rek ?? '-') }}<br>
                    Program/Kegiatan : {{ $spd->program_kegiatan ?? '-' }}
                </td>
                <td width="50%">
                    <strong>Beban Anggaran :</strong><br>
                    Biaya Transport : {{ formatRupiah($spd->biaya_transport_spd ?? 0) }}<br>
                    Biaya Penginapan : {{ formatRupiah($spd->biaya_penginapan_spd ?? 0) }}<br>
                    Uang Harian : {{ formatRupiah($spd->biaya_harian_spd ?? 0) }}<br>
                    Tiket/Retribusi : {{ formatRupiah($spd->biaya_tiket_spd ?? 0) }}<br>
                    <strong>Total Anggaran : {{ formatRupiah(($spd->biaya_transport_spd ?? 0) + ($spd->biaya_penginapan_spd ?? 0) + ($spd->biaya_harian_spd ?? 0) + ($spd->biaya_tiket_spd ?? 0)) }}</strong>
                </td>
            </tr>
        </table>

        <!-- Pengesahan Bendahara dan PPK -->
        <div class="subtitle">VI. PENGESAHAN</div>

        <table class="table-borderless">
            <tr>
                <td width="50%">
                    <strong>Bendahara Pengeluaran</strong><br>
                    Nama : {{ $spd->bendahara ? $spd->bendahara->nama : ($spd->nama_bendahara_spd ?? '-') }}<br>
                    NIP : {{ $spd->bendahara ? $spd->bendahara->nip : ($spd->nip_bendahara_spd ?? '-') }}
                </td>
                <td width="50%">
                    <strong>Pejabat Pembuat Komitmen (PPK)</strong><br>
                    Nama : {{ $spd->ppk ? $spd->ppk->nama : ($spd->nama_ppk_spd ?? '-') }}<br>
                    NIP : {{ $spd->ppk ? $spd->ppk->nip : ($spd->nip_ppk_spd ?? '-') }}
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan -->
        <div class="ttd-container" style="margin-top: 40px;">
            <div class="ttd-box">
                <p>Mengetahui,</p>
                <p>Pejabat Pelaksana Teknis Kegiatan,</p>
                <br><br><br>
                <p><strong><u>{{ $spd->pptk ? $spd->pptk->nama : ($spd->nama_pptk_spd ?? '____________________') }}</u></strong></p>
                <p>NIP. {{ $spd->pptk ? $spd->pptk->nip : ($spd->nip_pptk_spd ?? '____________________') }}</p>
                <p>Jabatan {{ $spd->pptk ? $spd->pptk->jabatan : ($spd->jabatan_pptk_spd ?? '-') }}</p>
            </div>
            <div class="ttd-box">
                <p>Yang Mengesahkan,</p>
                <p>Kepala Dinas,</p>
                <br><br><br>
                <p><strong><u>{{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nama : '____________________' }}</u></strong></p>
                <p>NIP. {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->nip : '____________________' }}</p>
                <p>Jabatan {{ $spd->penggunaAnggaran ? $spd->penggunaAnggaran->jabatan : 'Kepala Dinas' }}</p>
            </div>
        </div>
    </div>

    {{-- Tombol cetak (hanya tampil di browser, tidak saat print) --}}
    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <button onclick="window.print();" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
