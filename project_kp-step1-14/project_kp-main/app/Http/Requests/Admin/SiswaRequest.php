<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siswaId = $this->route('siswa')?->id;

        return [
            'nama_lengkap'  => ['required', 'string', 'max:100'],
            'nisn'          => ['required', 'string', 'max:20', Rule::unique('siswa', 'nisn')->ignore($siswaId)],
            'nis'           => ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($siswaId)],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id'      => ['required', 'exists:kelas,id'],
            'tempat_lahir'  => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat'        => ['nullable', 'string', 'max:500'],
            'no_hp'         => ['nullable', 'string', 'max:20'],
            'nama_orang_tua'=> ['nullable', 'string', 'max:100'],
            'foto'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap'   => 'nama lengkap',
            'nisn'           => 'NISN',
            'nis'            => 'NIS',
            'jenis_kelamin'  => 'jenis kelamin',
            'kelas_id'       => 'kelas',
            'tempat_lahir'   => 'tempat lahir',
            'tanggal_lahir'  => 'tanggal lahir',
            'alamat'         => 'alamat',
            'no_hp'          => 'nomor HP',
            'nama_orang_tua' => 'nama orang tua',
            'foto'           => 'foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'unique'      => ':attribute sudah terdaftar.',
            'exists'      => ':attribute tidak ditemukan.',
            'in'          => ':attribute tidak valid.',
            'max'         => ':attribute maksimal :max karakter.',
            'date'        => ':attribute harus berupa tanggal yang valid.',
            'image'       => ':attribute harus berupa gambar.',
            'mimes'       => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'    => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
