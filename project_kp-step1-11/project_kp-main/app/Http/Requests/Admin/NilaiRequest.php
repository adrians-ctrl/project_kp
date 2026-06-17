<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nilaiId = $this->route('nilai')?->id;

        return [
            'siswa_id'     => ['required', 'exists:siswa,id'],
            'mapel_id'     => [
                'required',
                'exists:mata_pelajaran,id',
                Rule::unique('nilai')->where(function ($query) {
                    return $query
                        ->where('siswa_id',    $this->input('siswa_id'))
                        ->where('semester',    $this->input('semester'))
                        ->where('tahun_ajaran',$this->input('tahun_ajaran'));
                })->ignore($nilaiId),
            ],
            'semester'     => ['required', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'nilai_tugas'  => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_uts'    => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_uas'    => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'siswa_id'     => 'siswa',
            'mapel_id'     => 'mata pelajaran',
            'semester'     => 'semester',
            'tahun_ajaran' => 'tahun ajaran',
            'nilai_tugas'  => 'nilai tugas',
            'nilai_uts'    => 'nilai UTS',
            'nilai_uas'    => 'nilai UAS',
        ];
    }

    public function messages(): array
    {
        return [
            'required'        => ':attribute wajib diisi.',
            'exists'          => ':attribute tidak ditemukan.',
            'in'              => ':attribute tidak valid.',
            'numeric'         => ':attribute harus berupa angka.',
            'min'             => ':attribute minimal :min.',
            'max'             => ':attribute maksimal :max.',
            'mapel_id.unique' => 'Data nilai siswa ini untuk mata pelajaran, semester, dan tahun ajaran yang sama sudah ada.',
        ];
    }
}
