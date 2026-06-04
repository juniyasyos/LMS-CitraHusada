<!DOCTYPE html>
<html>

<head>
    <title>Statistik Pelatihan RS Citra Husada</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #0056b3;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #0056b3;
            color: white;
            text-align: left;
            padding: 12px;
            font-size: 12px;
            text-transform: uppercase;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-terpenuhi {
            background-color: #d4edda;
            color: #155724;
        }

        .status-belum {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .signature {
            margin-top: 30px;
            margin-right: 50px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Statistik Pelatihan</h1>
        <p>RS Citra Husada - Learning Management System</p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Lengkap</th>
                <th width="25%">Unit Kerja</th>
                <th width="15%">Pelatihan</th>
                <th width="10%">Total JPL</th>
                <th width="15%">Status JPL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $user->nama }}</strong></td>
                    <td>{{ $user->unitKerja ? $user->unitKerja->unit_kerja : '-' }}</td>
                    <td>{{ $user->pelatihan_selesai }} Selesai</td>
                    <td>{{ $user->total_jpl ?? 0 }}</td>
                    <td>
                        @if(($user->total_jpl ?? 0) >= 20)
                            <span class="status-badge status-terpenuhi">Terpenuhi</span>
                        @else
                            <span class="status-badge status-belum">Belum Terpenuhi</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>