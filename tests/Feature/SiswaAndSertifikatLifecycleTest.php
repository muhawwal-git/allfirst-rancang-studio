<?php

namespace Tests\Feature;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\PengaturanSitus;
use App\Models\QuizQuestion;
use App\Models\Sertifikat;
use App\Models\Testimoni;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaAndSertifikatLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_guest_cannot_access_siswa_and_certificate_admin_endpoints(): void
    {
        $kelas = Kelas::factory()->published()->create();
        $student = User::factory()->student()->create();
        $akses = AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
        ]);

        $this->get(route('admin.siswa.index'))->assertRedirect(route('login'));
        $this->post(route('admin.siswa.store'), [])->assertRedirect(route('login'));
        $this->post(route('admin.siswa.pengaturan-sertifikat'), [])->assertRedirect(route('login'));
        $this->post(route('admin.siswa.sertifikat', $akses->id), [])->assertRedirect(route('login'));
    }

    public function test_student_role_is_forbidden_from_accessing_siswa_and_certificate_admin_endpoints(): void
    {
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $akses = AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
        ]);

        $this->actingAs($student)->get(route('admin.siswa.index'))->assertForbidden();
        $this->actingAs($student)->post(route('admin.siswa.store'), [])->assertForbidden();
        $this->actingAs($student)->post(route('admin.siswa.pengaturan-sertifikat'), [])->assertForbidden();
        $this->actingAs($student)->post(route('admin.siswa.sertifikat', $akses->id), [])->assertForbidden();
    }

    public function test_certificate_view_authorization_blocks_other_students_but_allows_owner_and_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->student()->create(['name' => 'Pemilik Sertifikat']);
        $intruder = User::factory()->student()->create(['name' => 'Siswa Lain']);
        $kelas = Kelas::factory()->published()->create();

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '13');
        $cert = Sertifikat::issueForStudent($owner->id, $kelas->id, 'Sangat Baik', 94);

        // Intruder cannot view owner's certificate (403 Forbidden)
        $this->actingAs($intruder)
            ->get(route('student.sertifikat.show', $cert->nomor_sertifikat))
            ->assertForbidden();

        // Owner can view certificate with slashed studio number
        $this->actingAs($owner)
            ->get(route('student.sertifikat.show', $cert->nomor_sertifikat))
            ->assertOk()
            ->assertSee($cert->nomor_sertifikat)
            ->assertSee('Sangat Baik')
            ->assertSee('RUBRIK PENILAIAN');

        // Admin can view & print any student's certificate
        $this->actingAs($admin)
            ->get(route('student.sertifikat.show', $cert->nomor_sertifikat))
            ->assertOk()
            ->assertSee('Pemilik Sertifikat');
    }

    public function test_manual_student_registration_and_certificate_endpoints_validate_inputs(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create();
        $student = User::factory()->student()->create();
        $akses = AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
        ]);

        // 1. Missing required fields & invalid tipe_kelas on store
        $this->actingAs($admin)->post(route('admin.siswa.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'nomor_whatsapp' => '',
            'kelas_id' => 99999,
            'tipe_kelas' => 'invalid_mode',
        ])->assertSessionHasErrors(['name', 'email', 'nomor_whatsapp', 'kelas_id', 'tipe_kelas']);

        // 2. Negative certificate number on pengaturan-sertifikat
        $this->actingAs($admin)->post(route('admin.siswa.pengaturan-sertifikat'), [
            'sertifikat_nomor_terakhir' => -10,
        ])->assertSessionHasErrors(['sertifikat_nomor_terakhir']);

        // 3. Invalid predikat & out-of-bounds nilai (< 60 or > 100) on issueSertifikat
        $this->actingAs($admin)->post(route('admin.siswa.sertifikat', $akses->id), [
            'predikat' => 'Predikat Tidak Valid',
            'nilai' => 45,
        ])->assertSessionHasErrors(['predikat', 'nilai']);
    }

    public function test_siswa_directory_filters_by_kategori_and_searches_accurately(): void
    {
        $admin = User::factory()->admin()->create();
        $kelasCad = Kelas::factory()->published()->create(['judul_kelas' => 'AutoCAD 2D/3D Drafter']);
        $kelasRevit = Kelas::factory()->published()->create(['judul_kelas' => 'Revit Architecture BIM']);

        $offlineUser = User::factory()->student()->create([
            'name' => 'Fadel Offline Makassar',
            'email' => 'fadel@offline.id',
            'nomor_whatsapp' => '081144455566',
        ]);
        $onlineUser = User::factory()->student()->create([
            'name' => 'Nadia Online Jakarta',
            'email' => 'nadia@online.id',
            'nomor_whatsapp' => '081277788899',
        ]);

        AksesKelas::factory()->create([
            'user_id' => $offlineUser->id,
            'kelas_id' => $kelasCad->id,
            'tipe_kelas' => 'offline',
            'status_akses' => 'aktif',
        ]);

        AksesKelas::factory()->create([
            'user_id' => $onlineUser->id,
            'kelas_id' => $kelasRevit->id,
            'tipe_kelas' => 'online',
            'status_akses' => 'aktif',
        ]);

        Sertifikat::issueForStudent($offlineUser->id, $kelasCad->id, 'Baik', 80);

        // Filter: offline only
        $this->actingAs($admin)
            ->get(route('admin.siswa.index', ['kategori' => 'offline']))
            ->assertOk()
            ->assertSee('Fadel Offline Makassar')
            ->assertSee('Kelola Akses')
            ->assertDontSee('Nadia Online Jakarta');

        // Filter: online only
        $this->actingAs($admin)
            ->get(route('admin.siswa.index', ['kategori' => 'online']))
            ->assertOk()
            ->assertSee('Nadia Online Jakarta')
            ->assertDontSee('Kelola Akses')
            ->assertDontSee('Fadel Offline Makassar');

        // Filter: bersertifikat only
        $this->actingAs($admin)
            ->get(route('admin.siswa.index', ['kategori' => 'bersertifikat']))
            ->assertOk()
            ->assertSee('Fadel Offline Makassar')
            ->assertDontSee('Nadia Online Jakarta');

        // Search by WhatsApp number
        $this->actingAs($admin)
            ->get(route('admin.siswa.index', ['search' => '081277788899']))
            ->assertOk()
            ->assertSee('Nadia Online Jakarta')
            ->assertDontSee('Fadel Offline Makassar');
    }

    public function test_end_to_end_offline_student_registration_login_and_certificate_flow(): void
    {
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create(['judul_kelas' => 'SketchUp Pro & V-Ray Studio']);

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '13');
        PengaturanSitus::setValue('sertifikat_kode_lokasi', 'MKS');

        // 1. Admin registers offline student manually
        $this->actingAs($admin)->post(route('admin.siswa.store'), [
            'name' => 'Ir. Hendra Wijaya, S.T.',
            'email' => 'hendra@studio.id',
            'nomor_whatsapp' => '085299887766',
            'kelas_id' => $kelas->id,
            'tipe_kelas' => 'offline',
            'status_profesi' => 'Kontraktor Pelaksana',
            'instansi_asal' => 'CV Karya Konstruksi Makassar',
        ])->assertRedirect(route('admin.siswa.index'));

        $student = User::query()->where('email', 'hendra@studio.id')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $student->password));

        $akses = AksesKelas::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->firstOrFail();
        $this->assertTrue($akses->isOffline());
        $this->assertTrue($akses->isAktif());

        // 2. Admin issues certificate for offline student (even with 0% online video progress)
        $this->actingAs($admin)->post(route('admin.siswa.sertifikat', $akses->id), [
            'predikat' => 'Sangat Baik',
            'nilai' => 96,
        ])->assertRedirect();

        $expectedNomor = sprintf('014-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y'));
        $cert = Sertifikat::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->firstOrFail();
        $this->assertSame($expectedNomor, $cert->nomor_sertifikat);
        $this->assertSame('Sangat Baik', $cert->predikat);
        $this->assertSame(96, $cert->nilai);

        // 3. Offline student logs into Student Dashboard and sees issued certificate
        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Sertifikat Terbit')
            ->assertSee('Lihat Sertifikat');

        // 4. Public verification page shows valid status, rubric grade, and score
        $this->get(route('sertifikat.verifikasi', $cert->hash_kode_qr))
            ->assertOk()
            ->assertSee('Ir. Hendra Wijaya, S.T.')
            ->assertSee($expectedNomor)
            ->assertSee('Sangat Baik (Skor: 96)');
    }

    public function test_updating_certificate_rubric_grade_is_idempotent_and_preserves_certificate_number(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $akses = AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
            'tipe_kelas' => 'offline',
        ]);

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '20');

        // First issue: Cukup (68) -> gets 021
        $this->actingAs($admin)->post(route('admin.siswa.sertifikat', $akses->id), [
            'predikat' => 'Cukup',
            'nilai' => 68,
        ])->assertRedirect();

        $cert = Sertifikat::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->firstOrFail();
        $originalNomor = $cert->nomor_sertifikat;
        $this->assertSame(sprintf('021-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y')), $originalNomor);
        $this->assertSame('Cukup', $cert->predikat);
        $this->assertSame(68, $cert->nilai);

        // Admin updates grade to Baik (80) -> keeps 021, does NOT increment sequence to 022
        $this->actingAs($admin)->post(route('admin.siswa.sertifikat', $akses->id), [
            'predikat' => 'Baik',
            'nilai' => 80,
        ])->assertRedirect();

        $this->assertSame(1, Sertifikat::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->count());
        $cert->refresh();
        $this->assertSame($originalNomor, $cert->nomor_sertifikat);
        $this->assertSame('Baik', $cert->predikat);
        $this->assertSame(80, $cert->nilai);
        $this->assertSame('21', PengaturanSitus::getValue('sertifikat_nomor_terakhir'));
    }

    public function test_end_to_end_online_student_auto_certificate_generation_upon_100_percent_completion(): void
    {
        $student = User::factory()->student()->create(['name' => 'Putri Maharani, S.Ars']);
        $admin = User::factory()->admin()->create();
        $kelas = Kelas::factory()->published()->create(['kategori' => 'Revit']);
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id, 'nomor_urutan' => 1]);
        $m1 = Materi::factory()->create(['modul_id' => $modul->id, 'nomor_urutan' => 1]);
        $m2 = Materi::factory()->create(['modul_id' => $modul->id, 'nomor_urutan' => 2]);

        AksesKelas::factory()->create([
            'user_id' => $student->id,
            'kelas_id' => $kelas->id,
            'tipe_kelas' => 'online',
            'status_akses' => 'aktif',
        ]);

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', '30');

        // 1. Completing 1st of 2 lessons (50%) -> quiz locked & certificate not yet issued
        $this->actingAs($student)->post(route('student.materi.complete', $m1->id))->assertRedirect();
        $this->actingAs($student)->get(route('student.quiz.show', $kelas->slug))->assertRedirect(route('student.classroom', $kelas->slug));
        $this->assertDatabaseMissing('sertifikats', ['user_id' => $student->id, 'kelas_id' => $kelas->id]);

        // 2. Completing 2nd of 2 lessons (100%) -> unlocks quiz, certificate still gated behind quiz & testimonial
        $this->actingAs($student)->post(route('student.materi.complete', $m2->id))->assertRedirect();
        $this->assertDatabaseMissing('sertifikats', ['user_id' => $student->id, 'kelas_id' => $kelas->id]);

        // 3. Failing quiz (< 70) does not unlock testimonial/certificate
        $questions = QuizQuestion::ensureForKelas($kelas);
        $this->assertCount(10, $questions);

        $wrongAnswers = $questions->mapWithKeys(fn ($q) => [$q->id => ($q->jawaban_benar === 'A' ? 'D' : 'A')])->all();
        $this->actingAs($student)
            ->post(route('student.quiz.submit', $kelas->slug), ['answers' => $wrongAnswers])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));

        $this->actingAs($student)
            ->post(route('student.quiz.testimoni', $kelas->slug), [
                'rating' => 5,
                'profesi' => 'Junior Architect',
                'ulasan' => 'Mencoba klaim sebelum lulus quiz.',
            ])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));
        $this->assertDatabaseMissing('sertifikats', ['user_id' => $student->id, 'kelas_id' => $kelas->id]);

        // 4. Passing quiz with 80/100 (8 correct out of 10)
        $eightCorrectAnswers = $questions->values()->mapWithKeys(function ($q, $idx) {
            return [$q->id => $idx < 8 ? $q->jawaban_benar : ($q->jawaban_benar === 'A' ? 'D' : 'A')];
        })->all();

        $this->actingAs($student)
            ->post(route('student.quiz.submit', $kelas->slug), ['answers' => $eightCorrectAnswers])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));

        // 5. Submitting rating & testimonial issues certificate 031-ARS... with score 80 ('Baik')
        $this->actingAs($student)
            ->post(route('student.quiz.testimoni', $kelas->slug), [
                'rating' => 5,
                'profesi' => 'Arsitek Muda',
                'ulasan' => 'Alur pemodelan BIM Revit di Allfirst Studio sangat aplikatif dan mudah diikuti!',
            ])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));

        $cert = Sertifikat::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->first();
        $this->assertNotNull($cert);
        $this->assertSame(sprintf('031-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y')), $cert->nomor_sertifikat);
        $this->assertSame('Baik', $cert->predikat);
        $this->assertSame(80, $cert->nilai);

        // 6. Admin moderates testimonial to feature on Homepage
        $testimoni = Testimoni::query()->where('user_id', $student->id)->where('kelas_id', $kelas->id)->firstOrFail();
        $this->assertFalse($testimoni->tampil_beranda);

        $this->actingAs($admin)
            ->post(route('admin.testimoni.toggle', $testimoni->id))
            ->assertRedirect();

        $this->assertTrue($testimoni->fresh()->tampil_beranda);
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Alur pemodelan BIM Revit di Allfirst Studio sangat aplikatif dan mudah diikuti!');
    }

    public function test_admin_can_control_bonus_online_class_access_for_offline_students_on_registration_approval_and_directory(): void
    {
        $admin = User::factory()->admin()->create();
        $kelasCad = Kelas::factory()->published()->create(['judul_kelas' => 'AutoCAD Studio']);
        $kelasSketchup = Kelas::factory()->published()->create(['judul_kelas' => 'SketchUp Studio']);
        $kelasRevit = Kelas::factory()->published()->create(['judul_kelas' => 'Revit BIM']);

        // 1. Manual offline registration with specific bonus online classes (AutoCAD + SketchUp, NOT Revit)
        $this->actingAs($admin)->post(route('admin.siswa.store'), [
            'name' => 'Rizky Offline Custom',
            'email' => 'rizky@studio.id',
            'nomor_whatsapp' => '081355566677',
            'kelas_id' => $kelasCad->id,
            'tipe_kelas' => 'offline',
            'has_bonus_selection' => 1,
            'bonus_kelas_ids' => [$kelasCad->id, $kelasSketchup->id],
        ])->assertRedirect(route('admin.siswa.index'));

        $rizky = User::query()->where('email', 'rizky@studio.id')->firstOrFail();
        $this->assertTrue($rizky->hasActiveAccessTo($kelasCad));
        $this->assertTrue($rizky->hasActiveAccessTo($kelasSketchup));
        $this->assertFalse($rizky->hasActiveAccessTo($kelasRevit));

        // 2. Admin uses "Kelola Akses" modal endpoint to restrict Rizky to ONLY AutoCAD (revoking SketchUp)
        $this->actingAs($admin)->post(route('admin.siswa.akses', $rizky->id), [
            'akses_kelas_ids' => [$kelasCad->id],
        ])->assertRedirect();

        $rizky->refresh();
        $this->assertTrue($rizky->hasActiveAccessTo($kelasCad));
        $this->assertFalse($rizky->hasActiveAccessTo($kelasSketchup));
        $this->assertFalse($rizky->hasActiveAccessTo($kelasRevit));

        // 3. Offline transaction approval via website checkout with Admin choosing bonus online access
        $webStudent = User::factory()->student()->create(['email' => 'webstudent@studio.id']);
        $offlineTrx = Transaksi::factory()->create([
            'user_id' => $webStudent->id,
            'kelas_id' => $kelasCad->id,
            'jenis_transaksi' => 'offline',
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $this->actingAs($admin)->post(route('admin.transaksi.approve', $offlineTrx->id), [
            'has_bonus_selection' => 1,
            'bonus_kelas_ids' => [$kelasCad->id, $kelasRevit->id],
        ])->assertRedirect();

        $this->assertTrue($webStudent->hasActiveAccessTo($kelasCad));
        $this->assertTrue($webStudent->hasActiveAccessTo($kelasRevit));
        $this->assertFalse($webStudent->hasActiveAccessTo($kelasSketchup));
    }
}
