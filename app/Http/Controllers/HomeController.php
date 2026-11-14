<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PengaturanSitus;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredCourses = Kelas::query()
            ->published()
            ->orderByDesc('is_unggulan')
            ->orderBy('id')
            ->take(3)
            ->get();

        $isRunningTextActive = PengaturanSitus::getValue('running_text_aktif', '1') === '1';
        $rawRunningText = (string) PengaturanSitus::getValue('running_text', '');
        $targetDateStr = PengaturanSitus::getValue('running_text_target_date');

        if ($targetDateStr) {
            try {
                $daysLeft = max(0, (int) now()->startOfDay()->diffInDays(Carbon::parse($targetDateStr)->startOfDay(), false));
                $countdownLabel = $daysLeft > 0 ? "(Sisa {$daysLeft} Hari)" : '(Hari Terakhir!)';

                if (str_contains($rawRunningText, '{sisa_hari}')) {
                    $rawRunningText = str_replace(['(Sisa {sisa_hari} Hari)', '{sisa_hari}'], [$countdownLabel, (string) $daysLeft], $rawRunningText);
                } elseif (preg_match('/\(Sisa\s+\d+\s+Hari\)/i', $rawRunningText)) {
                    $rawRunningText = (string) preg_replace('/\(Sisa\s+\d+\s+Hari\)/i', $countdownLabel, $rawRunningText);
                }
            } catch (\Throwable) {
                // Keep raw running text if date is invalid
            }
        } elseif (str_contains($rawRunningText, '{sisa_hari}')) {
            $rawRunningText = str_replace('{sisa_hari}', '4', $rawRunningText);
        }

        $runningTextItems = collect(explode('◆', $rawRunningText))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values();

        // Dynamic Hero Header
        $heroBadge = PengaturanSitus::getValue('hero_badge', 'Pusat Pelatihan Software Arsitektur & Engineering');
        $heroTitle = PengaturanSitus::getValue('hero_title', 'Belajar AutoCAD, SketchUp & Revit untuk Kebutuhan Kerja Nyata');
        $heroDescription = PengaturanSitus::getValue('hero_description', 'Tingkatkan kompetensi perancangan teknis dan pemodelan bangunan Anda. Dapatkan pembelajaran mandiri berbasis video terstruktur kapan saja, atau ikuti pelatihan tatap muka langsung di studio.');

        // Promo Popup & Floating Bar Settings
        $promoGambar = PengaturanSitus::getValue('promo_gambar');
        $promoPopup = [
            'aktif' => PengaturanSitus::getValue('promo_popup_aktif', '1') === '1',
            'badge' => PengaturanSitus::getValue('promo_badge', 'SPESIAL PROMO'),
            'judul' => PengaturanSitus::getValue('promo_judul', 'All Class Package'),
            'subjudul' => PengaturanSitus::getValue('promo_subjudul', 'Semua kelas arsitektur & engineering dalam 1 paket'),
            'harga_coret' => (int) PengaturanSitus::getValue('promo_harga_coret', '2121000'),
            'harga_diskon' => (int) PengaturanSitus::getValue('promo_harga_diskon', '1089000'),
            'label_hemat' => PengaturanSitus::getValue('promo_label_hemat', 'Hemat 49%'),
            'batas_waktu' => PengaturanSitus::getValue('promo_batas_waktu', now()->addDays(2)->format('Y-m-d\TH:i')),
            'cta_text' => PengaturanSitus::getValue('promo_cta_text', 'Lihat Paket'),
            'cta_url' => PengaturanSitus::getValue('promo_cta_url', '/kelas-online'),
            'gambar' => $promoGambar ?: null,
        ];

        $testimonials = collect(config('allfirst.testimonials'));

        return view('pages.home', [
            'featuredCourses' => $featuredCourses,
            'isRunningTextActive' => $isRunningTextActive && $runningTextItems->isNotEmpty(),
            'runningTextItems' => $runningTextItems,
            'heroBadge' => $heroBadge,
            'heroTitle' => $heroTitle,
            'heroDescription' => $heroDescription,
            'promoPopup' => $promoPopup,
            'testimonialRowTop' => $testimonials->take(4),
            'testimonialRowBottom' => $testimonials->slice(4)->values(),
        ]);
    }
}
