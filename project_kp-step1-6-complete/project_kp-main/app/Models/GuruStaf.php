<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruStaf extends Model
{
    protected $table = 'guru_staf';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jabatan',
        'mapel',
        'pendidikan',
        'no_hp',
        'foto',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }
}