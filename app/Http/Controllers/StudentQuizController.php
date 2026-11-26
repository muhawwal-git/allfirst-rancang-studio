<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Sertifikat;
use App\Models\Testimoni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentQuizController extends Controller
{
    public function show(Request $request, Kelas $kelas): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('checkout.create', $kelas->slug)
                ->with('error', 'Akses kelas ini belum aktif atau telah kedaluwarsa.');
        }

        if ($kelas->progressPercentageFor($user) < 100) {
            return redirect()->route('student.classroom', $kelas->slug)
                ->with('error', 'Selesaikan 100% materi video terlebih dahulu sebelum mengikuti Quiz Evaluasi Akhir.');
        }

        $questions = QuizQuestion::ensureForKelas($kelas);
        $latestAttempt = $kelas->latestQuizAttemptFor($user);
        $bestAttempt = $kelas->bestQuizAttemptFor($user);
        $hasPassedQuiz = $kelas->hasPassedQuizFor($user);
        $testimoni = $kelas->testimoniFor($user);
        $sertifikat = Sertifikat::query()
            ->where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->first();

        return view('student.quiz', [
            'kelas' => $kelas,
            'questions' => $questions,
            'passingGrade' => $kelas->passingGrade(),
            'latestAttempt' => $latestAttempt,
            'bestAttempt' => $bestAttempt,
            'hasPassedQuiz' => $hasPassedQuiz,
            'testimoni' => $testimoni,
            'sertifikat' => $sertifikat,
            'user' => $user,
        ]);
    }

    public function submitQuiz(Request $request, Kelas $kelas): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('checkout.create', $kelas->slug)
                ->with('error', 'Akses kelas ini belum aktif atau telah kedaluwarsa.');
        }

        if ($kelas->progressPercentageFor($user) < 100) {
            return redirect()->route('student.classroom', $kelas->slug)
                ->with('error', 'Selesaikan 100% materi video terlebih dahulu sebelum mengikuti Quiz Evaluasi Akhir.');
        }

        $questions = QuizQuestion::ensureForKelas($kelas);

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'string', 'in:A,B,C,D,a,b,c,d'],
        ], [
            'answers.required' => 'Silakan pilih jawaban untuk soal evaluasi sebelum mengirim.',
        ]);

        $submittedAnswers = $validated['answers'];
        $jumlahBenar = 0;
        $normalizedAnswers = [];

        foreach ($questions as $question) {
            $chosen = strtoupper((string) ($submittedAnswers[$question->id] ?? ''));
            $normalizedAnswers[$question->id] = $chosen;

            if ($chosen !== '' && $chosen === strtoupper($question->jawaban_benar)) {
                $jumlahBenar++;
            }
        }

        $totalSoal = max(1, $questions->count());
        $skor = (int) round(($jumlahBenar / $totalSoal) * 100);
        $lulus = $skor >= $kelas->passingGrade();

        QuizAttempt::query()->create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'skor' => $skor,
            'jumlah_benar' => $jumlahBenar,
            'total_soal' => $totalSoal,
            'lulus' => $lulus,
            'jawaban_siswa' => $normalizedAnswers,
        ]);

        // If certificate was already issued and the student achieved a higher passing score, sync grade
        $existingCert = Sertifikat::query()
            ->where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->first();

        if ($existingCert && $lulus && $skor > $existingCert->nilai) {
            $predikat = $skor >= 90 ? 'Sangat Baik' : 'Baik';
            Sertifikat::issueForStudent($user->id, $kelas->id, $predikat, $skor);
        }

        if ($lulus) {
            return redirect()->route('student.quiz.show', $kelas->slug)
                ->with('status', "Selamat! Anda lulus Quiz Evaluasi Akhir dengan skor {$skor}/100. Silakan lengkapi rating & testimoni di bawah untuk menerbitkan sertifikat resmi.");
        }

        return redirect()->route('student.quiz.show', $kelas->slug)
            ->with('error', "Skor evaluasi Anda {$skor}/100 ({$jumlahBenar} dari {$totalSoal} soal benar) belum mencapai batas kelulusan minimal ({$kelas->passingGrade()}/100). Silakan pelajari kembali dan ulangi quiz.");
    }

    public function submitTestimoni(Request $request, Kelas $kelas): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('checkout.create', $kelas->slug)
                ->with('error', 'Akses kelas ini belum aktif atau telah kedaluwarsa.');
        }

        if ($kelas->progressPercentageFor($user) < 100 || ! $kelas->hasPassedQuizFor($user)) {
            return redirect()->route('student.quiz.show', $kelas->slug)
                ->with('error', 'Anda wajib menyelesaikan 100% materi video dan lulus Quiz Evaluasi Akhir terlebih dahulu sebelum mengklaim sertifikat.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profesi' => ['nullable', 'string', 'max:100'],
            'ulasan' => ['required', 'string', 'min:10', 'max:600'],
        ], [
            'rating.required' => 'Pilih penilaian bintang (1 hingga 5).',
            'ulasan.required' => 'Tuliskan kesan dan ulasan pengalaman belajar Anda.',
            'ulasan.min' => 'Ulasan testimoni minimal terdiri dari 10 karakter.',
        ]);

        $profesi = trim((string) ($validated['profesi'] ?? '')) ?: ($user->status_profesi ?: 'Alumni Studio');

        Testimoni::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
            ],
            [
                'rating' => (int) $validated['rating'],
                'profesi' => $profesi,
                'ulasan' => trim($validated['ulasan']),
            ]
        );

        $bestAttempt = $kelas->bestQuizAttemptFor($user);
        $bestScore = $bestAttempt ? $bestAttempt->skor : 80;
        $predikat = $bestScore >= 90 ? 'Sangat Baik' : 'Baik';

        Sertifikat::issueForStudent($user->id, $kelas->id, $predikat, $bestScore);

        return redirect()->route('student.quiz.show', $kelas->slug)
            ->with('status', 'Terima kasih atas ulasan Anda! Sertifikat kelulusan resmi Anda telah diterbitkan dan siap diunduh.');
    }
}
