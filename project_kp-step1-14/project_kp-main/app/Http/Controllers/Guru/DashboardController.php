<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Nilai;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $guru = Auth::user()->guruStaf;

        // Guru tanpa data terhubung — tampilkan halaman kosong yang aman
        if (! $guru) {
            return view('guru.dashboard', [
                'guru'             => null,
                'kelasList'        => collect(),
                'stats'            => $this->emptyStats(),
                'absensiHariIni'   => collect(),
                'ringkasanAbsensi' => $this->emptyRingkasan(),
                'nilaiPerKelas'    => collect(),
                'today'            => Carbon::today(),
            ]);
        }

        $kelasList = $guru->kelas;
        $kelasIds  = $kelasList->pluck('id');
        $today     = Carbon::today();

        $stats            = $this->buildStats($guru, $kelasList, $kelasIds);
        $absensiHariIni    = $this->getAbsensiHariIni($kelasIds, $today);
        $ringkasanAbsensi  = $this->getRingkasanAbsensi($kelasIds, $today);
        $nilaiPerKelas     = $this->getNilaiPerKelas($kelasList);

        return view('guru.dashboard', compact(
            'guru', 'kelasList', 'stats',
            'absensiHariIni', 'ringkasanAbsensi', 'nilaiPerKelas', 'today'
        ));
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function buildStats($guru, $kelasList, $kelasIds): array
    {
        $totalSiswa = $kelasList->sum(fn($k) => $k->siswa()->count());

        $totalNilaiInput = Nilai::whereHas(
            'siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds)
        )->count();

        return [
            [
                'label' => 'Kelas Diampu',
                'value' => (string) $kelasList->count(),
                'delta' => $kelasList->count() > 0 ? $kelasList->pluck('nama_kelas')->join(', ') : 'Belum ada kelas',
                'trend' => 'neutral',
                'icon'  => 'school',
            ],
            [
                'label' => 'Total Siswa',
                'value' => number_format($totalSiswa),
                'delta' => 'Dari seluruh kelas diampu',
                'trend' => 'neutral',
                'icon'  => 'users',
            ],
            [
                'label' => 'Mata Pelajaran',
                'value' => $guru->mapel ?? '—',
                'delta' => $guru->jabatan,
                'trend' => 'neutral',
                'icon'  => 'book',
            ],
            [
                'label' => 'Nilai Terinput',
                'value' => number_format($totalNilaiInput),
                'delta' => 'Total data nilai',
                'trend' => 'neutral',
                'icon'  => 'academic',
            ],
        ];
    }

    private function getAbsensiHariIni($kelasIds, Carbon $today)
    {
        return Absensi::with(['siswa.kelas'])
            ->whereHas('siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->whereDate('tanggal', $today)
            ->latest()
            ->limit(10)
            ->get();
    }

    private function getRingkasanAbsensi($kelasIds, Carbon $today): array
    {
        $absensi = Absensi::whereHas(
            'siswa', fn($q) => $q->whereIn('kelas_id', $kelasIds)
        )->whereDate('tanggal', $today)->get();

        $total = $absensi->count();
        $hadir = $absensi->where('status', 'hadir')->count();

        return [
            'total'           => $total,
            'hadir'           => $hadir,
            'izin'            => $absensi->where('status', 'izin')->count(),
            'sakit'           => $absensi->where('status', 'sakit')->count(),
            'alpha'           => $absensi->where('status', 'alpha')->count(),
            'persentaseHadir' => $total > 0 ? round(($hadir / $total) * 100) : 0,
        ];
    }

    private function getNilaiPerKelas($kelasList)
    {
        return $kelasList->map(function ($kelas) {
            $rataRata = Nilai::whereHas(
                'siswa', fn($q) => $q->where('kelas_id', $kelas->id)
            )->get();

            $avg = $rataRata->count() > 0
                ? round($rataRata->avg(fn($n) => $n->nilai_akhir), 1)
                : null;

            return [
                'kelas'      => $kelas,
                'rata_rata'  => $avg,
                'jumlah_data'=> $rataRata->count(),
            ];
        });
    }

    private function emptyStats(): array
    {
        return [
            ['label' => 'Kelas Diampu',   'value' => '0', 'delta' => 'Belum terhubung', 'trend' => 'neutral', 'icon' => 'school'],
            ['label' => 'Total Siswa',    'value' => '0', 'delta' => '—', 'trend' => 'neutral', 'icon' => 'users'],
            ['label' => 'Mata Pelajaran', 'value' => '—', 'delta' => '—', 'trend' => 'neutral', 'icon' => 'book'],
            ['label' => 'Nilai Terinput', 'value' => '0', 'delta' => '—', 'trend' => 'neutral', 'icon' => 'academic'],
        ];
    }

    private function emptyRingkasan(): array
    {
        return ['total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0, 'persentaseHadir' => 0];
    }
}
