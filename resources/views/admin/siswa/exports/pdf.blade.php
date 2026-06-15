<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; color: #1a1a1a; }

        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { font-size: 13pt; font-weight: bold; color: #1e3a8a; }
        .header p { font-size: 9pt; color: #555; margin-top: 2px; }

        .meta { display: flex; justify-content: space-between; font-size: 8pt; color: #777; margin-bottom: 12px; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background-color: #1e3a8a;
            color: #fff;
            padding: 7px 8px;
            text-align: left;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        thead th.center { text-align: center; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 8.5pt; vertical-align: middle; }
        tbody tr:nth-child(even) td { background-color: #f5f7ff; }
        tfoot td { padding: 7px 8px; background: #f0f4ff; font-weight: bold; font-size: 8.5pt; border-top: 2px solid #1e3a8a; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 7.5pt; font-weight: bold; }
        .badge-l { background: #dbeafe; color: #1d4ed8; }
        .badge-p { background: #fce7f3; color: #be185d; }

        .footer { margin-top: 16px; font-size: 7.5pt; color: #aaa; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        @php $profil = \App\Models\ProfilSekolah::first(); @endphp
        <h1>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</h1>
        <p>Daftar Data Siswa &bull; {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="meta">
        <span>Total: {{ $siswa->count() }} siswa</span>
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px" class="center">No</th>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>NIS</th>
                <th class="center">JK</th>
                <th>Kelas</th>
                <th>Tempat Lahir</th>
                <th>Tgl Lahir</th>
                <th>Orang Tua</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswa as $i => $item)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td style="font-family:monospace">{{ $item->nisn }}</td>
                    <td style="font-family:monospace">{{ $item->nis }}</td>
                    <td style="text-align:center">
                        <span class="badge {{ $item->jenis_kelamin === 'L' ? 'badge-l' : 'badge-p' }}">
                            {{ $item->jenis_kelamin }}
                        </span>
                    </td>
                    <td>{{ $item->kelas->nama_kelas ?? '—' }}</td>
                    <td>{{ $item->tempat_lahir ?? '—' }}</td>
                    <td>{{ $item->tanggal_lahir?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $item->nama_orang_tua ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:#aaa;padding:20px;">
                        Tidak ada data siswa
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td colspan="7">{{ $siswa->count() }} siswa</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem informasi
        {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
    </div>

</body>
</html>
