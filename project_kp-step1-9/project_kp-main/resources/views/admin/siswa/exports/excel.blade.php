<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Nama Orang Tua</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->nisn }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>{{ $item->jenis_kelamin_label }}</td>
                    <td>{{ $item->kelas->nama_kelas ?? '—' }}</td>
                    <td>{{ $item->tempat_lahir ?? '—' }}</td>
                    <td>{{ $item->tanggal_lahir?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $item->alamat ?? '—' }}</td>
                    <td>{{ $item->no_hp ?? '—' }}</td>
                    <td>{{ $item->nama_orang_tua ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
