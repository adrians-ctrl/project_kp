<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapNilaiExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly ?int    $kelasId,
        private readonly ?int    $mapelId,
        private readonly string  $semester,
        private readonly string  $tahunAjaran,
    ) {}

    public function view(): View
    {
        $query = Nilai::with(['siswa.kelas', 'mapel'])
            ->where('semester', $this->semester)
            ->where('tahun_ajaran', $this->tahunAjaran)
            ->when($this->kelasId, fn($q) => $q->whereHas(
                'siswa', fn($s) => $s->where('kelas_id', $this->kelasId)
            ))
            ->when($this->mapelId, fn($q) => $q->where('mapel_id', $this->mapelId))
            ->get()
            ->sortBy(fn($n) => $n->siswa?->nama_lengkap);

        $kelas = $this->kelasId ? Kelas::find($this->kelasId) : null;

        return view('admin.rekap.exports.nilai-excel', compact('query', 'kelas'));
    }

    public function title(): string
    {
        return 'Rekap Nilai';
    }
}
