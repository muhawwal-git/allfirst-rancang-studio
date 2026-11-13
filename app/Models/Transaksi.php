<?php

namespace App\Models;

use Database\Factories\TransaksiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nomor_invoice',
    'user_id',
    'kelas_id',
    'jenis_transaksi',
    'jumlah_pembayaran',
    'bukti_pembayaran',
    'status_pembayaran',
    'alasan_penolakan',
    'batas_waktu_bayar',
    'verified_at',
])]
class Transaksi extends Model
{
    /** @use HasFactory<TransaksiFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_pembayaran' => 'integer',
            'batas_waktu_bayar' => 'datetime',
            'verified_at' => 'datetime',
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

    public function aksesKelas(): HasOne
    {
        return $this->hasOne(AksesKelas::class);
    }

    public function formattedJumlah(): string
    {
        return 'Rp '.number_format($this->jumlah_pembayaran, 0, ',', '.');
    }

    public function getFormattedJumlahAttribute(): string
    {
        return $this->formattedJumlah();
    }

    public function getFormattedTotalAttribute(): string
    {
        return $this->formattedJumlah();
    }

    public function getStatusAttribute(): string
    {
        return (string) ($this->attributes['status_pembayaran'] ?? 'menunggu_pembayaran');
    }

    public function isOfflineRegistration(): bool
    {
        return ($this->attributes['jenis_transaksi'] ?? '') === 'offline';
    }

    public function isPendingVerificationOrPayment(): bool
    {
        return in_array($this->status_pembayaran, ['menunggu_pembayaran', 'menunggu_verifikasi'], true);
    }
}
