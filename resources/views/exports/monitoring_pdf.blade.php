<!DOCTYPE html>
<html>
<head>
    <title>Laporan Monitoring Pelatihan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; color: #1e40af; }
        .header p { margin: 5px 0; color: #666; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #aaa; }
        .status-selesai { color: #059669; font-weight: bold; }
        .status-progres { color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h2>CITRA HUSADA</h2>
        <p>LAPORAN MONITORING PELATIHAN KARYAWAN</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>NIK</th>
                <th>Unit Kerja</th>
                <th>Nama Pelatihan</th>
                <th>Progres</th>
                <th>Status</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
                @php
                    $totalSteps = $report->materi->subMateris->count() + $report->materi->postTests->count();
                    $percent = $totalSteps > 0 ? round(($report->urutan_selesai / $totalSteps) * 100) : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->user->nik }}</td>
                    <td>{{ $report->user?->unitKerjas->pluck('unit_name')->join(', ') ?: '-' }}</td>
                    <td>{{ $report->materi->judul }}</td>
                    <td>{{ $percent }}%</td>
                    <td class="{{ $report->status == 'Selesai' ? 'status-selesai' : 'status-progres' }}">
                        {{ $report->status }}
                    </td>
                    <td>{{ $report->skor_total !== null ? round($report->skor_total, 1) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Laporan Otomatis Citra Husada - Halaman 1
    </div>
</body>
</html>
