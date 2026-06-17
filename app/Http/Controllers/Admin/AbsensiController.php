<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AbsensiRequest;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasId = $request->input('kelas_id');
        $tanggal = $request->input('tanggal', today()->toDateString());

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        // Data absensi hari ini per kelas yang dipilih
        $absensiHariIni = collect();
        $siswaList      = collect();
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

        // Ringkasan absensi hari ini (seluruh kelas)
        $ringkasan = $this->getRingkasanHarian($tanggal);

        return view('admin.absensi.index', compact(
            'kelasList', 'kelasId', 'tanggal',
            'siswaList', 'absensiHariIni', 'sudahDiisi', 'ringkasan'
        ));
    }

    public function store(AbsensiRequest $request): RedirectResponse
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
            ->route('admin.absensi.index', [
                'kelas_id' => $kelasId,
                'tanggal'  => $tanggal,
            ])
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function update(Request $request, Absensi $absensi): RedirectResponse
    {
        $request->validate([
            'status'     => ['required', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ]);

        $absensi->update($request->only(['status', 'keterangan']));

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function rekapHarian(Request $request): View
    {
        $tanggal   = $request->input('tanggal', today()->toDateString());
        $kelasId   = $request->input('kelas_id');
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        $absensi = Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->when($kelasId, fn($q) => $q->whereHas(
                'siswa', fn($s) => $s->where('kelas_id', $kelasId)
            ))
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

        return view('admin.absensi.rekap-harian', compact(
            'absensi', 'ringkasan', 'kelasList', 'kelasId', 'tanggal'
        ));
    }

    public function rekapBulanan(Request $request): View
    {
        $bulan     = (int) $request->input('bulan', now()->month);
        $tahun     = (int) $request->input('tahun', now()->year);
        $kelasId   = $request->input('kelas_id');
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        // Ambil semua siswa di kelas yang dipilih (atau semua jika tidak dipilih)
        $siswaQuery = Siswa::with('kelas')->orderBy('nama_lengkap');
        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }
        $siswaList = $siswaQuery->get();

        // Ambil semua absensi bulan tersebut
        $absensiList = Absensi::whereIn('siswa_id', $siswaList->pluck('id'))
            ->byBulan($bulan, $tahun)
            ->get()
            ->groupBy('siswa_id');

        // Hitung hari kerja (Senin–Sabtu) pada bulan ini
        $hariKerja = $this->getHariKerja($bulan, $tahun);

        // Susun rekap per siswa
        $rekap = $siswaList->map(function ($siswa) use ($absensiList, $hariKerja) {
            $absensiswa = $absensiList->get($siswa->id, collect());

            $hadir = $absensiswa->where('status', 'hadir')->count();
            $izin  = $absensiswa->where('status', 'izin')->count();
            $sakit = $absensiswa->where('status', 'sakit')->count();
            $alpha = $absensiswa->where('status', 'alpha')->count();

            return [
                'siswa'      => $siswa,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpha'      => $alpha,
                'total_hadir'=> $hadir,
                'persentase' => $hariKerja > 0 ? round(($hadir / $hariKerja) * 100, 1) : 0,
            ];
        });

        $bulanList = collect(range(1, 12))->mapWithKeys(
            fn($m) => [$m => Carbon::create(null, $m)->translatedFormat('F')]
        );

        $tahunList = range(now()->year - 2, now()->year + 1);

        return view('admin.absensi.rekap-bulanan', compact(
            'rekap', 'kelasList', 'kelasId',
            'bulan', 'tahun', 'hariKerja',
            'bulanList', 'tahunList'
        ));
    }

    // ── AJAX ──────────────────────────────────────────────────────────────

    public function getAbsensi(Siswa $siswa, string $tanggal): JsonResponse
    {
        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        return response()->json($absensi);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function getRingkasanHarian(string $tanggal): array
    {
        $absensi = Absensi::whereDate('tanggal', $tanggal)->get();

        return [
            'total' => $absensi->count(),
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin'  => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
        ];
    }

    private function getHariKerja(int $bulan, int $tahun): int
    {
        $start  = Carbon::create($tahun, $bulan, 1);
        $end    = $start->copy()->endOfMonth();
        $count  = 0;

        while ($start->lte($end)) {
            // Senin (1) s.d. Sabtu (6)
            if ($start->dayOfWeek !== Carbon::SUNDAY) {
                $count++;
            }
            $start->addDay();
        }

        return $count;
    }
}
