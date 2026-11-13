@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — Allfirst Rancang Studio')
@section('meta_description', 'Pulihkan akses akun Allfirst Rancang Studio Anda melalui tautan reset kata sandi resmi.')

@section('content')
    <!-- AUTH CONTAINER (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] min-h-[calc(100vh-160px)] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 border-b border-[#E2E8F0]">
        <div class="w-full max-w-md">
            <!-- Forgot Password Card (fill: #FFFFFF, stroke: #E2E8F0) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 sm:p-10 space-y-6">
                <!-- Header -->
                <div class="space-y-1.5 text-left">
                    <h1 class="text-2xl font-bold font-heading text-[#0F172A]">
                        Lupa Kata Sandi?
                    </h1>
                    <p class="text-sm text-[#64748B] leading-relaxed">
                        Masukkan alamat email yang terdaftar pada akun Allfirst Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-4 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] text-xs font-semibold leading-relaxed">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Alamat Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-semibold text-[#0F172A]">
                            Alamat Email Terdaftar
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               placeholder="contoh: nama@email.com"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('email') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                        Kirim Tautan Reset Kata Sandi
                    </button>
                </form>

                <!-- Footer Link -->
                <div class="pt-4 border-t border-[#F1F5F9] text-center text-sm text-[#334155]">
                    Ingat kata sandi Anda?
                    <a href="{{ route('login') }}" class="text-[#D11F2E] font-semibold hover:underline">
                        Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
