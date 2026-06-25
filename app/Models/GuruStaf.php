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

    // ── Relasi ────────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    // ── Accessor ──────────────────────────────────────────────────────────

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        // Fallback ke UI Avatars (tidak butuh file lokal)
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama_lengkap)
            . '&background=1e3a8a&color=fff&size=128&bold=true';
    }

    public function getInisialAttribute(): string
    {
        $words = explode(' ', trim($this->nama_lengkap));
        $inisial = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $inisial .= strtoupper(substr($word, 0, 1));
        }

        return $inisial;
    }

    public function getIsGuruAttribute(): bool
    {
        return str_starts_with($this->jabatan, 'Guru') || $this->jabatan === 'Kepala Sekolah';
    }

    public function getJabatanBadgeToneAttribute(): string
    {
        return match (true) {
            $this->jabatan === 'Kepala Sekolah' => 'warning',
            str_starts_with($this->jabatan, 'Guru') => 'info',
            default => 'neutral',
        };
    }

    // ── Scope ─────────────────────────────────────────────────────────────

    public function scopeGuru($query)
    {
        return $query->where(function ($q) {
            $q->where('jabatan', 'LIKE', 'Guru%')
              ->orWhere('jabatan', 'Kepala Sekolah');
        });
    }

    public function scopeStaf($query)
    {
        return $query->where('jabatan', 'NOT LIKE', 'Guru%')
                     ->where('jabatan', '!=', 'Kepala Sekolah');
    }
}
