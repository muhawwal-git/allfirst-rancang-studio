<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Sertifikat;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $transaksis = Transaksi::query()
            ->with(['user', 'kelas'])
            ->when($status && $status !== 'semua', fn ($q) => $q->where('status_pembayaran', $status))
            ->when($request->query('search'), function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('nomor_invoice', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->orderByRaw("CASE WHEN status_pembayaran = 'menunggu_verifikasi' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        $kelasList = Kelas::query()
            ->where('status_publikasi', 'published')
            ->orderBy('judul_kelas')
            ->get();

        return view('admin.transaksi.index', [
            'transaksis' => $transaksis,
            'currentStatus' => $status ?? 'semua',
            'kelasList' => $kelasList,
        ]);
    }

    public function show(Transaksi $transaksi): View
    {
        $transaksi->load(['user', 'kelas']);

        $kelasList = Kelas::query()
            ->where('status_publikasi', 'published')
            ->orderBy('judul_kelas')
            ->get();

        return view('admin.transaksi.show', [
            'transaksi' => $transaksi,
            'kelasList' => $kelasList,
        ]);
    }

    public function approve(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'bonus_kelas_ids' => ['nullable', 'array'],
            'bonus_kelas_ids.*' => ['integer', 'exists:kelas,id'],
            'has_bonus_selection' => ['nullable', 'boolean'],
        ]);

        $transaksi->update([
            'status_pembayaran' => 'berhasil',
            'verified_at' => now(),
        ]);

        $durationDays = (int) config('allfirst.access_duration_days', 365);
        $isOffline = $transaksi->jenis_transaksi === 'offline';

        if ($isOffline) {
            $hasCustomBonusPayload = $request->has('bonus_kelas_ids') || $request->boolean('has_bonus_selection');
            $selectedBonusIds = $hasCustomBonusPayload
                ? collect($validated['bonus_kelas_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values()
                : collect([(int) $transaksi->kelas_id]);

            $primaryOnlineActive = $selectedBonusIds->contains((int) $transaksi->kelas_id);

            AksesKelas::updateOrCreate(
                [
                    'user_id' => $transaksi->user_id,
                    'kelas_id' => $transaksi->kelas_id,
                ],
                [
                    'transaksi_id' => $transaksi->id,
                    'tipe_kelas' => 'offline',
                    'tanggal_mulai' => now(),
                    'tanggal_kedaluwarsa' => now()->addDays($durationDays),
                    'status_akses' => $primaryOnlineActive ? 'aktif' : 'nonaktif',
                ]
            );

            foreach ($selectedBonusIds as $bonusKelasId) {
                if ($bonusKelasId === (int) $transaksi->kelas_id) {
                    continue;
                }

                AksesKelas::updateOrCreate(
                    [
                        'user_id' => $transaksi->user_id,
                        'kelas_id' => $bonusKelasId,
                    ],
                    [
                        'transaksi_id' => $transaksi->id,
                        'tipe_kelas' => 'offline',
                        'tanggal_mulai' => now(),
                        'tanggal_kedaluwarsa' => now()->addDays($durationDays),
                        'status_akses' => 'aktif',
                    ]
                );
            }
        } else {
            // Activate or extend course access for student
            AksesKelas::updateOrCreate(
                [
                    'user_id' => $transaksi->user_id,
                    'kelas_id' => $transaksi->kelas_id,
                ],
                [
                    'transaksi_id' => $transaksi->id,
                    'tipe_kelas' => 'online',
                    'tanggal_mulai' => now(),
                    'tanggal_kedaluwarsa' => now()->addDays($durationDays),
                    'status_akses' => 'aktif',
                ]
            );
        }

        return back()->with('success', "Transaksi {$transaksi->nomor_invoice} berhasil disetujui! Hak akses kelas 365 hari telah aktif untuk siswa.");
    }

    public function reject(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:500'],
        ]);

        $transaksi->update([
            'status_pembayaran' => 'ditolak',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return back()->with('success', "Transaksi {$transaksi->nomor_invoice} berhasil ditolak dengan alasan yang tersimpan.");
    }

    public function issueSertifikat(Transaksi $transaksi): RedirectResponse
    {
        if ($transaksi->status_pembayaran !== 'berhasil') {
            return back()->with('error', 'Sertifikat hanya dapat diterbitkan untuk transaksi yang pembayarannya sudah disetujui.');
        }

        $sertifikat = Sertifikat::issueForStudent($transaksi->user_id, $transaksi->kelas_id);

        return back()->with('success', "Sertifikat kelulusan resmi ({$sertifikat->nomor_sertifikat}) berhasil diterbitkan untuk {$transaksi->user->name}!");
    }
}
