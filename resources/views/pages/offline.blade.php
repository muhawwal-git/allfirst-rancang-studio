@extends('layouts.app')

@section('title', 'Pelatihan Tatap Muka (Offline) — Allfirst Rancang Studio')
@section('meta_description', 'Informasi program bimbingan teknis tatap muka langsung di studio: AutoCAD, SketchUp, Revit, dan Lumion. Pendaftaran via WhatsApp resmi Allfirst Rancang Studio.')

@section('content')
    <!-- HERO OFFLINE (fill: linear-gradient(#FFFFFF, #FFF7F7, #F8FAFC) + subtle radial-faded CAD grid overlay) -->
    <section class="relative py-14 sm:py-20 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="max-w-3xl space-y-5">
                <!-- Badge Pelatihan Tatap Muka di Studio -->
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-xs font-semibold text-[#D11F2E]">
                    <span>Pelatihan Tatap Muka di Studio</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#0F172A] tracking-tight leading-tight">
                    Pelatihan Tatap Muka Allfirst Rancang Studio
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-[#334155] leading-relaxed">
                    Dapatkan bimbingan intensif dan evaluasi gambar langsung di studio kami. Pendampingan praktisi secara privat / kelompok kecil untuk akselerasi kemampuan teknis perancangan Anda.
                </p>

                <!-- CTA -->
                <div class="pt-2 flex flex-wrap items-center gap-3.5">
                    <a href="#daftar-program-offline"
                       class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all transform hover:-translate-y-0.5">
                        <span>Pilih Program &amp; Daftar Sekarang</span>
                        <span>&darr;</span>
                    </a>

                    <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text={{ urlencode('Halo Admin Allfirst Studio, saya ingin mendaftar Pelatihan Tatap Muka di Studio.') }}"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl text-sm font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 shadow-sm transition-all">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.771.83 2.791.83h.002c3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.761 0-3.411-.46-4.854-1.266l-5.146 1.346 1.371-5.012c-.896-1.492-1.371-3.21-1.371-5.068 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                        </svg>
                        <span>Daftar via WhatsApp Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI PILIHAN PROGRAM (fill: #FFFFFF, stroke: #E2E8F0) -->
    <section class="bg-white py-14 sm:py-18 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-10">
            <div class="flex flex-wrap items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                    KELAS REGULER STUDIO
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold text-[#0F172A] bg-[#F1F5F9] border border-[#E2E8F0]">
                    KELAS PRIVAT INTENSIF
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                    &check; GRATIS BONUS AKSES KELAS ONLINE 365 HARI
                </span>
            </div>

            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                    Informasi Materi Pelatihan di Studio
                </h2>
                <p class="text-sm sm:text-base text-[#334155] mt-1.5">
                    Pilihan konsentrasi program bimbingan teknis intensif tatap muka bersama instruktur konsultan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Program 1: AutoCAD -->
                <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 space-y-4 hover:border-rose-200 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                        AutoCAD (2D Drafting &amp; Gambar Kerja Teknik)
                    </h3>
                    <p class="text-sm text-[#334155] leading-relaxed">
                        Fokus pada pembuatan denah arsitektur, tampak bangunan, potongan struktural, manajemen layer standar kantor konsultan, dan penyusunan etiket gambar siap cetak.
                    </p>
                </div>

                <!-- Program 2: SketchUp -->
                <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 space-y-4 hover:border-rose-200 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                        SketchUp (3D Modeling Eksterior &amp; Interior)
                    </h3>
                    <p class="text-sm text-[#334155] leading-relaxed">
                        Fokus pada eksplorasi bentuk massa bangunan, detail penataan interior, manajemen Group &amp; Component rapi anti-berat, serta integrasi plugin esensial.
                    </p>
                </div>

                <!-- Program 3: Revit -->
                <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 space-y-4 hover:border-rose-200 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                        Revit (Building Information Modeling / BIM)
                    </h3>
                    <p class="text-sm text-[#334155] leading-relaxed">
                        Fokus pada perancangan berbasis parameter elemen konstruksi, sinkronisasi visual 2D/3D otomatis, penjadwalan volume material (BOQ), dan koordinasi model BIM.
                    </p>
                </div>

                <!-- Program 4: Lumion -->
                <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 space-y-4 hover:border-rose-200 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                        Lumion (Visualisasi Rendering &amp; Animasi)
                    </h3>
                    <p class="text-sm text-[#334155] leading-relaxed">
                        Fokus pada pencahayaan alami (sun &amp; shadow), pengaplikasian material realistik, penataan landscape &amp; entourage, serta produksi render still-image dan video animasi sinematik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI PENDAFTARAN LANGSUNG KELAS OFFLINE (+ BONUS AKSES KELAS ONLINE) -->
    @if(isset($courses) && $courses->isNotEmpty())
        <section id="daftar-program-offline" class="bg-[#F8FAFC] py-14 sm:py-18 border-b border-[#E2E8F0]">
            <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="space-y-1.5">
                        <span class="text-xs font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                            PENDAFTARAN TERINTEGRASI &middot; OFFLINE + ONLINE
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                            Daftar Kelas Offline &amp; Dapatkan Akses Kelas Online Gratis
                        </h2>
                        <p class="text-sm sm:text-base text-[#334155] max-w-3xl">
                            Setiap peserta yang mendaftar program pelatihan tatap muka di studio secara otomatis mendapatkan <strong>hak akses penuh ke video pembelajaran Kelas Online selama 1 Tahun (365 Hari)</strong> begitu pembayaran diverifikasi.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                    @foreach($courses as $kelas)
                        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between space-y-5">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                        {{ $kelas->kategori }} Studio
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                                        + Gratis Kelas Online 1 Tahun
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold font-heading text-[#0F172A] leading-snug">
                                    Pelatihan Studio: {{ $kelas->judul }}
                                </h3>

                                <p class="text-xs sm:text-sm text-[#64748B] line-clamp-2 leading-relaxed">
                                    {{ $kelas->deskripsi_singkat }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-[#F1F5F9] flex items-center justify-between gap-4">
                                <div>
                                    <span class="text-[10px] font-mono text-[#64748B] uppercase block">Investasi Program</span>
                                    <span class="text-lg font-extrabold font-mono text-[#0F172A]">{{ $kelas->formatted_harga }}</span>
                                </div>

                                <a href="{{ route('checkout.create', ['kelas' => $kelas->slug, 'mode' => 'offline']) }}"
                                   class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all shrink-0">
                                    Daftar Kelas Offline &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SEKSI ALUR PENDAFTARAN (fill: #FFFFFF) -->
    <section class="bg-white py-14 sm:py-18 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-10">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                    Alur Pendaftaran via WhatsApp
                </h2>
                <p class="text-sm sm:text-base text-[#334155] mt-1.5">
                    Proses reservasi sesi bimbingan tatap muka studio secara transparan dan fleksibel.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="p-6 rounded-2xl border border-[#F1F5F9] bg-[#F8FAFC]/50 space-y-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#BA151C] text-white font-mono font-bold text-xs">
                        01
                    </span>
                    <h3 class="text-base font-bold font-heading text-[#0F172A]">
                        Langkah 1 &mdash; Pilih Program Software
                    </h3>
                    <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Tentukan program software arsitektur atau pemodelan teknik yang ingin Anda pelajari sesuai kebutuhan proyek atau karier Anda.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-2xl border border-[#F1F5F9] bg-[#F8FAFC]/50 space-y-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#BA151C] text-white font-mono font-bold text-xs">
                        02
                    </span>
                    <h3 class="text-base font-bold font-heading text-[#0F172A]">
                        Langkah 2 &mdash; Hubungi via WhatsApp
                    </h3>
                    <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Klik tombol pendaftaran untuk terhubung langsung dengan tim administrasi pelatihan studio Allfirst Rancang Studio.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-2xl border border-[#F1F5F9] bg-[#F8FAFC]/50 space-y-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#BA151C] text-white font-mono font-bold text-xs">
                        03
                    </span>
                    <h3 class="text-base font-bold font-heading text-[#0F172A]">
                        Langkah 3 &mdash; Konsultasi &amp; Penjadwalan
                    </h3>
                    <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Diskusikan target kemampuan, jadwal belajar yang tersedia, serta konfirmasi administrasi langsung dengan tim kami.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-6 rounded-2xl border border-[#F1F5F9] bg-[#F8FAFC]/50 space-y-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#BA151C] text-white font-mono font-bold text-xs">
                        04
                    </span>
                    <h3 class="text-base font-bold font-heading text-[#0F172A]">
                        Langkah 4 &mdash; Mulai Belajar di Studio
                    </h3>
                    <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Hadiri sesi tatap muka di studio Allfirst Rancang Studio sesuai jadwal yang telah disepakati dan langsung praktik studi kasus riil.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- BANNER PENUTUP OFFLINE (fill: #F8FAFC, stroke: #E2E8F0) -->
    <section class="bg-[#F8FAFC] py-14 sm:py-18">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                Siap Memulai Pelatihan Tatap Muka?
            </h2>
            <p class="text-base text-[#334155] max-w-2xl mx-auto leading-relaxed">
                Konsultasikan target belajar dan jadwalkan sesi pelatihan studio Anda bersama tim instruktur praktisi kami.
            </p>
            <div class="pt-2">
                <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text={{ urlencode('Halo Admin Allfirst Studio, saya ingin konsultasi dan daftar Pelatihan Tatap Muka.') }}"
                   target="_blank"
                   rel="noopener"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.771.83 2.791.83h.002c3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.761 0-3.411-.46-4.854-1.266l-5.146 1.346 1.371-5.012c-.896-1.492-1.371-3.21-1.371-5.068 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                    </svg>
                    <span>Hubungi Admin WhatsApp</span>
                </a>
            </div>
        </div>
    </section>
@endsection
