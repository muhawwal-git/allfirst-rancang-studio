@extends('layouts.app')

@section('title', 'Katalog Kelas Online — Allfirst Rancang Studio')
@section('meta_description', 'Katalog pelatihan software arsitektur & sipil: AutoCAD, SketchUp, Revit, dan Lumion. Materi berstandar konsultan DED dengan akses video 1 tahun.')

@section('content')
    <!-- HERO KATALOG (Header Only with subtle radial-faded CAD grid per UI Design PDF) -->
    <section class="relative py-14 sm:py-18 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <!-- 4-Sided Radial-Faded CAD Grid Overlay -->
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="max-w-3xl space-y-4">
                <!-- Badge Direktori Kelas Mandiri -->
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-xs font-semibold text-[#D11F2E] uppercase tracking-wide">
                    <span>DIREKTORI KELAS MANDIRI &middot; AKSES 1 TAHUN</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#0F172A] tracking-tight leading-tight">
                    Katalog Kelas Online
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-[17px] text-[#334155] leading-relaxed">
                    Tingkatkan keahlian teknis Anda secara mandiri melalui kurikulum terarah berbasis proyek nyata. Setiap kelas menyajikan materi video lengkap dengan hak akses selama 1 tahun penuh (365 hari).
                </p>
            </div>
        </div>
    </section>

    <!-- KATALOG GRID SECTION (Clean solid #F8FAFC background without CAD grid per UI Design PDF) -->
    <section class="bg-[#F8FAFC] py-12 sm:py-16 min-h-[600px] border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">
            <!-- Filter Bar (fill: #FFFFFF, stroke: #E2E8F0) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 sm:p-5 shadow-sm flex flex-wrap items-center gap-2.5 sm:gap-3">
                <span class="text-sm font-bold text-[#0F172A] pr-2">
                    Kategori Software:
                </span>

                <a href="{{ route('kelas.index') }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all {{ is_null($selectedCategory) ? 'bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] font-bold shadow-sm' : 'bg-[#F1F5F9] border border-[#E2E8F0] text-[#334155] hover:bg-slate-200/70' }}">
                    Semua Software
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('kelas.index', ['kategori' => $cat]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all {{ $selectedCategory === $cat ? 'bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] font-bold shadow-sm' : 'bg-[#F1F5F9] border border-[#E2E8F0] text-[#334155] hover:bg-slate-200/70' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Course Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($courses as $course)
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-[0_10px_30px_-5px_rgba(15,23,42,0.05)] hover:shadow-[0_15px_35px_-5px_rgba(15,23,42,0.1)] transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                        <!-- 16:9 Thumbnail -->
                        <div class="relative aspect-video w-full p-4 bg-gradient-to-br from-[#0F172A] via-[#1E293B] to-[#4C0519] border-b border-[#E2E8F0] flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded bg-[#1E293B] text-[10px] font-mono text-[#E2E8F0] uppercase font-semibold">
                                    {{ strtoupper($course->kategori) }} WORKSPACE
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
                                        {{ $course->kategori }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                        Akses 1 Tahun
                                    </span>
                                </div>

                                <!-- Course Title -->
                                <h2 class="text-lg font-bold font-heading text-[#0F172A] group-hover:text-[#D11F2E] transition-colors leading-snug">
                                    <a href="{{ route('kelas.show', $course->slug) }}">
                                        {{ $course->judul }}
                                    </a>
                                </h2>

                                <!-- Description -->
                                <p class="text-sm text-[#64748B] line-clamp-2 leading-relaxed">
                                    {{ $course->deskripsi_singkat }}
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between mt-4">
                                <span class="text-lg font-bold font-mono text-[#0F172A]">
                                    {{ $course->formatted_harga }}
                                </span>

                                <a href="{{ route('kelas.show', $course->slug) }}"
                                   class="px-3.5 py-2 rounded-lg text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors">
                                    Lihat Detail Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-[#E2E8F0] p-8">
                        <p class="text-slate-500 font-medium">Belum ada kelas yang tersedia untuk kategori yang dipilih.</p>
                        <a href="{{ route('kelas.index') }}" class="inline-block mt-3 text-sm text-[#D11F2E] font-semibold hover:underline">
                            Tampilkan Semua Kelas &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="pt-6">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
