<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfilSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'npsn'         => ['nullable', 'string', 'max:20'],
            'akreditasi'   => ['nullable', 'string', 'max:10'],
            'alamat'       => ['required', 'string', 'max:500'],
            'kelurahan'    => ['nullable', 'string', 'max:100'],
            'kecamatan'    => ['nullable', 'string', 'max:100'],
            'kota'         => ['nullable', 'string', 'max:100'],
            'provinsi'     => ['nullable', 'string', 'max:100'],
            'kode_pos'     => ['nullable', 'string', 'max:10'],
            'telepon'      => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:150'],
            'website'      => ['nullable', 'url', 'max:150'],
            'logo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sambutan'     => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_sekolah' => 'nama sekolah',
            'npsn'         => 'NPSN',
            'akreditasi'   => 'akreditasi',
            'alamat'       => 'alamat',
            'logo'         => 'logo sekolah',
            'sambutan'     => 'sambutan kepala sekolah',
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => ':attribute wajib diisi.',
            'max'       => ':attribute maksimal :max karakter.',
            'email'     => ':attribute harus berupa email yang valid.',
            'url'       => ':attribute harus berupa URL yang valid.',
            'image'     => ':attribute harus berupa gambar.',
            'mimes'     => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'logo.max'  => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
