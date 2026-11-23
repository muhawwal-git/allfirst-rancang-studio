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
                    <a href="https://wa.me/{{ \App\Models\PengaturanSitus::getAdminWhatsapp() }}?text={{ urlencode('Halo Admin Allfirst Studio, saya ingin bertanya lebih lanjut mengenai kursus.') }}"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                        <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
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
