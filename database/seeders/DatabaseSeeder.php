<?php

namespace Database\Seeders;

use App\Models\ProfilSekolah;
use App\Models\User;
use App\Models\VisiMisi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'guru@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'guru',
        ]);

        ProfilSekolah::create([
            'nama_sekolah' => 'SD Negeri Babakan 02',
            'npsn'         => '12345678',
            'akreditasi'   => 'A',
            'alamat'       => 'Jl. Pendidikan No. 123',
            'kecamatan'    => 'Sejahtera',
            'kota'         => 'Bandung',
            'provinsi'     => 'Jawa Barat',
            'telepon'      => '022-1234567',
            'email'        => 'info@sdnbabakan02.sch.id',
        ]);

        VisiMisi::create([
            'visi' => 'Terwujudnya generasi yang beriman, berilmu, dan berakhlak mulia.',
            'misi' => implode("\n", [
                '1. Melaksanakan pembelajaran yang aktif, kreatif, dan menyenangkan.',
                '2. Mengembangkan potensi siswa secara optimal.',
                '3. Menumbuhkan rasa cinta tanah air dan budaya bangsa.',
                '4. Membiasakan perilaku hidup bersih dan sehat.',
            ]),
        ]);
    }
}