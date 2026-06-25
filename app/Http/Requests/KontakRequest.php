<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KontakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subjek'=> ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'   => 'nama',
            'email'  => 'email',
            'subjek' => 'subjek',
            'pesan'  => 'pesan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email'    => ':attribute harus berupa alamat email yang valid.',
            'max'      => ':attribute maksimal :max karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitasi dasar: hilangkan tag HTML dari input teks bebas
        $this->merge([
            'nama'   => strip_tags($this->input('nama', '')),
            'subjek' => strip_tags($this->input('subjek', '')),
            'pesan'  => strip_tags($this->input('pesan', '')),
        ]);
    }
}
