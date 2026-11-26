@extends('layouts.app')

@section('title', 'Verifikasi Alamat Email')
@section('meta_description', 'Verifikasi alamat email akun Allfirst Rancang Studio Anda untuk membuka akses dashboard belajar dan kelas online.')

@section('content')
    <!-- AUTH CONTAINER (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] min-h-[calc(100vh-160px)] flex items-center justify-center py-12 sm:py-16 px-4 sm:px-6 lg:px-8 border-b border-[#E2E8F0]">
        <div class="w-full max-w-md">
            <!-- Verification Card -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 sm:p-10 space-y-6">
                <!-- Icon & Header -->
                <div class="space-y-3 text-left">
                    <div class="w-12 h-12 rounded-xl bg-[#FEF2F2] border border-[#FECACA] flex items-center justify-center text-[#D11F2E]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div class="space-y-1.5">
                        <h1 class="text-2xl font-bold font-heading text-[#0F172A]">
                            Verifikasi Email Anda
                        </h1>
                        <p class="text-sm text-[#64748B] leading-relaxed">
                            Satu langkah lagi sebelum masuk ke Dashboard Belajar. Kami telah mengirimkan tautan verifikasi ke alamat email Anda:
                        </p>
                    </div>
                </div>

                <!-- Registered Email Pill -->
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                    <span class="w-2 h-2 rounded-full bg-[#D11F2E] shrink-0"></span>
                    <span class="text-sm font-semibold font-mono text-[#0F172A] truncate">
                        {{ auth()->user()->email }}
                    </span>
                </div>

                <!-- Resend Feedback Alert -->
                @if (session('status'))
                    <div class="p-4 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] text-xs font-semibold leading-relaxed">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Instructions -->
                <div class="space-y-2 text-xs text-[#475569] leading-relaxed bg-[#F8FAFC] rounded-xl p-4 border border-[#E2E8F0]">
                    <p class="font-semibold text-[#0F172A]">Langkah Verifikasi:</p>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Buka kotak masuk (Inbox) Gmail atau layanan email Anda.</li>
                        <li>Cari email dari <strong class="text-[#0F172A]">Allfirst Rancang Studio</strong>.</li>
                        <li>Klik tombol <strong class="text-[#0F172A]">Verifikasi Alamat Email</strong> di dalam pesan tersebut.</li>
                    </ol>
                    <p class="pt-1 text-[#64748B]">
                        Belum menemukan emailnya? Periksa folder <strong>Spam</strong> atau <strong>Promosi</strong>, lalu klik tombol kirim ulang di bawah.
                    </p>
                </div>

                <!-- Resend Verification Form -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all cursor-pointer">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <!-- Footer Logout Action -->
                <div class="pt-4 border-t border-[#F1F5F9] flex items-center justify-between text-xs text-[#64748B]">
                    <span>Salah mengetik email?</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-[#D11F2E] font-semibold hover:underline cursor-pointer">
                            Keluar &amp; Daftar Ulang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
