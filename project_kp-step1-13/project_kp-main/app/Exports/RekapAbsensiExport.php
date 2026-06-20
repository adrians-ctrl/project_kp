<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapAbsensiExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly ?int $kelasId,
        private readonly int  $bulan,
        private readonly int  $tahun,
    ) {}

    public function view(): View
    {
        $siswaQuery = Siswa::with('kelas')->orderBy('nama_lengkap');
        if ($this->kelasId) {
            $siswaQuery->where('kelas_id', $this->kelasId);
        }
        $siswaList = $siswaQuery->get();

        $absensiList = Absensi::whereIn('siswa_id', $siswaList->pluck('id'))
            ->byBulan($this->bulan, $this->tahun)
            ->get()
            ->groupBy('siswa_id');

        $rekap = $siswaList->map(function ($siswa) use ($absensiList) {
            $abs = $absensiList->get($siswa->id, collect());
            return [
                'siswa' => $siswa,
                'hadir' => $abs->where('status', 'hadir')->count(),
                'izin'  => $abs->where('status', 'izin')->count(),
                'sakit' => $abs->where('status', 'sakit')->count(),
                'alpha' => $abs->where('status', 'alpha')->count(),
            ];
        });

        $namaBulan = Carbon::create(null, $this->bulan)->translatedFormat('F');
        $kelas     = $this->kelasId ? Kelas::find($this->kelasId) : null;

        return view('admin.rekap.exports.absensi-excel', compact('rekap', 'namaBulan', 'kelas'));
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }
}
