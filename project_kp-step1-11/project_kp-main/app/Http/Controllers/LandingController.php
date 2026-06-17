<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use App\Models\GuruStaf;
use App\Models\BeritaPengumuman;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', [
            'profil'  => ProfilSekolah::first(),
            'visiMisi'=> VisiMisi::first(),
            'guru'    => GuruStaf::whereNotNull('foto')->orWhereIn('jabatan', ['Kepala Sekolah','Guru Kelas','Guru PAI','Guru PJOK'])->orderBy('nama_lengkap')->limit(8)->get(),
            'berita'  => BeritaPengumuman::published()->latest()->limit(3)->get(),
            'galeri'  => Galeri::latest()->limit(6)->get(),
        ]);
    }

    public function berita(): View
    {
        return view('landing.berita', [
            'profil' => ProfilSekolah::first(),
            'berita' => BeritaPengumuman::published()->latest()->paginate(9),
        ]);
    }

    public function beritaDetail(string $slug): View
    {
        $berita = BeritaPengumuman::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('landing.berita-detail', [
            'profil' => ProfilSekolah::first(),
            'berita' => $berita,
        ]);
    }

    public function galeri(): View
    {
        return view('landing.galeri', [
            'profil' => ProfilSekolah::first(),
            'galeri' => Galeri::latest()->paginate(12),
        ]);
    }

    public function kontak(): View
    {
        return view('landing.kontak', [
            'profil' => ProfilSekolah::first(),
        ]);
    }

    public function kirimPesan(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'pesan' => ['required', 'string', 'max:1000'],
        ]);

        return back()->with('success', 'Pesan Anda telah terkirim. Terima kasih.');
    }
}
