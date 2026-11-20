<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\PengaturanSitus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_be_rendered_with_running_text_and_courses(): void
    {
        PengaturanSitus::setValue('running_text_aktif', '1');
        PengaturanSitus::setValue('running_text', 'DISKON KHUSUS BATCH DED ARSITEKTUR ◆ AKSES PENUH 1 TAHUN');

        $course = Kelas::factory()->published()->create([
            'judul' => 'AutoCAD 2D: Gambar Kerja DED Lengkap',
            'is_unggulan' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Allfirst Rancang Studio', false);
        $response->assertSee('AutoCAD 2D: Gambar Kerja DED Lengkap');
        $response->assertSee('DISKON KHUSUS BATCH DED ARSITEKTUR');
        $response->assertSee('Alumni Terverifikasi');
    }

    public function test_course_catalog_page_can_be_rendered(): void
    {
        Kelas::factory()->published()->create([
            'judul' => 'Revit Architecture BIM Fundamental',
            'kategori' => 'Revit',
        ]);

        $response = $this->get(route('kelas.index'));

        $response->assertOk();
        $response->assertSee('Katalog Kelas Online');
        $response->assertSee('Revit Architecture BIM Fundamental');
        $response->assertSee('Semua Software');
        $response->assertSee('Revit');
    }

    public function test_course_catalog_can_be_filtered_by_category(): void
    {
        Kelas::factory()->published()->create([
            'judul' => 'Revit Architecture BIM Pro',
            'kategori' => 'Revit',
        ]);

        Kelas::factory()->published()->create([
            'judul' => 'AutoCAD Master Drafting',
            'kategori' => 'AutoCAD',
        ]);

        $response = $this->get(route('kelas.index', ['kategori' => 'Revit']));

        $response->assertOk();
        $response->assertSee('Revit Architecture BIM Pro');
        $response->assertDontSee('AutoCAD Master Drafting');
    }

    public function test_course_detail_page_can_be_rendered_for_published_course(): void
    {
        $kelas = Kelas::factory()->published()->create([
            'judul' => 'Lumion Cinematic Rendering',
            'harga' => 350000,
            'kategori' => 'Lumion',
        ]);

        $modul = Modul::factory()->create([
            'kelas_id' => $kelas->id,
            'judul_modul' => 'Modul 01: Pengenalan Interface Lumion',
            'nomor_urutan' => 1,
        ]);

        Materi::factory()->preview()->create([
            'modul_id' => $modul->id,
            'judul_materi' => '01. Import Model 3D & Setting Cuaca',
            'nomor_urutan' => 1,
            'durasi_detik' => 720,
        ]);

        $response = $this->get(route('kelas.show', $kelas->slug));

        $response->assertOk();
        $response->assertSee('Lumion Cinematic Rendering');
        $response->assertSee('Modul 01: Pengenalan Interface Lumion');
        $response->assertSee('Import Model 3D');
        $response->assertSee('Cuplikan Gratis');
        $response->assertSee('350.000');
    }

    public function test_course_detail_page_returns_404_for_draft_course(): void
    {
        $kelas = Kelas::factory()->draft()->create([
            'judul' => 'Kelas Masih Draft Internal',
        ]);

        $response = $this->get(route('kelas.show', $kelas->slug));

        $response->assertNotFound();
    }

    public function test_offline_page_can_be_rendered(): void
    {
        $response = $this->get(route('offline'));

        $response->assertOk();
        $response->assertSee('Pelatihan Tatap Muka di Studio');
        $response->assertSee('KELAS REGULER STUDIO');
        $response->assertSee('KELAS PRIVAT INTENSIF');
        $response->assertSee('Hubungi Admin WhatsApp');
    }

    public function test_ebook_page_can_be_rendered(): void
    {
        $response = $this->get(route('ebook'));

        $response->assertOk();
        $response->assertSee('Dokumen Referensi Gratis');
        $response->assertSee('Standar Kelengkapan Gambar Arsitektur PUPR 2021');
        $response->assertSee('Unduh via Google Drive');
    }

    public function test_faq_page_can_be_rendered(): void
    {
        $response = $this->get(route('faq'));

        $response->assertOk();
        $response->assertSee('Pertanyaan yang Sering Diajukan');
        $response->assertSee('Berapa lama masa aktif akses video setelah membeli kelas online?');
        $response->assertSee('365 hari');
    }

    public function test_preview_video_route_streams_when_file_exists(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('videos/sample-preview.mp4', 'dummy video binary content');

        $kelas = Kelas::factory()->published()->create();
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id]);
        $materi = Materi::factory()->preview()->create([
            'modul_id' => $modul->id,
            'path_video' => 'videos/sample-preview.mp4',
        ]);

        $response = $this->get(route('preview.video', $materi->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'video/mp4');
    }
}
