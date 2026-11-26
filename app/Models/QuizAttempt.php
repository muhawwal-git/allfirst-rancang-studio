<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'kelas_id',
    'skor',
    'jumlah_benar',
    'total_soal',
    'lulus',
    'jawaban_siswa',
])]
class QuizAttempt extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skor' => 'integer',
            'jumlah_benar' => 'integer',
            'total_soal' => 'integer',
            'lulus' => 'boolean',
            'jawaban_siswa' => 'array',
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
