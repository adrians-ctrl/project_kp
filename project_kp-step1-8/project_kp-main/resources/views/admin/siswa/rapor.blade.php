<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor — {{ $siswa->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #1a1a1a; background: #fff; }

        .page { max-width: 720px; margin: 0 auto; padding: 40px; }

        /* Header */
        .header { display: flex; align-items: center; gap: 20px; border-bottom: 3px double #1a1a1a; padding-bottom: 16px; margin-bottom: 20px; }
        .header-logo { width: 72px; height: 72px; border-radius: 50%; background: #1e3a8a; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24pt; font-weight: bold; flex-shrink: 0; }
        .header-text h1 { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-text h2 { font-size: 13pt; font-weight: normal; margin-top: 2px; }
        .header-text p { font-size: 10pt; color: #555; margin-top: 2px; }

        /* Title */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .report-title p { font-size: 10pt; color: #555; margin-top: 4px; }

        /* Biodata */
        .biodata { border: 1px solid #ccc; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px; }
        .biodata table { width: 100%; border-collapse: collapse; }
        .biodata td { padding: 4px 6px; font-size: 11pt; vertical-align: top; }
        .biodata td:first-child { width: 140px; color: #555; }
        .biodata td:nth-child(2) { width: 10px; }

        /* Nilai table */
        .nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .nilai-table thead th { background: #1e3a8a; color: #fff; padding: 8px 12px; text-align: center; font-size: 11pt; border: 1px solid #1e3a8a; }
        .nilai-table thead th:first-child { text-align: left; }
        .nilai-table tbody td { padding: 7px 12px; border: 1px solid #ddd; font-size: 11pt; }
        .nilai-table tbody tr:nth-child(even) td { background: #f5f7ff; }
        .nilai-table tfoot td { padding: 8px 12px; border: 1px solid #ccc; font-weight: bold; font-size: 11pt; background: #f0f4ff; }

        /* Grade badges */
        .grade-a { color: #15803d; font-weight: bold; }
        .grade-b { color: #1d4ed8; font-weight: bold; }
        .grade-c { color: #b45309; font-weight: bold; }
        .grade-d, .grade-e { color: #dc2626; font-weight: bold; }

        /* Absensi */
        .absensi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
        .absensi-box { border: 1px solid #ddd; border-radius: 6px; padding: 12px; text-align: center; }
        .absensi-box .number { font-size: 22pt; font-weight: bold; margin-bottom: 4px; }
        .absensi-box .label { font-size: 9pt; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .hadir .number { color: #15803d; }
        .izin .number  { color: #b45309; }
        .sakit .number { color: #1d4ed8; }
        .alpha .number { color: #dc2626; }

        /* Signature */
        .signature { display: flex; justify-content: flex-end; margin-top: 32px; }
        .signature-box { text-align: center; }
        .signature-box p { font-size: 11pt; }
        .signature-box .name { margin-top: 56px; font-weight: bold; border-top: 1px solid #333; padding-top: 4px; }

        /* Footer */
        .footer { border-top: 1px solid #ccc; margin-top: 24px; padding-top: 12px; text-align: center; font-size: 9pt; color: #888; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
            .page { padding: 20px; }
        }
    </style>
</head>
<body>

    {{-- Print button --}}
    <div class="no-print" style="background:#1e3a8a;padding:12px 40px;display:flex;justify-content:flex-end;gap:8px;">
        <button onclick="window.print()"
                style="background:#fff;color:#1e3a8a;border:none;padding:8px 20px;border-radius:6px;font-size:11pt;font-weight:bold;cursor:pointer;">
            Cetak Rapor
        </button>
        <button onclick="window.close()"
                style="background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.4);padding:8px 20px;border-radius:6px;font-size:11pt;cursor:pointer;">
            Tutup
        </button>
    </div>

    <div class="page">

        {{-- Header sekolah --}}
        <div class="header">
            <div class="header-logo">S</div>
            <div class="header-text">
                @php $profil = \App\Models\ProfilSekolah::first(); @endphp
                <h1>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</h1>
                <h2>Laporan Hasil Belajar Siswa</h2>
                <p>{{ $profil?->alamat_lengkap ?? 'Jl. Babakan Raya No. 10, Bogor' }}</p>
            </div>
        </div>

        {{-- Judul --}}
        <div class="report-title">
            <h3>Laporan Hasil Belajar</h3>
            <p>Semester 1 &bull; Tahun Ajaran 2025/2026</p>
        </div>

        {{-- Biodata siswa --}}
        <div class="biodata">
            <table>
                <tr>
                    <td>Nama Siswa</td>
                    <td>:</td>
                    <td><strong>{{ $siswa->nama_lengkap }}</strong></td>
                    <td style="width:40px"></td>
                    <td style="width:120px;color:#555;">NISN</td>
                    <td style="width:10px">:</td>
                    <td>{{ $siswa->nisn }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>{{ $siswa->kelas->nama_kelas ?? '—' }}</td>
                    <td></td>
                    <td style="color:#555;">NIS</td>
                    <td>:</td>
                    <td>{{ $siswa->nis }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $siswa->jenis_kelamin_label }}</td>
                    <td></td>
                    <td style="color:#555;">Wali Kelas</td>
                    <td>:</td>
                    <td>{{ $siswa->kelas?->waliKelas?->nama_lengkap ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tgl Lahir</td>
                    <td>:</td>
                    <td>{{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' . $siswa->tanggal_lahir_format : '—' }}</td>
                    <td></td>
                    <td style="color:#555;">Nama Orang Tua</td>
                    <td>:</td>
                    <td>{{ $siswa->nama_orang_tua ?? '—' }}</td>
                </tr>
            </table>
        </div>

        {{-- Tabel Nilai --}}
        <table class="nilai-table">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th style="text-align:left">Mata Pelajaran</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                    <th>Predikat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa->nilai->where('semester', '1') as $i => $nilai)
                    <tr>
                        <td style="text-align:center">{{ $i + 1 }}</td>
                        <td>{{ $nilai->mapel->nama_mapel ?? '—' }}</td>
                        <td style="text-align:center">{{ $nilai->nilai_tugas }}</td>
                        <td style="text-align:center">{{ $nilai->nilai_uts }}</td>
                        <td style="text-align:center">{{ $nilai->nilai_uas }}</td>
                        <td style="text-align:center;font-weight:bold">{{ number_format($nilai->nilai_akhir, 1) }}</td>
                        <td style="text-align:center" class="grade-{{ strtolower($nilai->grade) }}">{{ $nilai->grade }}</td>
                        <td>{{ $nilai->predikat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:#888;padding:20px;">
                            Belum ada data nilai
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($siswa->nilai->where('semester', '1')->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align:right">Rata-rata Keseluruhan</td>
                        <td style="text-align:center">
                            {{ number_format($siswa->nilai->where('semester', '1')->avg('nilai_akhir') ?? 0, 1) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- Absensi --}}
        <p style="font-weight:bold;margin-bottom:10px;font-size:11pt;">Rekap Kehadiran</p>
        <div class="absensi-grid">
            @php
                $totalAbsen = $siswa->absensi->count();
                $hadir = $siswa->absensi->where('status','hadir')->count();
                $izin  = $siswa->absensi->where('status','izin')->count();
                $sakit = $siswa->absensi->where('status','sakit')->count();
                $alpha = $siswa->absensi->where('status','alpha')->count();
            @endphp
            <div class="absensi-box hadir">
                <div class="number">{{ $hadir }}</div>
                <div class="label">Hadir</div>
            </div>
            <div class="absensi-box izin">
                <div class="number">{{ $izin }}</div>
                <div class="label">Izin</div>
            </div>
            <div class="absensi-box sakit">
                <div class="number">{{ $sakit }}</div>
                <div class="label">Sakit</div>
            </div>
            <div class="absensi-box alpha">
                <div class="number">{{ $alpha }}</div>
                <div class="label">Alpha</div>
            </div>
        </div>

        {{-- Tanda tangan --}}
        <div class="signature">
            <div class="signature-box">
                <p>Bogor, {{ now()->translatedFormat('d F Y') }}</p>
                <p>Wali Kelas</p>
                <p class="name">{{ $siswa->kelas?->waliKelas?->nama_lengkap ?? '................................' }}</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            Dokumen ini dicetak oleh sistem informasi {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
            pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
        </div>
    </div>

</body>
</html>
