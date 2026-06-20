<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GaleriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'foto'      => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'     => 'judul',
            'deskripsi' => 'deskripsi',
            'foto'      => 'foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => ':attribute wajib diisi.',
            'max'        => ':attribute maksimal :max karakter.',
            'image'      => ':attribute harus berupa gambar.',
            'mimes'      => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'foto.max'   => 'Ukuran foto maksimal 3MB.',
        ];
    }
}
