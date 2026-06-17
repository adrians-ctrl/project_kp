<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\BeritaPengumuman;
use App\Models\GuruStaf;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = $this->buildStats();
        $absensiHariIni = $this->getAbsensiHariIni($today);
        $ringkasanAbsensi = $this->getRingkasanAbsensi($today);
        $beritaTerbaru = $this->getBeritaTerbaru();
        $siswaPerKelas = $this->getSiswaPerKelas();

        return view('admin.dashboard', compact(
            'stats',
            'absensiHariIni',
            'ringkasanAbsensi',
            'beritaTerbaru',
            'siswaPerKelas',
            'today',
        ));
    }

    // -------------------------------------------------------------------------

    private function buildStats(): array
    {
        $totalSiswa     = Siswa::count();
        $totalGuru      = GuruStaf::count();
        $totalKelas     = Kelas::count();
        $totalMapel     = MataPelajaran::count();

        $siswaBaruBulanIni = Siswa::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $guruBaruBulanIni = GuruStaf::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            [
                'label' => 'Total Siswa',
                'value' => number_format($totalSiswa),
                'delta' => $siswaBaruBulanIni > 0
                    ? "+{$siswaBaruBulanIni} bulan ini"
                    : 'Belum ada data',
                'trend' => $siswaBaruBulanIni > 0 ? 'up' : 'neutral',
                'icon'  => 'users',
            ],
            [
                'label' => 'Guru & Staf',
                'value' => number_format($totalGuru),
                'delta' => $guruBaruBulanIni > 0
                    ? "+{$guruBaruBulanIni} bulan ini"
                    : 'Total keseluruhan',
                'trend' => $guruBaruBulanIni > 0 ? 'up' : 'neutral',
                'icon'  => 'academic',
            ],
            [
                'label' => 'Kelas Aktif',
                'value' => number_format($totalKelas),
                'delta' => 'Tahun ajaran berjalan',
                'trend' => 'neutral',
                'icon'  => 'school',
            ],
            [
                'label' => 'Mata Pelajaran',
                'value' => number_format($totalMapel),
                'delta' => 'Total terdaftar',
                'trend' => 'neutral',
                'icon'  => 'book',
            ],
        ];
    }

    private function getAbsensiHariIni(Carbon $today): \Illuminate\Database\Eloquent\Collection
    {
        return Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $today)
            ->latest()
            ->limit(10)
            ->get();
    }

    private function getRingkasanAbsensi(Carbon $today): array
    {
        $absensi = Absensi::whereDate('tanggal', $today)->get();

        $total  = $absensi->count();
        $hadir  = $absensi->where('status', 'hadir')->count();
        $izin   = $absensi->where('status', 'izin')->count();
        $sakit  = $absensi->where('status', 'sakit')->count();
        $alpha  = $absensi->where('status', 'alpha')->count();

        $persentaseHadir = $total > 0
            ? round(($hadir / $total) * 100)
            : 0;

        return compact('total', 'hadir', 'izin', 'sakit', 'alpha', 'persentaseHadir');
    }

    private function getBeritaTerbaru(): \Illuminate\Database\Eloquent\Collection
    {
        return BeritaPengumuman::published()
            ->latest()
            ->limit(5)
            ->get(['id', 'judul', 'slug', 'kategori', 'created_at']);
    }

    private function getSiswaPerKelas(): \Illuminate\Database\Eloquent\Collection
    {
        return Kelas::withCount('siswa')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
    }
}
