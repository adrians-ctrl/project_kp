<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasRequest;
use App\Models\GuruStaf;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::with('waliKelas')
            ->withCount('siswa')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(15)
            ->withQueryString();

        $guruList = GuruStaf::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'jabatan']);

        return view('admin.kelas.index', compact('kelas', 'guruList'));
    }

    public function store(KelasRequest $request): RedirectResponse
    {
        Kelas::create($request->validated());

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas): View
    {
        $guruList = GuruStaf::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'jabatan']);

        return view('admin.kelas.edit', compact('kelas', 'guruList'));
    }

    public function update(KelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $kelas->update($request->validated());

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        if ($kelas->siswa()->exists()) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
