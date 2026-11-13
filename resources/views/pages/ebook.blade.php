@extends('layouts.app')

@section('title', 'Katalog E-Book Gratis — Allfirst Rancang Studio')
@section('meta_description', 'Unduh e-book panduan praktis AutoCAD, SketchUp, Revit, dan manajemen gambar kerja arsitektur gratis dari Allfirst Rancang Studio.')

@section('content')
    <!-- HERO E-BOOK (fill: linear-gradient(#FFFFFF, #FFF7F7, #F8FAFC) + subtle radial-faded CAD grid overlay) -->
    <section class="relative py-14 sm:py-20 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="max-w-3xl space-y-4">
                <!-- Badge E-Book Gratis -->
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-xs font-semibold text-[#D11F2E]">
                    <span>MODUL &amp; REFERENSI GRATIS UNTUK UMUM</span>
                    <span class="sr-only">Dokumen Referensi Gratis</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#0F172A] tracking-tight leading-tight">
                    Katalog E-Book Arsitektur &amp; Panduan Gambar Kerja
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-[#334155] leading-relaxed">
                    Unduh modul referensi teknis, daftar shortcut software, dan panduan standar gambar kerja arsitektur persembahan Allfirst Rancang Studio secara cuma-cuma.
                </p>
            </div>
        </div>
    </section>

    <!-- E-BOOK GRID SECTION (fill: #FAFAFC) -->
    <section class="bg-[#FAFAFC] py-16 sm:py-20 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($ebooks as $ebook)
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                        <!-- Cover 16:9 Thumbnail -->
                        <div class="relative aspect-video w-full p-5 bg-gradient-to-br from-[#0F172A] via-[#1E293B] to-[#4C0519] border-b border-[#E2E8F0] flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded bg-[#1E293B] text-[10px] font-mono text-[#F8FAFC] uppercase font-semibold">
                                    E-BOOK PDF &middot; GOOGLE DRIVE
                                </span>
                                <svg class="w-5 h-5 text-[#FDA4AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>

                            <div>
                                <span class="text-xs font-mono text-[#CBD5E1]">
                                    {{ $ebook['meta'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <!-- Badges -->
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-medium text-[#334155] bg-[#F1F5F9] border border-[#E2E8F0]">
                                        {{ $ebook['tag'] }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#059669] bg-[#ECFDF5] border border-[#A7F3D0]">
                                        Akses Gratis
                                    </span>
                                </div>

                                <!-- Title -->
                                <h2 class="text-lg font-bold font-heading text-[#0F172A] group-hover:text-[#D11F2E] transition-colors leading-snug">
                                    {{ $ebook['title'] }}
                                </h2>

                                <!-- Description -->
                                <p class="text-sm text-[#64748B] leading-relaxed">
                                    {{ $ebook['description'] }}
                                </p>
                            </div>

                            <!-- Card Footer Button -->
                            <div class="pt-4 border-t border-[#F1F5F9] mt-4">
                                <a href="{{ $ebook['gdrive_url'] }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Unduh via Google Drive</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
