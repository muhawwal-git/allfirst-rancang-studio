<?php

namespace App\Models;

use Database\Factories\ModulFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kelas_id', 'judul_modul', 'nomor_urutan'])]
class Modul extends Model
{
    /** @use HasFactory<ModulFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nomor_urutan' => 'integer',
        ];
    }

    public function getJudulAttribute(): string
    {
        return (string) ($this->attributes['judul_modul'] ?? '');
    }

    public function setJudulAttribute(string $value): void
    {
        $this->attributes['judul_modul'] = $value;
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class)->orderBy('nomor_urutan');
    }
}
