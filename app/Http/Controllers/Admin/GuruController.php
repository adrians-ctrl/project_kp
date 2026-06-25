<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruRequest;
use App\Models\GuruStaf;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(): View
    {
        $guru = GuruStaf::with(['kelas', 'user'])
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        return view('admin.guru.index', compact('guru'));
    }

    public function create(): View
    {
        // Hanya user role guru yang belum punya data guru
        $users = User::where('role', 'guru')
            ->whereDoesntHave('guruStaf')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.guru.create', compact('users'));
    }

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

    public function edit(GuruStaf $guru): View
    {
        // User yang tersedia: belum punya guru, atau user yang sedang terhubung
        $users = User::where('role', 'guru')
            ->where(function ($q) use ($guru) {
                $q->whereDoesntHave('guruStaf')
                  ->orWhere('id', $guru->user_id);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.guru.edit', compact('guru', 'users'));
    }

    public function update(GuruRequest $request, GuruStaf $guru): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $data['foto'] = $request->file('foto')
                ->store('guru', 'public');
        } else {
            // Pertahankan foto lama
            unset($data['foto']);
        }

        $guru->update($data);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru/staf berhasil diperbarui.');
    }

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
