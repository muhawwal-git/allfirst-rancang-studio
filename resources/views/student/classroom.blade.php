@extends('layouts.app')

@section('title', $currentMateri->judul . ' — ' . $kelas->judul)

@section('content')
    <!-- CLASSROOM TOPBAR (fill: #0B0F19, stroke: #1E293B) -->
    <div class="bg-[#0B0F19] text-white border-b border-[#1E293B] py-3.5 px-4 sm:px-6 lg:px-8">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Back & Title -->
            <div class="flex items-center gap-3">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-xs text-[#CBD5E1] hover:text-white transition-colors">
                    <span>&larr;</span>
                    <span>Kembali ke Dashboard Siswa</span>
                </a>
                <span class="text-[#475569]">|</span>
                <span class="text-sm font-semibold text-white truncate max-w-sm sm:max-w-md">
                    {{ $kelas->judul }}
                </span>
            </div>

            <!-- Badge Progres Belajar -->
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                    Progres Belajar: {{ $progressPercentage }}% Tuntas
                </span>

                @if($sertifikat)
                    <a href="{{ route('student.sertifikat.show', $sertifikat->nomor_sertifikat) }}"
                       class="px-3 py-1 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition-colors shadow-sm">
                        Lihat Sertifikat &rarr;
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- CLASSROOM WORKSPACE -->
    <div class="bg-[#F8FAFC] py-8 lg:py-10 border-b border-[#E2E8F0] min-h-[calc(100vh-180px)]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Video Stage (lg:col-span-8) (fill: #0F172A, rounded-2xl) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-[#0F172A] rounded-2xl p-4 sm:p-6 border border-slate-800 shadow-xl space-y-4">
                        <!-- Adaptive Video Player Container -->
                        <div class="relative w-full aspect-video rounded-xl bg-[#0B0F19] border border-[#1E293B] overflow-hidden flex items-center justify-center">
                            @if($currentMateri->is_youtube)
                                <iframe id="classroomYoutubePlayer"
                                        src="{{ $currentMateri->youtube_embed_url }}&start={{ (int) $progres->detik_terakhir_ditonton }}"
                                        title="{{ $currentMateri->judul }}"
                                        class="w-full h-full border-0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin"
                                        allowfullscreen></iframe>
                            @else
                                <video id="classroomPlayer"
                                       controls
                                       playsinline
                                       controlsList="nodownload"
                                       class="w-full h-full object-contain">
                                    <source src="{{ route('student.materi.stream', [$kelas->slug, $currentMateri->id]) }}" type="video/mp4">
                                    Peramban Anda tidak mendukung pemutar video streaming.
                                </video>
                            @endif
                        </div>

                        <!-- Video Action Bar -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                            <div class="space-y-1">
                                <h2 class="text-base sm:text-lg font-bold font-heading text-white">
                                    {{ $currentMateri->judul }}
                                </h2>
                                <p class="text-xs font-mono text-[#F87171] flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Smart Resume Aktif: Posisi terakhir tersimpan otomatis</span>
                                </p>
                            </div>

                            <form method="POST" action="{{ route('student.materi.complete', $currentMateri->id) }}" class="shrink-0">
                                @csrf
                                <button type="submit"
                                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                    Tandai Video Selesai
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Navigation Prev / Next Buttons -->
                    <div class="flex items-center justify-between pt-2">
                        @if($prevMateri)
                            <a href="{{ route('student.classroom', [$kelas->slug, $prevMateri->id]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                                &larr; Video Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextMateri)
                            <a href="{{ route('student.classroom', [$kelas->slug, $nextMateri->id]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#0F172A] hover:bg-slate-800 transition-colors shadow-sm">
                                Video Selanjutnya &rarr;
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Silabus Interaktif (lg:col-span-4) (fill: #FFFFFF, stroke: #E2E8F0) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 space-y-6">
                        <div class="space-y-3">
                            <h3 class="text-base font-bold font-heading text-[#0F172A]">
                                Silabus Pembelajaran
                            </h3>

                            <!-- Kelulusan Progress -->
                            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-xs font-medium text-[#0F172A]">
                                    <span>{{ $progressPercentage }}% Selesai</span>
                                    <span class="text-[#64748B] text-[11px]">(Sertifikat terbit otomatis di 100%)</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-[#F1F5F9] overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#D11F2E] to-[#9E111B] rounded-full transition-all duration-300"
                                         style="width: {{ $progressPercentage }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Moduls List -->
                        <div class="space-y-5">
                            @foreach($kelas->moduls as $mIndex => $modul)
                                <div class="space-y-2">
                                    <span class="text-[11px] font-mono text-[#64748B] uppercase font-bold tracking-wider block">
                                        Modul {{ $mIndex + 1 }}: {{ $modul->judul }}
                                    </span>

                                    <div class="space-y-1.5">
                                        @foreach($modul->materis as $materi)
                                            @php
                                                $isCurrent = $materi->id === $currentMateri->id;
                                                $isCompleted = in_array($materi->id, $completedMateriIds, true);
                                            @endphp

                                            <a href="{{ route('student.classroom', [$kelas->slug, $materi->id]) }}"
                                               class="flex items-center justify-between gap-3 p-3 rounded-xl border text-xs transition-colors {{ $isCurrent ? 'bg-[#FEF2F2] border-[#FECACA] text-[#D11F2E] font-semibold' : ($isCompleted ? 'bg-[#F8FAFC] border-[#E2E8F0] text-[#0F172A] hover:bg-slate-100' : 'bg-white border-[#E2E8F0] text-[#0F172A] hover:bg-slate-50') }}">
                                                <div class="flex items-center gap-2.5 truncate">
                                                    @if($isCompleted)
                                                        <svg class="w-4 h-4 text-[#047857] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif($isCurrent)
                                                        <svg class="w-4 h-4 text-[#D11F2E] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <span class="w-3.5 h-3.5 rounded-full border border-slate-400 shrink-0"></span>
                                                    @endif

                                                    <span class="truncate">{{ $materi->judul }}</span>
                                                </div>

                                                @if($materi->durasi_menit)
                                                    <span class="text-[11px] font-mono text-[#64748B] shrink-0">
                                                        {{ sprintf('%02d:00', $materi->durasi_menit) }}
                                                    </span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SMART RESUME JAVASCRIPT (HTML5 Video & YouTube Unlisted IFrame API) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const player = document.getElementById('classroomPlayer');
            const ytIframe = document.getElementById('classroomYoutubePlayer');
            const savedTime = {{ (int) $progres->detik_terakhir_ditonton }};
            const progressEndpoint = "{{ route('student.materi.progress', $currentMateri->id) }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function sendProgressSeconds(sec) {
                fetch(progressEndpoint, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ seconds: sec })
                }).catch(() => {});
            }

            if (player) {
                if (savedTime > 0) {
                    player.currentTime = savedTime;
                }

                let lastSentTime = 0;
                player.addEventListener('timeupdate', function() {
                    const currentSec = Math.floor(player.currentTime);
                    if (Math.abs(currentSec - lastSentTime) >= 5) {
                        lastSentTime = currentSec;
                        sendProgressSeconds(currentSec);
                    }
                });
            }

            if (ytIframe) {
                const tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                let ytPlayer = null;
                let ytInterval = null;
                let lastYtSent = savedTime;

                window.onYouTubeIframeAPIReady = function() {
                    ytPlayer = new YT.Player('classroomYoutubePlayer', {
                        events: {
                            'onStateChange': function(event) {
                                if (event.data === YT.PlayerState.PLAYING) {
                                    if (!ytInterval) {
                                        ytInterval = setInterval(function() {
                                            if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                                                const cur = Math.floor(ytPlayer.getCurrentTime());
                                                if (Math.abs(cur - lastYtSent) >= 5) {
                                                    lastYtSent = cur;
                                                    sendProgressSeconds(cur);
                                                }
                                            }
                                        }, 5000);
                                    }
                                } else {
                                    if (ytInterval) {
                                        clearInterval(ytInterval);
                                        ytInterval = null;
                                    }
                                    if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                                        const cur = Math.floor(ytPlayer.getCurrentTime());
                                        if (cur > 0) {
                                            sendProgressSeconds(cur);
                                        }
                                    }
                                }
                            }
                        }
                    });
                };
            }
        });
    </script>
@endsection
