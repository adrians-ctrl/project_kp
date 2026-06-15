<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────────────

    public function guruStaf(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(GuruStaf::class);
    }

    // ── Helper ────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function getInisialAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $inisial = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $inisial .= strtoupper(substr($word, 0, 1));
        }

        return $inisial;
    }
}
