<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function offline(Request $request): View
    {
        $categories = config('allfirst.kategori_software', []);
        $selectedCategory = in_array($request->query('kategori'), $categories, true)
            ? $request->query('kategori')
            : null;

        $hasOfflineCatalog = Kelas::query()->published()->offlineCatalog()->exists();

        $courses = Kelas::query()
            ->published()
            ->with(['moduls.materis'])
            ->when($hasOfflineCatalog, fn ($q) => $q->offlineCatalog())
            ->when($selectedCategory, fn ($q, string $cat) => $q->where('kategori', $cat))
            ->orderBy('id')
            ->get();

        return view('pages.offline', [
            'courses' => $courses,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function ebook(): View
    {
        return view('pages.ebook', [
            'ebooks' => config('allfirst.ebooks'),
        ]);
    }

    public function faq(): View
    {
        return view('pages.faq', [
            'faqs' => config('allfirst.faqs'),
        ]);
    }
}
