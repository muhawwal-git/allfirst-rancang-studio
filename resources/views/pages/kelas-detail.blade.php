@extends('layouts.app')

@section('title', $kelas->judul)
@section('meta_description', Str::limit(strip_tags($kelas->deskripsi_singkat ?? $kelas->judul), 160))

@section('content')
    <!-- DETAIL TOP SECTION (fill: linear-gradient(#FFFFFF, #FFF7F7, #F8FAFC) + subtle radial-faded CAD grid overlay) -->
    <section class="relative py-8 sm:py-10 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                <!-- Left Column: Course Media 16:9 (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="relative w-full aspect-video rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-[#18181B] via-[#3F1D24] to-[#7F1D1D] border border-slate-700 shadow-xl flex flex-col justify-between overflow-hidden">
                        <!-- Media Header -->
                        <div class="relative flex items-center justify-between">
                            <span class="text-[11px] font-mono text-[#94A3B8] tracking-wider uppercase font-semibold">
                                {{ strtoupper($kelas->kategori) }} &middot; MODUL 1 PREVIEW TERSEDIA
                            </span>
                            <span class="text-[10px] font-mono text-white bg-[#0F172A] border border-slate-700 px-2 py-0.5 rounded font-semibold">
                                1080p HD
                            </span>
                        </div>

                        <!-- Play Trigger Center -->
                        <div class="relative flex flex-col items-center justify-center text-center py-6">
                            @if($previewMateri)
                                <button type="button" onclick="openPreviewModal()"
                                        class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-black/40 hover:bg-black/60 border border-red-500/40 hover:border-red-500 transition-all transform hover:scale-105">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-[#D11F2E] to-[#9E111B] flex items-center justify-center text-white shadow-lg shadow-red-950/80">
                                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold font-heading text-white tracking-wide">
                                        Tonton Cuplikan ({{ $previewLimitSeconds }} Detik)
                                    </span>
                                </button>
                            @else
                                <div class="text-center text-slate-300 font-mono text-xs">
                                    [Visual Ruang Belajar &amp; Pemodelan Software 16:9]
                                </div>
                            @endif
                        </div>

                        <!-- Media Footer -->
                        <div class="relative pt-3 border-t border-slate-700/80 flex items-center justify-between text-[11px] font-mono text-[#94A3B8]">
                            <span>{{ $kelas->moduls->count() }} MODUL &middot; {{ $totalMateri }} VIDEO MATERI</span>
                            <span>TOTAL DURASI {{ $kelas->durasi_jam ? $kelas->durasi_jam . ' JAM' : 'LENGKAP' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Panel Pembelian (lg:col-span-5) -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-sm space-y-5">
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-medium text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                {{ $kelas->kategori }}{{ $kelas->isOfflineCatalog() ? ' Studio' : '' }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                {{ $kelas->isOfflineCatalog() ? 'Tatap Muka + Bonus Online' : 'Akses 1 Tahun' }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-xl sm:text-2xl font-bold font-heading text-[#0F172A] leading-snug">
                            {{ $kelas->judul }}
                        </h1>

                        <!-- Price -->
                        <div class="space-y-1">
                            <div class="text-3xl font-extrabold font-mono text-[#0F172A]">
                                {{ $kelas->formatted_harga }}
                            </div>
                            <p class="text-xs text-[#64748B] leading-relaxed">
                                {{ $kelas->isOfflineCatalog()
                                    ? 'Investasi pelatihan tatap muka studio · Termasuk bonus akses kelas online terpilih selama 365 hari.'
                                    : 'Harga normal · Hak akses video berlaku 365 hari terhitung sejak verifikasi pembayaran.' }}
                            </p>
                        </div>

                        <!-- Buttons Group -->
                        <div class="space-y-3 pt-2">
                            <a href="{{ $kelas->isOfflineCatalog() ? route('checkout.create', ['kelas' => $kelas->slug, 'mode' => 'offline']) : route('checkout.create', $kelas->slug) }}"
                               class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                                <span>{{ $kelas->isOfflineCatalog() ? 'Daftar Kelas Offline Sekarang' : 'Beli Kelas Sekarang' }}</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>

                            @if($previewMateri)
                                <button type="button" onclick="openPreviewModal()"
                                        class="w-full inline-flex items-center justify-center py-3 px-6 rounded-xl text-sm font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                                    Tonton Cuplikan Gratis ({{ $previewLimitSeconds }} Detik)
                                </button>
                            @endif
                        </div>

                        <p class="text-[11px] text-[#64748B] text-center leading-relaxed">
                            Belum punya akun? Anda akan diminta masuk atau mendaftar terlebih dahulu sebelum pembayaran.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- DESKRIPSI & MANFAAT (fill: #FFFFFF, stroke: #E2E8F0) -->
    <section class="bg-white py-10 sm:py-12 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                <!-- Kolom Deskripsi (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-4">
                    <h2 class="text-xl sm:text-2xl font-bold font-heading text-[#0F172A]">
                        Deskripsi Materi Pembelajaran
                    </h2>
                    <div class="text-[#334155] text-sm sm:text-base leading-relaxed space-y-4">
                        {!! nl2br(e($kelas->deskripsi_lengkap ?? $kelas->deskripsi_singkat)) !!}
                    </div>
                </div>

                <!-- Kolom Manfaat (lg:col-span-5) -->
                <div class="lg:col-span-5">
                    <div class="bg-[#F8FAFC] rounded-2xl border border-[#F1F5F9] p-6 sm:p-7 space-y-5">
                        <h3 class="text-lg font-bold font-heading text-[#0F172A]">
                            Fasilitas &amp; Manfaat Kursus
                        </h3>

                        <div class="space-y-4">
                            <!-- Benefit 1 -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-[#0F172A]">Pembelajaran Mandiri Berbasis Video</div>
                                    <p class="text-xs text-[#64748B] leading-relaxed">
                                        Pelajari materi fleksibel dari perangkat Anda tanpa batas waktu pertemuan, dapat diulang kapan pun.
                                    </p>
                                </div>
                            </div>

                            <!-- Benefit 2 -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-[#0F172A]">Masa Aktif Akses 365 Hari</div>
                                    <p class="text-xs text-[#64748B] leading-relaxed">
                                        Akses video terbuka penuh selama 1 tahun untuk mendampingi proses belajar dan praktik proyek kerja riil.
                                    </p>
                                </div>
                            </div>

                            <!-- Benefit 3 -->
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-[#0F172A]">Sertifikat Digital Tanpa Ujian</div>
                                    <p class="text-xs text-[#64748B] leading-relaxed">
                                        Sertifikat kelulusan resmi diterbitkan otomatis begitu seluruh video materi tuntas ditonton 100%.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SEKSI SILABUS (fill: #F8FAFC) -->
    <section class="bg-[#F8FAFC] py-10 sm:py-12 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                    Silabus Kelas
                </h2>
                <p class="text-sm text-[#334155] mt-1">
                    {{ $kelas->moduls->count() }} Modul &middot; {{ $totalMateri }} Video Materi
                </p>
            </div>

            <div class="space-y-4">
                @forelse($kelas->moduls as $mIndex => $modul)
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm overflow-hidden">
                        <!-- Modul Header -->
                        <div class="bg-[#F1F5F9] px-6 py-4 flex items-center justify-between">
                            <span class="text-sm sm:text-base font-bold font-heading text-[#0F172A]">
                                Modul {{ $mIndex + 1 }}: {{ $modul->judul }}
                            </span>
                            <span class="text-xs font-mono text-[#64748B] uppercase font-semibold">
                                {{ $modul->materis->count() }} VIDEO
                            </span>
                        </div>

                        <!-- Video Rows -->
                        <div class="divide-y divide-[#E2E8F0]">
                            @forelse($modul->materis as $vIndex => $materi)
                                <div class="px-6 py-4 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        @if($materi->is_preview)
                                            <button type="button" onclick="openPreviewModal()" class="text-[#D11F2E] hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                                </svg>
                                            </button>
                                        @else
                                            <svg class="w-5 h-5 text-[#64748B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        @endif

                                        <span class="text-sm font-medium {{ $materi->is_preview ? 'text-[#0F172A]' : 'text-[#334155]' }}">
                                            {{ $materi->judul }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        @if($materi->is_preview)
                                            <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                                Cuplikan Gratis
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-[11px] font-medium text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                                Terkunci
                                            </span>
                                        @endif

                                        @if($materi->durasi_menit)
                                            <span class="text-xs font-mono text-[#64748B]">
                                                {{ sprintf('%02d:00', $materi->durasi_menit) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-4 text-xs text-[#334155]">
                                    {{ $kelas->isOfflineCatalog() ? 'Sesi praktik tatap muka & evaluasi gambar kerja langsung bersama instruktur di studio.' : 'Belum ada video materi di modul ini.' }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    @if(!empty($kelas->modul_offline) && is_array($kelas->modul_offline))
                        @foreach($kelas->modul_offline as $idx => $judulMod)
                            <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm overflow-hidden">
                                <div class="bg-[#F1F5F9] px-6 py-4 flex items-center justify-between">
                                    <span class="text-sm sm:text-base font-bold font-heading text-[#0F172A]">
                                        {{ str_starts_with(strtolower($judulMod), 'modul') ? $judulMod : 'Modul ' . ($idx + 1) . ': ' . $judulMod }}
                                    </span>
                                    <span class="text-xs font-mono text-[#D11F2E] uppercase font-semibold">
                                        TATAP MUKA STUDIO
                                    </span>
                                </div>
                                <div class="px-6 py-4 text-xs text-[#334155]">
                                    Sesi praktik tatap muka &amp; evaluasi gambar kerja langsung bersama instruktur di studio.
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-8 text-center text-slate-500">
                            Silabus kurikulum sedang disusun oleh instruktur.
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </section>

    <!-- MODAL CUPLIKAN GRATIS (Screen 4: G-03 Modal Preview Video, fill: #0B0F19CC) -->
    @if($previewMateri)
        <div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0B0F19]/80 backdrop-blur-sm">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] max-w-3xl w-full overflow-hidden shadow-2xl space-y-4 p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                    <div>
                        <span class="text-xs font-mono text-[#D11F2E] font-bold uppercase tracking-wider">Cuplikan Gratis ({{ $previewLimitSeconds }} Detik)</span>
                        <h3 class="text-base font-bold font-heading text-[#0F172A]">{{ $previewMateri->judul }}</h3>
                    </div>
                    <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-700 p-1">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Video Viewport -->
                <div class="relative aspect-video bg-black rounded-xl overflow-hidden">
                    @if($previewMateri->is_youtube)
                        <iframe id="previewYoutubePlayer"
                                data-src="{{ $previewMateri->youtube_embed_url }}&autoplay=1&end={{ $previewLimitSeconds }}"
                                src=""
                                title="{{ $previewMateri->judul }}"
                                class="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    @else
                        <video id="previewVideoPlayer" class="w-full h-full object-contain" controls controlsList="nodownload">
                            <source src="{{ route('preview.video', $previewMateri->id) }}" type="video/mp4">
                            Peramban Anda tidak mendukung pemutar video.
                        </video>
                    @endif

                    <!-- Limit Overlay (shows when limit exceeded) -->
                    <div id="previewLockOverlay" class="hidden absolute inset-0 bg-[#0B0F19]/90 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 space-y-4">
                        <div class="w-12 h-12 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-base font-bold text-white font-heading">Batas Cuplikan Gratis Selesai</h4>
                            <p class="text-xs text-slate-300 max-w-md">
                                Dapatkan akses video lengkap tanpa batasan waktu dengan mendaftar kelas ini sekarang.
                            </p>
                        </div>
                        <a href="{{ route('checkout.create', $kelas->slug) }}"
                           class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] shadow">
                            Beli Kelas Sekarang
                        </a>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between text-xs text-[#64748B] pt-2">
                    <span>Durasi cuplikan dibatasi {{ $previewLimitSeconds }} detik.</span>
                    <button type="button" onclick="closePreviewModal()" class="px-4 py-1.5 rounded-lg border border-[#E2E8F0] hover:bg-slate-50 font-medium text-[#0F172A]">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <script>
            let previewYtTimeout = null;

            function openPreviewModal() {
                const modal = document.getElementById('previewModal');
                const video = document.getElementById('previewVideoPlayer');
                const ytIframe = document.getElementById('previewYoutubePlayer');
                const overlay = document.getElementById('previewLockOverlay');
                const limit = {{ $previewLimitSeconds }};

                if (modal) {
                    modal.classList.remove('hidden');
                    if (overlay) overlay.classList.add('hidden');
                }

                if (video) {
                    video.currentTime = 0;
                    video.play();
                }

                if (ytIframe) {
                    ytIframe.src = ytIframe.getAttribute('data-src');
                    if (previewYtTimeout) clearTimeout(previewYtTimeout);
                    previewYtTimeout = setTimeout(function() {
                        ytIframe.src = '';
                        if (overlay) overlay.classList.remove('hidden');
                    }, limit * 1000);
                }
            }

            function closePreviewModal() {
                const modal = document.getElementById('previewModal');
                const video = document.getElementById('previewVideoPlayer');
                const ytIframe = document.getElementById('previewYoutubePlayer');
                if (previewYtTimeout) clearTimeout(previewYtTimeout);
                if (video) {
                    video.pause();
                }
                if (ytIframe) {
                    ytIframe.src = '';
                }
                if (modal) {
                    modal.classList.add('hidden');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('previewVideoPlayer');
                const overlay = document.getElementById('previewLockOverlay');
                const limit = {{ $previewLimitSeconds }};
                if (video) {
                    video.addEventListener('timeupdate', function() {
                        if (video.currentTime >= limit) {
                            video.pause();
                            if (overlay) overlay.classList.remove('hidden');
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
