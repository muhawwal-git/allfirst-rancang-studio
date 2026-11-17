<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $aksesKelas = $user->aksesKelas()
            ->with(['kelas.moduls.materis', 'transaksi'])
            ->latest('id')
            ->get();

        $aksesWithProgress = $aksesKelas->map(function ($akses) use ($user) {
            return [
                'akses' => $akses,
                'kelas' => $akses->kelas,
                'progress' => $akses->kelas->progressPercentageFor($user),
                'isAktif' => $akses->isAktif(),
                'sisaHari' => $akses->sisaHari(),
            ];
        });

        $sertifikats = $user->sertifikats()
            ->with('kelas')
            ->latest('id')
            ->get();

        $transaksis = $user->transaksis()
            ->with('kelas')
            ->latest('id')
            ->paginate(10);

        return view('student.dashboard', [
            'user' => $user,
            'aksesList' => $aksesWithProgress,
            'sertifikats' => $sertifikats,
            'transaksis' => $transaksis,
        ]);
    }
}
