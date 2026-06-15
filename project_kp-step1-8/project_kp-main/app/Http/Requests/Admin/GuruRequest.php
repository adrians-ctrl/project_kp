<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guruId = $this->route('guru')?->id;

        return [
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'jabatan'      => ['required', 'string', 'max:50'],
            'mapel'        => ['nullable', 'string', 'max:100'],
            'pendidikan'   => ['nullable', 'string', 'max:20'],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'nip'          => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('guru_staf', 'nip')->ignore($guruId),
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('guru_staf', 'user_id')->ignore($guruId),
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'jabatan'      => 'jabatan',
            'mapel'        => 'mata pelajaran',
            'pendidikan'   => 'pendidikan terakhir',
            'no_hp'        => 'nomor HP',
            'nip'          => 'NIP',
            'user_id'      => 'akun pengguna',
            'foto'         => 'foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => ':attribute wajib diisi.',
            'max'           => ':attribute maksimal :max karakter.',
            'unique'        => ':attribute sudah digunakan.',
            'exists'        => ':attribute tidak ditemukan.',
            'image'         => ':attribute harus berupa gambar.',
            'mimes'         => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'      => 'Ukuran foto maksimal 2MB.',
            'user_id.unique'=> 'Akun ini sudah terhubung ke data guru lain.',
        ];
    }
}
