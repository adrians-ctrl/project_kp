<?php

namespace App\Http\Requests\Guru;

use App\Models\Kelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuruAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan kelas yang diinput memang diampu oleh guru yang login
        $guru = Auth::user()->guruStaf;

        if (! $guru) {
            return false;
        }

        $kelasId = $this->input('kelas_id');

        return Kelas::where('id', $kelasId)
            ->where('wali_kelas_id', $guru->id)
            ->exists();
    }

    public function rules(): array
    {
        return [
            'kelas_id'             => ['required', 'exists:kelas,id'],
            'tanggal'              => ['required', 'date'],
            'absensi'              => ['required', 'array', 'min:1'],
            'absensi.*.siswa_id'   => ['required', 'exists:siswa,id'],
            'absensi.*.status'     => ['required', 'in:hadir,izin,sakit,alpha'],
            'absensi.*.keterangan' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kelas_id' => 'kelas',
            'tanggal'  => 'tanggal',
            'absensi'  => 'data absensi',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'exists'      => ':attribute tidak ditemukan.',
            'in'          => 'Status tidak valid.',
            'absensi.min' => 'Minimal satu siswa harus diisi.',
        ];
    }

    public function failedAuthorization(): void
    {
        abort(403, 'Anda tidak memiliki akses untuk mengisi absensi kelas ini.');
    }
}
