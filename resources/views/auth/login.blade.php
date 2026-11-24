@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')
@section('meta_description', 'Masuk ke akun Allfirst Rancang Studio untuk mengakses materi kelas online, video pembelajaran, dan sertifikat kelulusan.')

@section('content')
    <!-- AUTH CONTAINER (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] min-h-[calc(100vh-160px)] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 border-b border-[#E2E8F0]">
        <div class="w-full max-w-md">
            <!-- Login Card (fill: #FFFFFF, stroke: #E2E8F0) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 sm:p-10 space-y-6">
                <!-- Header -->
                <div class="space-y-1.5 text-left">
                    <h1 class="text-2xl font-bold font-heading text-[#0F172A]">
                        Masuk ke Ruang Belajar
                    </h1>
                    <p class="text-sm text-[#64748B] leading-relaxed">
                        Masuk ke Akun Anda untuk mengakses materi kelas online, video pembelajaran, dan sertifikat kelulusan.
                    </p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <!-- Alamat Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-semibold text-[#0F172A]">
                            Alamat Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="contoh: nama@email.com"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('email') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi Field -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-semibold text-[#0F172A]">
                            Kata Sandi
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Masukkan kata sandi Anda"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('password') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-[#334155]">
                            <input type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 rounded border-slate-300 text-[#D11F2E] focus:ring-[#D11F2E]">
                            <span>Ingat saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[#D11F2E] font-semibold hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <!-- Masuk Button (gradient: #D11F2E -> #9E111B, text: #FFFFFF) -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                        Masuk
                    </button>
                </form>

                <!-- Footer Link -->
                <div class="pt-4 border-t border-[#F1F5F9] text-center text-sm text-[#334155]">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="text-[#D11F2E] font-semibold hover:underline">
                        Daftar Akun Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
