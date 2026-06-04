<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Eksternal - {{ $user->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; color: #1e40af; }
        .header p { margin: 5px 0; color: #666; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #aaa; }
        .status-disetujui { color: #059669; font-weight: bold; }
        .status-menunggu { color: #d97706; font-weight: bold; }
        .status-ditolak { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>CITRA HUSADA</h2>
        <p>SERTIFIKAT EKSTERNAL - {{ strtoupper($user->nama) }}</p>
        <p>NIK: {{ $user->nik }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelatihan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sertifikats as $index => $sertifikat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sertifikat->judul }}</td>
                    <td>{{ $sertifikat->created_at->format('d/m/Y') }}</td>
                    <td class="{{ $sertifikat->status == 'Disetujui' ? 'status-disetujui' : ($sertifikat->status == 'Ditolak' ? 'status-ditolak' : 'status-menunggu') }}">
                        {{ $sertifikat->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Laporan Otomatis Citra Husada - Halaman 1
    </div>
</body>
</html>
