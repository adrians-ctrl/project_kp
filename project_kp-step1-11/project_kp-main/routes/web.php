<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\NilaiController as AdminNilai;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensi;
use App\Http\Controllers\Admin\RekapController as AdminRekap;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfilSekolahController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensi;
use App\Http\Controllers\Guru\NilaiController as GuruNilai;
use App\Http\Controllers\Guru\RekapController as GuruRekap;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/berita', [LandingController::class, 'berita'])->name('landing.berita');
Route::get('/berita/{slug}', [LandingController::class, 'beritaDetail'])->name('landing.berita.detail');
Route::get('/galeri', [LandingController::class, 'galeri'])->name('landing.galeri');
Route::get('/kontak', [LandingController::class, 'kontak'])->name('landing.kontak');
Route::post('/kontak', [LandingController::class, 'kirimPesan'])->name('landing.kontak.kirim');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Siswa
        Route::resource('siswa', SiswaController::class);
        Route::get('/siswa/{siswa}/rapor', [SiswaController::class, 'rapor'])->name('siswa.rapor');
        Route::get('/siswa-export/pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export.pdf');
        Route::get('/siswa-export/excel', [SiswaController::class, 'exportExcel'])->name('siswa.export.excel');

        // Kelas
        Route::resource('kelas', KelasController::class)->except(['show']);

        // Mata Pelajaran
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except(['show']);

        // Guru & Staf
        Route::resource('guru', GuruController::class)->except(['show']);

        // Nilai
        Route::get('/nilai', [AdminNilai::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [AdminNilai::class, 'store'])->name('nilai.store');
        Route::put('/nilai/{nilai}', [AdminNilai::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{nilai}', [AdminNilai::class, 'destroy'])->name('nilai.destroy');

        // Absensi
        Route::get('/absensi', [AdminAbsensi::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AdminAbsensi::class, 'store'])->name('absensi.store');
        Route::put('/absensi/{absensi}', [AdminAbsensi::class, 'update'])->name('absensi.update');
        Route::get('/absensi/rekap-harian', [AdminAbsensi::class, 'rekapHarian'])->name('absensi.rekap-harian');
        Route::get('/absensi/rekap-bulanan', [AdminAbsensi::class, 'rekapBulanan'])->name('absensi.rekap-bulanan');

        // Rekap Nilai
        Route::get('/rekap/nilai', [AdminRekap::class, 'nilai'])->name('rekap.nilai');
        Route::get('/rekap/nilai/export-pdf', [AdminRekap::class, 'exportNilaiPdf'])->name('rekap.nilai.export.pdf');
        Route::get('/rekap/nilai/export-excel', [AdminRekap::class, 'exportNilaiExcel'])->name('rekap.nilai.export.excel');

        // Rekap Absensi
        Route::get('/rekap/absensi', [AdminRekap::class, 'absensi'])->name('rekap.absensi');
        Route::get('/rekap/absensi/export-pdf', [AdminRekap::class, 'exportAbsensiPdf'])->name('rekap.absensi.export.pdf');
        Route::get('/rekap/absensi/export-excel', [AdminRekap::class, 'exportAbsensiExcel'])->name('rekap.absensi.export.excel');

        // Berita & Pengumuman
        Route::resource('berita', BeritaController::class)->except(['show']);

        // Galeri
        Route::resource('galeri', GaleriController::class)->except(['show', 'edit', 'update']);

        // Profil Sekolah
        Route::get('/profil-sekolah', [ProfilSekolahController::class, 'index'])->name('profil-sekolah.index');
        Route::put('/profil-sekolah/{profilSekolah}', [ProfilSekolahController::class, 'update'])->name('profil-sekolah.update');

        // Manajemen User
        Route::resource('users', UserController::class)->except(['show']);
        Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // AJAX
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/siswa-by-kelas/{kelas}', [SiswaController::class, 'getByKelas'])->name('siswa.by-kelas');
            Route::get('/nilai/{siswa}/{mapel}', [AdminNilai::class, 'getNilai'])->name('nilai.get');
            Route::get('/absensi/{siswa}/{tanggal}', [AdminAbsensi::class, 'getAbsensi'])->name('absensi.get');
        });
    });

/*
|--------------------------------------------------------------------------
| Guru
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

        // Absensi
        Route::get('/absensi', [GuruAbsensi::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [GuruAbsensi::class, 'store'])->name('absensi.store');
        Route::put('/absensi/{absensi}', [GuruAbsensi::class, 'update'])->name('absensi.update');

        // Nilai
        Route::get('/nilai', [GuruNilai::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [GuruNilai::class, 'store'])->name('nilai.store');
        Route::put('/nilai/{nilai}', [GuruNilai::class, 'update'])->name('nilai.update');

        // Rekap
        Route::get('/rekap', [GuruRekap::class, 'index'])->name('rekap.index');
        Route::get('/rekap/export-pdf', [GuruRekap::class, 'exportPdf'])->name('rekap.export.pdf');
        Route::get('/rekap/export-excel', [GuruRekap::class, 'exportExcel'])->name('rekap.export.excel');

        // AJAX
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/siswa-by-kelas/{kelas}', [GuruAbsensi::class, 'getSiswaByKelas'])->name('siswa.by-kelas');
        });
    });

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});