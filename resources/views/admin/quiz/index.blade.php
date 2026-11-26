@extends('layouts.admin')

@section('title', 'Kelola Quiz Evaluasi Akhir')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                    Kelola Quiz Evaluasi Akhir
                </h1>
                <p class="text-xs sm:text-sm text-[#64748B]">
                    Pilih program kelas online melalui dropdown di bawah untuk melihat dan mengelola soal evaluasi kelulusan.
                </p>
            </div>

            @if($selectedKelas)
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <form method="POST"
                          action="{{ route('admin.quiz.reset', $selectedKelas->id) }}"
                          onsubmit="return confirm('Muat ulang 10 soal teknis standar studio untuk kelas ini?')">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0F172A] bg-white hover:bg-slate-50 border border-[#CBD5E1] shadow-sm transition-colors">
                            <span>Reset 10 Soal Standar</span>
                        </button>
                    </form>

                    <button type="button"
                            onclick="document.getElementById('modal-tambah-soal').classList.toggle('hidden')"
                            class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                        + Tambah Soal Baru
                    </button>
                </div>
            @endif
        </div>

        <!-- Dropdown Pemilih Kelas Online -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1 max-w-2xl space-y-1.5">
                    <label for="selectKelasQuiz" class="block text-xs font-mono font-bold text-[#64748B] uppercase tracking-wider">
                        PILIH PROGRAM KELAS ONLINE
                    </label>
                    <div class="relative">
                        <select id="selectKelasQuiz"
                                onchange="window.location.href = this.value"
                                class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs sm:text-sm font-semibold text-[#0F172A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E] transition-colors cursor-pointer">
                            <option value="{{ route('admin.quiz.index') }}" {{ !$selectedKelas ? 'selected' : '' }}>
                                &mdash; Pilih Program Kelas untuk Menampilkan Soal Quiz &mdash;
                            </option>
                            @foreach($onlineCourses as $courseItem)
                                @php
                                    $isSelected = $selectedKelas && $selectedKelas->id === $courseItem->id;
                                    $countForCourse = $questionCounts[$courseItem->id] ?? 10;
                                @endphp
                                <option value="{{ route('admin.quiz.index', $courseItem->id) }}" {{ $isSelected ? 'selected' : '' }}>
                                    [{{ strtoupper($courseItem->kategori) }}] {{ $courseItem->judul_kelas }} ({{ $countForCourse }} Soal)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($selectedKelas)
                    <div class="flex flex-wrap items-center gap-3 shrink-0 pt-2 md:pt-5">
                        <div class="px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs font-mono">
                            Passing Grade: <strong class="text-[#D11F2E]">{{ $selectedKelas->passingGrade() }} / 100</strong>
                        </div>
                        <div class="px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs font-mono">
                            Total: <strong class="text-[#0F172A]">{{ $quizQuestions->count() }} Soal</strong>
                        </div>
                        <a href="{{ route('admin.quiz.index') }}"
                           class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-[#CBD5E1] text-xs font-semibold text-[#64748B] hover:text-[#0F172A] transition-colors">
                            Tutup Kelas
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if(!$selectedKelas)
            <!-- Default Empty State (Sebelum Kelas Dipilih) -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-12 sm:p-16 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] text-[#64748B] flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                    Belum Ada Kelas yang Dipilih
                </h2>
                <p class="text-xs sm:text-sm text-[#64748B] max-w-md mx-auto leading-relaxed">
                    Silakan pilih salah satu program kelas online pada dropdown di atas untuk menampilkan, mengubah, atau menambahkan soal evaluasi kelulusan.
                </p>
            </div>
        @else
            <!-- Collapsible Inline Form: Tambah Soal Baru -->
            <div id="modal-tambah-soal" class="hidden rounded-2xl bg-white border-2 border-[#D11F2E] p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                    <h3 class="text-sm font-extrabold font-heading text-[#0F172A]">
                        Tambah Soal Evaluasi Baru &mdash; {{ \Illuminate\Support\Str::before($selectedKelas->judul_kelas, ':') }}
                    </h3>
                    <button type="button"
                            onclick="document.getElementById('modal-tambah-soal').classList.add('hidden')"
                            class="text-xs font-semibold text-[#64748B] hover:text-[#0F172A]">
                        Tutup &times;
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.quiz.store', $selectedKelas->id) }}" class="space-y-4 text-xs">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="block font-bold text-[#0F172A]">Teks Pertanyaan Evaluasi</label>
                        <textarea name="pertanyaan"
                                  rows="2"
                                  required
                                  placeholder="Tuliskan pertanyaan teknis software..."
                                  class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi A</label>
                            <input type="text" name="opsi_a" required placeholder="Teks jawaban A" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi B</label>
                            <input type="text" name="opsi_b" required placeholder="Teks jawaban B" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi C</label>
                            <input type="text" name="opsi_c" required placeholder="Teks jawaban C" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi D</label>
                            <input type="text" name="opsi_d" required placeholder="Teks jawaban D" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-[#F1F5F9]">
                        <div class="flex items-center gap-3">
                            <label class="font-bold text-[#0F172A]">Kunci Jawaban Benar:</label>
                            <select name="jawaban_benar" class="px-4 py-2 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                                <option value="A">Opsi A</option>
                                <option value="B">Opsi B</option>
                                <option value="C">Opsi C</option>
                                <option value="D">Opsi D</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan Soal Baru
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Kartu Soal (In-Place Editing langsung pada teks & kotak pilihan tanpa kotak tambahan di bawah) -->
            <div class="space-y-4">
                @forelse($quizQuestions as $qIdx => $qItem)
                    @php
                        $opts = [
                            'A' => $qItem->opsi_a,
                            'B' => $qItem->opsi_b,
                            'C' => $qItem->opsi_c,
                            'D' => $qItem->opsi_d,
                        ];
                    @endphp

                    <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
                        <!-- 1. TAMPILAN BACA (VIEW MODE) -->
                        <div id="view-card-{{ $qItem->id }}">
                            <!-- Header Kartu -->
                            <div class="bg-[#F1F5F9] px-5 py-3.5 flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0]">
                                <div class="flex items-center gap-2.5">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#0F172A] text-white font-mono text-xs font-bold">
                                        SOAL {{ sprintf('%02d', $qIdx + 1) }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] font-mono text-xs font-bold">
                                        &#10003; Kunci Jawaban: Opsi {{ $qItem->jawaban_benar }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="toggleInPlaceEdit({{ $qItem->id }}, true)"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#CBD5E1] transition-colors">
                                        Ubah Soal
                                    </button>

                                    <form method="POST"
                                          action="{{ route('admin.quiz.destroy', $qItem->id) }}"
                                          onsubmit="return confirm('Hapus soal nomor {{ $qIdx + 1 }} ini dari bank quiz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#D11F2E] border border-[#FECACA] transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Isi Pertanyaan & Pilihan Jawaban -->
                            <div class="p-5 sm:p-6 space-y-4">
                                <p class="text-sm sm:text-[15px] font-bold font-heading text-[#0F172A] leading-relaxed">
                                    {{ $qItem->pertanyaan }}
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    @foreach($opts as $letter => $optText)
                                        @php
                                            $isCorrect = strtoupper($qItem->jawaban_benar) === $letter;
                                        @endphp
                                        <div class="flex items-start gap-3 p-3.5 rounded-xl border
                                            {{ $isCorrect
                                                ? 'bg-[#ECFDF5]/70 border-[#A7F3D0] text-[#065F46] font-semibold'
                                                : 'bg-[#F8FAFC] border-[#E2E8F0] text-[#334155]' }}">
                                            <span class="w-6 h-6 rounded-md font-mono text-xs font-bold flex items-center justify-center shrink-0
                                                {{ $isCorrect ? 'bg-[#047857] text-white' : 'bg-white text-[#0F172A] border border-[#CBD5E1]' }}">
                                                {{ $letter }}
                                            </span>
                                            <span class="leading-relaxed pt-0.5">{{ $optText }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- 2. TAMPILAN EDIT LANGSUNG DI TEMPAT (IN-PLACE EDIT MODE — Menggantikan View Mode Tanpa Kotak Baru di Bawah) -->
                        <form id="edit-card-{{ $qItem->id }}"
                              method="POST"
                              action="{{ route('admin.quiz.update', $qItem->id) }}"
                              class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="nomor_urutan" value="{{ $qItem->nomor_urutan }}">
                            <input type="hidden" name="jawaban_benar" id="input-kunci-{{ $qItem->id }}" value="{{ strtoupper($qItem->jawaban_benar) }}">

                            <!-- Header Kartu Saat Mode Edit -->
                            <div class="bg-[#F1F5F9] px-5 py-3.5 flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0]">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#0F172A] text-white font-mono text-xs font-bold">
                                        SOAL {{ sprintf('%02d', $qIdx + 1) }}
                                    </span>
                                    <span id="badge-kunci-{{ $qItem->id }}"
                                          class="px-2.5 py-1 rounded-lg bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] font-mono text-xs font-bold">
                                        &#10003; Kunci Jawaban: Opsi {{ strtoupper($qItem->jawaban_benar) }}
                                    </span>
                                    <span class="text-[11px] text-[#64748B]">
                                        (Klik huruf A/B/C/D untuk mengganti kunci jawaban)
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="toggleInPlaceEdit({{ $qItem->id }}, false)"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#64748B] hover:text-[#0F172A] border border-[#CBD5E1] transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                        Simpan
                                    </button>
                                </div>
                            </div>

                            <!-- Isi Pertanyaan & Pilihan Jawaban Langsung Jadi Input di Posisi yang Sama -->
                            <div class="p-5 sm:p-6 space-y-4">
                                <textarea name="pertanyaan"
                                          rows="2"
                                          required
                                          aria-label="Edit Pertanyaan"
                                          class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-sm sm:text-[15px] font-bold font-heading text-[#0F172A] leading-relaxed focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">{{ $qItem->pertanyaan }}</textarea>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    @foreach($opts as $letter => $optText)
                                        @php
                                            $isCorrect = strtoupper($qItem->jawaban_benar) === $letter;
                                            $fieldName = 'opsi_' . strtolower($letter);
                                        @endphp
                                        <div id="opt-row-{{ $qItem->id }}-{{ $letter }}"
                                             class="flex items-center gap-2.5 p-2.5 rounded-xl border transition-colors
                                                {{ $isCorrect
                                                    ? 'bg-[#ECFDF5]/70 border-[#A7F3D0]'
                                                    : 'bg-[#F8FAFC] border-[#E2E8F0]' }}">
                                            <button type="button"
                                                    onclick="selectInPlaceAnswerKey({{ $qItem->id }}, '{{ $letter }}')"
                                                    id="opt-btn-{{ $qItem->id }}-{{ $letter }}"
                                                    title="Jadikan Opsi {{ $letter }} sebagai Kunci Jawaban Benar"
                                                    class="w-7 h-7 rounded-md font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors cursor-pointer
                                                        {{ $isCorrect ? 'bg-[#047857] text-white' : 'bg-white text-[#0F172A] border border-[#CBD5E1] hover:border-[#047857]' }}">
                                                {{ $letter }}
                                            </button>

                                            <input type="text"
                                                   name="{{ $fieldName }}"
                                                   value="{{ $optText }}"
                                                   required
                                                   aria-label="Opsi {{ $letter }}"
                                                   class="w-full px-3 py-1.5 rounded-lg bg-white border border-[#CBD5E1] text-xs font-medium text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white border border-[#E2E8F0] p-10 text-center text-xs text-[#64748B]">
                        Belum ada soal quiz untuk kelas ini. Klik tombol <strong>Reset 10 Soal Standar</strong> atau <strong>+ Tambah Soal Baru</strong> di atas.
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <script>
        function toggleInPlaceEdit(questionId, showEdit) {
            const viewEl = document.getElementById('view-card-' + questionId);
            const editEl = document.getElementById('edit-card-' + questionId);
            if (!viewEl || !editEl) return;

            if (showEdit) {
                viewEl.classList.add('hidden');
                editEl.classList.remove('hidden');
                const textarea = editEl.querySelector('textarea');
                if (textarea) textarea.focus();
            } else {
                editEl.classList.add('hidden');
                viewEl.classList.remove('hidden');
            }
        }

        function selectInPlaceAnswerKey(questionId, chosenLetter) {
            const hiddenInput = document.getElementById('input-kunci-' + questionId);
            if (hiddenInput) hiddenInput.value = chosenLetter;

            const badge = document.getElementById('badge-kunci-' + questionId);
            if (badge) {
                badge.textContent = '\u2713 Kunci Jawaban: Opsi ' + chosenLetter;
            }

            ['A', 'B', 'C', 'D'].forEach((letter) => {
                const row = document.getElementById('opt-row-' + questionId + '-' + letter);
                const btn = document.getElementById('opt-btn-' + questionId + '-' + letter);
                if (!row || !btn) return;

                if (letter === chosenLetter) {
                    row.className = 'flex items-center gap-2.5 p-2.5 rounded-xl border transition-colors bg-[#ECFDF5]/70 border-[#A7F3D0]';
                    btn.className = 'w-7 h-7 rounded-md font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors cursor-pointer bg-[#047857] text-white';
                } else {
                    row.className = 'flex items-center gap-2.5 p-2.5 rounded-xl border transition-colors bg-[#F8FAFC] border-[#E2E8F0]';
                    btn.className = 'w-7 h-7 rounded-md font-mono text-xs font-bold flex items-center justify-center shrink-0 transition-colors cursor-pointer bg-white text-[#0F172A] border border-[#CBD5E1] hover:border-[#047857]';
                }
            });
        }
    </script>
@endsection
