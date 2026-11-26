<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\PengaturanSitus;
use App\Models\Sertifikat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $this->syncApprovedTransactionsToAccess();

        $kategori = (string) $request->query('kategori', 'semua');
        $search = trim((string) $request->query('search', ''));

        $sertifikatPairs = Sertifikat::query()->get(['user_id', 'kelas_id']);

        $enrollmentsQuery = AksesKelas::query()
            ->with(['user.aksesKelas.kelas', 'kelas.moduls.materis', 'transaksi'])
            ->when($kategori === 'offline', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tipe_kelas', 'offline')
                        ->orWhereHas('transaksi', fn ($tq) => $tq->where('jenis_transaksi', 'offline'));
                });
            })
            ->when($kategori === 'online', function ($q) {
                $q->where('tipe_kelas', '!=', 'offline')
                    ->whereDoesntHave('transaksi', fn ($tq) => $tq->where('jenis_transaksi', 'offline'));
            })
            ->when($kategori === 'bersertifikat', function ($q) use ($sertifikatPairs) {
                if ($sertifikatPairs->isEmpty()) {
                    $q->whereRaw('1 = 0');

                    return;
                }

                $q->where(function ($sub) use ($sertifikatPairs) {
                    foreach ($sertifikatPairs as $pair) {
                        $sub->orWhere(function ($match) use ($pair) {
                            $match->where('user_id', $pair->user_id)
                                ->where('kelas_id', $pair->kelas_id);
                        });
                    }
                });
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nomor_whatsapp', 'like', "%{$search}%");
                    })->orWhereHas('kelas', function ($kq) use ($search) {
                        $kq->where('judul_kelas', 'like', "%{$search}%");
                    });
                });
            })
            ->latest('id');

        $enrollments = $enrollmentsQuery->paginate(12)->withQueryString();

        $allSertifikats = Sertifikat::query()
            ->whereIn('user_id', $enrollments->pluck('user_id'))
            ->get()
            ->keyBy(fn (Sertifikat $s) => $s->user_id.'-'.$s->kelas_id);

        $totalCount = AksesKelas::query()->count();
        $offlineCount = AksesKelas::query()
            ->where(function ($q) {
                $q->where('tipe_kelas', 'offline')
                    ->orWhereHas('transaksi', fn ($tq) => $tq->where('jenis_transaksi', 'offline'));
            })
            ->count();
        $onlineCount = max(0, $totalCount - $offlineCount);
        $certifiedCount = Sertifikat::query()->count();

        $kelasList = Kelas::query()
            ->where('status_publikasi', 'published')
            ->orderBy('judul_kelas')
            ->get();

        $sertifikatNomorTerakhir = (int) PengaturanSitus::getValue('sertifikat_nomor_terakhir', '13');
        $sertifikatKodeLokasi = trim((string) PengaturanSitus::getValue('sertifikat_kode_lokasi', 'MKS')) ?: 'MKS';

        return view('admin.siswa.index', [
            'enrollments' => $enrollments,
            'allSertifikats' => $allSertifikats,
            'currentKategori' => $kategori,
            'totalCount' => $totalCount,
            'offlineCount' => $offlineCount,
            'onlineCount' => $onlineCount,
            'certifiedCount' => $certifiedCount,
            'kelasList' => $kelasList,
            'sertifikatNomorTerakhir' => $sertifikatNomorTerakhir,
            'sertifikatKodeLokasi' => $sertifikatKodeLokasi,
        ]);
    }

    public function updateNomorSertifikat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sertifikat_nomor_terakhir' => ['required', 'integer', 'min:0', 'max:999999'],
            'sertifikat_kode_lokasi' => ['nullable', 'string', 'max:15'],
        ]);

        $nomorTerakhir = (int) $validated['sertifikat_nomor_terakhir'];
        $kodeLokasi = strtoupper(trim((string) ($validated['sertifikat_kode_lokasi'] ?? 'MKS'))) ?: 'MKS';

        PengaturanSitus::setValue('sertifikat_nomor_terakhir', (string) $nomorTerakhir);
        PengaturanSitus::setValue('sertifikat_kode_lokasi', $kodeLokasi);

        $nextPreview = sprintf(
            '%03d-ARS%s-%s/%s/%s',
            $nomorTerakhir + 1,
            date('y'),
            date('m'),
            $kodeLokasi,
            date('Y')
        );

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', "Pengaturan penomoran sertifikat berhasil disimpan! Sertifikat berikutnya akan otomatis bernomor: {$nextPreview}");
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'nomor_whatsapp' => ['required', 'string', 'max:30'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tipe_kelas' => ['required', 'string', 'in:offline,online'],
            'status_profesi' => ['nullable', 'string', 'max:60'],
            'instansi_asal' => ['nullable', 'string', 'max:120'],
            'bonus_kelas_ids' => ['nullable', 'array'],
            'bonus_kelas_ids.*' => ['integer', 'exists:kelas,id'],
            'has_bonus_selection' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user) {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nomor_whatsapp' => $validated['nomor_whatsapp'],
                'password' => 'password123',
                'role' => 'student',
                'status_profesi' => ($validated['status_profesi'] ?? null) ?: 'Peserta Studio',
                'instansi_asal' => ($validated['instansi_asal'] ?? null) ?: 'Allfirst Rancang Studio Makassar',
            ]);
            $user->markEmailAsVerified();
        } else {
            $user->update([
                'name' => $validated['name'],
                'nomor_whatsapp' => $validated['nomor_whatsapp'],
                'status_profesi' => ($validated['status_profesi'] ?? null) ?: $user->status_profesi,
                'instansi_asal' => ($validated['instansi_asal'] ?? null) ?: $user->instansi_asal,
            ]);
        }

        $kelas = Kelas::query()->findOrFail($validated['kelas_id']);
        $isOffline = $validated['tipe_kelas'] === 'offline';

        $transaksi = Transaksi::query()->create([
            'nomor_invoice' => 'ARS-'.($isOffline ? 'OFF' : 'ONL').'-'.now()->format('Ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'jenis_transaksi' => $isOffline ? 'offline' : 'baru',
            'jumlah_pembayaran' => $kelas->harga,
            'status_pembayaran' => 'berhasil',
            'verified_at' => now(),
        ]);

        $durationDays = (int) config('allfirst.access_duration_days', 365);

        if ($isOffline) {
            $hasCustomBonusPayload = $request->has('bonus_kelas_ids') || $request->boolean('has_bonus_selection');
            $selectedBonusIds = $hasCustomBonusPayload
                ? collect($validated['bonus_kelas_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values()
                : collect([(int) $kelas->id]);

            $primaryOnlineActive = $selectedBonusIds->contains((int) $kelas->id);

            AksesKelas::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'kelas_id' => $kelas->id,
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
                if ($bonusKelasId === (int) $kelas->id) {
                    continue;
                }

                AksesKelas::updateOrCreate(
                    [
                        'user_id' => $user->id,
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
            AksesKelas::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'kelas_id' => $kelas->id,
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

        $labelTipe = $isOffline ? 'Offline Studio (+ Bonus Online Terpilih)' : 'Online LMS';

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', "Siswa {$user->name} berhasil didaftarkan pada kelas {$kelas->judul_kelas} ({$labelTipe}). Akun belajar sudah aktif (Password default jika akun baru: password123).");
    }

    public function updateAksesKelas(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'akses_kelas_ids' => ['nullable', 'array'],
            'akses_kelas_ids.*' => ['integer', 'exists:kelas,id'],
        ]);

        $selectedIds = collect($validated['akses_kelas_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $durationDays = (int) config('allfirst.access_duration_days', 365);

        $existingAksesList = $user->aksesKelas()->with('transaksi')->get();
        $isOfflineStudent = $existingAksesList->contains(fn (AksesKelas $a) => $a->isOffline())
            || $user->transaksis()->where('jenis_transaksi', 'offline')->exists();
        $primaryTransaksiId = $existingAksesList->firstWhere('transaksi_id', '!=', null)?->transaksi_id;
        $transactionKelasIds = $user->transaksis()->pluck('kelas_id')->map(fn ($id) => (int) $id)->all();
        $certifiedKelasIds = $user->sertifikats()->pluck('kelas_id')->map(fn ($id) => (int) $id)->all();

        foreach ($selectedIds as $kelasId) {
            $existing = $existingAksesList->firstWhere('kelas_id', $kelasId);

            if ($existing) {
                $existing->update([
                    'status_akses' => 'aktif',
                    'tanggal_kedaluwarsa' => $existing->tanggal_kedaluwarsa && $existing->tanggal_kedaluwarsa->isFuture()
                        ? $existing->tanggal_kedaluwarsa
                        : now()->addDays($durationDays),
                ]);
            } else {
                AksesKelas::query()->create([
                    'user_id' => $user->id,
                    'kelas_id' => $kelasId,
                    'transaksi_id' => $primaryTransaksiId,
                    'tipe_kelas' => $isOfflineStudent ? 'offline' : 'online',
                    'tanggal_mulai' => now(),
                    'tanggal_kedaluwarsa' => now()->addDays($durationDays),
                    'status_akses' => 'aktif',
                ]);
            }
        }

        $remainingAkses = $user->aksesKelas()->get();
        foreach ($remainingAkses as $akses) {
            if ($selectedIds->contains((int) $akses->kelas_id)) {
                continue;
            }

            $isPrimaryOrCertified = in_array((int) $akses->kelas_id, $transactionKelasIds, true)
                || in_array((int) $akses->kelas_id, $certifiedKelasIds, true);

            if ($isPrimaryOrCertified || $user->aksesKelas()->count() <= 1) {
                $akses->update([
                    'status_akses' => 'nonaktif',
                ]);
            } else {
                $akses->delete();
            }
        }

        return back()->with(
            'success',
            "Hak akses kelas online untuk {$user->name} ({$user->email}) berhasil diperbarui ({$selectedIds->count()} kelas online aktif)."
        );
    }

    public function issueSertifikat(Request $request, AksesKelas $aksesKelas): RedirectResponse
    {
        $validated = $request->validate([
            'predikat' => ['nullable', 'string', 'in:Sangat Baik,Baik,Cukup'],
            'nilai' => ['nullable', 'integer', 'min:60', 'max:100'],
        ]);

        $predikat = $validated['predikat'] ?? 'Baik';
        $nilai = isset($validated['nilai']) ? (int) $validated['nilai'] : null;

        $sertifikat = Sertifikat::issueForStudent(
            $aksesKelas->user_id,
            $aksesKelas->kelas_id,
            $predikat,
            $nilai
        );

        return back()->with(
            'success',
            "Sertifikat kelulusan resmi ({$sertifikat->nomor_sertifikat}) dengan predikat {$sertifikat->predikat} (Nilai: {$sertifikat->nilai}) berhasil diterbitkan untuk {$aksesKelas->user->name}!"
        );
    }

    /**
     * Ensure any approved transaction has a corresponding AksesKelas record and synced tipe_kelas.
     */
    private function syncApprovedTransactionsToAccess(): void
    {
        $approvedTransaksis = Transaksi::query()
            ->where('status_pembayaran', 'berhasil')
            ->get();

        $durationDays = (int) config('allfirst.access_duration_days', 365);

        foreach ($approvedTransaksis as $trx) {
            $isOffline = $trx->jenis_transaksi === 'offline';

            $existing = AksesKelas::query()
                ->where('user_id', $trx->user_id)
                ->where('kelas_id', $trx->kelas_id)
                ->first();

            if (! $existing) {
                AksesKelas::query()->create([
                    'user_id' => $trx->user_id,
                    'kelas_id' => $trx->kelas_id,
                    'transaksi_id' => $trx->id,
                    'tipe_kelas' => $isOffline ? 'offline' : 'online',
                    'tanggal_mulai' => $trx->verified_at ?? $trx->created_at ?? now(),
                    'tanggal_kedaluwarsa' => ($trx->verified_at ?? now())->copy()->addDays($durationDays),
                    'status_akses' => 'aktif',
                ]);
            } elseif ($isOffline && $existing->tipe_kelas !== 'offline') {
                $existing->update([
                    'tipe_kelas' => 'offline',
                    'transaksi_id' => $existing->transaksi_id ?? $trx->id,
                ]);
            }
        }
    }
}
