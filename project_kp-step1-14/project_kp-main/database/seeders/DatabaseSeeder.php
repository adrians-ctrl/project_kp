<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\BeritaPengumuman;
use App\Models\GuruStaf;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use App\Models\User;
use App\Models\VisiMisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $guruUser = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'guru@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'guru',
        ]);

        // Profil Sekolah
        ProfilSekolah::create([
            'nama_sekolah' => 'SD Negeri Babakan 02',
            'npsn'         => '20217685',
            'akreditasi'   => 'A',
            'alamat'       => 'Jl. Babakan Raya No. 10',
            'kelurahan'    => 'Babakan',
            'kecamatan'    => 'Bogor Tengah',
            'kota'         => 'Bogor',
            'provinsi'     => 'Jawa Barat',
            'kode_pos'     => '16128',
            'telepon'      => '0251-1234567',
            'email'        => 'info@sdbabakan02.sch.id',
            'website'      => 'https://sdbabakan02.sch.id',
        ]);

        // Visi Misi
        VisiMisi::create([
            'visi' => 'Terwujudnya peserta didik yang beriman, cerdas, terampil, dan berbudaya lingkungan.',
            'misi' => "1. Melaksanakan pembelajaran aktif, kreatif, efektif, dan menyenangkan.\n2. Menumbuhkan penghayatan terhadap ajaran agama dan budaya bangsa.\n3. Mengembangkan potensi siswa secara optimal.\n4. Membiasakan perilaku hidup bersih, sehat, dan peduli lingkungan.",
        ]);

        // Guru & Staf
        $guruData = [
            ['nip' => '197505151999031001', 'nama' => 'Drs. Ahmad Fauzi, M.Pd.',  'jabatan' => 'Kepala Sekolah', 'mapel' => null,                   'user_id' => null],
            ['nip' => '198002102006042010', 'nama' => 'Budi Santoso, S.Pd.',      'jabatan' => 'Guru Kelas',    'mapel' => 'Matematika',             'user_id' => $guruUser->id],
            ['nip' => '198509202010012015', 'nama' => 'Siti Rahayu, S.Pd.',       'jabatan' => 'Guru Kelas',    'mapel' => 'Bahasa Indonesia',       'user_id' => null],
            ['nip' => '199001152015031002', 'nama' => 'Rina Marlina, S.Pd.',      'jabatan' => 'Guru Kelas',    'mapel' => 'IPA',                    'user_id' => null],
            ['nip' => '199303082018022003', 'nama' => 'Hendra Gunawan, S.Pd.',    'jabatan' => 'Guru Kelas',    'mapel' => 'IPS',                    'user_id' => null],
            ['nip' => null,                 'nama' => 'Dewi Lestari, S.Pd.I.',    'jabatan' => 'Guru PAI',      'mapel' => 'Pendidikan Agama Islam', 'user_id' => null],
            ['nip' => null,                 'nama' => 'Agus Triyono, S.Pd.',      'jabatan' => 'Guru PJOK',     'mapel' => 'PJOK',                   'user_id' => null],
            ['nip' => null,                 'nama' => 'Fitri Handayani',           'jabatan' => 'Tata Usaha',    'mapel' => null,                     'user_id' => null],
        ];

        $guruModels = [];
        foreach ($guruData as $g) {
            $guruModels[] = GuruStaf::create([
                'user_id'      => $g['user_id'],
                'nip'          => $g['nip'],
                'nama_lengkap' => $g['nama'],
                'jabatan'      => $g['jabatan'],
                'mapel'        => $g['mapel'],
                'pendidikan'   => 'S1',
            ]);
        }

        // Kelas
        $kelasData = [
            ['nama_kelas' => 'Kelas 1A', 'tingkat' => '1', 'wali_id' => $guruModels[1]->id],
            ['nama_kelas' => 'Kelas 2A', 'tingkat' => '2', 'wali_id' => $guruModels[2]->id],
            ['nama_kelas' => 'Kelas 3A', 'tingkat' => '3', 'wali_id' => $guruModels[3]->id],
            ['nama_kelas' => 'Kelas 4A', 'tingkat' => '4', 'wali_id' => $guruModels[4]->id],
            ['nama_kelas' => 'Kelas 5A', 'tingkat' => '5', 'wali_id' => $guruModels[1]->id],
            ['nama_kelas' => 'Kelas 6A', 'tingkat' => '6', 'wali_id' => $guruModels[2]->id],
        ];

        $kelasModels = [];
        foreach ($kelasData as $k) {
            $kelasModels[] = Kelas::create([
                'nama_kelas'    => $k['nama_kelas'],
                'tingkat'       => $k['tingkat'],
                'tahun_ajaran'  => '2025/2026',
                'wali_kelas_id' => $k['wali_id'],
            ]);
        }

        // Mata Pelajaran
        $mapelData = [
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam'],
            ['kode_mapel' => 'IPS', 'nama_mapel' => 'Ilmu Pengetahuan Sosial'],
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam'],
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan'],
            ['kode_mapel' => 'SBK', 'nama_mapel' => 'Seni Budaya dan Keterampilan'],
            ['kode_mapel' => 'PJK', 'nama_mapel' => 'PJOK'],
        ];

        $mapelModels = [];
        foreach ($mapelData as $m) {
            $mapelModels[] = MataPelajaran::create($m);
        }

        // Siswa
        $namaLaki  = ['Ahmad Rizki','Budi Pratama','Dimas Arya','Eko Saputra','Fajar Nugraha','Gilang Ramadhan','Hendra Wijaya','Ivan Kurniawan','Joko Susilo','Kevin Andika'];
        $namaPerem = ['Alya Putri','Bunga Cantika','Citra Dewi','Dina Safitri','Eka Rahayu','Fina Maharani','Gita Lestari','Hana Puspita','Indah Sari','Julia Pratiwi'];

        $siswaModels = [];
        $nis = 2401;

        foreach ($kelasModels as $ki => $kelas) {
            for ($j = 0; $j < 25; $j++) {
                $isLaki = $j % 2 === 0;
                $nama   = ($isLaki ? $namaLaki : $namaPerem)[$j % 10];
                $siswaModels[] = Siswa::create([
                    'nisn'          => (string)(20240000 + $nis),
                    'nis'           => (string)$nis,
                    'nama_lengkap'  => $nama,
                    'jenis_kelamin' => $isLaki ? 'L' : 'P',
                    'tempat_lahir'  => 'Bogor',
                    'tanggal_lahir' => now()->subYears(6 + (int)$kelas->tingkat)->subDays(rand(0, 300)),
                    'alamat'        => 'Jl. Contoh No. ' . ($nis % 99 + 1) . ', Bogor',
                    'kelas_id'      => $kelas->id,
                ]);
                $nis++;
            }
        }

        // Nilai
        foreach ($siswaModels as $siswa) {
            foreach (array_slice($mapelModels, 0, 5) as $mapel) {
                Nilai::create([
                    'siswa_id'     => $siswa->id,
                    'mapel_id'     => $mapel->id,
                    'semester'     => '1',
                    'tahun_ajaran' => '2025/2026',
                    'nilai_tugas'  => rand(65, 95),
                    'nilai_uts'    => rand(60, 92),
                    'nilai_uas'    => rand(68, 98),
                ]);
            }
        }

        // Absensi 7 hari terakhir
        foreach ($siswaModels as $siswa) {
            for ($d = 0; $d < 7; $d++) {
                $r = rand(1, 10);
                Absensi::create([
                    'siswa_id' => $siswa->id,
                    'tanggal'  => now()->subDays($d)->toDateString(),
                    'status'   => match(true) { $r <= 7 => 'hadir', $r <= 8 => 'izin', $r <= 9 => 'sakit', default => 'alpha' },
                ]);
            }
        }

        // Berita
        $beritaList = [
            ['judul' => 'Penerimaan Peserta Didik Baru Tahun 2025/2026',                    'kategori' => 'pengumuman'],
            ['judul' => 'Jadwal Ujian Tengah Semester Genap',                               'kategori' => 'pengumuman'],
            ['judul' => 'Siswa SDN Babakan 02 Raih Juara 1 Lomba Cerdas Cermat',            'kategori' => 'berita'],
            ['judul' => 'Kegiatan Jambore Pramuka Tingkat Kecamatan',                       'kategori' => 'berita'],
            ['judul' => 'Libur Akhir Semester Gasal 2025',                                  'kategori' => 'pengumuman'],
        ];

        foreach ($beritaList as $b) {
            BeritaPengumuman::create([
                'judul'        => $b['judul'],
                'slug'         => Str::slug($b['judul']),
                'konten'       => 'Konten berita akan diisi lebih lanjut oleh administrator sekolah.',
                'kategori'     => $b['kategori'],
                'is_published' => true,
                'user_id'      => $admin->id,
            ]);
        }
    }
}
