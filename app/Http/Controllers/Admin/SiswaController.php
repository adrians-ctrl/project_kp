<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan halaman utama / dashboard Siswa
     */
    public function index()
    {
        return view('admin.siswa.index');
    }
}