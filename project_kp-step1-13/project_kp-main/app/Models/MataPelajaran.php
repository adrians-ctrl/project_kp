<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    public function nilai(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}