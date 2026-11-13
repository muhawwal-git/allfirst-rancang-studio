<?php

namespace App\Models;

use Database\Factories\AksesKelasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'kelas_id',
    'transaksi_id',
    'tipe_kelas',
    'tanggal_mulai',
    'tanggal_kedaluwarsa',
    'status_akses',
])]
class AksesKelas extends Model
{
    /** @use HasFactory<AksesKelasFactory> */
    use HasFactory;

    protected $table = 'akses_kelas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_kedaluwarsa' => 'datetime',
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

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function isOffline(): bool
    {
        if (($this->attributes['tipe_kelas'] ?? '') === 'offline') {
            return true;
        }

        return $this->transaksi !== null && $this->transaksi->jenis_transaksi === 'offline';
    }

    public function isAktif(): bool
    {
        return $this->status_akses === 'aktif' && $this->tanggal_kedaluwarsa->isFuture();
    }

    public function sisaHari(): int
    {
        if (! $this->isAktif()) {
            return 0;
        }

        return (int) max(0, ceil(now()->diffInHours($this->tanggal_kedaluwarsa, false) / 24));
    }
}
