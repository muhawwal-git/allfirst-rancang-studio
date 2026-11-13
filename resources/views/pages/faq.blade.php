@extends('layouts.app')

@section('title', 'Pertanyaan yang Sering Diajukan (FAQ) — Allfirst Rancang Studio')
@section('meta_description', 'Jawaban atas pertanyaan umum seputar program kursus online, bimbingan offline, masa aktif 365 hari, sertifikat, dan metode belajar Allfirst Rancang Studio.')

@section('content')
    <!-- HERO FAQ (fill: linear-gradient(#FFFFFF, #FFF7F7, #F8FAFC) + subtle radial-faded CAD grid overlay) -->
    <section class="relative py-14 sm:py-20 border-b border-[#F1F5F9] overflow-hidden"
             style="background-image: linear-gradient(145deg, #FFFFFF 0%, #FFF7F7 55%, #F8FAFC 100%);">
        <div class="pointer-events-none absolute inset-0 "
             style="background-image: url('{{ asset('images/bg-cad-grid-hero.png') }}?v=5'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="relative w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="max-w-3xl space-y-4">
                <!-- Badge FAQ -->
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-xs font-semibold text-[#D11F2E]">
                    <span>PUSAT INFORMASI &amp; TANYA JAWAB (FAQ)</span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#0F172A] tracking-tight leading-tight">
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-[#334155] leading-relaxed">
                    Temukan jawaban cepat dan transparan seputar masa aktif belajar 365 hari, alur pendaftaran, sertifikat resmi, dan metode pembelajaran kami.
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ LIST SECTION (fill: #FAFAFC) -->
    <section class="bg-[#FAFAFC] py-16 sm:py-20 border-b border-[#E2E8F0]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- FAQ Accordion Items -->
            <div class="space-y-4">
                @foreach($faqs as $index => $faq)
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm p-6 space-y-3 transition-colors">
                        <div class="flex items-center justify-between gap-4 cursor-pointer"
                             onclick="toggleFaq({{ $index }})">
                            <h3 class="text-base sm:text-lg font-bold font-heading text-[#0F172A]">
                                {{ $index + 1 }}. {{ $faq['question'] }}
                            </h3>
                            <div id="icon-{{ $index }}" class="text-[#D11F2E] shrink-0 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <div id="answer-{{ $index }}" class="pt-2 border-t border-[#F1F5F9] text-sm sm:text-base text-[#334155] leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FAQ Bottom CTA (fill: #FFFFFF, stroke: #F1F5F9) -->
            <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm p-8 sm:p-10 text-center space-y-4 mt-12">
                <h3 class="text-xl sm:text-2xl font-bold font-heading text-[#0F172A]">
                    Masih Punya Pertanyaan Lain?
                </h3>
                <p class="text-sm text-[#64748B] max-w-xl mx-auto leading-relaxed">
                    Tim admin Allfirst Rancang Studio siap membantu Anda melalui layanan WhatsApp resmi untuk informasi lebih lanjut.
                </p>
                <div class="pt-2">
                    <a href="https://wa.me/{{ config('allfirst.admin_whatsapp', '6282212345678') }}?text={{ urlencode('Halo Admin Allfirst Studio, saya ingin bertanya lebih lanjut mengenai kursus.') }}"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                        <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.771.83 2.791.83h.002c3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.77-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.761 0-3.411-.46-4.854-1.266l-5.146 1.346 1.371-5.012c-.896-1.492-1.371-3.21-1.371-5.068 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                        </svg>
                        <span>Tanya Admin via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleFaq(index) {
            const ans = document.getElementById('answer-' + index);
            const icon = document.getElementById('icon-' + index);
            if (ans && icon) {
                ans.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            }
        }
    </script>
@endsection
