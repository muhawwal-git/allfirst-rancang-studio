<?php

namespace Tests\Feature;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\PengaturanSitus;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaAndModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_populates_all_core_entities_and_relationships(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'allfirst.go@gmail.com')->firstOrFail();
        $student = User::query()->where('email', 'budi@student.id')->firstOrFail();

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($student->isStudent());
        $this->assertSame('Universitas Hasanuddin', $student->instansi_asal);
        $this->assertSame(6, Kelas::query()->published()->count());

        $autocad = Kelas::query()->where('slug', 'autocad-2d-gambar-kerja-mahir')->firstOrFail();
        $this->assertSame(175000, $autocad->hargaReaktivasi());
        $this->assertSame(3, $autocad->moduls()->count());
        $this->assertSame(6, $autocad->materis()->count());
        $this->assertNotNull($autocad->previewMateri());
        $this->assertSame(33, $autocad->progressPercentageFor($student));

        $aktifAkses = AksesKelas::query()
            ->where('user_id', $student->id)
            ->where('kelas_id', $autocad->id)
            ->firstOrFail();
        $this->assertTrue($aktifAkses->isAktif());
        $this->assertGreaterThan(240, $aktifAkses->sisaHari());

        $this->assertSame(1, Transaksi::query()->where('status_pembayaran', 'menunggu_verifikasi')->count());
        $this->assertSame('1', PengaturanSitus::getValue('running_text_aktif'));
    }
}
