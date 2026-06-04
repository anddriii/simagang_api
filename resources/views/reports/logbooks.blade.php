<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; vertical-align: top; }
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
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Kendala</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['student_name'] }}</td>
                    <td>{{ $row['nim'] }}</td>
                    <td>{{ $row['company'] }}</td>
                    <td>{{ $row['activity_date'] }}</td>
                    <td>{{ $row['start_time'] }} - {{ $row['end_time'] }}</td>
                    <td>{{ $row['title'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td>{{ $row['problem'] }}</td>
                    <td>{{ $row['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>