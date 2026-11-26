<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuizController extends Controller
{
    public function index(Request $request, ?Kelas $kelas = null): View
    {
        $onlineCourses = Kelas::query()
            ->onlineCatalog()
            ->orderBy('id')
            ->get();

        if ($onlineCourses->isEmpty()) {
            $onlineCourses = Kelas::query()->orderBy('id')->get();
        }

        $selectedKelas = ($kelas && $kelas->exists)
            ? $kelas
            : null;

        $quizQuestions = $selectedKelas
            ? QuizQuestion::ensureForKelas($selectedKelas)
            : collect();

        $questionCounts = QuizQuestion::query()
            ->selectRaw('kelas_id, count(*) as total')
            ->groupBy('kelas_id')
            ->pluck('total', 'kelas_id');

        return view('admin.quiz.index', [
            'onlineCourses' => $onlineCourses,
            'selectedKelas' => $selectedKelas,
            'quizQuestions' => $quizQuestions,
            'questionCounts' => $questionCounts,
        ]);
    }

    public function store(Request $request, Kelas $kelas): RedirectResponse
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

        return redirect()->route('admin.quiz.index', $kelas->id)
            ->with('success', 'Soal quiz evaluasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, QuizQuestion $quizQuestion): RedirectResponse
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

        $quizQuestion->update([
            'nomor_urutan' => $validated['nomor_urutan'] ?? $quizQuestion->nomor_urutan,
            'pertanyaan' => $validated['pertanyaan'],
            'opsi_a' => $validated['opsi_a'],
            'opsi_b' => $validated['opsi_b'],
            'opsi_c' => $validated['opsi_c'],
            'opsi_d' => $validated['opsi_d'],
            'jawaban_benar' => strtoupper($validated['jawaban_benar']),
        ]);

        return redirect()->route('admin.quiz.index', $quizQuestion->kelas_id)
            ->with('success', 'Soal quiz evaluasi berhasil diperbarui.');
    }

    public function destroy(QuizQuestion $quizQuestion): RedirectResponse
    {
        $kelasId = $quizQuestion->kelas_id;
        $quizQuestion->delete();

        return redirect()->route('admin.quiz.index', $kelasId)
            ->with('success', 'Soal quiz evaluasi berhasil dihapus.');
    }

    public function resetDefault(Kelas $kelas): RedirectResponse
    {
        $kelas->quizQuestions()->delete();
        QuizQuestion::ensureForKelas($kelas);

        return redirect()->route('admin.quiz.index', $kelas->id)
            ->with('success', '10 soal teknis standar studio untuk kelas ini berhasil dimuat ulang.');
    }
}
