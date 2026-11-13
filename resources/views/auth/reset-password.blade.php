@extends('layouts.app')

@section('title', 'Atur Ulang Kata Sandi — Allfirst Rancang Studio')
@section('meta_description', 'Buat kata sandi baru untuk akun Allfirst Rancang Studio Anda.')

@section('content')
    <!-- AUTH CONTAINER (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] min-h-[calc(100vh-160px)] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 border-b border-[#E2E8F0]">
        <div class="w-full max-w-md">
            <!-- Reset Password Card (fill: #FFFFFF, stroke: #E2E8F0) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 sm:p-10 space-y-6">
                <!-- Header -->
                <div class="space-y-1.5 text-left">
                    <h1 class="text-2xl font-bold font-heading text-[#0F172A]">
                        Atur Ulang Kata Sandi
                    </h1>
                    <p class="text-sm text-[#64748B] leading-relaxed">
                        Silakan buat kata sandi baru yang aman untuk akun belajar Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Alamat Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-semibold text-[#0F172A]">
                            Alamat Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $email) }}"
                               required
                               autocomplete="email"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('email') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-semibold text-[#0F172A]">
                            Kata Sandi Baru
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('password') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#0F172A]">
                            Ulangi Kata Sandi Baru
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Ulangi kata sandi baru"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all border-[#CBD5E1]">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                        Simpan Kata Sandi Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
