@extends('layouts.app')

@section('title', 'Daftar Akun Siswa Baru — Allfirst Rancang Studio')
@section('meta_description', 'Registrasi akun Allfirst Rancang Studio. Mulai belajar software arsitektur dan teknik sipil standar industri dengan sertifikat digital resmi.')

@section('content')
    <!-- REGISTER CONTAINER (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] min-h-[calc(100vh-160px)] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 border-b border-[#E2E8F0]">
        <div class="w-full max-w-xl">
            <!-- Register Card (fill: #FFFFFF, stroke: #E2E8F0) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 sm:p-10 space-y-6">
                <!-- Title -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold font-heading text-[#0F172A]">
                        Registrasi Peserta Baru
                    </h1>
                    <p class="text-sm text-[#64748B] mt-1">
                        Daftar Akun Siswa Baru &middot; Lengkapi data diri Anda untuk memulai pembelajaran dan penerbitan sertifikat resmi.
                    </p>
                </div>

                <!-- KTP Alert Box (fill: #FFFBEB, stroke: #FDE68A) -->
                <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#B45309] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-xs text-[#B45309] leading-relaxed">
                        <strong class="font-bold">PENTING: Verifikasi Nama Sertifikat</strong> — Pastikan penulisan nama lengkap sesuai dengan kartu identitas resmi (KTP/KTM) karena akan dicantumkan pada Sertifikat Kelulusan resmi Anda.
                    </p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    <!-- Nama Lengkap (Sesuai KTP) Field -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-sm font-semibold text-[#0F172A]">
                            Nama Lengkap (Sesuai KTP / Untuk Sertifikat) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               autocomplete="name"
                               placeholder="Masukkan nama lengkap tanpa singkatan"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('name') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & WhatsApp Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="email" class="block text-sm font-semibold text-[#0F172A]">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   placeholder="nama@email.com"
                                   class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('email') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="nomor_whatsapp" class="block text-sm font-semibold text-[#0F172A]">
                                Nomor WhatsApp Aktif <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nomor_whatsapp"
                                   name="nomor_whatsapp"
                                   value="{{ old('nomor_whatsapp') }}"
                                   required
                                   placeholder="contoh: 081234567890"
                                   class="w-full px-4 py-3 rounded-xl bg-white border text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all {{ $errors->has('nomor_whatsapp') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                            @error('nomor_whatsapp')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status / Profesi Field -->
                    <div class="space-y-1.5">
                        <label for="status_profesi" class="block text-sm font-semibold text-[#0F172A]">
                            Status / Latar Belakang Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <select id="status_profesi"
                                name="status_profesi"
                                required
                                class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-sm text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all">
                            <option value="">Pilih status Anda...</option>
                            @foreach(config('allfirst.status_profesi_options') as $option)
                                <option value="{{ $option }}" {{ old('status_profesi') === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_profesi')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Kampus / Instansi Field (Opsional) -->
                    <div class="space-y-1.5">
                        <label for="instansi" class="block text-sm font-semibold text-[#0F172A]">
                            Asal Kampus / Instansi <span class="text-xs font-normal text-[#64748B]">(Opsional — Kosongkan jika Umum)</span>
                        </label>
                        <input type="text"
                               id="instansi"
                               name="instansi"
                               value="{{ old('instansi') }}"
                               placeholder="Contoh: Universitas Hasanuddin / PT Karya Mandiri"
                               class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all">
                    </div>

                    <!-- Kata Sandi Field -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-semibold text-[#0F172A]">
                            Kata Sandi <span class="text-red-500">*</span>
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

                    <!-- Konfirmasi Kata Sandi -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#0F172A]">
                            Ulangi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Ketik ulang kata sandi"
                               class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-sm text-[#0F172A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#D11F2E] transition-all">
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                            Daftar Akun Sekarang
                        </button>
                    </div>
                </form>

                <!-- Footer Link -->
                <div class="pt-4 border-t border-[#F1F5F9] text-center text-sm text-[#334155]">
                    Sudah terdaftar sebelumnya?
                    <a href="{{ route('login') }}" class="text-[#D11F2E] font-semibold hover:underline">
                        Masuk ke Akun Anda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
