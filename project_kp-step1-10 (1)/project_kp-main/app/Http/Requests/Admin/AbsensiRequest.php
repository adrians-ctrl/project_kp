<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kelas_id'        => ['required', 'exists:kelas,id'],
            'tanggal'         => ['required', 'date'],
            'absensi'         => ['required', 'array', 'min:1'],
            'absensi.*.siswa_id'   => ['required', 'exists:siswa,id'],
            'absensi.*.status'     => ['required', 'in:hadir,izin,sakit,alpha'],
            'absensi.*.keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kelas_id'   => 'kelas',
            'tanggal'    => 'tanggal',
            'absensi'    => 'data absensi',
        ];
    }

    public function messages(): array
    {
        return [
            'required'              => ':attribute wajib diisi.',
            'exists'                => ':attribute tidak ditemukan.',
            'in'                    => 'Status tidak valid.',
            'absensi.min'           => 'Minimal satu siswa harus diisi.',
        ];
    }
}
