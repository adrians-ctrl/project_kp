<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
        'wali_kelas_id',
    ];

    public function waliKelas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GuruStaf::class, 'wali_kelas_id');
    }

    public function siswa(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswa()->count();
    }
}