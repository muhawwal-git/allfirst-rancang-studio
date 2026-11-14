<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request, Kelas $kelas): View|RedirectResponse
    {
        abort_unless($kelas->status_publikasi === 'published', 404);

        $user = $request->user();
        $isOffline = $request->query('mode') === 'offline';

        // If user already has active access and is not registering for offline studio class, redirect to classroom
        if (! $isOffline && $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('student.classroom', $kelas->slug)
                ->with('status', 'Anda sudah memiliki akses aktif ke kelas ini.');
        }

        // Check if this is an access reactivation (user previously enrolled but expired)
        $existingAccess = $user->aksesKelasFor($kelas);
        $isReaktivasi = ! $isOffline && $existingAccess !== null && ! $existingAccess->isAktif();
        $hargaFinal = $isReaktivasi ? $kelas->hargaReaktivasi() : $kelas->harga;

        // Check for existing pending transaction
        $pendingTransaksi = Transaksi::query()
            ->where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->whereIn('status_pembayaran', ['menunggu_pembayaran', 'menunggu_verifikasi'])
            ->latest('id')
            ->first();

        return view('pages.checkout', [
            'kelas' => $kelas,
            'user' => $user,
            'isOffline' => $isOffline,
            'isReaktivasi' => $isReaktivasi,
            'hargaFinal' => $hargaFinal,
            'pendingTransaksi' => $pendingTransaksi,
            'bankAccounts' => config('allfirst.bank_accounts', []),
        ]);
    }

    public function store(Request $request, Kelas $kelas): RedirectResponse
    {
        abort_unless($kelas->status_publikasi === 'published', 404);

        $user = $request->user();
        $isOffline = $request->input('mode') === 'offline' || $request->query('mode') === 'offline';

        if (! $isOffline && $user->hasActiveAccessTo($kelas)) {
            return redirect()->route('student.classroom', $kelas->slug)
                ->with('status', 'Anda sudah memiliki akses aktif ke kelas ini.');
        }

        $existingAccess = $user->aksesKelasFor($kelas);
        $isReaktivasi = ! $isOffline && $existingAccess !== null && ! $existingAccess->isAktif();
        $hargaFinal = $isReaktivasi ? $kelas->hargaReaktivasi() : $kelas->harga;

        // Reuse existing pending transaction if still waiting for payment
        $transaksi = Transaksi::query()
            ->where('user_id', $user->id)
            ->where('kelas_id', $kelas->id)
            ->where('status_pembayaran', 'menunggu_pembayaran')
            ->where('batas_waktu_bayar', '>', now())
            ->latest('id')
            ->first();

        if (! $transaksi) {
            $nomorInvoice = 'ARS-INV-'.date('Ymd').'-'.strtoupper(Str::random(5));

            $jenisTransaksi = 'baru';
            if ($isOffline) {
                $jenisTransaksi = 'offline';
            } elseif ($isReaktivasi) {
                $jenisTransaksi = 'reaktivasi';
            }

            $transaksi = Transaksi::query()->create([
                'nomor_invoice' => $nomorInvoice,
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
                'jenis_transaksi' => $jenisTransaksi,
                'jumlah_pembayaran' => $hargaFinal,
                'status_pembayaran' => 'menunggu_pembayaran',
                'batas_waktu_bayar' => now()->addHours(24),
            ]);
        }

        return redirect()->route('transaksi.show', $transaksi->nomor_invoice);
    }
}
