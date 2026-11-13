<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'statusProfesiOptions' => config('allfirst.status_profesi_options'),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'],
            'status_profesi' => $validated['status_profesi'],
            'instansi_asal' => filled($validated['instansi_asal'] ?? null) ? $validated['instansi_asal'] : null,
            'password' => $validated['password'],
            'role' => 'student',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Akun berhasil dibuat. Selamat belajar di Allfirst Rancang Studio!');
    }
}
