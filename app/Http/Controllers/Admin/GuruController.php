<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruRequest;
use App\Models\GuruStaf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Daftar semua guru dan staf dengan tampilan grid/tabel.
     */
    public function index(): View
    {
        $guru = GuruStaf::with('kelas')
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        return view('admin.guru.index', compact('guru'));
    }

    /**
     * Form tambah guru/staf baru.
     */
    public function create(): View
    {
        return view('admin.guru.create');
    }

    /**
     * Simpan guru/staf baru ke database.
     */
    public function store(GuruRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('guru', 'public');
        }

        GuruStaf::create($data);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru/staf berhasil ditambahkan.');
    }

    /**
     * Form edit guru/staf.
     */
    public function edit(GuruStaf $guru): View
    {
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Perbarui data guru/staf.
     */
    public function update(GuruRequest $request, GuruStaf $guru): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus foto lama sebelum menyimpan yang baru.
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('guru', 'public');
        } else {
            // Tidak ada file baru — pertahankan foto lama.
            unset($data['foto']);
        }

        $guru->update($data);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru/staf berhasil diperbarui.');
    }

    /**
     * Hapus guru/staf beserta fotonya.
     * Guru yang masih menjadi wali kelas tidak dapat dihapus.
     */
    public function destroy(GuruStaf $guru): RedirectResponse
    {
        if ($guru->kelas()->exists()) {
            return redirect()
                ->route('admin.guru.index')
                ->with('error', 'Guru tidak dapat dihapus karena masih menjadi wali kelas.');
        }

        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru/staf berhasil dihapus.');
    }
}
