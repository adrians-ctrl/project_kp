<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BeritaRequest;
use App\Models\BeritaPengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $keyword  = $request->input('search');
        $kategori = $request->input('kategori');
        $status   = $request->input('status');

        $berita = BeritaPengumuman::with('user')
            ->when($keyword, fn($q) => $q->where('judul', 'like', "%{$keyword}%"))
            ->when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_published', (bool) $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.berita.index', compact('berita', 'keyword', 'kategori', 'status'));
    }

    public function create(): View
    {
        return view('admin.berita.create');
    }

    public function store(BeritaRequest $request): RedirectResponse
    {
        $data                 = $request->validated();
        $data['slug']         = $this->generateUniqueSlug($data['judul']);
        $data['user_id']      = Auth::id();
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        BeritaPengumuman::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(BeritaPengumuman $beritum): View
    {
        return view('admin.berita.edit', ['berita' => $beritum]);
    }

    public function update(BeritaRequest $request, BeritaPengumuman $beritum): RedirectResponse
    {
        $data                 = $request->validated();
        $data['is_published'] = $request->boolean('is_published');

        if ($data['judul'] !== $beritum->judul) {
            $data['slug'] = $this->generateUniqueSlug($data['judul'], $beritum->id);
        }

        if ($request->hasFile('gambar')) {
            if ($beritum->gambar) {
                Storage::disk('public')->delete($beritum->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        } else {
            unset($data['gambar']);
        }

        $beritum->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(BeritaPengumuman $beritum): RedirectResponse
    {
        if ($beritum->gambar) {
            Storage::disk('public')->delete($beritum->gambar);
        }

        $beritum->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function togglePublish(BeritaPengumuman $beritum): RedirectResponse
    {
        $beritum->update(['is_published' => !$beritum->is_published]);
        $status = $beritum->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Berita berhasil {$status}.");
    }

    private function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($judul);
        $original = $slug;
        $counter  = 1;

        while (
            BeritaPengumuman::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}