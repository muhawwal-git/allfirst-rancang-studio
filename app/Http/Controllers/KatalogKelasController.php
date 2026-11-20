<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogKelasController extends Controller
{
    public function index(Request $request): View
    {
        $categories = config('allfirst.kategori_software');
        $selectedCategory = in_array($request->query('kategori'), $categories, true)
            ? $request->query('kategori')
            : null;

        $courses = Kelas::query()
            ->published()
            ->onlineCatalog()
            ->when($selectedCategory, fn ($query, string $category) => $query->where('kategori', $category))
            ->orderByDesc('is_unggulan')
            ->orderBy('id')
            ->paginate(6)
            ->withQueryString();

        return view('pages.katalog', [
            'courses' => $courses,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function show(Kelas $kelas): View
    {
        abort_unless($kelas->status_publikasi === 'published', 404);

        $kelas->load(['moduls.materis']);
        $previewMateri = $kelas->previewMateri();

        return view('pages.kelas-detail', [
            'kelas' => $kelas,
            'previewMateri' => $previewMateri,
            'totalMateri' => $kelas->moduls->sum(fn ($modul) => $modul->materis->count()),
            'previewLimitSeconds' => (int) config('allfirst.preview_limit_seconds'),
        ]);
    }
}
