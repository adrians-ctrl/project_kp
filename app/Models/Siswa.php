<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
     protected $table = 'siswa';
     
    protected $fillable = [
        'nisn',
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'nama_orang_tua',
        'foto',
        'kelas_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────

    public function kelas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    // ── Accessor ──────────────────────────────────────────────────────────

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_lengkap)
            . '&background=' . ($this->jenis_kelamin === 'L' ? '1e3a8a' : 'be185d')
            . '&color=fff&size=128&bold=true';
    }

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir
            ? Carbon::parse($this->tanggal_lahir)->age
            : null;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getTanggalLahirFormatAttribute(): string
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->translatedFormat('d F Y')
            : '-';
    }

    // ── Scope ─────────────────────────────────────────────────────────────

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
              ->orWhere('nisn', 'like', "%{$keyword}%")
              ->orWhere('nis', 'like', "%{$keyword}%");
        });
    }

    public function scopeFilterKelas($query, $kelasId)
    {
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        return $query;
    }

    public function scopeFilterJenisKelamin($query, $jk)
    {
        if ($jk) {
            $query->where('jenis_kelamin', $jk);
        }

        return $query;
    }
}
