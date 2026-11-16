<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\PengaturanSitus;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalRevenue = Transaksi::query()
            ->where('status_pembayaran', 'berhasil')
            ->sum('jumlah_pembayaran');

        $pendingCount = Transaksi::query()
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->count();

        $studentsCount = User::query()
            ->where('role', 'student')
            ->count();

        $coursesCount = Kelas::count();

        $recentTransaksis = Transaksi::query()
            ->with(['user', 'kelas'])
            ->orderByRaw("CASE WHEN status_pembayaran = 'menunggu_verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->take(6)
            ->get();

        $runningText = PengaturanSitus::getValue('running_text', 'Batch Tatap Muka Studio Sedang Berjalan (Sisa {sisa_hari} Hari) ◆ Pendaftaran Batch Berikutnya Dibuka via WhatsApp ◆ E-Book Gratis Tersedia');
        $runningTextAktif = PengaturanSitus::getValue('running_text_aktif', '1') === '1';
        $runningTextTargetDate = PengaturanSitus::getValue('running_text_target_date', now()->addDays(4)->format('Y-m-d'));

        // Hero Beranda Dynamic Settings
        $heroBadge = PengaturanSitus::getValue('hero_badge', 'Pusat Pelatihan Software Arsitektur & Engineering');
        $heroTitle = PengaturanSitus::getValue('hero_title', 'Belajar AutoCAD, SketchUp & Revit untuk Kebutuhan Kerja Nyata');
        $heroDescription = PengaturanSitus::getValue('hero_description', 'Tingkatkan kompetensi perancangan teknis dan pemodelan bangunan Anda. Dapatkan pembelajaran mandiri berbasis video terstruktur kapan saja, atau ikuti pelatihan tatap muka langsung di studio.');

        // Pop-up Promo & Floating Bar Settings
        $promoAktif = PengaturanSitus::getValue('promo_popup_aktif', '1') === '1';
        $promoBadge = PengaturanSitus::getValue('promo_badge', 'SPESIAL PROMO');
        $promoJudul = PengaturanSitus::getValue('promo_judul', 'All Class Package');
        $promoSubjudul = PengaturanSitus::getValue('promo_subjudul', 'Semua kelas arsitektur & engineering dalam 1 paket');
        $promoHargaCoret = PengaturanSitus::getValue('promo_harga_coret', '2121000');
        $promoHargaDiskon = PengaturanSitus::getValue('promo_harga_diskon', '1089000');
        $promoLabelHemat = PengaturanSitus::getValue('promo_label_hemat', 'Hemat 49%');
        $promoBatasWaktu = PengaturanSitus::getValue('promo_batas_waktu', now()->addDays(2)->format('Y-m-d\TH:i'));
        $promoCtaText = PengaturanSitus::getValue('promo_cta_text', 'Lihat Paket');
        $promoCtaUrl = PengaturanSitus::getValue('promo_cta_url', '/kelas-online');
        $promoGambar = PengaturanSitus::getValue('promo_gambar');

        // Rekening Bank Pembayaran & WhatsApp Studio
        $bankAccounts = PengaturanSitus::getBankAccounts();
        $bankSlots = count($bankAccounts) > 0
            ? array_values($bankAccounts)
            : [
                [
                    'bank' => 'BCA',
                    'nomor_rekening' => '816-123-4567',
                    'atas_nama' => 'PT Allfirst Rancang Studio',
                ],
            ];
        $whatsappStudio = PengaturanSitus::getAdminWhatsapp();
        $sertifikatNomorTerakhir = (int) PengaturanSitus::getValue('sertifikat_nomor_terakhir', '13');

        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'pendingCount' => $pendingCount,
            'studentsCount' => $studentsCount,
            'coursesCount' => $coursesCount,
            'recentTransaksis' => $recentTransaksis,
            'runningText' => $runningText,
            'runningTextAktif' => $runningTextAktif,
            'runningTextTargetDate' => $runningTextTargetDate,
            'heroBadge' => $heroBadge,
            'heroTitle' => $heroTitle,
            'heroDescription' => $heroDescription,
            'promoAktif' => $promoAktif,
            'promoBadge' => $promoBadge,
            'promoJudul' => $promoJudul,
            'promoSubjudul' => $promoSubjudul,
            'promoHargaCoret' => $promoHargaCoret,
            'promoHargaDiskon' => $promoHargaDiskon,
            'promoLabelHemat' => $promoLabelHemat,
            'promoBatasWaktu' => $promoBatasWaktu,
            'promoCtaText' => $promoCtaText,
            'promoCtaUrl' => $promoCtaUrl,
            'promoGambar' => $promoGambar,
            'bankSlots' => $bankSlots,
            'whatsappStudio' => $whatsappStudio,
            'sertifikatNomorTerakhir' => $sertifikatNomorTerakhir,
        ]);
    }

    public function updateRunningText(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'running_text' => ['required', 'string', 'max:500'],
            'running_text_aktif' => ['nullable'],
            'running_text_target_date' => ['nullable', 'date'],
        ]);

        PengaturanSitus::setValue('running_text', $validated['running_text']);
        PengaturanSitus::setValue('running_text_aktif', $request->has('running_text_aktif') ? '1' : '0');
        if (! empty($validated['running_text_target_date'])) {
            PengaturanSitus::setValue('running_text_target_date', $validated['running_text_target_date']);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pengaturan teks pengumuman running text & hitung mundur otomatis berhasil diperbarui.');
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_badge' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string', 'max:600'],
        ]);

        PengaturanSitus::setValue('hero_badge', $validated['hero_badge']);
        PengaturanSitus::setValue('hero_title', $validated['hero_title']);
        PengaturanSitus::setValue('hero_description', $validated['hero_description']);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Teks Header Beranda berhasil diperbarui dan langsung tampil di halaman utama.');
    }

    public function updatePromo(Request $request): RedirectResponse
    {
        if ($request->input('action_status') === 'disable') {
            PengaturanSitus::setValue('promo_popup_aktif', '0');

            return redirect()->route('admin.dashboard')
                ->with('success', 'Penawaran Pop-Up Promo telah dinonaktifkan dan disembunyikan dari pengunjung.');
        }

        $validated = $request->validate([
            'promo_popup_aktif' => ['nullable'],
            'promo_badge' => ['required', 'string', 'max:60'],
            'promo_judul' => ['required', 'string', 'max:120'],
            'promo_subjudul' => ['required', 'string', 'max:255'],
            'promo_harga_coret' => ['required', 'numeric', 'min:0'],
            'promo_harga_diskon' => ['required', 'numeric', 'min:0'],
            'promo_label_hemat' => ['required', 'string', 'max:50'],
            'promo_batas_waktu' => ['required', 'date'],
            'promo_cta_text' => ['required', 'string', 'max:60'],
            'promo_cta_url' => ['required', 'string', 'max:255'],
            'promo_gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_promo_gambar' => ['nullable'],
        ]);

        $isAktif = $request->input('action_status') === 'enable'
            ? true
            : ($request->has('promo_popup_aktif') && $request->input('promo_popup_aktif') !== '0');

        PengaturanSitus::setValue('promo_popup_aktif', $isAktif ? '1' : '0');
        PengaturanSitus::setValue('promo_badge', $validated['promo_badge']);
        PengaturanSitus::setValue('promo_judul', $validated['promo_judul']);
        PengaturanSitus::setValue('promo_subjudul', $validated['promo_subjudul']);
        PengaturanSitus::setValue('promo_harga_coret', (string) (int) $validated['promo_harga_coret']);
        PengaturanSitus::setValue('promo_harga_diskon', (string) (int) $validated['promo_harga_diskon']);
        PengaturanSitus::setValue('promo_label_hemat', $validated['promo_label_hemat']);
        PengaturanSitus::setValue('promo_batas_waktu', $validated['promo_batas_waktu']);
        PengaturanSitus::setValue('promo_cta_text', $validated['promo_cta_text']);
        PengaturanSitus::setValue('promo_cta_url', $validated['promo_cta_url']);

        $currentImage = PengaturanSitus::getValue('promo_gambar');
        if ($request->boolean('hapus_promo_gambar') && $currentImage) {
            if (Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }
            PengaturanSitus::setValue('promo_gambar', '');
            $currentImage = '';
        }

        if ($request->hasFile('promo_gambar')) {
            if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }
            $newPath = $request->file('promo_gambar')->store('promo_banners', 'public');
            PengaturanSitus::setValue('promo_gambar', $newPath);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pengaturan Pop-Up Promo & Floating Bar berhasil disimpan.');
    }

    public function updateRekening(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_studio' => ['required', 'string', 'max:30'],
            'sertifikat_nomor_terakhir' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'banks' => ['required', 'array', 'min:1', 'max:5'],
            'banks.0.bank' => ['required', 'string', 'max:100'],
            'banks.0.nomor_rekening' => ['required', 'string', 'max:60'],
            'banks.0.atas_nama' => ['required', 'string', 'max:120'],
            'banks.*.bank' => ['nullable', 'string', 'max:100'],
            'banks.*.nomor_rekening' => ['nullable', 'string', 'max:60'],
            'banks.*.atas_nama' => ['nullable', 'string', 'max:120'],
        ]);

        $cleanBanks = [];
        foreach ($validated['banks'] as $item) {
            $bankName = trim((string) ($item['bank'] ?? ''));
            $nomor = trim((string) ($item['nomor_rekening'] ?? ''));
            $atasNama = trim((string) ($item['atas_nama'] ?? ''));

            if ($bankName !== '' && $nomor !== '' && $atasNama !== '') {
                $cleanBanks[] = [
                    'bank' => $bankName,
                    'nomor_rekening' => $nomor,
                    'atas_nama' => $atasNama,
                ];
            }
        }

        $cleanWa = preg_replace('/[^0-9]/', '', $validated['whatsapp_studio']);
        $cleanWa = ($cleanWa !== '' && $cleanWa !== null) ? $cleanWa : trim($validated['whatsapp_studio']);

        PengaturanSitus::setValue('rekening_bank_list', (string) json_encode($cleanBanks, JSON_UNESCAPED_UNICODE));
        PengaturanSitus::setValue('whatsapp_studio', $cleanWa);

        if (isset($validated['sertifikat_nomor_terakhir']) && $validated['sertifikat_nomor_terakhir'] !== null) {
            PengaturanSitus::setValue('sertifikat_nomor_terakhir', (string) (int) $validated['sertifikat_nomor_terakhir']);
        }

        if (isset($cleanBanks[0])) {
            PengaturanSitus::setValue('rekening_bank', $cleanBanks[0]['bank']);
            PengaturanSitus::setValue('rekening_nomor', $cleanBanks[0]['nomor_rekening']);
            PengaturanSitus::setValue('rekening_atas_nama', $cleanBanks[0]['atas_nama']);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pengaturan Rekening Pembayaran, WhatsApp & Nomor Urut Sertifikat Studio berhasil diperbarui.');
    }
}
