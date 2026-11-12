<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Sertifikat>
 */
class SertifikatFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'kelas_id' => Kelas::factory(),
            'nomor_sertifikat' => 'ARS-'.now()->year.'-'.fake()->unique()->numerify('####'),
            'hash_kode_qr' => hash('sha256', Str::uuid()->toString()),
            'tanggal_terbit' => now(),
            'file_pdf' => null,
        ];
    }
}
