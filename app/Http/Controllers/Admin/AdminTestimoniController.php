<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminTestimoniController extends Controller
{
    public function index(): View
    {
        $testimonis = Testimoni::query()
            ->with(['user', 'kelas'])
            ->latest('id')
            ->get();

        return view('admin.testimoni.index', [
            'testimonis' => $testimonis,
            'featuredCount' => $testimonis->where('tampil_beranda', true)->count(),
            'averageRating' => $testimonis->isNotEmpty() ? round((float) $testimonis->avg('rating'), 1) : 5.0,
        ]);
    }

    public function toggleBeranda(Testimoni $testimoni): RedirectResponse
    {
        $testimoni->update([
            'tampil_beranda' => ! $testimoni->tampil_beranda,
        ]);

        $message = $testimoni->tampil_beranda
            ? 'Testimoni siswa berhasil ditampilkan di Halaman Beranda.'
            : 'Testimoni siswa disembunyikan dari Halaman Beranda.';

        return back()->with('success', $message);
    }

    public function destroy(Testimoni $testimoni): RedirectResponse
    {
        $testimoni->delete();

        return back()->with('success', 'Data testimoni berhasil dihapus.');
    }
}
