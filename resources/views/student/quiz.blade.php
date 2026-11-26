@extends('layouts.app')

@section('title', 'Quiz Evaluasi Akhir — ' . $kelas->judul)

@section('content')
    <div class="bg-[#F8FAFC] py-8 sm:py-12 min-h-screen border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">

            <!-- Top Breadcrumb & Course Header -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('student.classroom', $kelas->slug) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#64748B] hover:text-[#0F172A] transition-colors">
                            <span>&larr; Kembali ke Ruang Belajar</span>
                        </a>
                        <span class="text-[#CBD5E1]">&middot;</span>
                        <span class="px-2.5 py-0.5 rounded-md bg-[#FFF1F2] border border-[#FECDD3] text-[11px] font-mono font-bold text-[#D11F2E] uppercase">
                            EVALUASI AKHIR KELULUSAN
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                        {{ $kelas->judul }}
                    </h1>
                    <p class="text-xs sm:text-sm text-[#64748B]">
                        Jawab {{ $questions->count() }} soal evaluasi pemahaman materi di bawah ini dengan nilai minimal <strong class="text-[#0F172A]">{{ $passingGrade }}/100</strong>, lalu berikan ulasan belajar untuk menerbitkan sertifikat resmi.
                    </p>
                </div>

                <!-- Right Metrics Badges -->
                <div class="grid grid-cols-3 gap-3 shrink-0">
                    <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-4 py-3 text-center">
                        <span class="block text-[10px] font-mono font-bold text-[#64748B] uppercase">JUMLAH SOAL</span>
                        <span class="mt-0.5 block text-base sm:text-lg font-extrabold font-mono text-[#0F172A]">{{ $questions->count() }} Soal</span>
                    </div>
                    <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-4 py-3 text-center">
                        <span class="block text-[10px] font-mono font-bold text-[#64748B] uppercase">PASSING GRADE</span>
                        <span class="mt-0.5 block text-base sm:text-lg font-extrabold font-mono text-[#D11F2E]">{{ $passingGrade }} / 100</span>
                    </div>
                    <div class="rounded-xl {{ $hasPassedQuiz ? 'bg-[#ECFDF5] border-[#A7F3D0]' : 'bg-[#F8FAFC] border-[#E2E8F0]' }} border px-4 py-3 text-center">
                        <span class="block text-[10px] font-mono font-bold {{ $hasPassedQuiz ? 'text-[#047857]' : 'text-[#64748B]' }} uppercase">SKOR TERBAIK</span>
                        <span class="mt-0.5 block text-base sm:text-lg font-extrabold font-mono {{ $hasPassedQuiz ? 'text-[#047857]' : 'text-[#0F172A]' }}">
                            {{ $bestAttempt ? $bestAttempt->skor . '/100' : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 1. CERTIFICATE CLAIMED BANNER (If Certificate Already Issued) -->
            @if($sertifikat)
                <div class="bg-[#ECFDF5] border-2 border-[#A7F3D0] rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#047857] text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-white border border-[#A7F3D0] text-[11px] font-mono font-bold text-[#047857]">
                                    SERTIFIKAT RESMI TERBIT &middot; {{ $sertifikat->nomor_sertifikat }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md bg-[#047857] text-white text-[11px] font-mono font-bold">
                                    Nilai: {{ $sertifikat->nilai }}/100 ({{ $sertifikat->predikat }})
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-extrabold font-heading text-[#064E3B]">
                                Selamat, {{ $user->name }}! Anda Telah Menyelesaikan Seluruh Tahap Kelulusan
                            </h2>
                            <p class="text-xs sm:text-sm text-[#047857]">
                                Sertifikat kelulusan ber-QR Code resmi Anda sudah aktif dan dapat langsung dilihat, dicetak, atau diverifikasi secara publik.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto shrink-0">
                        <a href="{{ route('student.sertifikat.show', $sertifikat->nomor_sertifikat) }}"
                           class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                            <span>Lihat &amp; Cetak Sertifikat Resmi</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- 2. LATEST QUIZ ATTEMPT FEEDBACK BANNER (Failed vs Passed) -->
            @if($latestAttempt && !$latestAttempt->lulus && !$hasPassedQuiz)
                <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#D11F2E] text-white flex items-center justify-center shrink-0 font-mono font-bold text-sm">
                            {{ $latestAttempt->skor }}
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm sm:text-base font-bold font-heading text-[#991B1B]">
                                Skor Evaluasi Terakhir: {{ $latestAttempt->skor }}/100 ({{ $latestAttempt->jumlah_benar }} dari {{ $latestAttempt->total_soal }} Soal Benar) &mdash; Belum Mencapai Passing Grade
                            </h3>
                            <p class="text-xs text-[#B91C1C]">
                                Batas nilai kelulusan minimal adalah <strong>{{ $passingGrade }}/100</strong>. Silakan periksa kembali jawaban Anda pada arena quiz di bawah dan kirim ulang.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 3. RATING & TESTIMONI FORM (Unlocked Once Quiz Is Passed) -->
            @if($hasPassedQuiz)
                <div class="bg-white rounded-2xl border-2 {{ $sertifikat ? 'border-[#E2E8F0]' : 'border-[#D11F2E]' }} shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0F172A] via-[#1E293B] to-[#3F1D24] px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-bold text-[#FDA4AF] uppercase tracking-wider">
                                <span>&#10003; LULUS QUIZ EVALUASI (SKOR TERBAIK: {{ $bestAttempt->skor }}/100)</span>
                            </span>
                            <h2 class="text-lg sm:text-xl font-extrabold font-heading text-white">
                                {{ $sertifikat ? 'Ulasan & Rating Pengalaman Belajar Anda' : 'Langkah Terakhir: Berikan Rating & Ulasan untuk Klaim Sertifikat' }}
                            </h2>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-mono font-semibold text-white shrink-0">
                            {{ $testimoni ? 'Ulasan Tersimpan' : 'Wajib Diisi Sebelum Terbit Sertifikat' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('student.quiz.testimoni', $kelas->slug) }}" class="p-6 sm:p-8 space-y-6">
                        @csrf

                        <!-- Interactive Star Rating Selector (1 - 5) -->
                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-[#0F172A]">
                                Penilaian Bintang Kelas Ini <span class="text-[#D11F2E]">*</span>
                            </label>
                            @php
                                $currentRating = (int) old('rating', $testimoni?->rating ?? 5);
                            @endphp
                            <input type="hidden" name="rating" id="ratingInput" value="{{ $currentRating }}">

                            <div class="flex flex-wrap items-center gap-3">
                                <div class="inline-flex items-center gap-1.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl px-4 py-2.5" id="starContainer">
                                    @for($star = 1; $star <= 5; $star++)
                                        <button type="button"
                                                onclick="selectStarRating({{ $star }})"
                                                data-star="{{ $star }}"
                                                aria-label="Beri {{ $star }} Bintang"
                                                class="star-btn p-1 transition-transform hover:scale-110 focus:outline-none">
                                            <svg class="w-7 h-7 {{ $star <= $currentRating ? 'text-[#F59E0B]' : 'text-[#CBD5E1]' }} transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                <span id="ratingLabelText" class="text-xs font-mono font-bold text-[#0F172A] px-3 py-1.5 rounded-lg bg-[#FFFBEB] border border-[#FDE68A] text-[#B45309]">
                                    {{ $currentRating }} / 5 Bintang &mdash; Sangat Memuaskan
                                </span>
                            </div>
                            @error('rating')
                                <p class="text-xs text-[#D11F2E] font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Profession / Role -->
                            <div class="md:col-span-4 space-y-2">
                                <label for="profesi" class="block text-xs sm:text-sm font-bold text-[#0F172A]">
                                    Profesi / Latar Belakang Anda
                                </label>
                                <input type="text"
                                       id="profesi"
                                       name="profesi"
                                       value="{{ old('profesi', $testimoni?->profesi ?? $user->status_profesi) }}"
                                       placeholder="Contoh: Mahasiswa Arsitektur / Drafter Sipil"
                                       class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs sm:text-sm text-[#0F172A] placeholder-[#94A3B8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                                <p class="text-[11px] text-[#64748B]">
                                    Ditampilkan bersama nama Anda saat testimoni dimuat di Beranda.
                                </p>
                                @error('profesi')
                                    <p class="text-xs text-[#D11F2E] font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Testimonial Review Text -->
                            <div class="md:col-span-8 space-y-2">
                                <label for="ulasan" class="block text-xs sm:text-sm font-bold text-[#0F172A]">
                                    Testimoni &amp; Pengalaman Belajar <span class="text-[#D11F2E]">*</span>
                                </label>
                                <textarea id="ulasan"
                                          name="ulasan"
                                          rows="3"
                                          required
                                          minlength="10"
                                          placeholder="Ceritakan manfaat materi kelas ini bagi peningkatan skill kerja atau tugas studio Anda..."
                                          class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs sm:text-sm text-[#0F172A] placeholder-[#94A3B8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">{{ old('ulasan', $testimoni?->ulasan) }}</textarea>
                                @error('ulasan')
                                    <p class="text-xs text-[#D11F2E] font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-[#F1F5F9]">
                            <p class="text-xs text-[#64748B]">
                                @if($sertifikat)
                                    Ulasan Anda telah tercatat. Anda tetap dapat memperbarui isi testimoni kapan saja.
                                @else
                                    Begitu ulasan dikirim, <strong>Sertifikat Resmi Ber-QR Code</strong> Anda langsung terbit otomatis saat ini juga.
                                @endif
                            </p>

                            <button type="submit"
                                    class="px-6 py-3.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all shrink-0">
                                {{ $sertifikat ? 'Perbarui Rating & Testimoni' : 'Kirim Ulasan & Terbitkan Sertifikat Resmi →' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 4. INTERACTIVE 10-QUESTION ASSESSMENT ARENA (1 Question Per Screen + Question Map) -->
            <div class="space-y-4">
                @if($hasPassedQuiz)
                    <div class="flex items-center justify-between bg-white rounded-xl border border-[#E2E8F0] px-5 py-3.5">
                        <span class="text-xs font-semibold text-[#334155]">
                            Ingin mengerjakan ulang Quiz Evaluasi untuk meningkatkan nilai pada sertifikat Anda?
                        </span>
                        <button type="button"
                                onclick="document.getElementById('quizArenaSection').classList.toggle('hidden')"
                                class="px-4 py-2 rounded-lg text-xs font-semibold text-[#0F172A] bg-[#F8FAFC] hover:bg-slate-100 border border-[#CBD5E1] transition-colors">
                            Buka / Ulangi Arena Quiz
                        </button>
                    </div>
                @endif

                <div id="quizArenaSection" class="{{ $hasPassedQuiz ? 'hidden' : '' }}">
                    <form method="POST" action="{{ route('student.quiz.submit', $kelas->slug) }}" id="quizEvaluationForm">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                            <!-- LEFT COLUMN: Active Question Focus Card (lg:col-span-8) -->
                            <div class="lg:col-span-8 space-y-5">

                                <!-- Live Progress Bar Header -->
                                <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5 shadow-sm space-y-2.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-[#0F172A]" id="activeStepIndicator">
                                            Pertanyaan 1 dari {{ $questions->count() }}
                                        </span>
                                        <span class="font-mono font-bold text-[#D11F2E]" id="answeredCounterText">
                                            0 / {{ $questions->count() }} Terjawab
                                        </span>
                                    </div>
                                    <div class="w-full h-2.5 rounded-full bg-[#F1F5F9] overflow-hidden">
                                        <div id="quizLiveProgressBar"
                                             class="h-full bg-gradient-to-r from-[#D11F2E] to-[#9E111B] rounded-full transition-all duration-300"
                                             style="width: 0%;"></div>
                                    </div>
                                </div>

                                <!-- Question Cards Deck (Only 1 visible at a time via JS) -->
                                @foreach($questions as $qIndex => $q)
                                    @php
                                        $prevChosen = old("answers.{$q->id}", $latestAttempt->jawaban_siswa[$q->id] ?? '');
                                        $options = [
                                            'A' => $q->opsi_a,
                                            'B' => $q->opsi_b,
                                            'C' => $q->opsi_c,
                                            'D' => $q->opsi_d,
                                        ];
                                    @endphp

                                    <div class="quiz-question-card {{ $qIndex === 0 ? '' : 'hidden' }} bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-8 space-y-6"
                                         data-step-index="{{ $qIndex }}"
                                         data-question-id="{{ $q->id }}">

                                        <!-- Clean Question Number Header (No Redundant Topic Badge) -->
                                        <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-[#0F172A] text-white font-mono text-xs font-bold tracking-wider">
                                                SOAL {{ sprintf('%02d', $qIndex + 1) }} DARI {{ sprintf('%02d', $questions->count()) }}
                                            </span>
                                            <span class="text-xs font-mono text-[#64748B]">
                                                Bobot: {{ (int) round(100 / max(1, $questions->count())) }} Poin
                                            </span>
                                        </div>

                                        <!-- Question Prompt -->
                                        <h2 class="text-base sm:text-lg font-bold font-heading text-[#0F172A] leading-relaxed">
                                            {{ $q->pertanyaan }}
                                        </h2>

                                        <!-- 4 Interactive Option Cards (A, B, C, D) -->
                                        <div class="space-y-3">
                                            @foreach($options as $letter => $optionText)
                                                @php
                                                    $isChecked = strtoupper((string) $prevChosen) === $letter;
                                                @endphp
                                                <label class="quiz-option-label flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer transition-all
                                                    {{ $isChecked ? 'border-[#D11F2E] bg-[#FFF1F2]/60' : 'border-[#E2E8F0] bg-white hover:border-[#CBD5E1] hover:bg-[#F8FAFC]' }}"
                                                       data-step="{{ $qIndex }}"
                                                       data-letter="{{ $letter }}">
                                                    <input type="radio"
                                                           name="answers[{{ $q->id }}]"
                                                           value="{{ $letter }}"
                                                           {{ $isChecked ? 'checked' : '' }}
                                                           onchange="handleOptionSelect({{ $qIndex }}, '{{ $letter }}')"
                                                           class="sr-only">

                                                    <span class="quiz-option-badge w-8 h-8 rounded-lg font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors
                                                        {{ $isChecked ? 'bg-[#D11F2E] text-white' : 'bg-[#F1F5F9] text-[#0F172A] border border-[#E2E8F0]' }}">
                                                        {{ $letter }}
                                                    </span>

                                                    <span class="text-xs sm:text-sm font-medium text-[#0F172A] leading-relaxed pt-1">
                                                        {{ $optionText }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Step Navigation Bar Inside Question Card -->
                                        <div class="pt-4 border-t border-[#F1F5F9] flex items-center justify-between gap-4">
                                            @if($qIndex > 0)
                                                <button type="button"
                                                        onclick="goToQuestionStep({{ $qIndex - 1 }})"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors">
                                                    &larr; Soal Sebelumnya
                                                </button>
                                            @else
                                                <div></div>
                                            @endif

                                            @if($qIndex < $questions->count() - 1)
                                                <button type="button"
                                                        onclick="goToQuestionStep({{ $qIndex + 1 }})"
                                                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#0F172A] hover:bg-slate-800 transition-colors shadow-sm">
                                                    Soal Berikutnya &rarr;
                                                </button>
                                            @else
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                                                    <span>Kirim &amp; Nilai Jawaban Evaluasi</span>
                                                    <span>&rarr;</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- RIGHT COLUMN: Question Navigation Map Grid 01-10 (lg:col-span-4) -->
                            <div class="lg:col-span-4">
                                <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 space-y-5 sticky top-28">
                                    <div class="space-y-1">
                                        <h3 class="text-sm sm:text-base font-bold font-heading text-[#0F172A]">
                                            Peta Navigasi Soal
                                        </h3>
                                        <p class="text-xs text-[#64748B]">
                                            Klik nomor untuk berpindah cepat antar soal evaluasi.
                                        </p>
                                    </div>

                                    <!-- 5x2 Number Grid -->
                                    <div class="grid grid-cols-5 gap-2.5">
                                        @foreach($questions as $qIndex => $q)
                                            <button type="button"
                                                    id="map-btn-{{ $qIndex }}"
                                                    onclick="goToQuestionStep({{ $qIndex }})"
                                                    class="h-11 rounded-xl font-mono text-xs font-bold border-2 transition-all flex items-center justify-center
                                                        {{ $qIndex === 0 ? 'border-[#D11F2E] bg-[#FFF1F2] text-[#D11F2E]' : 'border-[#E2E8F0] bg-[#F8FAFC] text-[#0F172A] hover:border-[#CBD5E1]' }}">
                                                {{ sprintf('%02d', $qIndex + 1) }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Status Legend -->
                                    <div class="pt-3 border-t border-[#F1F5F9] grid grid-cols-3 gap-2 text-[11px] text-[#64748B]">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded bg-[#047857] inline-block shrink-0"></span>
                                            <span>Terjawab</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded border-2 border-[#D11F2E] bg-[#FFF1F2] inline-block shrink-0"></span>
                                            <span>Aktif</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded border border-[#CBD5E1] bg-[#F8FAFC] inline-block shrink-0"></span>
                                            <span>Belum</span>
                                        </div>
                                    </div>

                                    <!-- Primary Submit Button -->
                                    <div class="pt-2">
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                            <span>Selesaikan &amp; Nilai Quiz</span>
                                            <span>&rarr;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const totalQuizQuestions = {{ $questions->count() }};
        let currentQuizStep = 0;

        function goToQuestionStep(stepIndex) {
            if (stepIndex < 0 || stepIndex >= totalQuizQuestions) {
                return;
            }
            currentQuizStep = stepIndex;

            document.querySelectorAll('.quiz-question-card').forEach((card, idx) => {
                if (idx === stepIndex) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });

            const indicator = document.getElementById('activeStepIndicator');
            if (indicator) {
                indicator.textContent = 'Pertanyaan ' + (stepIndex + 1) + ' dari ' + totalQuizQuestions;
            }

            refreshQuestionMapAndProgress();
        }

        function handleOptionSelect(stepIndex, chosenLetter) {
            const card = document.querySelector('.quiz-question-card[data-step-index="' + stepIndex + '"]');
            if (card) {
                card.querySelectorAll('.quiz-option-label').forEach((label) => {
                    const letter = label.getAttribute('data-letter');
                    const badge = label.querySelector('.quiz-option-badge');
                    if (letter === chosenLetter) {
                        label.className = 'quiz-option-label flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer transition-all border-[#D11F2E] bg-[#FFF1F2]/60';
                        if (badge) {
                            badge.className = 'quiz-option-badge w-8 h-8 rounded-lg font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors bg-[#D11F2E] text-white';
                        }
                    } else {
                        label.className = 'quiz-option-label flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer transition-all border-[#E2E8F0] bg-white hover:border-[#CBD5E1] hover:bg-[#F8FAFC]';
                        if (badge) {
                            badge.className = 'quiz-option-badge w-8 h-8 rounded-lg font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors bg-[#F1F5F9] text-[#0F172A] border border-[#E2E8F0]';
                        }
                    }
                });
            }

            refreshQuestionMapAndProgress();
        }

        function isStepAnswered(stepIndex) {
            const card = document.querySelector('.quiz-question-card[data-step-index="' + stepIndex + '"]');
            if (!card) return false;
            return card.querySelector('input[type="radio"]:checked') !== null;
        }

        function refreshQuestionMapAndProgress() {
            let answeredCount = 0;

            for (let i = 0; i < totalQuizQuestions; i++) {
                const answered = isStepAnswered(i);
                if (answered) answeredCount++;

                const btn = document.getElementById('map-btn-' + i);
                if (!btn) continue;

                if (i === currentQuizStep) {
                    btn.className = 'h-11 rounded-xl font-mono text-xs font-bold border-2 transition-all flex items-center justify-center border-[#D11F2E] bg-[#FFF1F2] text-[#D11F2E] ring-2 ring-[#D11F2E]/20';
                } else if (answered) {
                    btn.className = 'h-11 rounded-xl font-mono text-xs font-bold border-2 transition-all flex items-center justify-center border-[#047857] bg-[#047857] text-white';
                } else {
                    btn.className = 'h-11 rounded-xl font-mono text-xs font-bold border-2 transition-all flex items-center justify-center border-[#E2E8F0] bg-[#F8FAFC] text-[#0F172A] hover:border-[#CBD5E1]';
                }
            }

            const counterText = document.getElementById('answeredCounterText');
            if (counterText) {
                counterText.textContent = answeredCount + ' / ' + totalQuizQuestions + ' Terjawab';
            }

            const bar = document.getElementById('quizLiveProgressBar');
            if (bar && totalQuizQuestions > 0) {
                const pct = Math.round((answeredCount / totalQuizQuestions) * 100);
                bar.style.width = pct + '%';
            }
        }

        function selectStarRating(rating) {
            const input = document.getElementById('ratingInput');
            if (input) input.value = rating;

            const labels = {
                1: '1 / 5 Bintang — Perlu Perbaikan',
                2: '2 / 5 Bintang — Cukup',
                3: '3 / 5 Bintang — Baik',
                4: '4 / 5 Bintang — Sangat Baik',
                5: '5 / 5 Bintang — Sangat Memuaskan'
            };

            const labelEl = document.getElementById('ratingLabelText');
            if (labelEl) {
                labelEl.textContent = labels[rating] || (rating + ' / 5 Bintang');
            }

            document.querySelectorAll('#starContainer .star-btn').forEach((btn) => {
                const starVal = parseInt(btn.getAttribute('data-star'), 10);
                const svg = btn.querySelector('svg');
                if (svg) {
                    if (starVal <= rating) {
                        svg.classList.remove('text-[#CBD5E1]');
                        svg.classList.add('text-[#F59E0B]');
                    } else {
                        svg.classList.remove('text-[#F59E0B]');
                        svg.classList.add('text-[#CBD5E1]');
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            refreshQuestionMapAndProgress();
        });
    </script>
@endsection
