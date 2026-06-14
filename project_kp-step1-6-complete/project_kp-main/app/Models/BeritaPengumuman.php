<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BeritaPengumuman extends Model
{
    protected $table = 'berita_pengumuman';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',
        'kategori',
        'is_published',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : null;
    }

    public function getKontenRingkasAttribute(): string
    {
        return Str::limit(strip_tags($this->konten), 150);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}