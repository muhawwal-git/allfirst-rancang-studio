<?php

namespace App\Models;

use Database\Factories\KelasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

#[Fillable([
    'judul_kelas',
    'slug',
    'kategori',
    'jenis_kelas',
    'harga',
    'deskripsi',
    'manfaat',
    'modul_offline',
    'tautan_software',
    'gambar_sampul',
    'status_publikasi',
    'is_unggulan',
])]
class Kelas extends Model
{
    /** @use HasFactory<KelasFactory> */
    use HasFactory;

    protected $table = 'kelas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'manfaat' => 'array',
            'modul_offline' => 'array',
            'is_unggulan' => 'boolean',
        ];
    }

    public function moduls(): HasMany
    {
        return $this->hasMany(Modul::class)->orderBy('nomor_urutan');
    }

    public function materis(): HasManyThrough
    {
        return $this->hasManyThrough(Materi::class, Modul::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function aksesKelas(): HasMany
    {
        return $this->hasMany(AksesKelas::class);
    }

    public function sertifikats(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('nomor_urutan')->orderBy('id');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function testimonis(): HasMany
    {
        return $this->hasMany(Testimoni::class);
    }

    public function passingGrade(): int
    {
        return 70;
    }

    public function bestQuizAttemptFor(User $user): ?QuizAttempt
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->orderByDesc('lulus')
            ->orderByDesc('skor')
            ->orderByDesc('id')
            ->first();
    }

    public function latestQuizAttemptFor(User $user): ?QuizAttempt
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();
    }

    public function hasPassedQuizFor(User $user): bool
    {
        return $this->quizAttempts()
            ->where('user_id', $user->id)
            ->where('lulus', true)
            ->exists();
    }

    public function testimoniFor(User $user): ?Testimoni
    {
        return $this->testimonis()
            ->where('user_id', $user->id)
            ->first();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status_publikasi', 'published');
    }

    public function scopeOnlineCatalog(Builder $query): Builder
    {
        return $query->where('jenis_kelas', 'online');
    }

    public function scopeOfflineCatalog(Builder $query): Builder
    {
        return $query->where('jenis_kelas', 'offline');
    }

    public function isOfflineCatalog(): bool
    {
        return ($this->attributes['jenis_kelas'] ?? 'online') === 'offline';
    }

    public function getJudulAttribute(): string
    {
        return (string) ($this->attributes['judul_kelas'] ?? '');
    }

    public function getKategoriSoftwareAttribute(): string
    {
        return (string) ($this->attributes['kategori'] ?? '');
    }

    public function setJudulAttribute(string $value): void
    {
        $this->attributes['judul_kelas'] = $value;
        if (! isset($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->attributes['gambar_sampul'] ?? null;
    }

    public function getLevelAttribute(): string
    {
        return 'Pemula - Menengah';
    }

    public function getDeskripsiSingkatAttribute(): string
    {
        return Str::limit(strip_tags((string) ($this->attributes['deskripsi'] ?? '')), 140);
    }

    public function getDeskripsiLengkapAttribute(): string
    {
        return (string) ($this->attributes['deskripsi'] ?? '');
    }

    public function getHargaCoretAttribute(): int
    {
        return (int) round(($this->attributes['harga'] ?? 0) * 1.4);
    }

    public function hargaReaktivasi(): int
    {
        return (int) round($this->harga * 0.5);
    }

    public function formattedHarga(): string
    {
        return 'Rp '.number_format($this->harga, 0, ',', '.');
    }

    public function getFormattedHargaAttribute(): string
    {
        return $this->formattedHarga();
    }

    public function formattedHargaReaktivasi(): string
    {
        return 'Rp '.number_format($this->hargaReaktivasi(), 0, ',', '.');
    }

    public function previewMateri(): ?Materi
    {
        return $this->materis()
            ->where('cuplikan_gratis', true)
            ->orderBy('materis.nomor_urutan')
            ->first()
            ?? $this->materis()->orderBy('materis.nomor_urutan')->first();
    }

    public function progressPercentageFor(User $user): int
    {
        $totalMateri = $this->materis()->count();
        if ($totalMateri === 0) {
            return 0;
        }

        $materiIds = $this->materis()->pluck('materis.id');
        $completedCount = ProgresMateri::query()
            ->where('user_id', $user->id)
            ->whereIn('materi_id', $materiIds)
            ->where('sudah_selesai', true)
            ->count();

        return (int) min(100, round(($completedCount / $totalMateri) * 100));
    }
}
