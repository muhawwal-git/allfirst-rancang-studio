@extends('layouts.admin')

@section('title', 'Kelola Quiz Evaluasi Akhir')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row (Konsisten dengan Kelola Kelas & Kurikulum) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                    Kelola Quiz Evaluasi Akhir
                </h1>
                <p class="text-xs sm:text-sm text-[#64748B]">
                    Atur bank soal pilihan ganda untuk masing-masing program kelas online sebagai syarat kelulusan dan penerbitan sertifikat otomatis.
                </p>
            </div>

            @if($selectedKelas)
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <form method="POST"
                          action="{{ route('admin.quiz.reset', $selectedKelas->id) }}"
                          onsubmit="return confirm('Muat ulang 10 soal teknis standar studio untuk kelas ini? Perubahan soal kustom pada kelas ini akan diganti dengan 10 soal bawaan.')">
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

        <!-- Course Selector Tabs (Konsisten dengan Filter Tab Kelola Kelas) -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-2.5 shadow-sm flex flex-wrap items-center gap-2">
            @foreach($onlineCourses as $courseItem)
                @php
                    $isSelected = $selectedKelas && $selectedKelas->id === $courseItem->id;
                    $countForCourse = $questionCounts[$courseItem->id] ?? 10;
                @endphp
                <a href="{{ route('admin.quiz.index', $courseItem->id) }}"
                   class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all
                       {{ $isSelected
                           ? 'bg-[#0F172A] text-white shadow-sm'
                           : 'bg-[#F8FAFC] text-[#334155] hover:bg-slate-100 hover:text-[#0F172A] border border-[#E2E8F0]' }}">
                    <span>{{ \Illuminate\Support\Str::before($courseItem->judul_kelas, ':') }}</span>
                    <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-bold
                        {{ $isSelected ? 'bg-white/15 text-white' : 'bg-white text-[#64748B] border border-[#E2E8F0]' }}">
                        {{ $countForCourse }} Soal
                    </span>
                </a>
            @endforeach
        </div>

        @if($selectedKelas)
            <!-- Selected Class Context Banner -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-[#FFF1F2] border border-[#FECDD3] text-[10px] font-mono font-bold text-[#D11F2E] uppercase">
                            {{ strtoupper($selectedKelas->kategori) }} WORKSPACE
                        </span>
                        <span class="text-xs font-mono text-[#64748B]">
                            Passing Grade Kelulusan: <strong class="text-[#0F172A]">{{ $selectedKelas->passingGrade() }} / 100</strong>
                        </span>
                    </div>
                    <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                        {{ $selectedKelas->judul_kelas }}
                    </h2>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="px-4 py-2 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs font-mono">
                        Total Soal Aktif: <strong class="text-[#0F172A]">{{ $quizQuestions->count() }} Soal</strong>
                    </div>
                    <a href="{{ route('admin.kelas.kurikulum', $selectedKelas->id) }}"
                       class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-[#CBD5E1] text-xs font-semibold text-[#0F172A] transition-colors">
                        Lihat Kurikulum Video &rarr;
                    </a>
                </div>
            </div>

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
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="sm:col-span-10 space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Teks Pertanyaan Evaluasi</label>
                            <textarea name="pertanyaan"
                                      rows="2"
                                      required
                                      placeholder="Tuliskan pertanyaan teknis software..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]"></textarea>
                        </div>
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">No. Urut</label>
                            <input type="number"
                                   name="nomor_urutan"
                                   min="1"
                                   value="{{ $quizQuestions->count() + 1 }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] font-mono focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi A</label>
                            <input type="text" name="opsi_a" required placeholder="Teks jawaban A" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi B</label>
                            <input type="text" name="opsi_b" required placeholder="Teks jawaban B" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi C</label>
                            <input type="text" name="opsi_c" required placeholder="Teks jawaban C" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Pilihan Opsi D</label>
                            <input type="text" name="opsi_d" required placeholder="Teks jawaban D" class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-[#F1F5F9]">
                        <div class="flex items-center gap-3">
                            <label class="font-bold text-[#0F172A]">Kunci Jawaban Benar:</label>
                            <select name="jawaban_benar" class="px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">
                                <option value="A">Opsi A</option>
                                <option value="B">Opsi B</option>
                                <option value="C">Opsi C</option>
                                <option value="D">Opsi D</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan Soal ke Bank Quiz
                        </button>
                    </div>
                </form>
            </div>

            <!-- Questions Cards List (Konsisten dengan Modul Cards di Kurikulum) -->
            <div class="space-y-4">
                @forelse($quizQuestions as $qIdx => $qItem)
                    <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
                        <!-- Card Top Bar (fill: #F1F5F9) -->
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
                                        onclick="document.getElementById('edit-soal-{{ $qItem->id }}').classList.toggle('hidden')"
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

                        <!-- Question Display Body -->
                        <div class="p-5 sm:p-6 space-y-4">
                            <p class="text-sm sm:text-[15px] font-bold font-heading text-[#0F172A] leading-relaxed">
                                {{ $qItem->pertanyaan }}
                            </p>

                            @php
                                $opts = [
                                    'A' => $qItem->opsi_a,
                                    'B' => $qItem->opsi_b,
                                    'C' => $qItem->opsi_c,
                                    'D' => $qItem->opsi_d,
                                ];
                            @endphp
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

                        <!-- Collapsible Inline Edit Form -->
                        <div id="edit-soal-{{ $qItem->id }}" class="hidden bg-[#F8FAFC] border-t border-[#E2E8F0] p-5 sm:p-6">
                            <form method="POST" action="{{ route('admin.quiz.update', $qItem->id) }}" class="space-y-4 text-xs">
                                @csrf
                                @method('PUT')

                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-[#D11F2E] uppercase">
                                        EDIT SOAL {{ sprintf('%02d', $qIdx + 1) }}
                                    </span>
                                    <button type="button"
                                            onclick="document.getElementById('edit-soal-{{ $qItem->id }}').classList.add('hidden')"
                                            class="text-[#64748B] hover:text-[#0F172A] font-semibold">
                                        Batal &times;
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                    <div class="sm:col-span-10 space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">Teks Pertanyaan</label>
                                        <textarea name="pertanyaan"
                                                  rows="2"
                                                  required
                                                  class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:border-[#D11F2E]">{{ $qItem->pertanyaan }}</textarea>
                                    </div>
                                    <div class="sm:col-span-2 space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">No. Urut</label>
                                        <input type="number"
                                               name="nomor_urutan"
                                               min="1"
                                               value="{{ $qItem->nomor_urutan }}"
                                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] font-mono">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">Pilihan Opsi A</label>
                                        <input type="text" name="opsi_a" value="{{ $qItem->opsi_a }}" required class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A]">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">Pilihan Opsi B</label>
                                        <input type="text" name="opsi_b" value="{{ $qItem->opsi_b }}" required class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A]">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">Pilihan Opsi C</label>
                                        <input type="text" name="opsi_c" value="{{ $qItem->opsi_c }}" required class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A]">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block font-bold text-[#0F172A]">Pilihan Opsi D</label>
                                        <input type="text" name="opsi_d" value="{{ $qItem->opsi_d }}" required class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A]">
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                                    <div class="flex items-center gap-3">
                                        <label class="font-bold text-[#0F172A]">Kunci Jawaban Benar:</label>
                                        <select name="jawaban_benar" class="px-4 py-2 rounded-xl bg-white border border-[#CBD5E1] font-mono font-bold text-[#0F172A]">
                                            @foreach(['A', 'B', 'C', 'D'] as $optLetter)
                                                <option value="{{ $optLetter }}" {{ strtoupper($qItem->jawaban_benar) === $optLetter ? 'selected' : '' }}>
                                                    Opsi {{ $optLetter }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit"
                                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                        Simpan Perubahan Soal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white border border-[#E2E8F0] p-10 text-center text-xs text-[#64748B]">
                        Belum ada soal quiz untuk kelas ini. Klik tombol <strong>Reset 10 Soal Standar</strong> atau <strong>+ Tambah Soal Baru</strong> di atas.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
@endsection
