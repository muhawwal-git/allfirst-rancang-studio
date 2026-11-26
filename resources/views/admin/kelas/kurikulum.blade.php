@extends('layouts.admin')

@section('title', 'Kurikulum & Video Pembelajaran — ' . $kelas->judul_kelas)

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row (A-04: mbNe9) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                Kurikulum &amp; Video Pembelajaran &middot; {{ \Illuminate\Support\Str::before($kelas->judul_kelas, ':') }}
                <span class="sr-only">{{ $kelas->judul_kelas }}</span>
            </h1>

            <button type="button"
                    onclick="document.getElementById('modal-tambah-modul').classList.toggle('hidden')"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all shrink-0">
                + Tambah Modul Baru
            </button>
        </div>

        <!-- Collapsible Inline Form: Tambah Modul Baru -->
        <div id="modal-tambah-modul" class="{{ $kelas->moduls->isEmpty() ? '' : 'hidden' }} rounded-2xl bg-white border border-[#E2E8F0] p-5 shadow-sm">
            <form method="POST" action="{{ route('admin.kelas.modul.store', $kelas->id) }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 text-xs">
                @csrf
                <div class="flex-1 space-y-1.5">
                    <label for="judul_modul" class="block font-bold text-[#0F172A]">
                        Judul Modul Baru
                    </label>
                    <input type="text"
                           id="judul_modul"
                           name="judul_modul"
                           required
                           placeholder="Contoh: Modul 1: Pengenalan Interface & Pengaturan Dasar"
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                </div>
                <div class="w-full sm:w-36 space-y-1.5">
                    <label for="nomor_urutan" class="block font-bold text-[#0F172A]">
                        Nomor Urutan
                    </label>
                    <input type="number"
                           id="nomor_urutan"
                           name="nomor_urutan"
                           min="1"
                           value="{{ $kelas->moduls->count() + 1 }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                </div>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all shrink-0">
                    Simpan Modul
                </button>
            </form>
        </div>

        <!-- Stacked Modules List (Full Width — A-04: mbNe9) -->
        <div class="space-y-5">
            @forelse($kelas->moduls as $index => $modul)
                <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
                    <!-- Header Modul (fill: #F1F5F9) -->
                    <div class="bg-[#F1F5F9] px-5 py-3.5 flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0]">
                        <div class="flex items-center gap-2.5">
                            <!-- Grip Vertical Icon -->
                            <svg class="w-4 h-4 text-[#64748B] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="9" cy="5" r="1.5" />
                                <circle cx="15" cy="5" r="1.5" />
                                <circle cx="9" cy="12" r="1.5" />
                                <circle cx="15" cy="12" r="1.5" />
                                <circle cx="9" cy="19" r="1.5" />
                                <circle cx="15" cy="19" r="1.5" />
                            </svg>
                            <h3 class="text-xs sm:text-[13px] font-extrabold font-heading text-[#0F172A]">
                                {{ str_starts_with(strtolower($modul->judul_modul), 'modul') ? $modul->judul_modul : 'Modul ' . ($index + 1) . ': ' . $modul->judul_modul }}
                            </h3>
                        </div>

                        <!-- Modul Actions -->
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    onclick="document.getElementById('judul_materi').focus()"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] transition-colors">
                                Ubah Nama Modul
                            </button>

                            <form method="POST" action="{{ route('admin.modul.destroy', $modul->id) }}" onsubmit="return confirm('Hapus modul ini beserta seluruh materi video di dalamnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#D11F2E] border border-[#FECACA] transition-colors">
                                    Hapus Modul
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Video Rows inside Modul -->
                    <div class="divide-y divide-[#E2E8F0]">
                        @forelse($modul->materis as $materi)
                            <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-4 text-xs hover:bg-slate-50/50 transition-colors">
                                <!-- Left Info Group -->
                                <div class="flex flex-wrap items-center gap-4 sm:gap-6 flex-1 min-w-[260px]">
                                    <!-- 1. Number -->
                                    <span class="font-mono text-[#64748B] w-4 shrink-0">
                                        {{ $loop->iteration }}.
                                    </span>

                                    <!-- 2. Video Title -->
                                    <span class="font-medium text-[#334155] max-w-[290px] leading-snug">
                                        {{ $materi->judul_materi }}
                                    </span>

                                    <!-- 3. Duration -->
                                    <span class="font-mono text-[#334155] shrink-0">
                                        {{ $materi->durasi_tampil }}
                                    </span>

                                    <!-- 4. Sumber Video (YouTube Unlisted / Stream) -->
                                    <span class="inline-flex items-center gap-1.5 font-mono text-[11px] text-[#64748B] shrink-0">
                                        @if($materi->is_youtube)
                                            <svg class="w-4 h-4 text-[#D11F2E] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                            <span>YouTube Unlisted ({{ $materi->youtube_id }})</span>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-[#64748B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18" />
                                                <line x1="7" y1="2" x2="7" y2="22" />
                                                <line x1="17" y1="2" x2="17" y2="22" />
                                                <line x1="2" y1="12" x2="22" y2="12" />
                                            </svg>
                                            <span>{{ basename($materi->path_video ?: 'modul-' . ($index + 1) . '-' . $loop->iteration . '.mp4') }}</span>
                                        @endif
                                    </span>

                                    <!-- 5. Checkbox Cuplikan Preview -->
                                    @if($materi->cuplikan_gratis)
                                        <span class="inline-flex items-center gap-2 font-semibold text-[#0F172A] shrink-0">
                                            <span class="w-4 h-4 rounded bg-[#BA151C] border border-[#BA151C] text-white flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                            <span>Cuplikan Preview</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 font-medium text-[#0F172A] shrink-0">
                                            <span class="w-4 h-4 rounded bg-white border border-[#CBD5E1]"></span>
                                            <span>Cuplikan Preview</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Right Actions (Edit, Hapus) -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button"
                                            onclick="document.getElementById('judul_materi').value = @js($materi->judul_materi); document.getElementById('durasi_tampil').value = @js($materi->durasi_tampil); document.getElementById('path_video').value = @js($materi->path_video); document.getElementById('judul_materi').focus();"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] transition-colors">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('admin.materi.destroy', $materi->id) }}" onsubmit="return confirm('Hapus pelajaran video ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#D11F2E] border border-[#FECACA] transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-xs text-[#64748B] italic">
                                Belum ada pelajaran video di dalam modul ini. Gunakan formulir di bawah untuk menambahkan tautan video YouTube Unlisted.
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm text-center text-xs text-[#64748B]">
                    Kelas ini belum memiliki modul pembelajaran. Buat modul pertama di atas terlebih dahulu.
                </div>
            @endforelse
        </div>

        <!-- Bottom Form Tambah Materi / Video Pembelajaran Baru (A-04) -->
        @php
            $targetModul = $kelas->moduls->first();
            $isOfflineClass = $kelas->isOfflineCatalog();
        @endphp
        @if($targetModul)
            <div class="rounded-2xl bg-white border border-[#E2E8F0] p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-extrabold font-heading text-[#0F172A]">
                            {{ $isOfflineClass ? 'Tambah Pokok Bahasan / Materi Pertemuan Studio' : 'Tambah Video Pembelajaran Baru (YouTube Unlisted)' }}
                        </h2>
                        <p class="text-xs text-[#64748B] mt-0.5">
                            @if($isOfflineClass)
                                Tambahkan rincian pokok bahasan materi yang akan dipelajari siswa pada modul kelas offline tatap muka ini.
                            @else
                                Tempel tautan video YouTube berstatus <strong>Tidak Publik (Unlisted)</strong> untuk hemat ruang server dan streaming lancar.
                            @endif
                        </p>
                    </div>

                    @if($kelas->moduls->count() > 1)
                        <div class="flex items-center gap-2 text-xs">
                            <label for="select-target-modul" class="font-semibold text-[#64748B]">Target Modul:</label>
                            <select id="select-target-modul"
                                    onchange="document.getElementById('form-upload-materi').action = this.value"
                                    class="px-3 py-1.5 rounded-lg bg-[#F8FAFC] border border-[#CBD5E1] text-xs font-semibold text-[#0F172A] focus:outline-none">
                                @foreach($kelas->moduls as $idx => $mod)
                                    <option value="{{ route('admin.modul.materi.store', $mod->id) }}">
                                        {{ str_starts_with(strtolower($mod->judul_modul), 'modul') ? $mod->judul_modul : 'Modul ' . ($idx + 1) . ': ' . $mod->judul_modul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <form id="form-upload-materi"
                      method="POST"
                      action="{{ route('admin.modul.materi.store', $targetModul->id) }}"
                      class="space-y-4 text-xs">
                    @csrf

                    <!-- Row Input 1: Judul Materi, Durasi/Sesi, Nomor Urutan Tampil -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-7 space-y-1.5">
                            <label for="judul_materi" class="block font-bold text-[#0F172A]">
                                {{ $isOfflineClass ? 'Judul Pokok Bahasan / Materi Tatap Muka' : 'Judul Video Materi' }}
                            </label>
                            <input type="text"
                                   id="judul_materi"
                                   name="judul_materi"
                                   required
                                   placeholder="{{ $isOfflineClass ? 'Contoh: Pembuatan Denah Arsitektur & Pengaturan Skala Gambar Kerja' : 'Masukkan judul materi spesifik video' }}"
                                   class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>

                        <div class="md:col-span-3 space-y-1.5">
                            <label for="durasi_tampil" class="block font-bold text-[#0F172A]">
                                {{ $isOfflineClass ? 'Alokasi Sesi (Opsional)' : 'Durasi Video (MM:SS)' }}
                            </label>
                            <input type="text"
                                   id="durasi_tampil"
                                   name="durasi_tampil"
                                   {{ $isOfflineClass ? '' : 'required' }}
                                   value="{{ $isOfflineClass ? 'Tatap Muka' : '' }}"
                                   placeholder="{{ $isOfflineClass ? 'contoh: Sesi Studio' : 'contoh: 08:30' }}"
                                   class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>

                        <div class="md:col-span-2 space-y-1.5">
                            <label for="nomor_urutan_materi" class="block font-bold text-[#0F172A]">
                                Nomor Urutan Tampil
                            </label>
                            <input type="number"
                                   id="nomor_urutan_materi"
                                   name="nomor_urutan"
                                   min="1"
                                   placeholder="contoh: 1"
                                   class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>
                    </div>

                    @if(!$isOfflineClass)
                        <!-- Tautan YouTube Unlisted Input Group -->
                        <div class="space-y-1.5">
                            <label for="path_video" class="block font-bold text-[#0F172A]">
                                Tautan / URL Video YouTube (Unlisted)
                            </label>
                            <div class="rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] p-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#FEF2F2] border border-[#FECACA] text-[#D11F2E] flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           id="path_video"
                                           name="path_video"
                                           required
                                           placeholder="Contoh: https://youtu.be/dQw4w9WgXcQ atau https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                                           class="flex-1 px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] font-mono text-xs focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                                </div>
                                <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] font-mono text-[#64748B]">
                                    <span>YOUTUBE UNLISTED EMBED &middot; STREAMING HD 720p / 1080p &middot; TANPA BEBAN PENYIMPANAN VPS</span>
                                    <span class="text-[#047857] font-semibold">&check; Mendukung Smart Resume</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Bottom Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
                        @if(!$isOfflineClass)
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox"
                                       name="cuplikan_gratis"
                                       value="1"
                                       class="w-4 h-4 rounded border-[#CBD5E1] text-[#BA151C] focus:ring-[#D11F2E]">
                                <span class="text-xs font-medium text-[#0F172A]">
                                    Jadikan Cuplikan Preview Gratis (Dapat ditonton 20–30 detik oleh pengunjung publik)
                                </span>
                            </label>
                        @else
                            <span class="text-xs text-[#64748B]">
                                Pokok bahasan ini akan ditampilkan sebagai silabus resmi pada halaman Katalog Kelas Offline.
                            </span>
                        @endif

                        <button type="submit"
                                class="px-6 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all shrink-0">
                            {{ $isOfflineClass ? 'Simpan Pokok Bahasan ke Modul' : 'Simpan Video ke Kurikulum' }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(!$isOfflineClass && isset($quizQuestions))
            <!-- Bank Soal Quiz Evaluasi Akhir Section -->
            <div class="rounded-2xl bg-white border border-[#E2E8F0] p-6 sm:p-7 space-y-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#E2E8F0] pb-4">
                    <div class="space-y-1">
                        <span class="text-[11px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider">
                            SYARAT KELULUSAN &amp; SERTIFIKAT OTOMATIS
                        </span>
                        <h2 class="text-lg font-extrabold font-heading text-[#0F172A]">
                            Bank Soal Quiz Evaluasi Akhir ({{ $quizQuestions->count() }} Soal Aktif &middot; Passing Grade {{ $kelas->passingGrade() }}/100)
                        </h2>
                    </div>

                    <button type="button"
                            onclick="document.getElementById('form-tambah-quiz').classList.toggle('hidden')"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#0F172A] hover:bg-slate-800 transition-colors shrink-0">
                        + Tambah Soal Quiz
                    </button>
                </div>

                <!-- Collapsible Add Question Form -->
                <div id="form-tambah-quiz" class="hidden rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-5">
                    <form method="POST" action="{{ route('admin.kelas.quiz.store', $kelas->id) }}" class="space-y-4 text-xs">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="block font-bold text-[#0F172A]">Pertanyaan Evaluasi</label>
                            <textarea name="pertanyaan" rows="2" required placeholder="Tuliskan pertanyaan teknis..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A]"></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-[#0F172A] mb-1">Opsi A</label>
                                <input type="text" name="opsi_a" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-[#CBD5E1]">
                            </div>
                            <div>
                                <label class="block font-bold text-[#0F172A] mb-1">Opsi B</label>
                                <input type="text" name="opsi_b" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-[#CBD5E1]">
                            </div>
                            <div>
                                <label class="block font-bold text-[#0F172A] mb-1">Opsi C</label>
                                <input type="text" name="opsi_c" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-[#CBD5E1]">
                            </div>
                            <div>
                                <label class="block font-bold text-[#0F172A] mb-1">Opsi D</label>
                                <input type="text" name="opsi_d" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-[#CBD5E1]">
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 pt-2">
                            <div class="flex items-center gap-3">
                                <label class="font-bold text-[#0F172A]">Kunci Jawaban Benar:</label>
                                <select name="jawaban_benar" class="px-3 py-2 rounded-xl bg-white border border-[#CBD5E1] font-mono font-bold">
                                    <option value="A">Opsi A</option>
                                    <option value="B">Opsi B</option>
                                    <option value="C">Opsi C</option>
                                    <option value="D">Opsi D</option>
                                </select>
                            </div>
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B]">
                                Simpan Soal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Questions List -->
                <div class="divide-y divide-[#E2E8F0]">
                    @foreach($quizQuestions as $qIdx => $qItem)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4 text-xs">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-[#0F172A] text-white font-mono text-[10px] font-bold">
                                        SOAL {{ sprintf('%02d', $qIdx + 1) }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] font-mono text-[10px] font-bold">
                                        Kunci: {{ $qItem->jawaban_benar }}
                                    </span>
                                </div>
                                <p class="font-bold text-[#0F172A]">{{ $qItem->pertanyaan }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-[#64748B] pt-1">
                                    <span><strong>A.</strong> {{ $qItem->opsi_a }}</span>
                                    <span><strong>B.</strong> {{ $qItem->opsi_b }}</span>
                                    <span><strong>C.</strong> {{ $qItem->opsi_c }}</span>
                                    <span><strong>D.</strong> {{ $qItem->opsi_d }}</span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.quiz.destroy', $qItem->id) }}" onsubmit="return confirm('Hapus soal quiz ini?')" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-[#D11F2E] bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA]">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
