<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'semester',
        'tahun_ajaran',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
    ];

    protected function casts(): array
    {
        return [
            'nilai_tugas' => 'float',
            'nilai_uts'   => 'float',
            'nilai_uas'   => 'float',
        ];
    }

    public function siswa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function getNilaiAkhirAttribute(): float
    {
        return round(($this->nilai_tugas + $this->nilai_uts + $this->nilai_uas) / 3, 2);
    }

    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->nilai_akhir >= 90 => 'A',
            $this->nilai_akhir >= 80 => 'B',
            $this->nilai_akhir >= 70 => 'C',
            $this->nilai_akhir >= 60 => 'D',
            default                  => 'E',
        };
    }

    public function getPredikatAttribute(): string
    {
        return match ($this->grade) {
            'A'     => 'Sangat Baik',
            'B'     => 'Baik',
            'C'     => 'Cukup',
            'D'     => 'Kurang',
            default => 'Sangat Kurang',
        };
    }
}