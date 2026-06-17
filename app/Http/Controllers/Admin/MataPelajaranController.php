<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MataPelajaranRequest;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function index(): View
    {
        $mapel = MataPelajaran::withCount('nilai')
            ->orderBy('kode_mapel')
            ->paginate(15)->withQueryString();

        return view('admin.mata-pelajaran.index', compact('mapel'));
    }

    public function store(MataPelajaranRequest $request): RedirectResponse
    {
        MataPelajaran::create($request->validated());

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mata_pelajaran): View
    {
        return view('admin.mata-pelajaran.edit', ['mapel' => $mata_pelajaran]);
    }

    public function update(MataPelajaranRequest $request, MataPelajaran $mata_pelajaran): RedirectResponse
    {
        $mata_pelajaran->update($request->validated());

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mata_pelajaran): RedirectResponse
    {
        if ($mata_pelajaran->nilai()->exists()) {
            return redirect()->route('admin.mata-pelajaran.index')
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena sudah memiliki data nilai.');
        }

        $mata_pelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
