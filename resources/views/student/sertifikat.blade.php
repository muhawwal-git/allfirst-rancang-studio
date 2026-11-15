<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Kelulusan — {{ $sertifikat->nomor_sertifikat }} — {{ $sertifikat->user->name }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Styles -->
    @vite(['resources/css/app.css'])

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        @media print {
            body {
                background: white !important;
                color: #0F172A !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .certificate-container {
                box-shadow: none !important;
                border: 2px solid #D11F2E !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: 100vw !important;
                padding: 1.5cm !important;
            }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen flex flex-col items-center py-8 px-4 sm:px-6 lg:px-8 antialiased">

    <!-- Header Bar (No Print) -->
    <div class="no-print w-full max-w-[1000px] mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm">
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('student.dashboard') }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                    &larr; Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('home') }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                    &larr; Beranda Studio
                </a>
            @endauth
            <span class="text-xs font-semibold text-[#047857]">
                ✓ Sertifikat Resmi Digital &middot; Terverifikasi
            </span>
        </div>

        <button type="button" onclick="window.print()"
                class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span>Unduh Sertifikat (PDF)</span>
        </button>
    </div>

    <!-- SERTIFIKAT LANDSCAPE FRAME (fill: #FFFFFF, stroke: #D11F2E) -->
    <div class="certificate-container w-full max-w-[1000px] aspect-[1.414/1] bg-white text-slate-900 rounded-2xl shadow-xl p-8 sm:p-14 relative flex flex-col justify-between overflow-hidden select-none border-2 border-[#D11F2E]">

        <!-- CAD Border Ornamentation -->
        <div class="absolute inset-3 border border-[#FFE4E6] pointer-events-none"></div>

        <!-- Kop Sertifikat -->
        <div class="relative text-center space-y-2">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/logo-ars.png') }}" alt="Allfirst Rancang Studio" class="h-10 w-auto object-contain">
                <span class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A] tracking-wider uppercase">
                    ALLFIRST RANCANG STUDIO
                </span>
            </div>
            <h2 class="text-xl sm:text-3xl font-extrabold font-heading text-[#0F172A] tracking-tight uppercase">
                SERTIFIKAT KELULUSAN RESMI
            </h2>
            <p class="text-xs sm:text-sm font-mono font-bold text-[#D11F2E]">
                Sertifikat Nomor : {{ $sertifikat->nomor_sertifikat }}
                <span class="sr-only">No. Registrasi Sertifikat: {{ $sertifikat->nomor_sertifikat }}</span>
            </p>
        </div>

        <!-- Penerima & Kompetensi -->
        <div class="relative text-center space-y-4 my-auto py-4">
            <p class="text-xs uppercase tracking-widest text-[#64748B] font-medium">
                Diberikan secara sah kepada:
            </p>

            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#0F172A] tracking-tight">
                {{ $sertifikat->user->name }}
            </h1>

            <div class="w-32 h-1 bg-gradient-to-r from-[#D11F2E] to-[#9E111B] mx-auto rounded-full"></div>

            <p class="text-xs sm:text-sm text-[#334155] max-w-xl mx-auto leading-relaxed">
                Atas keberhasilannya menyelesaikan seluruh kurikulum materi pelatihan dan pemodelan software:
            </p>

            <h3 class="text-lg sm:text-2xl font-bold font-heading text-[#0F172A]">
                {{ $sertifikat->kelas->judul }}
            </h3>

            <!-- Predikat & Rubrik Penilaian Studio (Sesuai Format Sertifikat Fisik Allfirst) -->
            <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-6 text-left">
                <div class="text-center sm:text-left">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-[#64748B] block">Predikat Kelulusan:</span>
                    <span class="text-2xl sm:text-3xl font-extrabold font-heading text-[#D11F2E] inline-block border-b-4 border-[#D11F2E] pb-0.5">
                        {{ $sertifikat->predikat ?? 'Baik' }}
                    </span>
                </div>

                <table class="text-[11px] border-collapse border border-[#0F172A]">
                    <thead>
                        <tr class="bg-[#F8FAFC]">
                            <th colspan="2" class="border border-[#0F172A] px-3 py-1 font-bold text-center tracking-wider uppercase text-[10px]">
                                RUBRIK PENILAIAN
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="{{ ($sertifikat->predikat ?? 'Baik') === 'Cukup' ? 'bg-[#FFF1F2] font-bold text-[#D11F2E]' : '' }}">
                            <td class="border border-[#0F172A] px-2.5 py-0.5 font-mono">60 - 70</td>
                            <td class="border border-[#0F172A] px-3 py-0.5">Cukup</td>
                        </tr>
                        <tr class="{{ ($sertifikat->predikat ?? 'Baik') === 'Baik' ? 'bg-[#FFF1F2] font-bold text-[#D11F2E]' : '' }}">
                            <td class="border border-[#0F172A] px-2.5 py-0.5 font-mono">70 - 80</td>
                            <td class="border border-[#0F172A] px-3 py-0.5">Baik</td>
                        </tr>
                        <tr class="{{ ($sertifikat->predikat ?? 'Baik') === 'Sangat Baik' ? 'bg-[#FFF1F2] font-bold text-[#D11F2E]' : '' }}">
                            <td class="border border-[#0F172A] px-2.5 py-0.5 font-mono">90 - 100</td>
                            <td class="border border-[#0F172A] px-3 py-0.5">Sangat Baik</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pengesahan & QR Verification -->
        <div class="relative pt-6 border-t border-[#E2E8F0] flex items-end justify-between">
            <!-- QR Verification -->
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white border border-[#CBD5E1] p-1.5 rounded-xl shadow-sm flex items-center justify-center">
                    {!! $qrCodeSvg !!}
                </div>
                <div class="space-y-0.5 text-left">
                    <span class="text-[11px] font-mono font-bold text-[#0F172A] block uppercase">
                        QR Verifikasi Keaslian
                    </span>
                    <span class="text-[10px] font-mono text-[#64748B] block">
                        Pindai untuk memeriksa status
                    </span>
                    <span class="text-[10px] font-mono text-[#D11F2E] block truncate max-w-[200px]">
                        {{ route('sertifikat.verifikasi', $sertifikat->hash_kode_qr) }}
                    </span>
                </div>
            </div>

            <!-- Tanda Tangan & Tanggal -->
            <div class="text-right space-y-1">
                <p class="text-xs text-[#64748B] font-mono">
                    Diterbitkan {{ $sertifikat->tanggal_terbit->translatedFormat('d F Y') }}
                </p>
                <div class="w-40 sm:w-48 border-b-2 border-[#0F172A] ml-auto pt-8"></div>
                <p class="text-xs sm:text-sm font-bold text-[#0F172A]">
                    Ir. Allfirst Rancang, IAI
                </p>
                <p class="text-[11px] text-[#64748B]">
                    Lead Instructor Allfirst Rancang Studio
                </p>
            </div>
        </div>
    </div>

</body>
</html>
