<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
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
            background-color: #1e3a8a; color: #fff;
            padding: 7px 8px; font-size: 8pt; font-weight: bold;
            text-transform: uppercase; letter-spacing: 0.3px;
            border: 1px solid #1e3a8a;
        }
        thead th.center { text-align: center; }
        tbody td { padding: 6px 8px; border: 1px solid #e5e7eb; font-size: 8.5pt; vertical-align: middle; }
        tbody tr:nth-child(even) td { background-color: #f5f7ff; }
        tfoot td { padding: 7px 8px; background: #f0f4ff; font-weight: bold; font-size: 8.5pt; border: 1px solid #ccc; }

        .bar-wrap { background: #e5e7eb; border-radius: 4px; height: 6px; width: 80px; display: inline-block; vertical-align: middle; margin-right: 4px; }
        .bar-fill  { height: 6px; border-radius: 4px; }

        .footer { margin-top: 14px; font-size: 7.5pt; color: #aaa; text-align: center;
                  border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        @php $profil = \App\Models\ProfilSekolah::first(); @endphp
        <h1>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</h1>
        <h2>Rekap Absensi Siswa — {{ $namaBulan }} {{ $tahun }}</h2>
        <p>{{ $kelas ? 'Kelas: ' . $kelas->nama_kelas : 'Semua Kelas' }}</p>
    </div>

    <div class="meta">
        <span>Total: {{ $rekap->count() }} siswa</span>
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px" class="center">No</th>
                <th style="text-align:left">Nama Siswa</th>
                <th style="text-align:left">Kelas</th>
                <th class="center" style="color:#86efac">Hadir</th>
                <th class="center" style="color:#fde68a">Izin</th>
                <th class="center" style="color:#93c5fd">Sakit</th>
                <th class="center" style="color:#fca5a5">Alpha</th>
                <th class="center">% Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $i => $item)
                @php
                    $pct      = $item['persentase'];
                    $barColor = $pct >= 90 ? '#22c55e' : ($pct >= 75 ? '#eab308' : '#ef4444');
                    $pctColor = $pct >= 90 ? '#15803d'  : ($pct >= 75 ? '#a16207'  : '#b91c1c');
                @endphp
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $item['siswa']->nama_lengkap }}</td>
                    <td>{{ $item['siswa']->kelas->nama_kelas ?? '—' }}</td>
                    <td style="text-align:center;font-weight:bold;color:#15803d">{{ $item['hadir'] }}</td>
                    <td style="text-align:center;font-weight:bold;color:#a16207">{{ $item['izin'] }}</td>
                    <td style="text-align:center;font-weight:bold;color:#1d4ed8">{{ $item['sakit'] }}</td>
                    <td style="text-align:center;font-weight:bold;color:#b91c1c">{{ $item['alpha'] }}</td>
                    <td style="text-align:center">
                        <span class="bar-wrap">
                            <span class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};display:block"></span>
                        </span>
                        <span style="color:{{ $pctColor }};font-weight:bold">{{ $pct }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#aaa;padding:20px;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        @if ($rekap->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right">Total</td>
                    <td style="text-align:center;color:#15803d">{{ $rekap->sum('hadir') }}</td>
                    <td style="text-align:center;color:#a16207">{{ $rekap->sum('izin') }}</td>
                    <td style="text-align:center;color:#1d4ed8">{{ $rekap->sum('sakit') }}</td>
                    <td style="text-align:center;color:#b91c1c">{{ $rekap->sum('alpha') }}</td>
                    <td style="text-align:center;color:#1e3a8a">
                        {{ round($rekap->avg('persentase'), 1) }}%
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem informasi {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
    </div>

</body>
</html>
