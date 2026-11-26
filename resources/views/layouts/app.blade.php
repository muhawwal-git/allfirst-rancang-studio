<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kursus Software Arsitektur & Sipil Siap Kerja') — {{ config('allfirst.brand_name', 'Allfirst Rancang Studio') }}</title>
    <meta name="description" content="@yield('meta_description', 'Studio pelatihan software perancangan arsitektur, sipil & BIM profesional: AutoCAD 2D/3D, SketchUp, Revit, dan Enscape berstandar industri konsultan DED.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    <!-- Google Fonts: Space Grotesk, Plus Jakarta Sans, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-white font-sans text-slate-900 antialiased selection:bg-[#D11F2E] selection:text-white">

    <!-- Global Toast / Flash Notifications -->
    @if(session('status') || session('success') || session('error'))
        <div id="flash-banner" class="relative z-50 transition-all duration-300">
            @if(session('status') || session('success'))
                <div class="bg-emerald-600 text-white text-xs sm:text-sm font-medium py-2.5 px-4 text-center flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('status') ?? session('success') }}</span>
                    <button type="button" onclick="document.getElementById('flash-banner').remove()" class="ml-3 text-emerald-200 hover:text-white font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 text-white text-xs sm:text-sm font-medium py-2.5 px-4 text-center flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="document.getElementById('flash-banner').remove()" class="ml-3 text-red-200 hover:text-white font-bold">&times;</button>
                </div>
            @endif
        </div>
    @endif

    <!-- Global Navigation -->
    @include('layouts.navigation')

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    @include('layouts.footer')

    @unless(request()->routeIs('student.classroom', 'student.quiz.*', 'login', 'register', 'password.*', 'verification.*', 'student.sertifikat.show'))
        <!-- Floating WhatsApp Button (Bottom Right) -->
        <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text={{ urlencode('Halo Admin Allfirst Rancang Studio, saya ingin konsultasi mengenai program pelatihan.') }}"
           target="_blank"
           rel="noopener"
           aria-label="Konsultasi via WhatsApp"
           title="Konsultasi via WhatsApp"
           class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-40 inline-flex items-center justify-center rounded-full transition-transform duration-200 hover:scale-110 active:scale-95 focus:outline-none">
            <svg class="w-14 h-14 sm:w-[60px] sm:h-[60px] drop-shadow-[0_6px_16px_rgba(15,23,42,0.28)]" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="24" cy="24" r="21.5" fill="#25D366" stroke="#0F172A" stroke-width="2.5"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M31.6 16.3C29.6 14.3 26.9 13.2 24.1 13.2C18.2 13.2 13.5 17.9 13.5 23.8C13.5 25.7 14 27.5 14.9 29.1L13.4 34.6L19 33.1C20.5 34 22.3 34.4 24.1 34.4C30 34.4 34.7 29.7 34.7 23.8C34.7 21 33.6 18.3 31.6 16.3ZM24.1 32.6C22.5 32.6 20.9 32.2 19.6 31.4L19.3 31.2L15.9 32.1L16.8 28.8L16.6 28.5C15.7 27.1 15.3 25.5 15.3 23.8C15.3 18.9 19.2 15 24.1 15C26.5 15 28.7 15.9 30.3 17.6C32 19.2 32.9 21.5 32.9 23.8C32.9 28.7 29 32.6 24.1 32.6ZM28.9 26C28.6 25.9 27.3 25.2 27.1 25.1C26.8 25 26.7 25 26.5 25.2C26.3 25.5 25.8 26.1 25.7 26.2C25.5 26.4 25.4 26.4 25.1 26.3C24.8 26.2 24 25.9 23 25C22.2 24.3 21.7 23.4 21.6 23.2C21.4 22.9 21.6 22.8 21.7 22.6C21.8 22.5 22 22.3 22.1 22.2C22.2 22 22.3 21.9 22.4 21.7C22.5 21.6 22.4 21.4 22.4 21.3C22.3 21.2 21.8 19.9 21.6 19.3C21.4 18.8 21.1 18.9 21 18.9C20.8 18.9 20.7 18.9 20.5 18.9C20.3 18.9 20 19 19.8 19.2C19.5 19.5 18.8 20.1 18.8 21.4C18.8 22.7 19.8 24 19.9 24.2C20 24.3 21.7 27 24.4 28.1C25 28.4 25.5 28.6 25.9 28.7C26.5 28.9 27.1 28.9 27.5 28.8C28 28.7 29 28.2 29.3 27.6C29.5 27 29.5 26.5 29.4 26.4C29.3 26.2 29.2 26.2 28.9 26Z" fill="#FFFFFF"/>
            </svg>
        </a>
    @endunless

    @stack('scripts')
</body>
</html>
