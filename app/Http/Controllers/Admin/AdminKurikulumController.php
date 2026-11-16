<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKurikulumController extends Controller
{
    public function index(Kelas $kelas): View
    {
        $kelas->load(['moduls.materis']);

        return view('admin.kelas.kurikulum', [
            'kelas' => $kelas,
        ]);
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
        $validated = $request->validate([
            'judul_materi' => ['required', 'string', 'max:255'],
            'path_video' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:102400'],
            'durasi_tampil' => ['required', 'string', 'max:10'],
            'cuplikan_gratis' => ['nullable'],
            'nomor_urutan' => ['nullable', 'integer', 'min:1'],
        ]);

        $videoPath = $validated['path_video'] ?? 'videos/sample-lesson.mp4';
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'local');
        }

        // Parse mm:ss to seconds
        $parts = explode(':', $validated['durasi_tampil']);
        $seconds = 0;
        if (count($parts) === 2) {
            $seconds = ((int) $parts[0] * 60) + (int) $parts[1];
        } elseif (count($parts) === 3) {
            $seconds = ((int) $parts[0] * 3600) + ((int) $parts[1] * 60) + (int) $parts[2];
        }

        $order = $validated['nomor_urutan'] ?? ($modul->materis()->count() + 1);

        $modul->materis()->create([
            'judul_materi' => $validated['judul_materi'],
            'path_video' => $videoPath,
            'durasi_tampil' => $validated['durasi_tampil'],
            'durasi_detik' => $seconds,
            'nomor_urutan' => $order,
            'cuplikan_gratis' => $request->has('cuplikan_gratis'),
        ]);

        return back()->with('success', 'Pelajaran video berhasil ditambahkan ke modul.');
    }

    public function destroyMateri(Materi $materi): RedirectResponse
    {
        $materi->delete();

        return back()->with('success', 'Materi video berhasil dihapus.');
    }
}
