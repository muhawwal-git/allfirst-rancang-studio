@extends('layouts.app')

@section('title', 'Allfirst Rancang Studio — Kursus Software Arsitektur, Sipil & BIM Siap Kerja')
@section('meta_description', 'Studio pelatihan software AutoCAD, SketchUp, Revit BIM, dan Lumion standar konsultan DED perencana. Materi terstruktur, video streaming, dan sertifikat ber-QR.')

@section('content')
    <!-- HERO SECTION (fill: linear-gradient(#FFFFFF, #FFF7F7, #F8FAFC) + subtle radial-faded CAD grid overlay) -->
    <section class="relative pt-14 pb-16 md:pt-18 md:pb-22 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <!-- 4-Sided Radial-Faded CAD Grid Overlay (Never cropped at top/bottom/left/right) -->
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                <!-- Hero Left Column (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <!-- Badge Pusat Pelatihan Software Arsitektur & Engineering -->
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-xs font-semibold text-[#D11F2E]">
                        <span>{{ $heroBadge ?? 'Pusat Pelatihan Software Arsitektur & Engineering' }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-5xl lg:text-[46px] font-extrabold font-heading text-[#0F172A] tracking-tight leading-[1.15]">
                        {{ $heroTitle ?? 'Belajar AutoCAD, SketchUp & Revit untuk Kebutuhan Kerja Nyata' }}
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-base sm:text-[17px] text-[#334155] max-w-2xl leading-relaxed">
                        {{ $heroDescription ?? 'Tingkatkan kompetensi perancangan teknis dan pemodelan bangunan Anda. Dapatkan pembelajaran mandiri berbasis video terstruktur kapan saja, atau ikuti pelatihan tatap muka langsung di studio.' }}
                    </p>

                    <!-- Hero CTA Group -->
                    <div class="pt-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5">
                        <a href="{{ route('kelas.index') }}"
                           class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md shadow-red-950/10 transition-all transform hover:-translate-y-0.5">
                            <span>Jelajahi Kelas Online</span>
                        </a>

                        <a href="{{ route('offline') }}"
                           class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                            <span>Info Pelatihan Studio</span>
                        </a>
                    </div>
                </div>

                <!-- Hero Visual 16:9 (lg:col-span-5) -->
                <div class="lg:col-span-5">
                    <div class="relative w-full aspect-video rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-[#18181B] via-[#3F1D24] to-[#7F1D1D] border border-slate-700/80 shadow-[0_20px_50px_-12px_rgba(15,23,42,0.28)] flex flex-col justify-between overflow-hidden">
                        <!-- Technical Grid Overlay in viewport -->
                        <div class="absolute inset-0 bg-cad-grid-dark opacity-30 pointer-events-none"></div>

                        <!-- Viewport Topbar -->
                        <div class="relative flex items-center justify-between">
                            <span class="text-[11px] font-mono text-[#94A3B8] tracking-wider uppercase">
                                AUTOCAD / REVIT / SKETCHUP &middot; STUDIO VIEWPORT
                            </span>
                            <span class="text-[10px] font-mono text-white bg-[#0F172A] border border-slate-700 px-2.5 py-0.5 rounded font-semibold">
                                16:9 HD
                            </span>
                        </div>

                        <!-- Viewport Center Graphic (Compass Icon per PDF) -->
                        <div class="relative flex flex-col items-center justify-center text-center py-4 space-y-2.5">
                            <div class="w-11 h-11 rounded-full border-2 border-[#EF4444]/80 flex items-center justify-center text-[#EF4444]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-sans text-[#F8FAFC] font-medium tracking-wide">
                                [Visual Ruang Belajar &amp; Pemodelan Software 16:9]
                            </p>
                        </div>

                        <!-- Viewport Specs -->
                        <div class="relative pt-3 border-t border-white/10 flex items-center justify-between text-[11px] font-mono">
                            <span class="text-[#94A3B8]">2D DRAFTING &middot; 3D BIM &middot; RENDERING</span>
                            <span class="text-[#F87171] font-bold">AKSES 365 HARI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KINETIC ANNOUNCEMENT RIBBON (Placed directly beneath Hero Banner) -->
    <div class="bg-gradient-to-r from-[#9E111B] via-[#D11F2E] to-[#9E111B] border-y border-[#FFE4E6]/20 py-2.5 px-4 overflow-hidden select-none">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 flex items-center justify-between gap-4">
            <!-- Left Live Status Pill -->
            <div class="shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-[#FECDD3] text-xs font-bold text-[#D11F2E] shadow-sm">
                    <span>● LIVE OFFLINE</span>
                </span>
            </div>

            <!-- Kinetic Ticker Stream -->
            <div class="relative flex-grow overflow-x-hidden text-xs sm:text-sm">
                <div class="animate-ribbon py-0.5">
                    @if($isRunningTextActive && count($runningTextItems) > 0)
                        @foreach(range(1, 2) as $loopIdx)
                            @foreach($runningTextItems as $item)
                                <span class="mx-6 inline-flex items-center gap-2 text-white font-medium">
                                    <span>{{ $item }}</span>
                                </span>
                                <span class="text-[#FDA4AF]">◆</span>
                            @endforeach
                        @endforeach
                    @else
                        @foreach(range(1, 2) as $loopIdx)
                            <span class="mx-6 inline-flex items-center gap-2 text-white font-medium">
                                <span>Batch Tatap Muka Studio Sedang Berjalan</span>
                                <span class="text-[#FEF08A] font-bold">(Sisa 4 Hari)</span>
                            </span>
                            <span class="text-[#FDA4AF]">◆</span>
                            <span class="mx-6 inline-flex items-center gap-2 text-white font-medium">
                                <span>Pendaftaran Batch Berikutnya Dibuka via WhatsApp</span>
                            </span>
                            <span class="text-[#FDA4AF]">◆</span>
                            <span class="mx-6 inline-flex items-center gap-2 text-[#FFE4E6] font-medium">
                                <span>E-Book Gratis Tersedia</span>
                            </span>
                            <span class="text-[#FDA4AF]">◆</span>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Ribbon Action Button -->
            <div class="shrink-0 hidden sm:block">
                <a href="{{ route('offline') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/15 border border-white/30 text-white text-xs font-medium hover:bg-white/25 transition-colors">
                    <span>Info Jadwal &rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- SEKSI PENGANTAR KELAS OFFLINE (fill: #FFFFFF, stroke: #E2E8F0) -->
    <section class="bg-white border-b border-[#E2E8F0] py-12">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-3xl">
                    <h2 class="text-xl sm:text-2xl font-bold font-heading text-[#0F172A]">
                        Ingin Belajar Langsung dengan Pendampingan Instruktur?
                    </h2>
                    <p class="text-sm sm:text-base text-[#334155] leading-relaxed">
                        Setiap orang memiliki cara belajar yang berbeda. Bagi kamu yang ingin memperdalam pemahaman melalui interaksi langsung, diskusi, dan praktik yang lebih intensif, pelatihan tatap muka bisa menjadi pengalaman belajar yang tepat.<br>
                         Jadwal pelatihan dikonsultasikan bersama instruktur. Hubungi WhatsApp resmi kami untuk informasi pendaftaran dan konsultasi materi.
                    </p>
                </div>

                <div class="shrink-0 w-full md:w-auto flex md:justify-end">
                    <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text={{ urlencode('Halo Admin Allfirst Studio, saya ingin konsultasi mengenai pendaftaran Pelatihan Tatap Muka (Offline).') }}"
                       target="_blank"
                       rel="noopener"
                       aria-label="Konsultasi via WhatsApp"
                       title="Konsultasi via WhatsApp"
                       class="inline-flex items-center justify-center p-4 rounded-xl text-white bg-[#0F172A] hover:bg-slate-800 border border-[#1E293B] shadow-sm transition-all">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.771.83 2.791.83h.002c3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.761 0-3.411-.46-4.854-1.266l-5.146 1.346 1.371-5.012c-.896-1.492-1.371-3.21-1.371-5.068 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI KELAS ONLINE PILIHAN (fill: #F8FAFC — Clean solid background per PDF) -->
    <section class="bg-[#F8FAFC] py-16 sm:py-20 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-10">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div class="space-y-1.5">
                    <span class="block text-xs font-mono font-bold tracking-widest text-[#D11F2E] uppercase">
                        KATALOG UNGGULAN
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                        Kelas Online Pilihan
                    </h2>
                </div>

                <a href="{{ route('kelas.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#D11F2E] hover:underline">
                    <span>Lihat Semua Katalog Kelas &rarr;</span>
                </a>
            </div>

            <!-- Card Row (3 Featured Courses) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @forelse($featuredCourses as $kelas)
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-[0_10px_30px_-5px_rgba(15,23,42,0.05)] hover:shadow-[0_15px_35px_-5px_rgba(15,23,42,0.1)] transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                        <!-- 16:9 Thumbnail -->
                        <div class="relative aspect-video w-full p-4 bg-gradient-to-br from-[#0F172A] via-[#1E293B] to-[#4C0519] border-b border-[#E2E8F0] flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded bg-[#1E293B] text-[10px] font-mono text-[#E2E8F0] uppercase font-semibold">
                                    {{ strtoupper($kelas->kategori_software) }} WORKSPACE
                                </span>
                                <svg class="w-4 h-4 text-[#64748B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                </svg>
                            </div>

                            <div>
                                <span class="text-[11px] font-mono text-[#94A3B8]">
                                    16:9 &middot; HD STREAMING
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 sm:p-6 space-y-4 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <!-- Badges -->
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-medium text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                        {{ $kelas->kategori_software }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                        Akses 1 Tahun
                                    </span>
                                </div>

                                <!-- Course Title -->
                                <h3 class="text-lg font-bold font-heading text-[#0F172A] group-hover:text-[#D11F2E] transition-colors leading-snug">
                                    {{ $kelas->judul }}
                                </h3>

                                <!-- Description -->
                                <p class="text-sm text-[#64748B] line-clamp-2 leading-relaxed">
                                    {{ $kelas->deskripsi_singkat }}
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between mt-4">
                                <span class="text-lg font-bold font-mono text-[#0F172A]">
                                    {{ $kelas->formatted_harga }}
                                </span>

                                <a href="{{ route('kelas.show', $kelas->slug) }}"
                                   class="px-3.5 py-2 rounded-lg text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors">
                                    Lihat Detail Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm p-6 text-center text-slate-500">
                        Belum ada kelas yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SEKSI TESTIMONI ALUMNI (Dual-Row Marquee, fill: #FFFFFF + subtle radial-faded bg-cad-grid-testi.png) -->
    <section class="relative bg-white py-16 sm:py-20 overflow-hidden">
        <!-- 4-Sided Radial-Faded CAD Grid Overlay (Never cropped at top/bottom/left/right) -->
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-testi.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20 mb-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <!-- Title Group -->
                <div class="space-y-1.5">
                    <span class="block text-xs font-mono font-bold tracking-widest text-[#D11F2E] uppercase">
                        APA KATA ALUMNI &amp; MEMBER STUDIO
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                        Dipercaya Mahasiswa Teknik, Drafter &amp; Praktisi Arsitektur
                    </h2>
                </div>

                <!-- Marquee Controls -->
                
            </div>
        </div>

        <!-- Marquee Tracks Wrapper (Aligned to max-w-7xl for perfect symmetry on all screen widths & zoom levels) -->
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="space-y-6 overflow-hidden"
                 style="-webkit-mask-image: linear-gradient(to right, transparent, black 2%, black 98%, transparent); mask-image: linear-gradient(to right, transparent, black 2%, black 98%, transparent);">
                <!-- Row 1: Scroll Left -->
                <div class="relative overflow-x-hidden">
                    <div class="animate-marquee-left py-2 flex">
                        @foreach(range(1, 2) as $loopPass)
                            <div class="flex shrink-0 gap-6 pr-6">
                                @foreach(range(1, 2) as $subPass)
                                    @foreach($testimonialRowTop as $testi)
                                    <div class="w-[300px] sm:w-[360px] shrink-0 bg-white rounded-2xl border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.05)] hover:shadow-md transition-shadow flex flex-col justify-between">
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[#F59E0B] text-sm tracking-wider">★★★★★</span>
                                                <span class="text-2xl text-[#FECDD3] font-serif leading-none">❞</span>
                                            </div>
                                            <p class="text-sm text-[#334155] leading-relaxed">
                                                &ldquo;{{ $testi['quote'] }}&rdquo;
                                            </p>
                                        </div>

                                        <div class="pt-4 border-t border-[#F1F5F9] mt-4 flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-[#FFF1F2] border border-[#FECDD3] text-[#D11F2E] flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($testi['name'], 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-sm font-semibold text-[#0F172A]">{{ $testi['name'] }}</span>
                                                    <span class="sr-only">Alumni Terverifikasi</span>
                                                    <svg class="w-3.5 h-3.5 text-[#D11F2E] shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Alumni Terverifikasi">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs text-[#64748B]">{{ $testi['course'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Row 2: Scroll Right -->
                <div class="relative overflow-x-hidden">
                    <div class="animate-marquee-right py-2 flex">
                        @foreach(range(1, 2) as $loopPass)
                            <div class="flex shrink-0 gap-6 pr-6">
                                @foreach(range(1, 2) as $subPass)
                                    @foreach($testimonialRowBottom as $testi)
                                    <div class="w-[300px] sm:w-[360px] shrink-0 bg-white rounded-2xl border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.05)] hover:shadow-md transition-shadow flex flex-col justify-between">
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[#F59E0B] text-sm tracking-wider">★★★★★</span>
                                                <span class="text-2xl text-[#FECDD3] font-serif leading-none">❞</span>
                                            </div>
                                            <p class="text-sm text-[#334155] leading-relaxed">
                                                &ldquo;{{ $testi['quote'] }}&rdquo;
                                            </p>
                                        </div>

                                        <div class="pt-4 border-t border-[#F1F5F9] mt-4 flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-[#FFF1F2] border border-[#FECDD3] text-[#D11F2E] flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($testi['name'], 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-sm font-semibold text-[#0F172A]">{{ $testi['name'] }}</span>
                                                    <span class="sr-only">Alumni Terverifikasi</span>
                                                    <svg class="w-3.5 h-3.5 text-[#D11F2E] shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Alumni Terverifikasi">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs text-[#64748B]">{{ $testi['course'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- POP-UP PROMO & COLLAPSIBLE FLOATING BAR (Desain Khas Studio Arsitektur Allfirst) -->
    @if(!empty($promoPopup['aktif']))
        <!-- 1. Centered Architectural Studio Pop-Up Promo Modal -->
        <div id="promoPopupModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0B0F19]/75 backdrop-blur-sm transition-opacity duration-300"
             onclick="if(event.target === this) minimizePromoToBar()">
            <div class="relative w-full max-w-[440px] bg-white rounded-2xl overflow-hidden shadow-[0_25px_70px_-15px_rgba(15,23,42,0.45)] border border-[#E2E8F0]">

                <!-- Top Studio Viewport Header (Consistent with Hero Viewport & Course Cards, or Custom Promo Photo) -->
                <div class="relative bg-gradient-to-br from-[#0F172A] via-[#27151D] to-[#6B121B] p-6 border-b border-[#1E293B] overflow-hidden">
                    @if(!empty($promoPopup['gambar']))
                        <img src="{{ asset('storage/' . $promoPopup['gambar']) }}"
                             alt="{{ $promoPopup['judul'] }}"
                             class="pointer-events-none absolute inset-0 w-full h-full object-cover opacity-45">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-[#0B0F19]/60 to-transparent"></div>
                    @else
                        <!-- Subtle CAD Grid Overlay -->
                        <div class="pointer-events-none absolute inset-0 bg-cad-grid-dark opacity-30"></div>
                    @endif

                    <!-- Top Row: Promo Badge & Close Button -->
                    <div class="relative z-10 flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] font-mono text-[10px] font-bold uppercase tracking-wider shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D11F2E] animate-pulse"></span>
                            <span>{{ $promoPopup['badge'] }}</span>
                        </span>

                        <button type="button"
                                onclick="minimizePromoToBar()"
                                aria-label="Minimize Pop-up Promo"
                                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-[#D11F2E] text-white border border-white/15 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Viewport Title & Technical Software Tags -->
                    <div class="relative z-10 mt-5 space-y-2.5">
                        <div class="text-[10px] font-mono text-[#FDA4AF] uppercase tracking-widest font-semibold">
                            ALLFIRST RANCANG STUDIO &middot; PROGRAM SPESIAL
                        </div>
                        <h3 class="text-2xl font-extrabold font-heading text-white tracking-tight leading-snug">
                            {{ $promoPopup['judul'] }}
                        </h3>
                        <p class="text-xs text-[#CBD5E1] leading-relaxed">
                            {{ $promoPopup['subjudul'] }}
                        </p>
                        <div class="pt-1 flex flex-wrap items-center gap-1.5">
                            @foreach(['AUTOCAD 2D/3D', 'SKETCHUP', 'REVIT BIM', 'LUMION'] as $swTag)
                                <span class="px-2 py-0.5 rounded bg-[#0B0F19]/70 border border-white/15 text-[10px] font-mono font-semibold text-[#E2E8F0]">
                                    {{ $swTag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Body (Light Architectural Card Surface) -->
                <div class="p-6 space-y-5 bg-white">
                    <!-- Price & Discount Box -->
                    <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 flex items-center justify-between gap-4">
                        <div class="space-y-0.5 text-left">
                            <span class="text-[10px] font-mono font-bold text-[#64748B] uppercase tracking-wider block">
                                INVESTASI PENDAFTARAN
                            </span>
                            @if($promoPopup['harga_coret'] > 0)
                                <div class="text-xs font-mono text-[#94A3B8] line-through">
                                    Rp {{ number_format($promoPopup['harga_coret'], 0, ',', '.') }}
                                </div>
                            @endif
                            <div class="text-2xl sm:text-[26px] font-extrabold font-mono text-[#0F172A] tracking-tight">
                                Rp {{ number_format($promoPopup['harga_diskon'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="text-right space-y-1 shrink-0">
                            <span class="inline-block px-3 py-1 rounded-lg bg-[#FFF1F2] border border-[#FECDD3] text-[#D11F2E] font-mono text-xs font-bold">
                                {{ $promoPopup['label_hemat'] }}
                            </span>
                            <span class="block text-[10px] font-mono text-[#64748B]">
                                Akses Penuh 365 Hari
                            </span>
                        </div>
                    </div>

                    <!-- Live 4-Box Technical Countdown Timer -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[10px] font-mono text-[#64748B] uppercase tracking-wider font-semibold">
                            <span>BATAS WAKTU PENAWARAN</span>
                            <span class="text-[#D11F2E]">● AKTIF TERBATAS</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2.5">
                            <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] py-2.5 px-2 text-center">
                                <div id="promo-cd-days" class="font-mono text-lg font-extrabold text-[#0F172A] leading-none">00</div>
                                <span class="text-[9px] font-mono text-[#64748B] uppercase mt-1 block">HARI</span>
                            </div>
                            <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] py-2.5 px-2 text-center">
                                <div id="promo-cd-hours" class="font-mono text-lg font-extrabold text-[#0F172A] leading-none">14</div>
                                <span class="text-[9px] font-mono text-[#64748B] uppercase mt-1 block">JAM</span>
                            </div>
                            <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] py-2.5 px-2 text-center">
                                <div id="promo-cd-minutes" class="font-mono text-lg font-extrabold text-[#0F172A] leading-none">22</div>
                                <span class="text-[9px] font-mono text-[#64748B] uppercase mt-1 block">MENIT</span>
                            </div>
                            <div class="rounded-xl bg-[#FFF1F2] border border-[#FECDD3] py-2.5 px-2 text-center">
                                <div id="promo-cd-seconds" class="font-mono text-lg font-extrabold text-[#D11F2E] leading-none">18</div>
                                <span class="text-[9px] font-mono text-[#D11F2E] font-semibold uppercase mt-1 block">DETIK</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="space-y-2 pt-1">
                        <a href="{{ $promoPopup['cta_url'] }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md shadow-red-950/15 transition-all">
                            <span>{{ $promoPopup['cta_text'] }}</span>
                            <span>&rarr;</span>
                        </a>
                        <button type="button"
                                onclick="minimizePromoToBar()"
                                class="w-full py-2 text-[11px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">
                            Sembunyikan ke Bar Bawah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Architectural Dark Floating Promo Bar (Consistent with #0B0F19 Studio Topbar/Sidebar) -->
        <div id="promoFloatingBar"
             class="hidden fixed bottom-5 inset-x-0 z-40 px-4 pointer-events-none flex justify-center">
            <div class="pointer-events-auto w-full max-w-4xl bg-[#0B0F19]/95 backdrop-blur-md text-white rounded-2xl border border-[#1E293B] shadow-[0_20px_50px_-10px_rgba(11,15,25,0.5)] p-3.5 sm:px-5 sm:py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Left Studio Promo Info -->
                <div class="flex items-center gap-3.5 w-full sm:w-auto">
                    <!-- Crimson Studio Badge Icon or Custom Uploaded Thumbnail -->
                    @if(!empty($promoPopup['gambar']))
                        <div class="w-14 h-11 rounded-xl bg-[#1E293B] border border-slate-700 overflow-hidden shrink-0 shadow-sm">
                            <img src="{{ asset('storage/' . $promoPopup['gambar']) }}"
                                 alt="{{ $promoPopup['judul'] }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#D11F2E] to-[#9E111B] border border-[#BA151C] flex flex-col items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[9px] font-mono font-bold text-rose-200 uppercase leading-none">PROMO</span>
                            <span class="text-xs font-mono font-extrabold text-white leading-tight mt-0.5">CAD</span>
                        </div>
                    @endif

                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm sm:text-base font-bold font-heading text-white">
                                {{ $promoPopup['judul'] }}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-[#FFF1F2] text-[#D11F2E] font-mono text-[10px] font-bold">
                                {{ $promoPopup['label_hemat'] }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 text-xs">
                            @if($promoPopup['harga_coret'] > 0)
                                <span class="font-mono text-[#64748B] line-through text-[11px]">
                                    Rp {{ number_format($promoPopup['harga_coret'], 0, ',', '.') }}
                                </span>
                            @endif
                            <span class="font-mono font-extrabold text-white">
                                Rp {{ number_format($promoPopup['harga_diskon'], 0, ',', '.') }}
                            </span>
                            <span class="text-[#475569]">&middot;</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-[#1E293B] border border-slate-700 text-[#FDA4AF] font-mono text-[11px] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D11F2E] animate-pulse"></span>
                                <span id="promo-bar-timer">14:22:04</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Actions (Crimson CTA + Slate Close Button) -->
                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end shrink-0">
                    <a href="{{ $promoPopup['cta_url'] }}"
                       class="flex-1 sm:flex-initial text-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                        {{ $promoPopup['cta_text'] }} &rarr;
                    </a>
                    <button type="button"
                            onclick="closePromoCompletely()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-[#CBD5E1] hover:text-white bg-[#1E293B] hover:bg-slate-800 border border-slate-700 transition-colors">
                        <span>&times;</span>
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function minimizePromoToBar() {
                const modal = document.getElementById('promoPopupModal');
                const bar = document.getElementById('promoFloatingBar');
                if (modal) modal.classList.add('hidden');
                if (bar) bar.classList.remove('hidden');
            }

            function closePromoCompletely() {
                const modal = document.getElementById('promoPopupModal');
                const bar = document.getElementById('promoFloatingBar');
                if (modal) modal.classList.add('hidden');
                if (bar) bar.classList.add('hidden');
            }

            (function() {
                const targetStr = @js($promoPopup['batas_waktu']);
                const targetDate = new Date(targetStr).getTime();

                function pad2(n) {
                    return String(n).padStart(2, '0');
                }

                function updateCountdown() {
                    const now = Date.now();
                    let diff = Math.max(0, Math.floor((targetDate - now) / 1000));

                    const days = Math.floor(diff / 86400);
                    diff %= 86400;
                    const hours = Math.floor(diff / 3600);
                    diff %= 3600;
                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;

                    const dEl = document.getElementById('promo-cd-days');
                    const hEl = document.getElementById('promo-cd-hours');
                    const mEl = document.getElementById('promo-cd-minutes');
                    const sEl = document.getElementById('promo-cd-seconds');
                    const barEl = document.getElementById('promo-bar-timer');

                    if (dEl) dEl.textContent = pad2(days);
                    if (hEl) hEl.textContent = pad2(hours);
                    if (mEl) mEl.textContent = pad2(minutes);
                    if (sEl) sEl.textContent = pad2(seconds);
                    if (barEl) {
                        const totalHours = (days * 24) + hours;
                        barEl.textContent = pad2(totalHours) + ':' + pad2(minutes) + ':' + pad2(seconds);
                    }
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            })();
        </script>
    @endif
@endsection
