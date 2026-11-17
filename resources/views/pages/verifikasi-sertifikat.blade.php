@extends('layouts.app')

@section('title', 'Verifikasi Keaslian Sertifikat — Allfirst Rancang Studio')

@section('content')
    <!-- HERO BANNER (Light Architectural Studio Theme) -->
    <section class="relative bg-white text-slate-900 pt-14 pb-16 border-b border-[#E2E8F0]">
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-[#F1F5F9] border border-[#E2E8F0] text-xs font-mono text-[#D11F2E] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#D11F2E]"></span>
                [PUBLIC REGISTRY: CERTIFICATE AUTHENTICATION]
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-heading text-[#0F172A] tracking-tight">
                Verifikasi Keaslian Sertifikat Digital
            </h1>
            <p class="text-[#475569] text-sm sm:text-base mt-3 max-w-2xl mx-auto leading-relaxed">
                Pusat validasi data kelulusan resmi Allfirst Rancang Studio untuk memastikan keaslian kredensial kompetensi peserta pelatihan AEC.
            </p>
        </div>
    </section>

    <!-- VERIFICATION RESULT CONTAINER -->
    <section class="py-16 bg-[#F8FAFC] min-h-[500px]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($sertifikat)
                <!-- SUCCESS VALID CERTIFICATE CARD -->
                <div class="rounded-2xl bg-white border-2 border-[#10B981] p-8 sm:p-10 shadow-lg relative overflow-hidden space-y-8">
                    <!-- Top Ribbon Badge -->
                    <div class="flex items-center justify-between pb-6 border-b border-[#E2E8F0]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] flex items-center justify-center text-2xl font-bold">
                                &check;
                            </div>
                            <div>
                                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#047857] block">STATUS VALID & RESMI</span>
                                <h2 class="text-xl font-bold font-heading text-[#0F172A]">Sertifikat Terverifikasi Asli</h2>
                            </div>
                        </div>
                        <div class="w-16 h-16 p-1 bg-white rounded-lg border border-[#CBD5E1] shadow shrink-0 hidden sm:flex items-center justify-center">
                            {!! $qrCodeSvg !!}
                        </div>
                    </div>

                    <!-- Certificate Metadata Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs font-mono">
                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Nama Penerima:</span>
                            <span class="text-base font-bold text-[#0F172A] block">{{ $sertifikat->user->name }}</span>
                        </div>

                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Nomor Registrasi:</span>
                            <span class="text-base font-bold text-[#0F172A] block">{{ $sertifikat->nomor_sertifikat }}</span>
                        </div>

                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] sm:col-span-2">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Program Pelatihan / Kompetensi:</span>
                            <span class="text-base font-bold text-[#D11F2E] block">{{ $sertifikat->kelas->judul_kelas }}</span>
                            <span class="text-[11px] text-[#64748B] mt-1 block">Kategori Software: {{ $sertifikat->kelas->kategori }} &middot; Standar DED Konsultan Perencana</span>
                        </div>

                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Predikat &amp; Nilai Rubrik:</span>
                            <span class="text-sm font-bold text-[#D11F2E] block">{{ $sertifikat->predikat ?? 'Baik' }} (Skor: {{ $sertifikat->nilai ?? 80 }})</span>
                        </div>

                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Tanggal Kelulusan:</span>
                            <span class="text-sm font-bold text-[#0F172A] block">{{ $sertifikat->tanggal_terbit->translatedFormat('d F Y') }}</span>
                        </div>

                        <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <span class="text-[#64748B] block mb-1 uppercase tracking-wider">Lembaga Penerbit:</span>
                            <span class="text-sm font-bold text-[#0F172A] block">Allfirst Rancang Studio</span>
                        </div>
                    </div>

                    <!-- Assurance Footer -->
                    <div class="pt-6 border-t border-[#E2E8F0] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-mono text-[#64748B]">
                        <span>Basis Data Resmi Allfirst Rancang Studio &middot; Terintegrasi</span>
                        <a href="{{ route('home') }}" class="text-[#D11F2E] font-semibold hover:underline">
                            Kunjungi allfirst.id &rarr;
                        </a>
                    </div>
                </div>
            @else
                <!-- INVALID / NOT FOUND CERTIFICATE CARD -->
                <div class="rounded-2xl bg-white border border-[#FCA5A5] p-8 sm:p-10 shadow-lg text-center space-y-6">
                    <div class="w-16 h-16 rounded-full bg-[#FEF2F2] border border-[#FCA5A5] text-[#D11F2E] flex items-center justify-center text-3xl font-bold mx-auto">
                        &times;
                    </div>
                    <div class="space-y-2">
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-[#D11F2E] block">VERIFIKASI GAGAL</span>
                        <h2 class="text-2xl font-bold font-heading text-[#0F172A]">Sertifikat Tidak Ditemukan</h2>
                        <p class="text-xs text-[#64748B] max-w-md mx-auto leading-relaxed">
                            Kode registrasi atau QR code <strong class="text-[#0F172A] font-mono">{{ $hash }}</strong> tidak terdaftar pada pangkalan data resmi Allfirst Rancang Studio.
                        </p>
                    </div>
                    <div class="pt-4">
                        <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text=Halo%20Admin%20Allfirst,%20saya%20ingin%20mengonfirmasi%20keaslian%20sertifikat%20dengan%20kode%20{{ urlencode($hash) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-xs font-mono font-bold uppercase bg-[#0F172A] hover:bg-[#1E293B] text-white transition-colors shadow-sm">
                            <span>Hubungi Admin Studio untuk Konfirmasi Manual</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
