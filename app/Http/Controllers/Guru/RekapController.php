<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RekapController extends Controller
{
    /**
     * Halaman ringkasan rekap, menautkan ke rekap nilai dan rekap absensi
     * milik guru yang login. Detail rekap ditangani oleh:
     * - Guru\NilaiController::rekap()
     * - Guru\AbsensiController::rekapHarian() / rekapBulanan()
     */
    public function index(): View
    {
        $guru = Auth::user()->guruStaf;

        $kelasList = $guru ? $guru->kelas()->orderBy('nama_kelas')->get(['id', 'nama_kelas']) : collect();

        return view('guru.rekap.index', compact('guru', 'kelasList'));
    }
}
