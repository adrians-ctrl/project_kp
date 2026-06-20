<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NilaiRequest;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasId     = $request->input('kelas_id');
        $mapelId     = $request->input('mapel_id');
        $semester    = $request->input('semester', '1');
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        $nilai = Nilai::with(['siswa.kelas', 'mapel'])
            ->when($kelasId, fn($q) => $q->whereHas(
                'siswa', fn($s) => $s->where('kelas_id', $kelasId)
            ))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->orderBy(
                Siswa::select('nama_lengkap')
                    ->whereColumn('siswa.id', 'nilai.siswa_id')
            )
            ->paginate(20)
            ->withQueryString();

        $kelasList   = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $mapelList   = MataPelajaran::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $siswaList   = $kelasId
            ? Siswa::where('kelas_id', $kelasId)
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'nis'])
            : collect();

        $tahunAjaranList = ['2023/2024', '2024/2025', '2025/2026', '2026/2027'];

        return view('admin.nilai.index', compact(
            'nilai', 'kelasList', 'mapelList', 'siswaList',
            'kelasId', 'mapelId', 'semester', 'tahunAjaran', 'tahunAjaranList'
        ));
    }

    public function store(NilaiRequest $request): RedirectResponse
    {
        Nilai::create($request->validated());

        return redirect()
            ->route('admin.nilai.index', request()->only([
                'kelas_id', 'mapel_id', 'semester', 'tahun_ajaran',
            ]))
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function update(NilaiRequest $request, Nilai $nilai): RedirectResponse
    {
        $nilai->update($request->validated());

        return redirect()
            ->route('admin.nilai.index', request()->only([
                'kelas_id', 'mapel_id', 'semester', 'tahun_ajaran',
            ]))
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $nilai->delete();

        return redirect()
            ->route('admin.nilai.index', request()->only([
                'kelas_id', 'mapel_id', 'semester', 'tahun_ajaran',
            ]))
            ->with('success', 'Data nilai berhasil dihapus.');
    }

    // ── AJAX ──────────────────────────────────────────────────────────────

    public function getNilai(Siswa $siswa, MataPelajaran $mapel): JsonResponse
    {
        $nilai = Nilai::where('siswa_id', $siswa->id)
            ->where('mapel_id', $mapel->id)
            ->where('semester', request('semester', '1'))
            ->where('tahun_ajaran', request('tahun_ajaran', '2025/2026'))
            ->first();

        return response()->json($nilai);
    }
}
