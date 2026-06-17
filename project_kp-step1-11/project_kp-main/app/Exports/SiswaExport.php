<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SiswaExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly ?int $kelasId,
        private readonly ?string $keyword,
    ) {}

    public function view(): View
    {
        $siswa = Siswa::with('kelas')
            ->search($this->keyword ?? '')
            ->filterKelas($this->kelasId)
            ->orderBy('kelas_id')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.siswa.exports.excel', compact('siswa'));
    }

    public function title(): string
    {
        return 'Data Siswa';
    }
}
