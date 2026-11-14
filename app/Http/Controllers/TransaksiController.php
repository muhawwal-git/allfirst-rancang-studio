<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBuktiRequest;
use App\Models\PengaturanSitus;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function show(Request $request, Transaksi $transaksi): View
    {
        $user = $request->user();
        abort_unless($user->id === $transaksi->user_id || $user->isAdmin(), 403);

        $transaksi->load(['kelas', 'user']);

        $adminPhone = PengaturanSitus::getAdminWhatsapp();
        $nominalFormatted = 'Rp '.number_format($transaksi->jumlah_pembayaran, 0, ',', '.');
        $waMessage = "Halo Admin Allfirst Rancang Studio,\nSaya telah melakukan transfer untuk pembayaran kelas online:\n- Nomor Invoice: {$transaksi->nomor_invoice}\n- Kelas: {$transaksi->kelas->judul_kelas}\n- Nominal: {$nominalFormatted}\n- Atas Nama: {$transaksi->user->name}\n\nMohon bantu verifikasi akses belajar saya. Terima kasih.";
        $waConfirmUrl = "https://wa.me/{$adminPhone}?text=".urlencode($waMessage);

        return view('pages.transaksi-detail', [
            'transaksi' => $transaksi,
            'bankAccounts' => PengaturanSitus::getBankAccounts(),
            'waConfirmUrl' => $waConfirmUrl,
        ]);
    }

    public function uploadBukti(UploadBuktiRequest $request, Transaksi $transaksi): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->id === $transaksi->user_id, 403);
        abort_if($transaksi->status_pembayaran === 'berhasil', 400, 'Transaksi ini telah diverifikasi dan aktif.');

        // Delete previous proof file if exists
        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $transaksi->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('transaksi.show', $transaksi->nomor_invoice)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Admin Allfirst Studio akan segera memverifikasi transaksi Anda.');
    }
}
