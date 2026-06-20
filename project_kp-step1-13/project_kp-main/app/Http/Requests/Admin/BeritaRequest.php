<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'        => ['required', 'string', 'max:200'],
            'konten'       => ['required', 'string'],
            'kategori'     => ['required', 'in:berita,pengumuman'],
            'is_published' => ['boolean'],
            'gambar'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'        => 'judul',
            'konten'       => 'konten',
            'kategori'     => 'kategori',
            'is_published' => 'status publikasi',
            'gambar'       => 'gambar',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
            'in'       => ':attribute tidak valid.',
            'image'    => ':attribute harus berupa gambar.',
            'mimes'    => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
