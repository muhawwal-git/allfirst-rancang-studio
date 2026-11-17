<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SertifikatController extends Controller
{
    public function show(Request $request, string $sertifikat): View
    {
        $user = $request->user();

        $cert = Sertifikat::query()
            ->where('nomor_sertifikat', $sertifikat)
            ->first();

        if (! $cert) {
            $cert = Sertifikat::query()
                ->where('user_id', $user->id)
                ->whereHas('kelas', function ($q) use ($sertifikat) {
                    $q->where('slug', $sertifikat);
                })
                ->first();
        }

        abort_unless($cert !== null, 404);
        abort_unless($user->id === $cert->user_id || $user->isAdmin(), 403);

        $cert->load(['user', 'kelas']);

        $verifyUrl = route('sertifikat.verifikasi', $cert->hash_kode_qr);
        $qrCodeSvg = QrCodeService::svg($verifyUrl, 160, '#0F172A', '#FFFFFF');

        return view('student.sertifikat', [
            'sertifikat' => $cert,
            'qrCodeSvg' => $qrCodeSvg,
            'verifyUrl' => $verifyUrl,
        ]);
    }

    public function verifikasi(string $hash): View
    {
        $sertifikat = Sertifikat::query()
            ->where('hash_kode_qr', $hash)
            ->with(['user', 'kelas'])
            ->first();

        $qrCodeSvg = null;
        if ($sertifikat) {
            $qrCodeSvg = QrCodeService::svg(
                route('sertifikat.verifikasi', $sertifikat->hash_kode_qr),
                160,
                '#0F172A',
                '#FFFFFF'
            );
        }

        return view('pages.verifikasi-sertifikat', [
            'sertifikat' => $sertifikat,
            'hash' => $hash,
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }
}
