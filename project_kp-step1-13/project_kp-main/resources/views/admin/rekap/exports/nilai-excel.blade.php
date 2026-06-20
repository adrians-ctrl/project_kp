<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Semester</th>
                <th>Tahun Ajaran</th>
                <th>Nilai Tugas</th>
                <th>Nilai UTS</th>
                <th>Nilai UAS</th>
                <th>Nilai Akhir</th>
                <th>Grade</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($query as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->siswa->nama_lengkap }}</td>
                    <td>{{ $item->siswa->nis }}</td>
                    <td>{{ $item->siswa->kelas->nama_kelas ?? '—' }}</td>
                    <td>{{ $item->mapel->nama_mapel ?? '—' }}</td>
                    <td>{{ $item->semester }}</td>
                    <td>{{ $item->tahun_ajaran }}</td>
                    <td>{{ $item->nilai_tugas }}</td>
                    <td>{{ $item->nilai_uts }}</td>
                    <td>{{ $item->nilai_uas }}</td>
                    <td>{{ number_format($item->nilai_akhir, 2) }}</td>
                    <td>{{ $item->grade }}</td>
                    <td>{{ $item->predikat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
