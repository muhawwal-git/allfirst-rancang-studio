<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Admin') — Allfirst Rancang Studio</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8FAFC] font-sans text-[#0F172A] flex flex-col md:flex-row antialiased">

    @php
        $firstCourseForNav = \App\Models\Kelas::orderBy('id')->first();
        $kurikulumNavUrl = $firstCourseForNav
            ? route('admin.kelas.kurikulum', $firstCourseForNav->id)
            : route('admin.kelas.index');
        $isKurikulumActive = request()->routeIs('admin.kelas.kurikulum');
        $isKelolaKelasActive = request()->routeIs('admin.kelas.*') && ! $isKurikulumActive;
    @endphp

    <!-- ADMIN SIDEBAR (pen.dev: fill #0B0F19, pad 24, gap 6, width 260px) -->
    <aside class="w-full md:w-[260px] bg-[#0B0F19] text-white p-6 flex flex-col gap-1.5 shrink-0 md:min-h-screen">
        <!-- Admin Brand -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-6 px-1">
            <img src="{{ asset('images/logo-ars.png') }}" alt="Allfirst Rancang Studio" class="w-8 h-8 object-contain shrink-0">
            <div class="flex flex-col gap-0.5">
                <span class="text-[15px] font-bold font-heading text-white leading-tight tracking-tight">Allfirst Rancang Studio</span>
                <span class="text-[10px] font-mono text-[#94A3B8] tracking-widest uppercase">PANEL ADMIN</span>
            </div>
        </a>

        <!-- 5 Navigation Items (Dashboard, Verifikasi Pembayaran, Kelola Kelas, Kurikulum, Keluar Sistem) -->
        <nav class="flex flex-col gap-1.5 text-[13px]">
            <!-- 1. Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.dashboard')
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Verifikasi Pembayaran -->
            <a href="{{ route('admin.transaksi.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.transaksi.*')
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.transaksi.*') ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5zm-4 0a1 1 0 110 2 1 1 0 010-2z" />
                </svg>
                <span>Verifikasi Pembayaran</span>
            </a>

            <!-- 3. Data Siswa & Sertifikat -->
            <a href="{{ route('admin.siswa.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.siswa.*')
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Data Siswa &amp; Sertifikat</span>
            </a>

            <!-- 3. Kelola Kelas -->
            <a href="{{ route('admin.kelas.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ $isKelolaKelasActive
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ $isKelolaKelasActive ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2V3zm20 0h-6a4 4 0 00-4 4v14a3 3 0 013-3h7V3z" />
                </svg>
                <span>Kelola Kelas</span>
            </a>

            <!-- 4. Kurikulum -->
            <a href="{{ $kurikulumNavUrl }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ $isKurikulumActive
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ $isKurikulumActive ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12H3m13-6H3m9 12H3m13-6l5 3-5 3v-6z" />
                </svg>
                <span>Kurikulum</span>
            </a>

            <!-- 5. Testimoni Siswa -->
            <a href="{{ route('admin.testimoni.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.testimoni.*')
                       ? 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white font-semibold shadow-sm'
                       : 'text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.testimoni.*') ? 'text-white' : 'text-[#94A3B8]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <span>Testimoni Siswa</span>
            </a>

            <!-- 5. Keluar Sistem -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-xl text-[#CBD5E1] hover:text-white hover:bg-white/5 font-medium transition-all">
                    <svg class="w-4 h-4 shrink-0 text-[#94A3B8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4m7 14l5-5-5-5m5 5H9" />
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- MAIN CONTENT CANVAS (pen.dev: fill #F8FAFC, pad [36, 44], gap 24) -->
    <main class="flex-1 min-w-0 p-6 sm:p-9 lg:px-11 overflow-y-auto">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-[#FEF2F2] border border-[#FECACA] text-[#D11F2E] text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
