<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MataPelajaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $mapelId = $this->route('mata_pelajaran')?->id;

        return [
            'kode_mapel' => ['required', 'string', 'max:20', Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($mapelId)],
            'nama_mapel' => ['required', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_mapel' => 'kode mata pelajaran',
            'nama_mapel' => 'nama mata pelajaran',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
            'unique'   => ':attribute sudah digunakan.',
        ];
    }
}
