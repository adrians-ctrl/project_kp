<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SiswaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaRequest;
use App\Models\Kelas;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $keyword  = $request->input('search');
        $kelasId  = $request->input('kelas_id');
        $jk       = $request->input('jenis_kelamin');

        $siswa = Siswa::with('kelas')
            ->search($keyword ?? '')
            ->filterKelas($kelasId)
            ->filterJenisKelamin($jk)
            ->orderBy('kelas_id')
            ->orderBy('nama_lengkap')
            ->paginate(20)
            ->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        return view('admin.siswa.index', compact('siswa', 'kelasList', 'keyword', 'kelasId', 'jk'));
    }

    public function create(): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        return view('admin.siswa.create', compact('kelasList'));
    }

    public function store(SiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        Siswa::create($data);

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load([
            'kelas.waliKelas',
            'nilai.mapel',
            'absensi' => fn($q) => $q->orderBy('tanggal', 'desc')->limit(30),
        ]);

        $ringkasanAbsensi = $this->getRingkasanAbsensi($siswa);
        $nilaiPerMapel    = $this->getNilaiPerMapel($siswa);

        return view('admin.siswa.show', compact('siswa', 'ringkasanAbsensi', 'nilaiPerMapel'));
    }

    public function edit(Siswa $siswa): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(SiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        } else {
            unset($data['foto']);
        }

        $siswa->update($data);

        return redirect()
            ->route('admin.siswa.show', $siswa)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function rapor(Siswa $siswa): View
    {
        $siswa->load(['kelas', 'nilai.mapel']);

        return view('admin.siswa.rapor', compact('siswa'));
    }

    public function exportPdf(Request $request)
    {
        $keyword = $request->input('search');
        $kelasId = $request->input('kelas_id');

        $siswa = Siswa::with('kelas')
            ->search($keyword ?? '')
            ->filterKelas($kelasId)
            ->orderBy('kelas_id')
            ->orderBy('nama_lengkap')
            ->get();

        $pdf = Pdf::loadView('admin.siswa.exports.pdf', compact('siswa'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-siswa-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $keyword = $request->input('search');
        $kelasId = $request->input('kelas_id');

        return Excel::download(
            new SiswaExport($kelasId, $keyword),
            'data-siswa-' . now()->format('Ymd') . '.xlsx'
        );
    }

    public function getByKelas(Kelas $kelas): JsonResponse
    {
        $siswa = $kelas->siswa()
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis', 'nisn']);

        return response()->json($siswa);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function getRingkasanAbsensi(Siswa $siswa): array
    {
        $absensi = $siswa->absensi;

        return [
            'total' => $absensi->count(),
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin'  => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
        ];
    }

    private function getNilaiPerMapel(Siswa $siswa): \Illuminate\Support\Collection
    {
        return $siswa->nilai
            ->groupBy('mapel_id')
            ->map(function ($items) {
                $latest = $items->sortByDesc('created_at')->first();
                return [
                    'mapel'       => $latest->mapel->nama_mapel ?? '-',
                    'nilai_akhir' => $latest->nilai_akhir,
                    'grade'       => $latest->grade,
                    'predikat'    => $latest->predikat,
                ];
            })
            ->values();
    }
}
