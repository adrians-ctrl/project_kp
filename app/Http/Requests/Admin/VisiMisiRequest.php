<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VisiMisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visi' => ['required', 'string', 'max:1000'],
            'misi' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'visi' => 'visi',
            'misi' => 'misi',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
        ];
    }
}
