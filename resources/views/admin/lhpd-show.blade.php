<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LHPD - {{ $lhpd->id_lhpd }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }
        .info-label {
            width: 180px;
            font-weight: bold;
        }
        .result-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 5px;
            background-color: #f9f9f9;
            min-height: 150px;
        }
        .foto-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .foto-item {
            width: 150px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        .foto-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL PERJALANAN DINAS (LHPD)</h1>
        <h2>DINAS PENANAMAN MODAL DAN PTSP KABUPATEN TANAH LAUT</h2>
    </div>

    <table class="info-table">
        <tr><td class="info-label">Tujuan Perjalanan</td><td>{{ $lhpd->tujuan }}</td></tr>
        <tr><td class="info-label">Dasar</td><td>
            @php $dasarList = is_array($lhpd->dasar) ? $lhpd->dasar : json_decode($lhpd->dasar, true); @endphp
            @if($dasarList && count($dasarList) > 0)
                <ul style="margin:0; padding-left:20px;">
                    @foreach($dasarList as $dasar)
                        <li>{{ $dasar }}</li>
                    @endforeach
                </ul>
            @else
                -
            @endif
        </td></tr>
        <tr><td class="info-label">Tanggal Berangkat</td><td>{{ $lhpd->tanggal_berangkat ? \Carbon\Carbon::parse($lhpd->tanggal_berangkat)->format('d F Y') : '-' }}</td></tr>
        <tr><td class="info-label">Daerah Tujuan</td><td>{{ $lhpd->daerahTujuan?->nama ?? '-' }}</td></tr>
        <tr><td class="info-label">Pegawai yang Ditugaskan</td><td>
            @php $pegawaiList = $lhpd->pegawai_list; @endphp
            @if($pegawaiList && count($pegawaiList) > 0)
                <ol style="margin:0; padding-left:20px;">
                    @foreach($pegawaiList as $pegawai)
                        <li>{{ $pegawai->nama }} (NIP: {{ $pegawai->nip ?? '-' }})</li>
                    @endforeach
                </ol>
            @else
                -
            @endif
        </td></tr>
        <tr><td class="info-label">Hasil Perjalanan Dinas</td><td>
            <div class="result-box">{{ $lhpd->hasil ?? '-' }}</div>
        </td></tr>
        <tr><td class="info-label">Tempat LHPD Dikeluarkan</td><td>{{ $lhpd->tempatDikeluarkan?->nama ?? '-' }}</td></tr>
        <tr><td class="info-label">Tanggal LHPD</td><td>{{ $lhpd->tanggal_lhpd ? \Carbon\Carbon::parse($lhpd->tanggal_lhpd)->format('d F Y') : '-' }}</td></tr>
    </table>

    @if(isset($fotoUrls) && $fotoUrls->count() > 0)
    <div>
        <h4 style="margin-bottom: 10px;">Dokumentasi Foto:</h4>
        <div class="foto-grid">
            @foreach($fotoUrls as $fotoUrl)
            <div class="foto-item">
                <img src="{{ public_path(str_replace('/storage', 'storage', $fotoUrl)) }}" alt="Foto">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="signature">
        <p>{{ $lhpd->tempatDikeluarkan?->nama ?? '________' }}, {{ $lhpd->tanggal_lhpd ? \Carbon\Carbon::parse($lhpd->tanggal_lhpd)->format('d F Y') : '________' }}</p>
        <p style="margin-top: 50px;">Mengetahui,</p>
        <p style="margin-top: 50px;">Kepala Dinas Penanaman Modal dan PTSP</p>
        <p style="margin-top: 50px;"><strong>_________________________</strong></p>
        <p>NIP. _________________</p>
    </div>
</body>
</html>