<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKurikulumController extends Controller
{
    public function index(Kelas $kelas): View
    {
        $kelas->load(['moduls.materis']);
        $quizQuestions = $kelas->isOfflineCatalog()
            ? collect()
            : QuizQuestion::ensureForKelas($kelas);

        return view('admin.kelas.kurikulum', [
            'kelas' => $kelas,
            'quizQuestions' => $quizQuestions,
        ]);
    }

    public function storeQuiz(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:1000'],
            'opsi_a' => ['required', 'string', 'max:255'],
            'opsi_b' => ['required', 'string', 'max:255'],
            'opsi_c' => ['required', 'string', 'max:255'],
            'opsi_d' => ['required', 'string', 'max:255'],
            'jawaban_benar' => ['required', 'string', 'in:A,B,C,D'],
            'nomor_urutan' => ['nullable', 'integer', 'min:1'],
        ]);

        $order = $validated['nomor_urutan'] ?? ($kelas->quizQuestions()->count() + 1);

        $kelas->quizQuestions()->create([
            'nomor_urutan' => $order,
            'pertanyaan' => $validated['pertanyaan'],
            'opsi_a' => $validated['opsi_a'],
            'opsi_b' => $validated['opsi_b'],
            'opsi_c' => $validated['opsi_c'],
            'opsi_d' => $validated['opsi_d'],
            'jawaban_benar' => strtoupper($validated['jawaban_benar']),
        ]);

        return back()->with('success', 'Soal quiz evaluasi baru berhasil ditambahkan.');
    }

    public function destroyQuiz(QuizQuestion $quizQuestion): RedirectResponse
    {
        $quizQuestion->delete();

        return back()->with('success', 'Soal quiz evaluasi berhasil dihapus.');
    }

    public function storeModul(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'judul_modul' => ['required', 'string', 'max:255'],
            'nomor_urutan' => ['nullable', 'integer', 'min:1'],
        ]);

        $order = $validated['nomor_urutan'] ?? ($kelas->moduls()->count() + 1);

        $kelas->moduls()->create([
            'judul_modul' => $validated['judul_modul'],
            'nomor_urutan' => $order,
        ]);

        return back()->with('success', 'Modul baru berhasil ditambahkan ke kelas.');
    }

    public function destroyModul(Modul $modul): RedirectResponse
    {
        $modul->delete();

        return back()->with('success', 'Modul berhasil dihapus.');
    }

    public function storeMateri(Request $request, Modul $modul): RedirectResponse
    {
        $isOffline = $modul->kelas && $modul->kelas->isOfflineCatalog();

        $validated = $request->validate([
            'judul_materi' => ['required', 'string', 'max:255'],
            'path_video' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:102400'],
            'durasi_tampil' => [$isOffline ? 'nullable' : 'required', 'string', 'max:20'],
            'cuplikan_gratis' => ['nullable'],
            'nomor_urutan' => ['nullable', 'integer', 'min:1'],
        ]);

        $videoPath = $validated['path_video'] ?? ($isOffline ? 'tatap-muka-studio' : 'videos/sample-lesson.mp4');
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'local');
        }

        $durasiTampil = ($validated['durasi_tampil'] ?? null) ?: ($isOffline ? 'Tatap Muka' : '10:00');

        // Parse mm:ss to seconds
        $parts = explode(':', $durasiTampil);
        $seconds = 0;
        if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
            $seconds = ((int) $parts[0] * 60) + (int) $parts[1];
        } elseif (count($parts) === 3 && is_numeric($parts[0]) && is_numeric($parts[1]) && is_numeric($parts[2])) {
            $seconds = ((int) $parts[0] * 3600) + ((int) $parts[1] * 60) + (int) $parts[2];
        }

        $order = $validated['nomor_urutan'] ?? ($modul->materis()->count() + 1);

        $modul->materis()->create([
            'judul_materi' => $validated['judul_materi'],
            'path_video' => $videoPath,
            'durasi_tampil' => $durasiTampil,
            'durasi_detik' => $seconds,
            'nomor_urutan' => $order,
            'cuplikan_gratis' => $request->has('cuplikan_gratis'),
        ]);

        return back()->with('success', $isOffline ? 'Pokok bahasan materi berhasil ditambahkan ke modul.' : 'Pelajaran video berhasil ditambahkan ke modul.');
    }

    public function destroyMateri(Materi $materi): RedirectResponse
    {
        $materi->delete();

        return back()->with('success', 'Materi video berhasil dihapus.');
    }
}
