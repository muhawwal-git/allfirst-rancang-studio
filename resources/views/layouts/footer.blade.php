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

                <!-- Social Media & Maps Links -->
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="https://www.instagram.com/allfirst.id?fqnt=cnR1Z2h5cW5iNHYy"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-xs font-semibold text-[#CBD5E1] hover:text-white border border-white/10 transition-colors">
                        <svg class="w-4 h-4 text-[#E1306C]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <span>@allfirst.id</span>
                    </a>

                    <a href="https://maps.app.goo.gl/T1YSXMVx7mrEcEq4A?g_st=iw"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-xs font-semibold text-[#CBD5E1] hover:text-white border border-white/10 transition-colors">
                        <svg class="w-4 h-4 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Lokasi Studio (Google Maps)</span>
                    </a>
                </div>
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
