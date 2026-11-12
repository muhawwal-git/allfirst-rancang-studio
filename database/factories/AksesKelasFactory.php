<?php

namespace Database\Factories;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AksesKelas>
 */
class AksesKelasFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now();

        return [
            'user_id' => User::factory(),
            'kelas_id' => Kelas::factory(),
            'transaksi_id' => Transaksi::factory(),
            'tanggal_mulai' => $start,
            'tanggal_kedaluwarsa' => $start->copy()->addDays(365),
            'status_akses' => 'aktif',
        ];
    }
}
