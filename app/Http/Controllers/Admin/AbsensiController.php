<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AbsensiController extends Controller
{{
    public function index(): View
    {{
        return view('coming-soon', ['title' => 'AbsensiController']);
    }}

    public function store(Request $request): RedirectResponse
    {{
        return back()->with('error', 'Fitur ini sedang dalam pengembangan.');
    }}

    public function update(Request $request, $id): RedirectResponse
    {{
        return back()->with('error', 'Fitur ini sedang dalam pengembangan.');
    }}

    public function destroy($id): RedirectResponse
    {{
        return back()->with('error', 'Fitur ini sedang dalam pengembangan.');
    }}

    public function getSiswaByKelas($kelas): JsonResponse
    {{
        return response()->json([]);
    }}

    public function getNilai($siswa, $mapel): JsonResponse
    {{
        return response()->json([]);
    }}

    public function getAbsensi($siswa, $tanggal): JsonResponse
    {{
        return response()->json([]);
    }}

    public function exportPdf() {{}}
    public function exportExcel() {{}}
    public function exportNilaiPdf() {{}}
    public function exportNilaiExcel() {{}}
    public function exportAbsensiPdf() {{}}
    public function exportAbsensiExcel() {{}}

    public function nilai(): View {{ return view('coming-soon', ['title' => 'Rekap Nilai']); }}
    public function absensi(): View {{ return view('coming-soon', ['title' => 'Rekap Absensi']); }}
    public function rapor($id): View {{ return view('coming-soon', ['title' => 'Rapor']); }}
    public function getByKelas($kelas): JsonResponse {{ return response()->json([]); }}
    public function resetPassword(Request $request, $user): RedirectResponse {{ return back(); }}
}}
