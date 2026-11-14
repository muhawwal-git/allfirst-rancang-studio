<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function offline(): View
    {
        $courses = Kelas::query()
            ->published()
            ->orderBy('id')
            ->get();

        return view('pages.offline', [
            'courses' => $courses,
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
