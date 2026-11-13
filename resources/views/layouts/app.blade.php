<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kursus Software Arsitektur & Sipil Siap Kerja') — {{ config('allfirst.brand_name', 'Allfirst Rancang Studio') }}</title>
    <meta name="description" content="@yield('meta_description', 'Studio pelatihan software perancangan arsitektur, sipil & BIM profesional: AutoCAD 2D/3D, SketchUp, Revit, dan Lumion berstandar industri konsultan DED.')">

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

    @stack('scripts')
</body>
</html>
