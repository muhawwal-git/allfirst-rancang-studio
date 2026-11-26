<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\ProgresMateri;
use App\Models\Sertifikat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClassroomController extends Controller
{
    public function show(Request $request, Kelas $kelas, ?Materi $materi = null): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('checkout.create', $kelas->slug)
                ->with('error', 'Akses kelas ini belum aktif atau telah kedaluwarsa. Silakan lakukan pembayaran atau perpanjangan akses.');
        }

        $kelas->load(['moduls.materis']);
        $allMateris = $kelas->moduls->flatMap->materis->values();

        if ($allMateris->isEmpty()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Materi untuk kelas ini sedang dipersiapkan oleh instruktur.');
        }

        // Determine current lesson to display
        $currentMateri = $materi;
        if (! $currentMateri || ! $allMateris->contains('id', $currentMateri->id)) {
            // Find first incomplete lesson, or default to first lesson
            $completedIds = ProgresMateri::query()
                ->where('user_id', $user->id)
                ->whereIn('materi_id', $allMateris->pluck('id'))
                ->where('sudah_selesai', true)
                ->pluck('materi_id')
                ->all();

            $currentMateri = $allMateris->first(fn ($m) => ! in_array($m->id, $completedIds, true))
                ?? $allMateris->first();
        }

        // Retrieve or create progress record for current lesson
        $progres = ProgresMateri::firstOrCreate(
            ['user_id' => $user->id, 'materi_id' => $currentMateri->id],
            ['detik_terakhir_ditonton' => 0, 'sudah_selesai' => false]
        );

        $completedMateriIds = ProgresMateri::query()
            ->where('user_id', $user->id)
            ->whereIn('materi_id', $allMateris->pluck('id'))
            ->where('sudah_selesai', true)
            ->pluck('materi_id')
            ->toArray();

        $currentIndex = $allMateris->search(fn ($m) => $m->id === $currentMateri->id);
        $prevMateri = $currentIndex > 0 ? $allMateris->get($currentIndex - 1) : null;
        $nextMateri = ($currentIndex !== false && $currentIndex < $allMateris->count() - 1)
            ? $allMateris->get($currentIndex + 1)
            : null;

        $progressPercentage = $kelas->progressPercentageFor($user);
        $sertifikat = Sertifikat::query()
            ->where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->first();
        $hasPassedQuiz = $kelas->hasPassedQuizFor($user);
        $bestQuizAttempt = $kelas->bestQuizAttemptFor($user);
        $testimoni = $kelas->testimoniFor($user);

        return view('student.classroom', [
            'kelas' => $kelas,
            'currentMateri' => $currentMateri,
            'progres' => $progres,
            'completedMateriIds' => $completedMateriIds,
            'prevMateri' => $prevMateri,
            'nextMateri' => $nextMateri,
            'progressPercentage' => $progressPercentage,
            'sertifikat' => $sertifikat,
            'hasPassedQuiz' => $hasPassedQuiz,
            'bestQuizAttempt' => $bestQuizAttempt,
            'testimoni' => $testimoni,
            'allMateris' => $allMateris,
        ]);
    }

    public function stream(Request $request, Kelas $kelas, Materi $materi): BinaryFileResponse
    {
        $user = $request->user();
        abort_unless($user->canAccessCourse($kelas), 403);
        abort_unless($materi->modul && $materi->modul->kelas_id === $kelas->id, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($materi->path_video), 404, 'Berkas video tidak ditemukan di server.');

        return response()->file($disk->path($materi->path_video), [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function saveProgress(Request $request, Materi $materi): JsonResponse
    {
        $user = $request->user();
        $seconds = (int) $request->input('seconds', 0);

        ProgresMateri::updateOrCreate(
            ['user_id' => $user->id, 'materi_id' => $materi->id],
            ['detik_terakhir_ditonton' => max(0, $seconds)]
        );

        return response()->json(['status' => 'success', 'saved_seconds' => $seconds]);
    }

    public function markComplete(Request $request, Materi $materi): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        ProgresMateri::updateOrCreate(
            ['user_id' => $user->id, 'materi_id' => $materi->id],
            ['sudah_selesai' => true]
        );

        $kelas = $materi->modul->kelas;
        $totalMateris = $kelas->materis()->count();
        $completedCount = ProgresMateri::query()
            ->where('user_id', $user->id)
            ->whereIn('materi_id', $kelas->materis()->pluck('materis.id'))
            ->where('sudah_selesai', true)
            ->count();

        $isAllCompleted = $totalMateris > 0 && $completedCount >= $totalMateris;

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'is_completed' => true,
                'progress' => $kelas->progressPercentageFor($user),
            ]);
        }

        if ($isAllCompleted) {
            return back()->with('status', 'Seluruh materi video telah selesai (100%)! Silakan kerjakan Quiz Evaluasi Akhir untuk mengklaim sertifikat kelulusan Anda.');
        }

        return back()->with('status', 'Pelajaran berhasil diselesaikan!');
    }
}
