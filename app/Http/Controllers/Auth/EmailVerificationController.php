<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->dashboardRoute($request));
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->intended($this->dashboardRoute($request))
            ->with('status', 'Alamat email Anda berhasil diverifikasi! Selamat belajar di Allfirst Rancang Studio.');
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->dashboardRoute($request));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Tautan verifikasi baru telah dikirim ke '.$request->user()->email.'. Silakan periksa kotak masuk atau folder Spam Anda.');
    }

    private function dashboardRoute(Request $request): string
    {
        return $request->user()->isAdmin()
            ? route('admin.dashboard')
            : route('student.dashboard');
    }
}
