<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────

    public function siswa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // ── Accessor ──────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'green',
            'izin'  => 'yellow',
            'sakit' => 'blue',
            'alpha' => 'red',
            default => 'gray',
        };
    }

    // ── Scope ─────────────────────────────────────────────────────────────

    public function scopeByBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    public function scopeByTanggal($query, string $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }
}
