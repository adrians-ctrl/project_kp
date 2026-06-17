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

        return view('admin.dashboard', [
            'stats'             => $this->buildStats(),
            'absensiHariIni'    => $this->getAbsensiHariIni($today),
            'ringkasanAbsensi'  => $this->getRingkasanAbsensi($today),
            'beritaTerbaru'     => $this->getBeritaTerbaru(),
            'siswaPerKelas'     => $this->getSiswaPerKelas(),
            'today'             => $today,
        ]);
    }

    private function buildStats(): array
    {
        $siswaBaruBulanIni = Siswa::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        $guruBaruBulanIni = GuruStaf::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        return [
            ['label' => 'Total Siswa',      'value' => number_format(Siswa::count()),          'delta' => $siswaBaruBulanIni > 0 ? "+{$siswaBaruBulanIni} bulan ini" : 'Total keseluruhan', 'trend' => $siswaBaruBulanIni > 0 ? 'up' : 'neutral', 'icon' => 'users'],
            ['label' => 'Guru & Staf',      'value' => number_format(GuruStaf::count()),       'delta' => $guruBaruBulanIni > 0 ? "+{$guruBaruBulanIni} bulan ini" : 'Total keseluruhan',   'trend' => $guruBaruBulanIni > 0 ? 'up' : 'neutral', 'icon' => 'academic'],
            ['label' => 'Kelas Aktif',      'value' => number_format(Kelas::count()),          'delta' => 'Tahun ajaran berjalan', 'trend' => 'neutral', 'icon' => 'school'],
            ['label' => 'Mata Pelajaran',   'value' => number_format(MataPelajaran::count()),  'delta' => 'Total terdaftar',       'trend' => 'neutral', 'icon' => 'book'],
        ];
    }

    private function getAbsensiHariIni(Carbon $today)
    {
        return Absensi::with(['siswa.kelas'])
            ->whereDate('tanggal', $today)
            ->latest()->limit(10)->get();
    }

    private function getRingkasanAbsensi(Carbon $today): array
    {
        $absensi = Absensi::whereDate('tanggal', $today)->get();
        $total   = $absensi->count();
        $hadir   = $absensi->where('status', 'hadir')->count();

        return [
            'total'             => $total,
            'hadir'             => $hadir,
            'izin'              => $absensi->where('status', 'izin')->count(),
            'sakit'             => $absensi->where('status', 'sakit')->count(),
            'alpha'             => $absensi->where('status', 'alpha')->count(),
            'persentaseHadir'   => $total > 0 ? round(($hadir / $total) * 100) : 0,
        ];
    }

    private function getBeritaTerbaru()
    {
        return BeritaPengumuman::published()
            ->latest()->limit(5)
            ->get(['id', 'judul', 'slug', 'kategori', 'created_at']);
    }

    private function getSiswaPerKelas()
    {
        return Kelas::withCount('siswa')
            ->orderBy('tingkat')->orderBy('nama_kelas')->get();
    }
}
