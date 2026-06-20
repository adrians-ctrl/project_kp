<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RekapAbsensiExport;
use App\Exports\RekapNilaiExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    // ── Rekap Nilai ───────────────────────────────────────────────────────

    public function nilai(Request $request): View
    {
        $kelasId     = $request->input('kelas_id');
        $mapelId     = $request->input('mapel_id');
        $semester    = $request->input('semester', '1');
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        $nilaiQuery = Nilai::with(['siswa.kelas', 'mapel'])
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->when($kelasId, fn($q) => $q->whereHas(
                'siswa', fn($s) => $s->where('kelas_id', $kelasId)
            ))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->get()
            ->sortBy(fn($n) => $n->siswa?->nama_lengkap);

        // Statistik ringkasan
        $stats = [
            'total'        => $nilaiQuery->count(),
            'rata_rata'    => $nilaiQuery->count() > 0
                ? round($nilaiQuery->avg(fn($n) => $n->nilai_akhir), 2)
                : 0,
            'nilai_max'    => $nilaiQuery->count() > 0
                ? round($nilaiQuery->max(fn($n) => $n->nilai_akhir), 2)
                : 0,
            'nilai_min'    => $nilaiQuery->count() > 0
                ? round($nilaiQuery->min(fn($n) => $n->nilai_akhir), 2)
                : 0,
            'grade_a'      => $nilaiQuery->filter(fn($n) => $n->grade === 'A')->count(),
            'grade_b'      => $nilaiQuery->filter(fn($n) => $n->grade === 'B')->count(),
            'grade_c'      => $nilaiQuery->filter(fn($n) => $n->grade === 'C')->count(),
            'grade_d_e'    => $nilaiQuery->filter(fn($n) => in_array($n->grade, ['D','E']))->count(),
        ];

        $kelasList       = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id','nama_kelas']);
        $mapelList       = MataPelajaran::orderBy('nama_mapel')->get(['id','nama_mapel']);
        $tahunAjaranList = ['2023/2024','2024/2025','2025/2026','2026/2027'];

        return view('admin.rekap.nilai', compact(
            'nilaiQuery', 'stats',
            'kelasList', 'mapelList', 'tahunAjaranList',
            'kelasId', 'mapelId', 'semester', 'tahunAjaran'
        ));
    }

    public function exportNilaiPdf(Request $request)
    {
        $kelasId     = $request->input('kelas_id');
        $mapelId     = $request->input('mapel_id');
        $semester    = $request->input('semester', '1');
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        $nilaiQuery = Nilai::with(['siswa.kelas', 'mapel'])
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->when($kelasId, fn($q) => $q->whereHas(
                'siswa', fn($s) => $s->where('kelas_id', $kelasId)
            ))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->get()
            ->sortBy(fn($n) => $n->siswa?->nama_lengkap);

        $kelas = $kelasId ? Kelas::find($kelasId) : null;
        $mapel = $mapelId ? MataPelajaran::find($mapelId) : null;

        $pdf = Pdf::loadView('admin.rekap.exports.nilai-pdf', compact(
            'nilaiQuery', 'kelas', 'mapel', 'semester', 'tahunAjaran'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('rekap-nilai-' . now()->format('Ymd') . '.pdf');
    }

    public function exportNilaiExcel(Request $request)
    {
        return Excel::download(
            new RekapNilaiExport(
                $request->input('kelas_id'),
                $request->input('mapel_id'),
                $request->input('semester', '1'),
                $request->input('tahun_ajaran', '2025/2026'),
            ),
            'rekap-nilai-' . now()->format('Ymd') . '.xlsx'
        );
    }

    // ── Rekap Absensi ─────────────────────────────────────────────────────

    public function absensi(Request $request): View
    {
        $kelasId   = $request->input('kelas_id');
        $bulan     = (int) $request->input('bulan', now()->month);
        $tahun     = (int) $request->input('tahun', now()->year);

        $siswaQuery = Siswa::with('kelas')->orderBy('nama_lengkap');
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }
        $siswaList = $siswaQuery->get();

        $absensiList = Absensi::whereIn('siswa_id', $siswaList->pluck('id'))
            ->byBulan($bulan, $tahun)
            ->get()
            ->groupBy('siswa_id');

        $rekap = $siswaList->map(function ($siswa) use ($absensiList) {
            $abs = $absensiList->get($siswa->id, collect());
            $hadir = $abs->where('status', 'hadir')->count();
            $total = $abs->count();
            return [
                'siswa'      => $siswa,
                'hadir'      => $hadir,
                'izin'       => $abs->where('status', 'izin')->count(),
                'sakit'      => $abs->where('status', 'sakit')->count(),
                'alpha'      => $abs->where('status', 'alpha')->count(),
                'total'      => $total,
                'persentase' => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
            ];
        });

        // Statistik
        $stats = [
            'total_siswa'      => $siswaList->count(),
            'total_hadir'      => $rekap->sum('hadir'),
            'total_izin'       => $rekap->sum('izin'),
            'total_sakit'      => $rekap->sum('sakit'),
            'total_alpha'      => $rekap->sum('alpha'),
            'rata_persentase'  => $rekap->count() > 0
                ? round($rekap->avg('persentase'), 1)
                : 0,
        ];

        $kelasList  = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id','nama_kelas']);
        $bulanList  = collect(range(1, 12))->mapWithKeys(
            fn($m) => [$m => Carbon::create(null, $m)->translatedFormat('F')]
        );
        $tahunList  = range(now()->year - 2, now()->year + 1);
        $namaBulan  = $bulanList[$bulan];

        return view('admin.rekap.absensi', compact(
            'rekap', 'stats', 'kelasList',
            'kelasId', 'bulan', 'tahun', 'namaBulan',
            'bulanList', 'tahunList'
        ));
    }

    public function exportAbsensiPdf(Request $request)
    {
        $kelasId   = $request->input('kelas_id');
        $bulan     = (int) $request->input('bulan', now()->month);
        $tahun     = (int) $request->input('tahun', now()->year);

        $siswaQuery = Siswa::with('kelas')->orderBy('nama_lengkap');
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }
        $siswaList   = $siswaQuery->get();
        $absensiList = Absensi::whereIn('siswa_id', $siswaList->pluck('id'))
            ->byBulan($bulan, $tahun)
            ->get()
            ->groupBy('siswa_id');

        $rekap = $siswaList->map(function ($siswa) use ($absensiList) {
            $abs   = $absensiList->get($siswa->id, collect());
            $hadir = $abs->where('status', 'hadir')->count();
            $total = $abs->count();
            return [
                'siswa'      => $siswa,
                'hadir'      => $hadir,
                'izin'       => $abs->where('status', 'izin')->count(),
                'sakit'      => $abs->where('status', 'sakit')->count(),
                'alpha'      => $abs->where('status', 'alpha')->count(),
                'persentase' => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
            ];
        });

        $kelas     = $kelasId ? Kelas::find($kelasId) : null;
        $namaBulan = Carbon::create(null, $bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('admin.rekap.exports.absensi-pdf', compact(
            'rekap', 'kelas', 'namaBulan', 'bulan', 'tahun'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('rekap-absensi-' . now()->format('Ymd') . '.pdf');
    }

    public function exportAbsensiExcel(Request $request)
    {
        return Excel::download(
            new RekapAbsensiExport(
                $request->input('kelas_id'),
                (int) $request->input('bulan', now()->month),
                (int) $request->input('tahun', now()->year),
            ),
            'rekap-absensi-' . now()->format('Ymd') . '.xlsx'
        );
    }
}
