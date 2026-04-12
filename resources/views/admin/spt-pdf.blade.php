<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Tugas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 1cm 2cm 2cm 2cm;
            color: #000;
            line-height: 1.2;
        }
        
        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: none;
        }
        
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        
        .logo-cell {
            width: 55px;
            padding-right: 5px;
        }
        
        .logo {
            width: 55px;
            height: 55px;
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
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .header-text .dinas {
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
            letter-spacing: -0.2px;
        }
        
        .header-text .alamat {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .header-text .kontak {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .garis-kop {
            width: 100%;
            margin: 0 0 15px 0;
            clear: both;
            border-top: 1px solid #000;
            border-bottom: 3px solid #000;
            height: 2px;
        }
        
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .judul-surat h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            font-weight: normal;
            text-transform: uppercase;
            margin: 0;
        }
        
        .judul-surat .nomor {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .memerintahkan {
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: normal;
            text-transform: uppercase;
            margin: 0;
        }
        
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .content-table td {
            padding: 1px 2px;
            vertical-align: top;
            border: none;
            font-size: 12pt;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .label-col {
            width: 60px;
            font-weight: normal;
        }
        
        .titikdua-col {
            width: 15px;
            text-align: center;
        }
        
        .nomor-col {
            width: 25px;
            text-align: right;
        }
        
        .sub-label-col {
            width: 80px;
            padding-left: 5px;
        }
        
        .sub-titikdua-col {
            width: 15px;
            text-align: center;
        }
        
        .content-col {
            text-align: left;
        }
        
        .spacer-row td {
            height: 5px;
        }
        
        .pegawai-spacer td {
            height: 15px;
        }
        
        .section-spacer {
            height: 24px;
        }
        
        /* ===== STYLE UNTUK TTD DIGITAL (QR CODE) ===== */
        .tanda-tangan-digital {
            float: right;
            width: 320px;
            text-align: center;
            margin-top: 30px;
            font-size: 12pt;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .tempat-tanggal {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .jabatan-ttd {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        /* QR Code Digital Signature */
        .digital-signature-container {
            display: inline-block;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 10px 0;
            border: 1px solid #ddd;
        }
        
        .digital-signature-qr {
            width: 150px;
            height: 150px;
            display: block;
            margin: 0 auto;
        }
        
        .digital-signature-text {
            font-size: 9pt;
            color: #0d6efd;
            font-family: Arial, sans-serif;
            margin-top: 5px;
            font-weight: bold;
        }
        
        .verification-info {
            font-size: 8pt;
            color: #555;
            font-family: Arial, sans-serif;
            margin-top: 5px;
            line-height: 1.3;
        }
        
        .verification-code {
            font-family: monospace;
            font-size: 9pt;
            background: #f8f9fa;
            padding: 3px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        
        .nama-ttd {
            font-size: 12pt;
            text-decoration: underline;
            margin-top: 5px;
            margin-bottom: 0;
            padding-top: 0;
        }
        
        .pangkat-ttd {
            font-size: 12pt;
            margin-top: 0;
            margin-bottom: 0;
        }
        
        .nip-ttd {
            font-size: 12pt;
            margin-top: 0;
        }
        
        .approval-info {
            font-size: 8pt;
            color: #666;
            margin-top: 8px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        
        .status-watermark {
            position: fixed;
            bottom: 50%;
            right: -50px;
            transform: rotate(-45deg);
            font-size: 60px;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.15);
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
        }
        
        .clearfix {
            clear: both;
        }
        
        .footer-verifikasi {
            position: fixed;
            bottom: 20px;
            right: 30px;
            font-size: 7pt;
            color: #999;
            text-align: right;
            font-family: Arial, sans-serif;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <div class="logo">
                    <img src="{{ public_path('image/Logo_Tala-removebg-preview (2).png') }}" alt="Logo">
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
        <h1>SURAT PERINTAH TUGAS</h1>
        <div class="nomor">Nomor : {{ $spt->nomor_surat }}</div>
    </div>

    <!-- SECTION DASAR -->
    <table class="content-table">
        <tr>
            <td class="label-col">Dasar</td>
            <td class="titikdua-col">:</td>
            <td class="nomor-col">1.</td>
            <td colspan="3">{{ $dasarList[0] ?? '' }}</td>
        </tr>
        @if(isset($dasarList[1]))
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">2.</td>
            <td colspan="3">{{ $dasarList[1] }}</td>
        </tr>
        @endif
        @if(isset($dasarList[2]))
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">3.</td>
            <td colspan="3">{{ $dasarList[2] }}</td>
        </tr>
        @endif
    </table>

    <div class="section-spacer"></div>

    <!-- MEMERINTAHKAN -->
    <div class="memerintahkan">MEMERINTAHKAN</div>

    <div class="section-spacer"></div>

    <!-- SECTION KEPADA -->
    <table class="content-table">
        @foreach($pegawaiList as $index => $pegawai)
        <tr>
            @if($index == 0)
            <td class="label-col">Kepada</td>
            <td class="titikdua-col">:</td>
            @else
            <td></td>
            <td></td>
            @endif
            <td class="nomor-col">{{ $index + 1 }}.</td>
            <td class="sub-label-col">Nama</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->nama }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">Pangkat/gol</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">
                @if($pegawai->pangkat && $pegawai->gol)
                    {{ $pegawai->pangkat }} ({{ $pegawai->gol }})
                @elseif($pegawai->pangkat)
                    {{ $pegawai->pangkat }}
                @elseif($pegawai->gol)
                    Gol. {{ $pegawai->gol }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">NIP</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="sub-label-col">Jabatan</td>
            <td class="sub-titikdua-col">:</td>
            <td class="content-col">{{ $pegawai->jabatan ?? '-' }}</td>
        </tr>
        @if(!$loop->last)
        <tr class="pegawai-spacer"><td colspan="6"></td></tr>
        @endif
        @endforeach
    </table>

    <div class="section-spacer"></div>

    <!-- SECTION UNTUK -->
    <table class="content-table">
        <tr>
            <td class="label-col">Untuk</td>
            <td class="titikdua-col">:</td>
            <td class="nomor-col">1.</td>
            <td colspan="3">{{ $spt->tujuan }}</td>
        </tr>
        @if(isset($spt->tujuan_lain) && $spt->tujuan_lain)
        <tr>
            <td></td>
            <td></td>
            <td class="nomor-col">2.</td>
            <td colspan="3">{{ $spt->tujuan_lain }}</td>
        </tr>
        @endif
    </table>

    <div class="section-spacer"></div>

    <!-- ===== TANDA TANGAN DIGITAL (QR CODE) ===== -->
    <div class="tanda-tangan-digital">
        @php
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
            
            $tanggal = $spt->tanggal->format('d') . ' ' . 
                       $bulanIndonesia[$spt->tanggal->format('F')] . ' ' . 
                       $spt->tanggal->format('Y');
        @endphp
        
        <div class="tempat-tanggal">Pelaihari, {{ $tanggal }}</div>
        <div class="jabatan-ttd">{{ $spt->penandaTangan->jabatan ?? 'Kepala Dinas' }}</div>
        
        <!-- CEK STATUS APPROVAL -->
        @if($spt->isApproved())
            @php
                // Generate QR Code dengan Endroid
                $qrImage = null;
                if (class_exists('Endroid\QrCode\Builder\Builder')) {
                    try {
                        $result = \Endroid\QrCode\Builder\Builder::create()
                            ->writer(new \Endroid\QrCode\Writer\PngWriter())
                            ->data($spt->verification_url)
                            ->encoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
                            ->errorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High)
                            ->size(150)
                            ->margin(10)
                            ->roundBlockSizeMode(\Endroid\QrCode\RoundBlockSizeMode::Margin)
                            ->build();
                        $qrImage = 'data:image/png;base64,' . base64_encode($result->getString());
                    } catch (\Exception $e) {
                        \Log::error('Endroid QR Code failed: ' . $e->getMessage());
                    }
                }
                
                // Fallback ke Simple QR Code jika Endroid gagal
                if (!$qrImage && extension_loaded('imagick')) {
                    try {
                        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                            ->size(150)
                            ->margin(1)
                            ->errorCorrection('H')
                            ->generate($spt->verification_url);
                        $qrImage = 'data:image/png;base64,' . base64_encode($qrCode);
                    } catch (\Exception $e) {
                        \Log::warning('Simple QR failed: ' . $e->getMessage());
                    }
                }
                
                // Fallback ke Google Charts API
                if (!$qrImage) {
                    try {
                        $googleQrUrl = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($spt->verification_url) . '&choe=UTF-8';
                        $qrImageContent = @file_get_contents($googleQrUrl);
                        if ($qrImageContent !== false) {
                            $qrImage = 'data:image/png;base64,' . base64_encode($qrImageContent);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Google Charts QR failed: ' . $e->getMessage());
                    }
                }
            @endphp
            
            @if($qrImage)
                <!-- TAMPILKAN QR CODE SEBAGAI TANDA TANGAN DIGITAL -->
                <div class="digital-signature-container">
                    <img src="{{ $qrImage }}" class="digital-signature-qr" alt="QR Code Tanda Tangan Digital">
                    {{-- <div class="digital-signature-text">
                        ✓ TERTANDA TANGAN SECARA DIGITAL
                    </div> --}}
                </div>
                
                <div class="verification-info">
                    Scan QR Code untuk verifikasi keaslian dokumen
                </div>
                
                <div class="verification-code">
                    Kode Verifikasi: {{ $spt->verification_code }}
                </div>
            @else
                <!-- FALLBACK JIKA QR CODE GAGAL -->
                <div class="digital-signature-container" style="padding: 15px; min-width: 200px;">
                    <div class="digital-signature-text" style="font-size: 11pt;">
                        ✓ TERTANDA TANGAN DIGITAL
                    </div>
                    <div class="verification-info" style="margin-top: 10px;">
                        <strong>Kode Verifikasi:</strong> {{ $spt->verification_code }}
                    </div>
                    <div class="verification-info" style="font-size: 7pt; word-break: break-all; margin-top: 5px;">
                        {{ $spt->verification_url }}
                    </div>
                </div>
            @endif
            
            <!-- NAMA DAN NIP PENANDA TANGAN -->
            <div class="nama-ttd">{{ $spt->penandaTangan->nama ?? '-' }}</div>
            <div class="pangkat-ttd">
                @if($spt->penandaTangan->pangkat && $spt->penandaTangan->gol)
                    {{ $spt->penandaTangan->pangkat }} ({{ $spt->penandaTangan->gol }})
                @elseif($spt->penandaTangan->pangkat)
                    {{ $spt->penandaTangan->pangkat }}
                @elseif($spt->penandaTangan->gol)
                    Gol. {{ $spt->penandaTangan->gol }}
                @else
                    -
                @endif
            </div>
            <div class="nip-ttd">NIP. {{ $spt->penandaTangan->nip ?? '-' }}</div>
            
            <div class="approval-info">
                Disetujui secara digital pada {{ $spt->approved_at ? $spt->approved_at->format('d/m/Y H:i:s') : '-' }}
                @if($spt->verification_count > 0)
                    <br>✓ Telah diverifikasi {{ $spt->verification_count }} kali
                @endif
            </div>
        @elseif($spt->isRejected())
            <div style="height: 80px;"></div>
            <div class="nama-ttd" style="text-decoration: none; color: #999;">{{ $spt->penandaTangan->nama ?? '-' }}</div>
            <div class="pangkat-ttd" style="color: #999;">
                @if($spt->penandaTangan->pangkat && $spt->penandaTangan->gol)
                    {{ $spt->penandaTangan->pangkat }} ({{ $spt->penandaTangan->gol }})
                @elseif($spt->penandaTangan->pangkat)
                    {{ $spt->penandaTangan->pangkat }}
                @elseif($spt->penandaTangan->gol)
                    Gol. {{ $spt->penandaTangan->gol }}
                @endif
            </div>
            <div class="nip-ttd" style="color: #999;">NIP. {{ $spt->penandaTangan->nip ?? '-' }}</div>
            <div class="approval-info" style="color: #dc2626;">
                STATUS: DITOLAK - {{ $spt->rejection_reason ?? 'Tidak ada alasan' }}
            </div>
            <div class="status-watermark">DITOLAK</div>
        @else
            <div style="height: 80px;"></div>
            <div class="nama-ttd" style="text-decoration: none; color: #999;">{{ $spt->penandaTangan->nama ?? '-' }}</div>
            <div class="pangkat-ttd" style="color: #999;">
                @if($spt->penandaTangan->pangkat && $spt->penandaTangan->gol)
                    {{ $spt->penandaTangan->pangkat }} ({{ $spt->penandaTangan->gol }})
                @elseif($spt->penandaTangan->pangkat)
                    {{ $spt->penandaTangan->pangkat }}
                @elseif($spt->penandaTangan->gol)
                    Gol. {{ $spt->penandaTangan->gol }}
                @endif
            </div>
            <div class="nip-ttd" style="color: #999;">NIP. {{ $spt->penandaTangan->nip ?? '-' }}</div>
            <div class="approval-info" style="color: #d97706;">
                Menunggu persetujuan Kepala Dinas
            </div>
            <div class="status-watermark" style="color: rgba(217, 119, 6, 0.15);">MENUNGGU</div>
        @endif
    </div>
    
    <div class="clearfix"></div>

    <!-- Footer Verifikasi -->
    @if($spt->isApproved() && $spt->verification_code)
    <div class="footer-verifikasi">
        Dokumen ini ditandatangani secara elektronik.<br>
        Verifikasi: {{ $spt->verification_code }}
    </div>
    @endif
</body>
</html>