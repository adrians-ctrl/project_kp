<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontakRequest;
use App\Models\BeritaPengumuman;
use App\Models\Galeri;
use App\Models\GuruStaf;
use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', [
            'profil'   => ProfilSekolah::first(),
            'visiMisi' => VisiMisi::first(),
            'guru'     => GuruStaf::orderByRaw("jabatan = 'Kepala Sekolah' DESC")
                ->orderBy('nama_lengkap')
                ->limit(8)
                ->get(),
            'berita' => BeritaPengumuman::published()->latest()->limit(3)->get(),
            'galeri' => Galeri::latest()->limit(6)->get(),
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
        $berita = BeritaPengumuman::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $beritaLain = BeritaPengumuman::published()
            ->where('id', '!=', $berita->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('landing.berita-detail', [
            'profil'     => ProfilSekolah::first(),
            'berita'     => $berita,
            'beritaLain' => $beritaLain,
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

    public function kirimPesan(KontakRequest $request): RedirectResponse
    {
        // Catatan: pengiriman email/penyimpanan pesan dapat ditambahkan di sini.
        // Untuk saat ini, pesan divalidasi dan pengguna diberi konfirmasi.

        return back()->with('success', 'Pesan Anda telah terkirim. Terima kasih telah menghubungi kami.');
    }
}
