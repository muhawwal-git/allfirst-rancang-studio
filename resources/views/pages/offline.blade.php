@extends('layouts.app')

@section('title', 'Pelatihan Tatap Muka (Offline)')
@section('meta_description', 'Informasi program bimbingan teknis tatap muka langsung di studio: AutoCAD, SketchUp, Revit, dan Enscape. Pendaftaran via WhatsApp resmi Allfirst Rancang Studio.')

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
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span>Daftar via WhatsApp Sekarang</span>
                    </a>

                    <a href="https://maps.app.goo.gl/T1YSXMVx7mrEcEq4A?g_st=iw"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl text-sm font-semibold text-[#0F172A] bg-white border border-[#CBD5E1] hover:bg-slate-50 shadow-sm transition-all">
                        <svg class="w-4 h-4 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Petunjuk Lokasi Studio (Maps) &nearr;</span>
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

                <!-- Program 4: Enscape -->
                <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 space-y-4 hover:border-rose-200 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                        Enscape (Visualisasi Rendering &amp; Animasi)
                    </h3>
                    <p class="text-sm text-[#334155] leading-relaxed">
                        Fokus pada pencahayaan alami (sun &amp; shadow), pengaplikasian material realistik, penataan landscape &amp; entourage, serta produksi render still-image dan video animasi sinematik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI KATALOG & PENDAFTARAN KELAS OFFLINE STUDIO (+ BONUS AKSES KELAS ONLINE) -->
    @if(isset($courses) && ($courses->isNotEmpty() || !empty($selectedCategory)))
        <section id="daftar-program-offline" class="bg-[#F8FAFC] py-14 sm:py-18 border-b border-[#E2E8F0]">
            <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="space-y-1.5">
                        <span class="text-xs font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                            KATALOG PELATIHAN STUDIO &middot; OFFLINE + BONUS ONLINE
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                            Katalog Kelas Offline &amp; Modul Tatap Muka Studio
                        </h2>
                        <p class="text-sm sm:text-base text-[#334155] max-w-3xl">
                            Pilih program pelatihan tatap muka di studio Makassar lengkap dengan modul silabus praktik dan dapatkan <strong>bonus akses kelas online selama 1 Tahun (365 Hari)</strong>.
                        </p>
                    </div>
                </div>

                <!-- Filter Bar (Konsisten 100% dengan Katalog Kelas Online) -->
                @if(!empty($categories))
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="text-xs sm:text-sm font-semibold text-[#0F172A] mr-1">
                            Filter Software:
                        </span>

                        <a href="{{ route('offline') }}#daftar-program-offline"
                           class="px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all {{ empty($selectedCategory)
                               ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white border border-[#BA151C] shadow-sm font-semibold'
                               : 'bg-white text-[#334155] border border-[#E2E8F0] hover:bg-slate-50' }}">
                            Semua Software
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('offline', ['kategori' => $cat]) }}#daftar-program-offline"
                               class="px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all {{ ($selectedCategory ?? null) === $cat
                                   ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white border border-[#BA151C] shadow-sm font-semibold'
                                   : 'bg-white text-[#334155] border border-[#E2E8F0] hover:bg-slate-50' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @forelse($courses as $kelas)
                        @php
                            $modulItems = collect($kelas->modul_offline ?? [])
                                ->whenEmpty(fn () => $kelas->moduls->pluck('judul_modul'))
                                ->filter()
                                ->values();
                        @endphp
                        <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-[0_10px_30px_-5px_rgba(15,23,42,0.05)] hover:shadow-[0_15px_35px_-5px_rgba(15,23,42,0.1)] transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                            <!-- 16:9 Thumbnail (Konsisten dengan Katalog Kelas Online) -->
                            <div class="relative aspect-video w-full p-4 bg-gradient-to-br from-[#0F172A] via-[#1E293B] to-[#4C0519] border-b border-[#E2E8F0] flex flex-col justify-between overflow-hidden">
                                @if($kelas->gambar_sampul && \Illuminate\Support\Facades\Storage::disk('public')->exists($kelas->gambar_sampul))
                                    <img src="{{ asset('storage/' . $kelas->gambar_sampul) }}"
                                         alt="{{ $kelas->judul }}"
                                         class="absolute inset-0 w-full h-full object-cover opacity-55 group-hover:scale-105 transition-transform duration-300">
                                @endif
                                <div class="relative flex items-center justify-between">
                                    <span class="px-2.5 py-1 rounded bg-[#1E293B]/90 text-[10px] font-mono text-[#E2E8F0] uppercase font-semibold">
                                        {{ strtoupper($kelas->kategori) }} STUDIO
                                    </span>
                                    <svg class="w-4 h-4 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                    </svg>
                                </div>

                                <div class="relative">
                                    <span class="text-[11px] font-mono text-[#E2E8F0]">
                                        TATAP MUKA STUDIO &middot; MAKASSAR
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 sm:p-6 space-y-4 flex-grow flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-md text-xs font-medium text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                            {{ $kelas->kategori }} Studio
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                            + Bonus Kelas Online
                                        </span>
                                    </div>

                                    <h3 class="text-lg font-bold font-heading text-[#0F172A] group-hover:text-[#D11F2E] transition-colors leading-snug">
                                        <a href="{{ route('kelas.show', $kelas->slug) }}">
                                            {{ $kelas->judul }}
                                        </a>
                                    </h3>

                                    <p class="text-sm text-[#64748B] line-clamp-2 leading-relaxed">
                                        {{ $kelas->deskripsi_singkat }}
                                    </p>

                                    @if($modulItems->isNotEmpty())
                                        <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-3 space-y-1.5">
                                            <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                                                MODUL TATAP MUKA ({{ $modulItems->count() }} MODUL)
                                            </span>
                                            <ul class="space-y-1 text-xs text-[#334155]">
                                                @foreach($modulItems->take(3) as $modTitle)
                                                    <li class="flex items-start gap-1.5 truncate" title="{{ $modTitle }}">
                                                        <span class="text-[#D11F2E] font-bold">&bull;</span>
                                                        <span class="truncate">{{ $modTitle }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @if($modulItems->count() > 3)
                                                <a href="{{ route('kelas.show', $kelas->slug) }}" class="inline-block text-[11px] font-semibold text-[#D11F2E] hover:underline pt-0.5">
                                                    + {{ $modulItems->count() - 3 }} modul lainnya &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between gap-3 mt-4">
                                    <div>
                                        <span class="text-[10px] font-mono text-[#64748B] uppercase block">Investasi Program</span>
                                        <span class="text-lg font-bold font-mono text-[#0F172A]">{{ $kelas->formatted_harga }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ route('kelas.show', $kelas->slug) }}"
                                           class="px-3 py-2 rounded-lg text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors">
                                            Detail
                                        </a>
                                        <a href="{{ route('checkout.create', ['kelas' => $kelas->slug, 'mode' => 'offline']) }}"
                                           class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                            Daftar Offline &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-[#E2E8F0] p-8">
                            <p class="text-slate-500 font-medium">Belum ada program kelas offline untuk kategori software yang dipilih.</p>
                            <a href="{{ route('offline') }}#daftar-program-offline" class="inline-block mt-3 text-sm text-[#D11F2E] font-semibold hover:underline">
                                Tampilkan Semua Program Offline &rarr;
                            </a>
                        </div>
                    @endforelse
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
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span>Hubungi Admin WhatsApp</span>
                </a>
            </div>
        </div>
    </section>
@endsection
