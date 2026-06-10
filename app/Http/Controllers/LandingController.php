<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function berita()
    {
        //
    }

    public function beritaDetail($slug)
    {
        //
    }

    public function galeri()
    {
        //
    }

    public function kontak()
    {
        //
    }

    public function kirimPesan(Request $request)
    {
        //
    }
}