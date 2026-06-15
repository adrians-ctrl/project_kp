<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_kelas'    => ['required', 'string', 'max:50'],
            'tingkat'       => ['required', 'string', 'max:10'],
            'tahun_ajaran'  => ['required', 'string', 'max:20'],
            'wali_kelas_id' => ['nullable', 'exists:guru_staf,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_kelas'    => 'nama kelas',
            'tingkat'       => 'tingkat',
            'tahun_ajaran'  => 'tahun ajaran',
            'wali_kelas_id' => 'wali kelas',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
            'exists'   => ':attribute tidak ditemukan.',
        ];
    }
}
