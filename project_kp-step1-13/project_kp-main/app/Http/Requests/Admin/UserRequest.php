<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isEdit = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role'     => ['required', 'in:admin,guru'],
            'password' => $isEdit
                ? ['nullable', 'confirmed', Password::min(8)]
                : ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'nama',
            'email'    => 'email',
            'role'     => 'role',
            'password' => 'password',
        ];
    }

    public function messages(): array
    {
        return [
            'required'         => ':attribute wajib diisi.',
            'email'            => ':attribute harus berupa alamat email yang valid.',
            'max'              => ':attribute maksimal :max karakter.',
            'unique'           => ':attribute sudah digunakan.',
            'in'               => ':attribute tidak valid.',
            'confirmed'        => ':attribute konfirmasi tidak cocok.',
            'password.min'     => 'Password minimal 8 karakter.',
        ];
    }
}
