<?php

namespace App\Http\Controllers\Guru;

use App\Exports\RekapAbsensiExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\GuruAbsensiRequest;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            return view('guru.absensi.index', [
                'guru' => null, 'kelasList' => collect(), 'kelasId' => null,
                'tanggal' => today()->toDateString(), 'siswaList' => collect(),
                'absensiHariIni' => collect(), 'sudahDiisi' => false,
            ]);
        }

        // Hanya kelas yang diampu guru ini
        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        $kelasId = $request->input('kelas_id');
        $tanggal = $request->input('tanggal', today()->toDateString());

        // Validasi: kelas yang dipilih harus milik guru ini
        if ($kelasId && ! $kelasList->pluck('id')->contains((int) $kelasId)) {
            $kelasId = null;
        }

        $siswaList      = collect();
        $absensiHariIni = collect();
        $sudahDiisi     = false;

        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'nis', 'nisn']);

            $absensiHariIni = Absensi::with('siswa')
                ->whereIn('siswa_id', $siswaList->pluck('id'))
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');

            $sudahDiisi = $absensiHariIni->count() > 0;
        }

        return view('guru.absensi.index', compact(
            'guru', 'kelasList', 'kelasId', 'tanggal',
            'siswaList', 'absensiHariIni', 'sudahDiisi'
        ));
    }

    public function store(GuruAbsensiRequest $request): RedirectResponse
    {
        $tanggal  = $request->input('tanggal');
        $kelasId  = $request->input('kelas_id');
        $dataList = $request->input('absensi');

        DB::transaction(function () use ($tanggal, $dataList) {
            foreach ($dataList as $data) {
                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $data['siswa_id'],
                        'tanggal'  => $tanggal,
                    ],
                    [
                        'status'     => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('guru.absensi.index', ['kelas_id' => $kelasId, 'tanggal' => $tanggal])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function rekapHarian(Request $request): View
    {
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            abort(403, 'Akun Anda belum terhubung ke data guru.');
        }

        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasId   = $this->resolveKelasId($request->input('kelas_id'), $kelasList);
        $tanggal   = $request->input('tanggal', today()->toDateString());

        $absensi = Absensi::with('siswa')
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->whereDate('tanggal', $tanggal)
            ->orderBy(
                Siswa::select('nama_lengkap')->whereColumn('siswa.id', 'absensi.siswa_id')
            )
            ->get();

        $ringkasan = [
            'total' => $absensi->count(),
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin'  => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
        ];

        return view('guru.absensi.rekap-harian', compact(
            'absensi', 'ringkasan', 'kelasList', 'kelasId', 'tanggal'
        ));
    }

    public function rekapBulanan(Request $request): View
    {
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            abort(403, 'Akun Anda belum terhubung ke data guru.');
        }

        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasId   = $this->resolveKelasId($request->input('kelas_id'), $kelasList);
        $bulan     = (int) $request->input('bulan', now()->month);
        $tahun     = (int) $request->input('tahun', now()->year);

        $siswaList   = Siswa::with('kelas')->where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get();
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

        $bulanList = collect(range(1, 12))->mapWithKeys(
            fn($m) => [$m => Carbon::create(null, $m)->translatedFormat('F')]
        );
        $tahunList = range(now()->year - 2, now()->year + 1);
        $namaBulan = $bulanList[$bulan];

        return view('guru.absensi.rekap-bulanan', compact(
            'rekap', 'kelasList', 'kelasId',
            'bulan', 'tahun', 'namaBulan', 'bulanList', 'tahunList'
        ));
    }

    public function exportPdf(Request $request)
    {
        $guru      = Auth::user()->guruStaf;
        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasId   = $this->resolveKelasId($request->input('kelas_id'), $kelasList);
        $bulan     = (int) $request->input('bulan', now()->month);
        $tahun     = (int) $request->input('tahun', now()->year);

        $siswaList   = Siswa::with('kelas')->where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get();
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

        $kelas     = Kelas::find($kelasId);
        $namaBulan = Carbon::create(null, $bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('admin.rekap.exports.absensi-pdf', compact(
            'rekap', 'kelas', 'namaBulan', 'bulan', 'tahun'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('rekap-absensi-' . $kelas->nama_kelas . '-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $guru      = Auth::user()->guruStaf;
        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasId   = $this->resolveKelasId($request->input('kelas_id'), $kelasList);

        return Excel::download(
            new RekapAbsensiExport(
                $kelasId,
                (int) $request->input('bulan', now()->month),
                (int) $request->input('tahun', now()->year),
            ),
            'rekap-absensi-' . now()->format('Ymd') . '.xlsx'
        );
    }

    // ── AJAX ──────────────────────────────────────────────────────────────

    public function getSiswaByKelas(Kelas $kelas): JsonResponse
    {
        $guru = Auth::user()->guruStaf;

        // Pastikan kelas ini diampu oleh guru yang login
        if (! $guru || $kelas->wali_kelas_id !== $guru->id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $siswa = $kelas->siswa()
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis', 'nisn']);

        return response()->json($siswa);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function resolveKelasId(?string $requested, $kelasList): ?int
    {
        if ($requested && $kelasList->pluck('id')->contains((int) $requested)) {
            return (int) $requested;
        }

        return $kelasList->first()?->id;
    }
}
