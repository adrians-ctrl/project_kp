<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['siswa']->nama_lengkap }}</td>
                    <td>{{ $item['siswa']->nis }}</td>
                    <td>{{ $item['siswa']->kelas->nama_kelas ?? '—' }}</td>
                    <td>{{ $item['hadir'] }}</td>
                    <td>{{ $item['izin'] }}</td>
                    <td>{{ $item['sakit'] }}</td>
                    <td>{{ $item['alpha'] }}</td>
                    <td>{{ $item['persentase'] ?? 0 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
