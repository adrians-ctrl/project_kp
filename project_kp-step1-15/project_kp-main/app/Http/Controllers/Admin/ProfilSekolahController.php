<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilSekolahRequest;
use App\Http\Requests\Admin\VisiMisiRequest;
use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilSekolahController extends Controller
{
    public function index(): View
    {
        $profil   = ProfilSekolah::first();
        $visiMisi = VisiMisi::first();

        return view('admin.profil-sekolah.index', compact('profil', 'visiMisi'));
    }

    public function update(ProfilSekolahRequest $request, ProfilSekolah $profilSekolah): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($profilSekolah->logo) {
                Storage::disk('public')->delete($profilSekolah->logo);
            }
            $data['logo'] = $request->file('logo')->store('sekolah', 'public');
        } else {
            unset($data['logo']);
        }

        $profilSekolah->update($data);

        return redirect()
            ->route('admin.profil-sekolah.index')
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    public function updateVisiMisi(VisiMisiRequest $request): RedirectResponse
    {
        $visiMisi = VisiMisi::first();

        if ($visiMisi) {
            $visiMisi->update($request->validated());
        } else {
            VisiMisi::create($request->validated());
        }

        return redirect()
            ->route('admin.profil-sekolah.index')
            ->with('success', 'Visi dan misi berhasil diperbarui.');
    }
}
