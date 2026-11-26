<?php

namespace Tests\Feature;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\QuizQuestion;
use App\Models\Sertifikat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutAndStudentClassroomTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_checkout(): void
    {
        $kelas = Kelas::factory()->published()->create();

        $response = $this->get(route('checkout.create', $kelas->slug));

        $response->assertRedirect(route('login'));
    }

    public function test_student_can_view_checkout_page(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create([
            'harga' => 450000,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.create', $kelas->slug));

        $response->assertOk();
        $response->assertSee($kelas->judul_kelas);
        $response->assertSee($user->name);
        $response->assertSee('450.000');
        $response->assertSee('Ringkasan Pembayaran');
    }

    public function test_student_can_submit_checkout_and_generate_transaction(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create([
            'harga' => 400000,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store', $kelas->slug));

        $this->assertDatabaseHas('transaksis', [
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'jumlah_pembayaran' => 400000,
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        $transaksi = Transaksi::first();
        $response->assertRedirect(route('transaksi.show', $transaksi->nomor_invoice));
    }

    public function test_student_can_view_payment_instruction_page(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create(['harga' => 350000]);
        $transaksi = Transaksi::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'jumlah_pembayaran' => 350000,
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        $response = $this->actingAs($user)->get(route('transaksi.show', $transaksi->nomor_invoice));

        $response->assertOk();
        $response->assertSee($transaksi->nomor_invoice);
        $response->assertSee('350.000');
        $response->assertSee('Pilihan Rekening Resmi Studio');
        $response->assertSee('Konfirmasi Cepat via WhatsApp');
    }

    public function test_student_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $transaksi = Transaksi::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        $file = UploadedFile::fake()->create('struk-transfer.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('transaksi.upload', $transaksi->nomor_invoice), [
            'bukti_transfer' => $file,
        ]);

        $response->assertRedirect(route('transaksi.show', $transaksi->nomor_invoice));

        $transaksi->refresh();
        $this->assertSame('menunggu_verifikasi', $transaksi->status_pembayaran);
        $this->assertNotNull($transaksi->bukti_pembayaran);
        Storage::disk('public')->assertExists($transaksi->bukti_pembayaran);

        // PDF format must be rejected (only JPG/PNG allowed)
        $pdfFile = UploadedFile::fake()->create('struk-transfer.pdf', 500, 'application/pdf');
        $this->actingAs($user)
            ->post(route('transaksi.upload', $transaksi->nomor_invoice), [
                'bukti_transfer' => $pdfFile,
            ])
            ->assertSessionHasErrors('bukti_transfer');
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();

        AksesKelas::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'tanggal_mulai' => now(),
            'tanggal_kedaluwarsa' => now()->addDays(365),
            'status_akses' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get(route('student.dashboard'));

        $response->assertOk();
        $response->assertSee('Area Belajar Siswa');
        $response->assertSee($user->name);
        $response->assertSee($kelas->judul_kelas);
        $response->assertSee('Masuk Ruang Belajar');
    }

    public function test_student_without_active_access_cannot_enter_classroom(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();

        $response = $this->actingAs($user)->get(route('student.classroom', $kelas->slug));

        $response->assertRedirect(route('checkout.create', $kelas->slug));
    }

    public function test_student_with_active_access_can_enter_classroom(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id, 'nomor_urutan' => 1]);
        $materi = Materi::factory()->create(['modul_id' => $modul->id, 'nomor_urutan' => 1]);

        AksesKelas::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'tanggal_mulai' => now(),
            'tanggal_kedaluwarsa' => now()->addDays(365),
            'status_akses' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get(route('student.classroom', $kelas->slug));

        $response->assertOk();
        $response->assertSee($kelas->judul_kelas);
        $response->assertSee($materi->judul_materi);
        $response->assertSee('Smart Resume Aktif');
    }

    public function test_smart_resume_saves_progress_seconds(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id]);
        $materi = Materi::factory()->create(['modul_id' => $modul->id]);

        AksesKelas::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'status_akses' => 'aktif',
        ]);

        $response = $this->actingAs($user)->postJson(route('student.materi.progress', $materi->id), [
            'seconds' => 145,
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'success', 'saved_seconds' => 145]);

        $this->assertDatabaseHas('progres_materis', [
            'user_id' => $user->id,
            'materi_id' => $materi->id,
            'detik_terakhir_ditonton' => 145,
        ]);
    }

    public function test_marking_all_lessons_complete_unlocks_quiz_and_testimonial_before_certificate_generation(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create(['kategori' => 'AutoCAD']);
        $modul = Modul::factory()->create(['kelas_id' => $kelas->id]);
        $materi = Materi::factory()->create(['modul_id' => $modul->id]);

        AksesKelas::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'status_akses' => 'aktif',
        ]);

        // 1. Completing 100% video lessons unlocks the quiz (certificate is not yet issued)
        $response = $this->actingAs($user)->post(route('student.materi.complete', $materi->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('sertifikats', [
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
        ]);

        // 2. Opening the Quiz Arena seeds 10 technical questions for the course
        $quizPage = $this->actingAs($user)->get(route('student.quiz.show', $kelas->slug));
        $quizPage->assertOk();
        $quizPage->assertSee('SOAL 01 DARI 10');

        $questions = QuizQuestion::ensureForKelas($kelas);
        $this->assertCount(10, $questions);

        // 3. Submitting correct answers passes the quiz
        $correctAnswers = $questions->mapWithKeys(fn ($q) => [$q->id => $q->jawaban_benar])->all();
        $this->actingAs($user)
            ->post(route('student.quiz.submit', $kelas->slug), ['answers' => $correctAnswers])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'skor' => 100,
            'lulus' => true,
        ]);

        // 4. Submitting rating & testimonial auto-issues the official certificate
        $this->actingAs($user)
            ->post(route('student.quiz.testimoni', $kelas->slug), [
                'rating' => 5,
                'profesi' => 'Drafter Arsitektur',
                'ulasan' => 'Materi AutoCAD sangat terstruktur dan langsung siap pakai untuk proyek DED.',
            ])
            ->assertRedirect(route('student.quiz.show', $kelas->slug));

        $this->assertDatabaseHas('sertifikats', [
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'nilai' => 100,
            'predikat' => 'Sangat Baik',
        ]);
    }

    public function test_student_can_view_certificate_and_qr_code(): void
    {
        $user = User::factory()->student()->create();
        $kelas = Kelas::factory()->published()->create();
        $sertifikat = Sertifikat::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'nomor_sertifikat' => 'ARS/2026/AUTOCAD-00042',
            'hash_kode_qr' => 'abc-valid-qr-hash-1234',
        ]);

        $response = $this->actingAs($user)->get(route('student.sertifikat.show', $sertifikat->nomor_sertifikat));

        $response->assertOk();
        $response->assertSee('SERTIFIKAT KELULUSAN RESMI');
        $response->assertSee($user->name);
        $response->assertSee('ARS/2026/AUTOCAD-00042');
        $response->assertSee('Ir. Allfirst Rancang, IAI');
    }

    public function test_public_can_verify_authentic_certificate_via_qr_hash(): void
    {
        $user = User::factory()->student()->create(['name' => 'Ahmad Faisal, S.T.']);
        $kelas = Kelas::factory()->published()->create(['judul_kelas' => 'Revit Architecture BIM Fundamental']);
        $sertifikat = Sertifikat::factory()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'nomor_sertifikat' => 'ARS/2026/REVIT-00099',
            'hash_kode_qr' => 'valid-qr-hash-code-99',
        ]);

        // Unauthenticated guest verification
        $response = $this->get(route('sertifikat.verifikasi', 'valid-qr-hash-code-99'));

        $response->assertOk();
        $response->assertSee('STATUS VALID & RESMI', false);
        $response->assertSee('Ahmad Faisal, S.T.');
        $response->assertSee('Revit Architecture BIM Fundamental');
        $response->assertSee('ARS/2026/REVIT-00099');
    }

    public function test_public_verification_shows_not_found_for_invalid_hash(): void
    {
        $response = $this->get(route('sertifikat.verifikasi', 'invalid-non-existent-hash'));

        $response->assertOk();
        $response->assertSee('Sertifikat Tidak Ditemukan');
        $response->assertSee('invalid-non-existent-hash');
    }
}
