<?php

namespace Database\Factories;

use App\Models\Materi;
use App\Models\Modul;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Materi>
 */
class MateriFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'judul_materi' => fake()->sentence(5),
            'path_video' => 'videos/sample-lesson.mp4',
            'durasi_tampil' => '08:30',
            'durasi_detik' => 510,
            'nomor_urutan' => 1,
            'cuplikan_gratis' => false,
        ];
    }

    public function preview(): static
    {
        return $this->state(fn (array $attributes) => [
            'cuplikan_gratis' => true,
        ]);
    }
}
