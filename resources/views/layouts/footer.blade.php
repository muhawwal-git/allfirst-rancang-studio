<!-- Global Architectural Footer (fill: #0B0F19) -->
<footer class="bg-[#0B0F19] text-slate-300 border-t border-[#1E293B]">
    <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 py-12 lg:py-14">
        <!-- Footer Top: Studio Info & Quick Links -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 pb-10 border-b border-[#1E293B]">
            <!-- Studio Info -->
            <div class="space-y-3 max-w-xl">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-ars.png') }}" alt="Allfirst Rancang Studio" class="h-9 w-auto object-contain">
                    <span class="text-lg font-bold font-heading text-white tracking-tight">
                        Allfirst Rancang Studio
                    </span>
                </a>
                <p class="text-sm text-[#94A3B8] leading-relaxed">
                    Studio Pelatihan Allfirst Rancang Studio &middot; Layanan Pelatihan Software CAD, BIM, &amp; 3D Modeling Arsitektur &middot; Bimbingan Online Mandiri &amp; Tatap Muka Studio
                </p>
            </div>

            <!-- Quick Links -->
            <div class="flex flex-wrap items-center gap-6 sm:gap-8 text-sm">
                <a href="{{ route('kelas.index') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                    Kelas Online
                </a>
                <a href="{{ route('offline') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                    Kelas Offline
                </a>
                <a href="{{ route('ebook') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                    E-Book
                </a>
                <a href="{{ route('faq') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                    FAQ
                </a>
                @guest
                    <a href="{{ route('login') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                        Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                        Registrasi Siswa
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="text-[#CBD5E1] hover:text-white transition-colors">
                        Dashboard Siswa
                    </a>
                @endguest
            </div>
        </div>

        <!-- Footer Bottom: Copyright & Architectural Badge -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <p class="text-[#64748B]">
                &copy; {{ date('Y') }} Allfirst Rancang Studio. Seluruh hak cipta dilindungi undang-undang.
            </p>
            <div class="text-[#475569] font-mono tracking-wider uppercase font-semibold">
                ARCHITECTURAL &amp; ENGINEERING CAD TRAINING
            </div>
        </div>
    </div>
</footer>
