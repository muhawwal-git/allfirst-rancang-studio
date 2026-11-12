<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_invoice' => 'INV-'.fake()->unique()->numerify('####'),
            'user_id' => User::factory(),
            'kelas_id' => Kelas::factory(),
            'jenis_transaksi' => 'baru',
            'jumlah_pembayaran' => 350000,
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'menunggu_pembayaran',
            'alasan_penolakan' => null,
            'batas_waktu_bayar' => now()->addHours(24),
            'verified_at' => null,
        ];
    }
}
