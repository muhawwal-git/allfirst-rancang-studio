<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Modul;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Modul>
 */
class ModulFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'judul_modul' => fake()->sentence(4),
            'nomor_urutan' => 1,
        ];
    }
}
