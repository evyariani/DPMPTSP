<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 14px;
        }
        .title {
            text-align: center;
            margin: 30px 0;
            font-weight: bold;
            font-size: 16px;
            text-decoration: underline;
        }
        .content {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            width: 300px;
            text-align: center;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #666;
        }
        .list-item {
            margin: 3px 0;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH KABUPATEN TANAH LAUT</h1>
        <h2>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU</h2>
        <p>Jl. A. Syairani No. 1 Pelaihari Kode Pos 70814</p>
        <p>Telp. (0512) 123456, Email: dpmptsp@tanahlautkab.go.id</p>
    </div>

    <div class="title">
        SURAT PERINTAH TUGAS<br>
        Nomor: {{ $spt->nomor_surat }}
    </div>

    <div class="content">
        <table>
            <tr>
                <td width="25%"><strong>Dasar</strong></td>
                <td width="75%">:</td>
            </tr>
            <tr>
                <td colspan="2">
                    @foreach($dasarList as $index => $dasar)
                        <div class="list-item">{{ $index + 1 }}. {{ $dasar }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2">MEMERINTAHKAN</th>
            </tr>
            <tr>
                <td width="25%">Kepada</td>
                <td width="75%">:</td>
            </tr>
            <tr>
                <td colspan="2">
                    @foreach($pegawaiList as $index => $pegawai)
                        <div class="list-item">
                            <strong>{{ $index + 1 }}. {{ $pegawai->nama }}</strong><br>
                            NIP: {{ $pegawai->nip ?? '-' }}<br>
                            Pangkat/Gol: {{ $pegawai->pangkat ?? '-' }} / {{ $pegawai->gol ?? '-' }}<br>
                            Jabatan: {{ $pegawai->jabatan ?? '-' }}
                        </div>
                        @if(!$loop->last)
                            <hr style="margin: 10px 0; border: 0.5px dashed #ccc;">
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="25%"><strong>Untuk</strong></td>
                <td width="75%">:</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="list-item">{{ $spt->tujuan }}</div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="25%"><strong>Tempat</strong></td>
                <td width="75%">: {{ $spt->lokasi }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: {{ $spt->tanggal->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div>Ditetapkan di : Pelaihari</div>
            <div>Pada tanggal : {{ $spt->tanggal->format('d F Y') }}</div>
            <div class="signature-line">
                <strong>{{ $spt->penandaTangan->nama ?? '-' }}</strong><br>
                NIP. {{ $spt->penandaTangan->nip ?? '-' }}<br>
                {{ $spt->penandaTangan->jabatan ?? '-' }}
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara elektronik, tidak memerlukan tanda tangan basah.</p>
    </div>
</body>
</html>