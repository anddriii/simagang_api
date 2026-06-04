<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>NIM</th>
                <th>Perusahaan</th>
                <th>Dosen</th>
                <th>Pembimbing Lapangan</th>
                <th>Periode</th>
                <th>Total Logbook</th>
                <th>Disetujui</th>
                <th>Progress</th>
                <th>Warning</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['student_name'] }}</td>
                    <td>{{ $row['nim'] }}</td>
                    <td>{{ $row['company'] }}</td>
                    <td>{{ $row['lecturer'] }}</td>
                    <td>{{ $row['field_supervisor'] }}</td>
                    <td>{{ $row['period'] }}</td>
                    <td>{{ $row['total_logbooks'] }}</td>
                    <td>{{ $row['approved_logbooks'] }}</td>
                    <td>{{ $row['progress_percentage'] }}%</td>
                    <td>{{ $row['warning_status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>