<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\GuruNilaiRequest;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            return view('guru.nilai.index', [
                'guru' => null, 'kelasList' => collect(), 'kelasId' => null,
                'mapelList' => collect(), 'siswaList' => collect(),
                'nilai' => collect(), 'semester' => '1',
                'tahunAjaran' => '2025/2026', 'tahunAjaranList' => [],
            ]);
        }

        // Hanya kelas yang diampu guru ini
        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasIds  = $kelasList->pluck('id');

        $kelasId = $request->input('kelas_id');
        if ($kelasId && ! $kelasIds->contains((int) $kelasId)) {
            $kelasId = null;
        }
        $kelasId = $kelasId ?? $kelasList->first()?->id;

        $mapelId     = $request->input('mapel_id');
        $semester    = $request->input('semester', '1');
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        $mapelList       = MataPelajaran::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $tahunAjaranList = ['2023/2024', '2024/2025', '2025/2026', '2026/2027'];

        $siswaList = $kelasId
            ? Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nis'])
            : collect();

        $nilai = Nilai::with(['siswa', 'mapel'])
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn($q) => $q->where('mapel_id', $mapelId))
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get()
            ->sortBy(fn($n) => $n->siswa?->nama_lengkap);

        return view('guru.nilai.index', compact(
            'guru', 'kelasList', 'kelasId', 'mapelList', 'mapelId',
            'siswaList', 'nilai', 'semester', 'tahunAjaran', 'tahunAjaranList'
        ));
    }

    public function store(GuruNilaiRequest $request): RedirectResponse
    {
        Nilai::create($request->validated());

        return redirect()
            ->route('guru.nilai.index', request()->only(['kelas_id', 'mapel_id', 'semester', 'tahun_ajaran']))
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function update(GuruNilaiRequest $request, Nilai $nilai): RedirectResponse
    {
        $nilai->update($request->validated());

        return redirect()
            ->route('guru.nilai.index', request()->only(['kelas_id', 'mapel_id', 'semester', 'tahun_ajaran']))
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $guru = Auth::user()->guruStaf;

        // Pastikan siswa dari nilai ini berada di kelas yang diampu guru
        $kelasIds = $guru?->kelas()->pluck('id') ?? collect();

        if (! $guru || ! $kelasIds->contains($nilai->siswa->kelas_id)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus nilai ini.');
        }

        $nilai->delete();

        return redirect()
            ->route('guru.nilai.index', request()->only(['kelas_id', 'mapel_id', 'semester', 'tahun_ajaran']))
            ->with('success', 'Data nilai berhasil dihapus.');
    }

    public function rekap(Request $request): View
    {
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            abort(403, 'Akun Anda belum terhubung ke data guru.');
        }

        $kelasList = $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']);
        $kelasIds  = $kelasList->pluck('id');

        $kelasId = $request->input('kelas_id');
        if ($kelasId && ! $kelasIds->contains((int) $kelasId)) {
            $kelasId = null;
        }
        $kelasId = $kelasId ?? $kelasList->first()?->id;

        $semester    = $request->input('semester', '1');
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        $nilaiByMapel = Nilai::with(['siswa', 'mapel'])
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get()
            ->groupBy(fn($n) => $n->mapel?->nama_mapel ?? 'Tanpa Mapel')
            ->map(function ($items) {
                return [
                    'items'     => $items->sortBy(fn($n) => $n->siswa?->nama_lengkap),
                    'rata_rata' => round($items->avg(fn($n) => $n->nilai_akhir), 1),
                    'tertinggi' => round($items->max(fn($n) => $n->nilai_akhir), 1),
                    'terendah'  => round($items->min(fn($n) => $n->nilai_akhir), 1),
                ];
            });

        $tahunAjaranList = ['2023/2024', '2024/2025', '2025/2026', '2026/2027'];

        return view('guru.nilai.rekap', compact(
            'kelasList', 'kelasId', 'semester', 'tahunAjaran',
            'tahunAjaranList', 'nilaiByMapel'
        ));
    }

    // ── AJAX ──────────────────────────────────────────────────────────────

    public function getNilai(Siswa $siswa, MataPelajaran $mapel): JsonResponse
    {
        $guru     = Auth::user()->guruStaf;
        $kelasIds = $guru?->kelas()->pluck('id') ?? collect();

        if (! $guru || ! $kelasIds->contains($siswa->kelas_id)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $nilai = Nilai::where('siswa_id', $siswa->id)
            ->where('mapel_id', $mapel->id)
            ->where('semester', request('semester', '1'))
            ->where('tahun_ajaran', request('tahun_ajaran', '2025/2026'))
            ->first();

        return response()->json($nilai);
    }
}
