<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; color: #1a1a1a; }

        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { font-size: 13pt; font-weight: bold; color: #1e3a8a; }
        .header h2 { font-size: 10pt; font-weight: normal; color: #444; margin-top: 2px; }
        .header p  { font-size: 8.5pt; color: #777; margin-top: 2px; }

        .meta { display: flex; justify-content: space-between; font-size: 8pt; color: #777; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background-color: #1e3a8a;
            color: #fff;
            padding: 7px 8px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1e3a8a;
        }
        thead th.center { text-align: center; }
        tbody td { padding: 6px 8px; border: 1px solid #e5e7eb; font-size: 8.5pt; vertical-align: middle; }
        tbody tr:nth-child(even) td { background-color: #f5f7ff; }
        tfoot td { padding: 7px 8px; background: #f0f4ff; font-weight: bold; font-size: 8.5pt; border: 1px solid #ccc; }

        .grade-a { background:#dcfce7; color:#15803d; font-weight:bold; padding:2px 6px; border-radius:10px; }
        .grade-b { background:#dbeafe; color:#1d4ed8; font-weight:bold; padding:2px 6px; border-radius:10px; }
        .grade-c { background:#fef9c3; color:#b45309; font-weight:bold; padding:2px 6px; border-radius:10px; }
        .grade-d { background:#fee2e2; color:#dc2626; font-weight:bold; padding:2px 6px; border-radius:10px; }
        .grade-e { background:#fee2e2; color:#dc2626; font-weight:bold; padding:2px 6px; border-radius:10px; }

        .footer { margin-top: 14px; font-size: 7.5pt; color: #aaa; text-align: center;
                  border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        @php $profil = \App\Models\ProfilSekolah::first(); @endphp
        <h1>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</h1>
        <h2>Rekap Nilai Siswa — Semester {{ $semester }} / Tahun Ajaran {{ $tahunAjaran }}</h2>
        <p>
            {{ $kelas ? 'Kelas: ' . $kelas->nama_kelas : 'Semua Kelas' }}
            {{ $mapel ? ' | Mapel: ' . $mapel->nama_mapel : '' }}
        </p>
    </div>

    <div class="meta">
        <span>Total: {{ $nilaiQuery->count() }} data</span>
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px" class="center">No</th>
                <th style="text-align:left">Nama Siswa</th>
                <th style="text-align:left">Kelas</th>
                <th style="text-align:left">Mata Pelajaran</th>
                <th class="center">Tugas</th>
                <th class="center">UTS</th>
                <th class="center">UAS</th>
                <th class="center">Nilai Akhir</th>
                <th class="center">Grade</th>
                <th style="text-align:left">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nilaiQuery as $i => $item)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $item->siswa->nama_lengkap }}</td>
                    <td>{{ $item->siswa->kelas->nama_kelas ?? '—' }}</td>
                    <td>{{ $item->mapel->nama_mapel ?? '—' }}</td>
                    <td style="text-align:center">{{ $item->nilai_tugas }}</td>
                    <td style="text-align:center">{{ $item->nilai_uts }}</td>
                    <td style="text-align:center">{{ $item->nilai_uas }}</td>
                    <td style="text-align:center;font-weight:bold">{{ number_format($item->nilai_akhir, 1) }}</td>
                    <td style="text-align:center">
                        <span class="grade-{{ strtolower($item->grade) }}">{{ $item->grade }}</span>
                    </td>
                    <td>{{ $item->predikat }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:#aaa;padding:20px;">Tidak ada data nilai</td>
                </tr>
            @endforelse
        </tbody>
        @if ($nilaiQuery->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="7" style="text-align:right">Rata-rata Keseluruhan</td>
                    <td style="text-align:center">
                        {{ number_format($nilaiQuery->avg(fn($n) => $n->nilai_akhir), 1) }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem informasi {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
    </div>

</body>
</html>
