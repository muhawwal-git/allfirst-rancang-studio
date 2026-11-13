<?php

namespace App\Models;

use Database\Factories\MateriFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'modul_id',
    'judul_materi',
    'path_video',
    'durasi_tampil',
    'durasi_detik',
    'nomor_urutan',
    'cuplikan_gratis',
])]
class Materi extends Model
{
    /** @use HasFactory<MateriFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'durasi_detik' => 'integer',
            'nomor_urutan' => 'integer',
            'cuplikan_gratis' => 'boolean',
        ];
    }

    public function getJudulAttribute(): string
    {
        return (string) ($this->attributes['judul_materi'] ?? '');
    }

    public function setJudulAttribute(string $value): void
    {
        $this->attributes['judul_materi'] = $value;
    }

    public function getIsPreviewAttribute(): bool
    {
        return (bool) ($this->attributes['cuplikan_gratis'] ?? false);
    }

    public function getFormattedDurasiAttribute(): string
    {
        return (string) ($this->attributes['durasi_tampil'] ?? '00:00');
    }

    public function getYoutubeIdAttribute(): ?string
    {
        $path = trim((string) ($this->attributes['path_video'] ?? ''));
        if ($path === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:watch\?.*v=|embed\/|shorts\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $path, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $path)) {
            return $path;
        }

        return null;
    }

    public function getIsYoutubeAttribute(): bool
    {
        return $this->youtube_id !== null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        if (! $id) {
            return null;
        }

        return "https://www.youtube.com/embed/{$id}?rel=0&modestbranding=1&enablejsapi=1&playsinline=1";
    }

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function progresMateris(): HasMany
    {
        return $this->hasMany(ProgresMateri::class);
    }

    public function progressFor(User $user): ?ProgresMateri
    {
        return $this->progresMateris()->where('user_id', $user->id)->first();
    }
}
