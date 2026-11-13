<?php

namespace App\Models;

use Database\Factories\SertifikatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'kelas_id',
    'nomor_sertifikat',
    'predikat',
    'nilai',
    'hash_kode_qr',
    'tanggal_terbit',
    'file_pdf',
])]
class Sertifikat extends Model
{
    /** @use HasFactory<SertifikatFactory> */
    use HasFactory;

    /**
     * Generate the next sequential certificate number matching Allfirst Studio's official format:
     * {NomorUrut3Digit}-ARS{YY}-{MM}/{Lokasi}/{YYYY} (e.g., 014-ARS26-11/MKS/2026)
     */
    public static function generateNomorSertifikat(): string
    {
        $lastConfigured = (int) PengaturanSitus::getValue('sertifikat_nomor_terakhir', '13');

        $maxFromDb = 0;
        $existingNumbers = static::query()->pluck('nomor_sertifikat');
        foreach ($existingNumbers as $num) {
            if (preg_match('/^(\d+)-ARS/i', (string) $num, $matches)) {
                $val = (int) $matches[1];
                if ($val > $maxFromDb) {
                    $maxFromDb = $val;
                }
            }
        }

        $nextSequence = max($lastConfigured, $maxFromDb) + 1;
        PengaturanSitus::setValue('sertifikat_nomor_terakhir', (string) $nextSequence);

        $lokasi = trim((string) PengaturanSitus::getValue('sertifikat_kode_lokasi', 'MKS')) ?: 'MKS';

        return sprintf(
            '%03d-ARS%s-%s/%s/%s',
            $nextSequence,
            date('y'),
            date('m'),
            strtoupper($lokasi),
            date('Y')
        );
    }

    /**
     * Issue a certificate for a student and course if not already issued, or update its grade rubric.
     */
    public static function issueForStudent(int $userId, int $kelasId, string $predikat = 'Baik', ?int $nilai = null): self
    {
        $resolvedNilai = $nilai ?? match ($predikat) {
            'Sangat Baik' => 95,
            'Cukup' => 68,
            default => 80,
        };

        $existing = static::query()
            ->where('user_id', $userId)
            ->where('kelas_id', $kelasId)
            ->first();

        if ($existing) {
            $existing->update([
                'predikat' => $predikat,
                'nilai' => $resolvedNilai,
            ]);

            return $existing;
        }

        return static::query()->create([
            'user_id' => $userId,
            'kelas_id' => $kelasId,
            'nomor_sertifikat' => static::generateNomorSertifikat(),
            'predikat' => $predikat,
            'nilai' => $resolvedNilai,
            'hash_kode_qr' => Str::random(32),
            'tanggal_terbit' => now(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nilai' => 'integer',
            'tanggal_terbit' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
