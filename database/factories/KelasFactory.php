<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'judul_kelas' => $title,
            'slug' => Str::slug($title),
            'kategori' => fake()->randomElement(['AutoCAD', 'SketchUp', 'Revit', 'Enscape']),
            'harga' => fake()->randomElement([350000, 380000, 400000, 420000, 450000]),
            'deskripsi' => fake()->paragraph(3),
            'manfaat' => [
                'Pembelajaran Mandiri Berbasis Video HD',
                'Masa Aktif Akses Penuh 1 Tahun (365 Hari)',
                'Sertifikat Kelulusan Digital Resmi Ber-QR Code',
            ],
            'gambar_sampul' => null,
            'status_publikasi' => 'published',
            'is_unggulan' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_publikasi' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_publikasi' => 'draft',
        ]);
    }

    public function unggulan(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_unggulan' => true,
        ]);
    }
}
