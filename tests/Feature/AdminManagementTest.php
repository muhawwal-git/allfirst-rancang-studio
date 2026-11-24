<?php

namespace Tests\Feature;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\PengaturanSitus;
use App\Models\Sertifikat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard Pengelola Allfirst');
        $response->assertSee('Running Text Header');
    }

    public function test_admin_can_update_running_text(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.running-text.update'), [
            'running_text' => 'PENGUMUMAN BARU DARI ADMIN ◆ DISKON 50%',
            'running_text_aktif' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertSame('PENGUMUMAN BARU DARI ADMIN ◆ DISKON 50%', PengaturanSitus::getValue('running_text'));
        $this->assertSame('1', PengaturanSitus::getValue('running_text_aktif'));
    }

    public function test_admin_can_view_transaction_list(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();

        $transaksi = Transaksi::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.transaksi.index', ['status' => 'menunggu_verifikasi']));

        $response->assertOk();
        $response->assertSee($transaksi->nomor_invoice);
        $response->assertSee($user->name);
    }

    public function test_admin_can_approve_payment_and_grant_365_days_access(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();

        $transaksi = Transaksi::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.transaksi.approve', $transaksi->id));

        $response->assertRedirect();

        $transaksi->refresh();
        $this->assertSame('berhasil', $transaksi->status_pembayaran);
        $this->assertNotNull($transaksi->verified_at);

        $akses = AksesKelas::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($akses);
        $this->assertSame('aktif', $akses->status_akses);
        $this->assertTrue($akses->isAktif());
        $this->assertGreaterThan(360, $akses->sisaHari());
    }

    public function test_admin_can_reject_payment_with_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();

        $transaksi = Transaksi::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.transaksi.reject', $transaksi->id), [
            'alasan_penolakan' => 'Struk transfer tidak dapat terbaca dengan jelas.',
        ]);

        $response->assertRedirect();

        $transaksi->refresh();
        $this->assertSame('ditolak', $transaksi->status_pembayaran);
        $this->assertSame('Struk transfer tidak dapat terbaca dengan jelas.', $transaksi->alasan_penolakan);

        // Student sees rejection reason on invoice detail page and student dashboard
        $this->actingAs($student)
            ->get(route('transaksi.show', $transaksi->nomor_invoice))
            ->assertOk()
            ->assertSee('Alasan Penolakan Admin:')
            ->assertSee('Struk transfer tidak dapat terbaca dengan jelas.');

        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Struk transfer tidak dapat terbaca dengan jelas.');
    }

    public function test_admin_can_create_new_course(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.kelas.store'), [
            'judul_kelas' => 'Tekla Structures: Pemodelan Baja DED',
            'kategori' => 'Revit',
            'harga' => 450000,
            'deskripsi' => 'Panduan komprehensif pemodelan konstruksi baja berstandar fabrikasi.',
            'status_publikasi' => 'published',
            'is_unggulan' => '1',
        ]);

        $response->assertRedirect(route('admin.kelas.index'));

        $this->assertDatabaseHas('kelas', [
            'judul_kelas' => 'Tekla Structures: Pemodelan Baja DED',
            'kategori' => 'Revit',
            'harga' => 450000,
            'status_publikasi' => 'published',
            'is_unggulan' => true,
        ]);
    }

    public function test_admin_can_update_course(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create([
            'judul_kelas' => 'Judul Lama',
            'harga' => 300000,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.kelas.update', $kelas->id), [
            'judul_kelas' => 'Judul Baru yang Diperbarui',
            'kategori' => 'AutoCAD',
            'harga' => 380000,
            'deskripsi' => 'Deskripsi yang telah diedit.',
            'status_publikasi' => 'published',
        ]);

        $response->assertRedirect(route('admin.kelas.index'));

        $kelas->refresh();
        $this->assertSame('Judul Baru yang Diperbarui', $kelas->judul_kelas);
        $this->assertSame(380000, $kelas->harga);
    }

    public function test_admin_can_delete_course(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create();

        $response = $this->actingAs($admin)->delete(route('admin.kelas.destroy', $kelas->id));

        $response->assertRedirect(route('admin.kelas.index'));
        $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
    }

    public function test_admin_can_create_module_and_lesson(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create();

        // 1. Create Module
        $responseModul = $this->actingAs($admin)->post(route('admin.kelas.modul.store', $kelas->id), [
            'judul_modul' => 'Modul 01: Setup Standar Sheet DED',
            'nomor_urutan' => 1,
        ]);
        $responseModul->assertRedirect();

        $modul = Modul::query()->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($modul);
        $this->assertSame('Modul 01: Setup Standar Sheet DED', $modul->judul_modul);

        // 2. Create Lesson inside Module
        $responseMateri = $this->actingAs($admin)->post(route('admin.modul.materi.store', $modul->id), [
            'judul_materi' => '01. Penataan Title Block & Skala',
            'durasi_tampil' => '10:45',
            'path_video' => 'videos/sample.mp4',
            'cuplikan_gratis' => '1',
            'nomor_urutan' => 1,
        ]);
        $responseMateri->assertRedirect();

        $materi = Materi::query()->where('modul_id', $modul->id)->first();
        $this->assertNotNull($materi);
        $this->assertSame('01. Penataan Title Block & Skala', $materi->judul_materi);
        $this->assertSame(645, $materi->durasi_detik);
        $this->assertTrue($materi->cuplikan_gratis);
    }

    public function test_admin_can_update_hero_and_promo_popup_and_running_text_countdown(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Update Hero Beranda
        $this->actingAs($admin)->post(route('admin.hero.update'), [
            'hero_badge' => 'Studio Arsitektur & BIM Terbaik',
            'hero_title' => 'Kuasai AutoCAD, SketchUp & Revit Standar Proyek DED',
            'hero_description' => 'Bimbingan langsung oleh praktisi arsitek konsultan.',
        ])->assertRedirect(route('admin.dashboard'));

        // 2. Update Running Text with automatic countdown target date (7 days from today)
        $targetDate = now()->addDays(7)->format('Y-m-d');
        $this->actingAs($admin)->post(route('admin.running-text.update'), [
            'running_text' => 'Batch Tatap Muka Studio Sedang Berjalan (Sisa {sisa_hari} Hari) ◆ Promo Spesial',
            'running_text_aktif' => '1',
            'running_text_target_date' => $targetDate,
        ])->assertRedirect(route('admin.dashboard'));

        // 3. Update Promo Pop-Up
        $this->actingAs($admin)->post(route('admin.promo.update'), [
            'promo_popup_aktif' => '1',
            'promo_badge' => 'PROMO BUNDEL',
            'promo_judul' => 'All Class Package Studio',
            'promo_subjudul' => 'Semua kelas dalam 1 paket hemat',
            'promo_harga_coret' => 2121000,
            'promo_harga_diskon' => 1089000,
            'promo_label_hemat' => 'Hemat 49%',
            'promo_batas_waktu' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'promo_cta_text' => 'Lihat Paket',
            'promo_cta_url' => '/kelas-online',
        ])->assertRedirect(route('admin.dashboard'));

        // Verify on Homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Kuasai AutoCAD, SketchUp & Revit Standar Proyek DED');
        $homeResponse->assertSee('(Sisa 7 Hari)');
        $homeResponse->assertSee('All Class Package Studio');
    }

    public function test_offline_registration_checkout_and_approval_grants_online_classroom_access(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create(['harga' => 450000]);

        // 1. Student registers for Offline Class via checkout?mode=offline
        $checkoutPage = $this->actingAs($student)->get(route('checkout.create', ['kelas' => $kelas->slug, 'mode' => 'offline']));
        $checkoutPage->assertOk();
        $checkoutPage->assertSee('Checkout Kelas Offline (+ Bonus Akses Online)');

        $storeResponse = $this->actingAs($student)->post(route('checkout.store', $kelas->slug), [
            'mode' => 'offline',
        ]);
        $storeResponse->assertRedirect();

        $transaksi = Transaksi::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($transaksi);
        $this->assertSame('offline', $transaksi->jenis_transaksi);

        // 2. Admin approves Offline payment -> automatically grants 365-day Online Classroom access
        $this->actingAs($admin)->post(route('admin.transaksi.approve', $transaksi->id))->assertRedirect();

        $akses = AksesKelas::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($akses);
        $this->assertTrue($akses->isAktif());
        $this->assertGreaterThan(360, $akses->sisaHari());
    }

    public function test_youtube_unlisted_video_url_is_parsed_correctly(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create();
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id]);

        $this->actingAs($admin)->post(route('admin.modul.materi.store', $modul->id), [
            'judul_materi' => '01. Pengenalan Workspace AutoCAD via YouTube Unlisted',
            'durasi_tampil' => '12:30',
            'path_video' => 'https://youtu.be/VtLXKU1PpRU',
            'cuplikan_gratis' => '1',
            'nomor_urutan' => 1,
        ])->assertRedirect();

        $materi = Materi::query()->where('modul_id', $modul->id)->first();
        $this->assertNotNull($materi);
        $this->assertTrue($materi->is_youtube);
        $this->assertSame('VtLXKU1PpRU', $materi->youtube_id);
        $this->assertStringContainsString('https://www.youtube.com/embed/VtLXKU1PpRU', (string) $materi->youtube_embed_url);
    }

    public function test_admin_can_update_multiple_bank_accounts_and_student_sees_them_on_invoice(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create(['harga' => 350000]);

        $this->actingAs($admin)->post(route('admin.rekening.update'), [
            'whatsapp_studio' => '6289988776655',
            'banks' => [
                [
                    'bank' => 'BCA Prioritas Studio',
                    'nomor_rekening' => '777-888-9990',
                    'atas_nama' => 'Asrul Allfirst Studio',
                ],
                [
                    'bank' => 'Bank Syariah Indonesia (BSI)',
                    'nomor_rekening' => '444-555-6661',
                    'atas_nama' => 'PT Allfirst Rancang Studio',
                ],
                [
                    'bank' => '',
                    'nomor_rekening' => '',
                    'atas_nama' => '',
                ],
            ],
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertCount(2, PengaturanSitus::getBankAccounts());
        $this->assertSame('6289988776655', PengaturanSitus::getAdminWhatsapp());

        $transaksi = Transaksi::query()->create([
            'nomor_invoice' => 'ARS-INV-20261117-TEST1',
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
            'jenis_transaksi' => 'baru',
            'jumlah_pembayaran' => 350000,
            'status_pembayaran' => 'menunggu_pembayaran',
            'batas_waktu_bayar' => now()->addHours(24),
        ]);

        $invoiceResponse = $this->actingAs($student)->get(route('transaksi.show', $transaksi->nomor_invoice));
        $invoiceResponse->assertOk();
        $invoiceResponse->assertSee('BCA Prioritas Studio');
        $invoiceResponse->assertSee('777-888-9990');
        $invoiceResponse->assertSee('Asrul Allfirst Studio');
        $invoiceResponse->assertSee('Bank Syariah Indonesia (BSI)');
        $invoiceResponse->assertSee('444-555-6661');
        $invoiceResponse->assertSee('6289988776655');
    }

    public function test_admin_can_manually_issue_certificate_with_sequential_studio_numbering(): void
    {
        $admin = User::factory()->admin()->create();
        $student1 = User::factory()->student()->create(['name' => 'Siswa Offline Satu']);
        $student2 = User::factory()->student()->create(['name' => 'Siswa Offline Dua']);
        $kelas = Kelas::factory()->published()->create();

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '13');

        $trx1 = Transaksi::query()->create([
            'nomor_invoice' => 'ARS-INV-OFF-001',
            'user_id' => $student1->id,
            'kelas_id' => $kelas->id,
            'jenis_transaksi' => 'offline',
            'jumlah_pembayaran' => 450000,
            'status_pembayaran' => 'berhasil',
            'verified_at' => now(),
        ]);

        $trx2 = Transaksi::query()->create([
            'nomor_invoice' => 'ARS-INV-OFF-002',
            'user_id' => $student2->id,
            'kelas_id' => $kelas->id,
            'jenis_transaksi' => 'offline',
            'jumlah_pembayaran' => 450000,
            'status_pembayaran' => 'berhasil',
            'verified_at' => now(),
        ]);

        // Admin issues certificate for student 1 -> gets 014-ARS{YY}-{MM}/MKS/{YYYY}
        $this->actingAs($admin)->post(route('admin.transaksi.sertifikat', $trx1->id))->assertRedirect();
        $cert1 = Sertifikat::query()->where('user_id', $student1->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($cert1);
        $expected1 = sprintf('014-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y'));
        $this->assertSame($expected1, $cert1->nomor_sertifikat);

        // Admin issues certificate for student 2 -> automatically increments to 015-ARS{YY}-{MM}/MKS/{YYYY}
        $this->actingAs($admin)->post(route('admin.transaksi.sertifikat', $trx2->id))->assertRedirect();
        $cert2 = Sertifikat::query()->where('user_id', $student2->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($cert2);
        $expected2 = sprintf('015-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y'));
        $this->assertSame($expected2, $cert2->nomor_sertifikat);
    }

    public function test_admin_can_manage_siswa_directory_and_issue_certificate_with_rubric_grade(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create(['judul_kelas' => 'SketchUp + V-Ray Studio']);

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '13');

        // 1. Admin manually registers an Offline Studio student from Data Siswa menu
        $storeResponse = $this->actingAs($admin)->post(route('admin.siswa.store'), [
            'name' => 'Andi Pratama, S.Ars',
            'email' => 'andi.offline@student.id',
            'nomor_whatsapp' => '081234567899',
            'kelas_id' => $kelas->id,
            'tipe_kelas' => 'offline',
            'status_profesi' => 'Arsitek Junior',
            'instansi_asal' => 'Universitas Hasanuddin',
        ]);

        $storeResponse->assertRedirect(route('admin.siswa.index'));

        $student = User::query()->where('email', 'andi.offline@student.id')->first();
        $this->assertNotNull($student);
        $this->assertSame('Andi Pratama, S.Ars', $student->name);

        $akses = AksesKelas::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($akses);
        $this->assertTrue($akses->isOffline());
        $this->assertTrue($akses->isAktif());

        // 2. Admin updates certificate sequence number from Data Siswa page (e.g. sets last number to 25)
        $this->actingAs($admin)->post(route('admin.siswa.pengaturan-sertifikat'), [
            'sertifikat_nomor_terakhir' => 25,
            'sertifikat_kode_lokasi' => 'MKS',
        ])->assertRedirect(route('admin.siswa.index'));

        $this->assertSame('25', PengaturanSitus::getValue('sertifikat_nomor_terakhir'));

        // 3. Admin views Data Siswa page with offline filter
        $indexResponse = $this->actingAs($admin)->get(route('admin.siswa.index', ['kategori' => 'offline']));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Andi Pratama, S.Ars');
        $indexResponse->assertSee('SketchUp + V-Ray Studio');
        $indexResponse->assertSee('OFFLINE STUDIO');

        // 4. Admin issues certificate from Data Siswa menu with predikat "Sangat Baik" (95) -> gets 026
        $certResponse = $this->actingAs($admin)->post(route('admin.siswa.sertifikat', $akses->id), [
            'predikat' => 'Sangat Baik',
            'nilai' => 95,
        ]);
        $certResponse->assertRedirect();

        $cert = Sertifikat::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($cert);
        $this->assertSame('Sangat Baik', $cert->predikat);
        $this->assertSame(95, $cert->nilai);
        $this->assertSame(sprintf('026-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y')), $cert->nomor_sertifikat);
    }

    public function test_admin_can_create_offline_class_with_modules_and_render_in_offline_catalog(): void
    {
        $admin = User::factory()->admin()->create();
        $onlineClass = Kelas::factory()->published()->create([
            'judul_kelas' => 'Video Online AutoCAD 2D',
            'jenis_kelas' => 'online',
        ]);

        // 1. Admin creates an Offline Studio Class with module list
        $this->actingAs($admin)->post(route('admin.kelas.store'), [
            'judul_kelas' => 'Bimbingan Privat Tatap Muka Revit BIM Studio',
            'kategori' => 'Revit',
            'jenis_kelas' => 'offline',
            'harga' => 1500000,
            'deskripsi' => 'Program bimbingan teknis tatap muka intensif Revit BIM di studio Makassar.',
            'modul_offline_text' => "Modul 1: Pemodelan Elemen Arsitektur BIM\nModul 2: Koordinasi Struktur & Jadwal BOQ",
            'status_publikasi' => 'published',
        ])->assertRedirect(route('admin.kelas.index'));

        $offlineClass = Kelas::query()->where('jenis_kelas', 'offline')->firstOrFail();
        $this->assertTrue($offlineClass->isOfflineCatalog());
        $this->assertCount(2, $offlineClass->moduls);

        // 2. Admin adds a sub-topic to Modul 1 without needing a YouTube video link
        $firstModul = $offlineClass->moduls()->firstOrFail();
        $this->actingAs($admin)->post(route('admin.modul.materi.store', $firstModul->id), [
            'judul_materi' => 'Praktik Pembuatan Grid, Level, Dinding & Lantai Parameter',
        ])->assertRedirect();

        $this->assertSame(1, $firstModul->materis()->count());

        // 3. Public /kelas-offline shows the offline class & its modules
        $this->get(route('offline'))
            ->assertOk()
            ->assertSee('Bimbingan Privat Tatap Muka Revit BIM Studio')
            ->assertSee('Modul 1: Pemodelan Elemen Arsitektur BIM');

        // 4. Public /kelas-online shows only the online catalog class
        $this->get(route('kelas.index'))
            ->assertOk()
            ->assertSee('Video Online AutoCAD 2D')
            ->assertDontSee('Bimbingan Privat Tatap Muka Revit BIM Studio');

        // 5. Admin sets tautan_software (Google Drive link) on online class and enrolled student sees it in Classroom
        $this->actingAs($admin)->put(route('admin.kelas.update', $onlineClass->id), [
            'judul_kelas' => $onlineClass->judul_kelas,
            'kategori' => $onlineClass->kategori,
            'jenis_kelas' => 'online',
            'harga' => $onlineClass->harga,
            'deskripsi' => $onlineClass->deskripsi,
            'tautan_software' => 'https://drive.google.com/drive/folders/1jAfbtnk5fqRO4u2JrL0VdIvIYANZDzj2',
            'status_publikasi' => 'published',
        ])->assertRedirect(route('admin.kelas.index'));

        $modulOnline = Modul::factory()->create(['kelas_id' => $onlineClass->id, 'nomor_urutan' => 1]);
        $materiOnline = Materi::factory()->create(['modul_id' => $modulOnline->id, 'nomor_urutan' => 1]);
        $student = User::factory()->student()->create();
        AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $onlineClass->id,
            'status_akses' => 'aktif',
        ]);

        $this->actingAs($student)
            ->get(route('student.classroom', [$onlineClass->slug, $materiOnline->id]))
            ->assertOk()
            ->assertSee('Dapatkan Software & Bahan Praktik')
            ->assertSee('https://drive.google.com/drive/folders/1jAfbtnk5fqRO4u2JrL0VdIvIYANZDzj2');
    }
}
