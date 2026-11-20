@extends('layouts.admin')

@section('title', 'Data Siswa & Penerbitan Sertifikat — Allfirst Rancang Studio')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[11px] font-mono text-[#D11F2E] font-bold uppercase tracking-wider block">
                    DIREKTORI AKADEMIK &amp; ALUMNI
                </span>
                <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                    Data Siswa &amp; Penerbitan Sertifikat
                </h1>
                <p class="text-xs text-[#64748B]">
                    Kelola seluruh peserta kelas Offline Studio Makassar maupun Online LMS, pantau progress belajar, dan terbitkan sertifikat kelulusan resmi.
                </p>
            </div>

            <button type="button"
                    onclick="document.getElementById('modal-tambah-siswa').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all shrink-0 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Daftarkan Siswa Offline / Manual</span>
            </button>
        </div>

        <!-- 4 Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1.5">
                <span class="text-xs font-semibold text-[#64748B] block">Total Pendaftaran Kelas</span>
                <div class="text-2xl font-extrabold font-mono text-[#0F172A]">{{ $totalCount }} Siswa</div>
                <p class="text-[11px] text-[#64748B]">Akumulasi peserta kelas aktif</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#FFF1F2] border border-[#FECDD3] shadow-2xs space-y-1.5">
                <span class="text-xs font-bold text-[#D11F2E] block">Siswa Offline Studio</span>
                <div class="text-2xl font-extrabold font-mono text-[#D11F2E]">{{ $offlineCount }} Siswa</div>
                <p class="text-[11px] text-[#9F1239]">Tatap muka Makassar (+ Bonus Online)</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs space-y-1.5">
                <span class="text-xs font-semibold text-[#64748B] block">Siswa Online LMS</span>
                <div class="text-2xl font-extrabold font-mono text-[#0F172A]">{{ $onlineCount }} Siswa</div>
                <p class="text-[11px] text-[#64748B]">Pembelajaran video mandiri</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] shadow-2xs space-y-1.5">
                <span class="text-xs font-bold text-[#047857] block">Alumni Bersertifikat</span>
                <div class="text-2xl font-extrabold font-mono text-[#047857]">{{ $certifiedCount }} Sertifikat</div>
                <p class="text-[11px] text-[#065F46]">Sertifikat resmi yang telah terbit</p>
            </div>
        </div>

        <!-- Pengaturan Penomoran Sertifikat Terakhir Studio (Format: Nomor-ARS{YY}-{MM}/{Lokasi}/{YYYY}) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] p-5 shadow-2xs">
            <form method="POST" action="{{ route('admin.siswa.pengaturan-sertifikat') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end text-xs">
                @csrf
                <div class="lg:col-span-5 space-y-1">
                    <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                        PENGATURAN NOMOR REGISTRASI SERTIFIKAT STUDIO
                    </span>
                    <h2 class="text-sm font-extrabold font-heading text-[#0F172A]">
                        Sinkronisasi Nomor Urut Sertifikat Terakhir
                    </h2>
                    <p class="text-[11px] text-[#64748B] leading-relaxed">
                        Sesuaikan angka terakhir jika ada sertifikat fisik yang diterbitkan manual di luar web. Sertifikat berikutnya otomatis terbit dengan nomor:
                        <span id="live-cert-preview" class="inline-block px-2 py-0.5 rounded bg-[#FFF1F2] border border-[#FECDD3] font-mono font-bold text-[#D11F2E] mt-0.5">
                            {{ sprintf('%03d-ARS%s-%s/%s/%s', ($sertifikatNomorTerakhir ?? 13) + 1, date('y'), date('m'), $sertifikatKodeLokasi ?? 'MKS', date('Y')) }}
                        </span>
                    </p>
                </div>

                <div class="lg:col-span-3 space-y-1.5">
                    <label for="sertifikat_nomor_terakhir" class="block font-bold text-[#0F172A]">
                        No. Urut Sertifikat Terakhir
                    </label>
                    <input type="number"
                           id="sertifikat_nomor_terakhir"
                           name="sertifikat_nomor_terakhir"
                           value="{{ old('sertifikat_nomor_terakhir', $sertifikatNomorTerakhir ?? 13) }}"
                           min="0"
                           required
                           oninput="updateCertNumberPreview()"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-extrabold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                </div>

                <div class="lg:col-span-2 space-y-1.5">
                    <label for="sertifikat_kode_lokasi" class="block font-bold text-[#0F172A]">
                        Kode Lokasi Studio
                    </label>
                    <input type="text"
                           id="sertifikat_kode_lokasi"
                           name="sertifikat_kode_lokasi"
                           value="{{ old('sertifikat_kode_lokasi', $sertifikatKodeLokasi ?? 'MKS') }}"
                           maxlength="10"
                           required
                           oninput="updateCertNumberPreview()"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold uppercase text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                </div>

                <div class="lg:col-span-2">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all cursor-pointer">
                        Simpan Nomor Urut
                    </button>
                </div>
            </form>
        </div>

        <script>
            function updateCertNumberPreview() {
                const numInput = document.getElementById('sertifikat_nomor_terakhir');
                const locInput = document.getElementById('sertifikat_kode_lokasi');
                const previewEl = document.getElementById('live-cert-preview');
                if (!numInput || !locInput || !previewEl) return;

                const nextNum = String((parseInt(numInput.value || '0', 10) || 0) + 1).padStart(3, '0');
                const loc = (locInput.value || 'MKS').trim().toUpperCase() || 'MKS';
                previewEl.textContent = `${nextNum}-ARS{{ date('y') }}-{{ date('m') }}/${loc}/{{ date('Y') }}`;
            }
        </script>

        <!-- Filter Tabs & Search Bar -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-2xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-[#0F172A] mr-1">Kategori:</span>
                @php
                    $tabs = [
                        'semua' => 'Semua Siswa (' . $totalCount . ')',
                        'offline' => 'Siswa Offline Studio (' . $offlineCount . ')',
                        'online' => 'Siswa Online LMS (' . $onlineCount . ')',
                        'bersertifikat' => 'Sudah Bersertifikat (' . $certifiedCount . ')',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <a href="{{ route('admin.siswa.index', ['kategori' => $key, 'search' => request('search')]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs transition-all
                           {{ $currentKategori === $key
                               ? 'bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3] font-bold'
                               : 'bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0] hover:bg-[#E2E8F0]/70 font-medium' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex items-center gap-2 w-full lg:w-auto">
                <input type="hidden" name="kategori" value="{{ $currentKategori }}">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama, email, WA, atau kelas..."
                       class="w-full lg:w-64 px-3.5 py-2 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-[#0F172A] hover:bg-[#1E293B] text-white text-xs font-semibold transition-colors shrink-0">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.siswa.index', ['kategori' => $currentKategori]) }}"
                       class="px-3 py-2 rounded-xl bg-[#FEF2F2] text-[#D11F2E] border border-[#FECACA] text-xs font-semibold hover:bg-[#FEE2E2] shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Data Siswa & Sertifikat -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#F1F5F9] text-[#334155] border-b border-[#E2E8F0] font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama &amp; Profil Siswa</th>
                            <th class="px-6 py-4">Kontak (WA &amp; Email)</th>
                            <th class="px-6 py-4">Kelas &amp; Tipe Belajar</th>
                            <th class="px-6 py-4">Progress Materi Web</th>
                            <th class="px-6 py-4">Status Sertifikat &amp; Predikat</th>
                            <th class="px-6 py-4">Tindakan Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($enrollments as $akses)
                            @php
                                $student = $akses->user;
                                $kelas = $akses->kelas;
                                $isOffline = $akses->isOffline();
                                $progress = $kelas ? $kelas->progressPercentageFor($student) : 0;
                                $cert = $allSertifikats->get($student->id . '-' . $akses->kelas_id);
                                $waClean = preg_replace('/[^0-9]/', '', (string) $student->nomor_whatsapp);
                                if (str_starts_with($waClean, '0')) {
                                    $waClean = '62' . substr($waClean, 1);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- 1. Nama & Profil Siswa -->
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0F172A] text-[13px]">{{ $student->name }}</div>
                                    <div class="text-[11px] text-[#64748B] mt-0.5">
                                        {{ $student->status_profesi ?? 'Peserta Pelatihan' }}
                                        @if($student->instansi_asal)
                                            &middot; {{ $student->instansi_asal }}
                                        @endif
                                    </div>
                                </td>

                                <!-- 2. Kontak -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-[#334155] font-mono text-[11px]">{{ $student->email }}</div>
                                    @if($student->nomor_whatsapp)
                                        <a href="https://wa.me/{{ $waClean }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="inline-flex items-center gap-1 mt-1 text-[11px] font-mono font-bold text-[#047857] hover:underline">
                                            <span>WA: {{ $student->nomor_whatsapp }} &nearr;</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-[#94A3B8] italic">WA belum diisi</span>
                                    @endif
                                </td>

                                <!-- 3. Kelas & Tipe Belajar -->
                                <td class="px-6 py-4 max-w-[260px]">
                                    <div class="font-bold text-[#0F172A] leading-snug">{{ $kelas->judul_kelas }}</div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        @if($isOffline)
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3]">
                                                OFFLINE STUDIO
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE]">
                                                ONLINE LMS
                                            </span>
                                        @endif

                                        @if($akses->isAktif())
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]">
                                                VIDEO AKTIF
                                            </span>
                                            <span class="text-[10px] font-mono text-[#64748B]">
                                                Sisa {{ $akses->sisaHari() }} hari
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F8FAFC] text-[#64748B] border border-[#CBD5E1]">
                                                VIDEO TERKUNCI
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- 4. Progress Materi Web -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2 bg-[#E2E8F0] rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $progress >= 100 ? 'bg-[#10B981]' : 'bg-[#D11F2E]' }}"
                                                 style="width: {{ min(100, $progress) }}%"></div>
                                        </div>
                                        <span class="font-mono font-bold text-[#0F172A]">{{ $progress }}%</span>
                                    </div>
                                    @if($isOffline)
                                        <span class="text-[10px] text-[#64748B] block mt-1">
                                            Tatap muka studio (Video opsional)
                                        </span>
                                    @endif
                                </td>

                                <!-- 5. Status Sertifikat & Predikat -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cert)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]">
                                            ✓ {{ $cert->nomor_sertifikat }}
                                        </span>
                                        <div class="text-[11px] font-semibold text-[#0F172A] mt-1">
                                            Predikat: <span class="text-[#D11F2E] font-bold">{{ $cert->predikat ?? 'Baik' }}</span>
                                            <span class="font-mono text-[#64748B]">({{ $cert->nilai ?? 80 }})</span>
                                        </div>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-[#F8FAFC] text-[#64748B] border border-[#CBD5E1]">
                                            Belum Terbit
                                        </span>
                                    @endif
                                </td>

                                <!-- 6. Tindakan Sertifikat & Akses -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($cert)
                                            <a href="{{ route('student.sertifikat.show', $cert->nomor_sertifikat) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-bold text-[#047857] bg-[#ECFDF5] hover:bg-[#D1FAE5] border border-[#A7F3D0] transition-colors">
                                                <span>Lihat / Cetak PDF &nearr;</span>
                                            </a>
                                            <button type="button"
                                                    onclick="document.getElementById('modal-sertifikat-{{ $akses->id }}').classList.remove('hidden')"
                                                    class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold text-[#334155] bg-white hover:bg-slate-50 border border-[#CBD5E1] transition-colors cursor-pointer">
                                                Ubah Nilai
                                            </button>
                                        @else
                                            <button type="button"
                                                    onclick="document.getElementById('modal-sertifikat-{{ $akses->id }}').classList.remove('hidden')"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all cursor-pointer">
                                                <span>Terbitkan Sertifikat</span>
                                            </button>
                                        @endif

                                        <button type="button"
                                                onclick="document.getElementById('modal-akses-user-{{ $student->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-[#0F172A] bg-[#F8FAFC] hover:bg-[#F1F5F9] border border-[#CBD5E1] transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                            <span>Kelola Akses</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                    Belum ada data siswa pada kategori ini. Klik tombol <strong>+ Daftarkan Siswa Offline / Manual</strong> di kanan atas untuk menambahkan peserta baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($enrollments->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] flex justify-center">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL PENERBITAN SERTIFIKAT & RUBRIK PENILAIAN (PER SISWA) -->
    @foreach($enrollments as $akses)
        @php
            $cert = $allSertifikats->get($akses->user_id . '-' . $akses->kelas_id);
            $currentPredikat = $cert->predikat ?? 'Baik';
            $currentNilai = $cert->nilai ?? 80;
        @endphp
        <div id="modal-sertifikat-{{ $akses->id }}"
             class="hidden fixed inset-0 z-50 bg-[#0B0F19]/80 flex items-center justify-center p-4 overflow-y-auto"
             onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 max-w-[520px] w-full shadow-2xl space-y-5 my-8">
                <div class="flex items-center justify-between gap-2 border-b border-[#F1F5F9] pb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                            PENERBITAN SERTIFIKAT RESMI STUDIO
                        </span>
                        <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                            {{ $cert ? 'Perbarui Predikat Nilai Sertifikat' : 'Terbitkan Sertifikat Kelulusan' }}
                        </h2>
                    </div>
                    <button type="button"
                            onclick="document.getElementById('modal-sertifikat-{{ $akses->id }}').classList.add('hidden')"
                            class="text-[#64748B] hover:text-[#D11F2E] p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2 text-xs">
                    <div class="flex justify-between gap-3">
                        <span class="text-[#64748B]">Nama Siswa (Tercetak):</span>
                        <span class="font-bold text-[#0F172A] text-right">{{ $akses->user->name }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-[#64748B]">Program Kelas:</span>
                        <span class="font-bold text-[#0F172A] text-right">{{ $akses->kelas->judul_kelas }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-[#64748B]">Kategori Peserta:</span>
                        <span class="font-mono font-bold text-[#D11F2E] text-right">
                            {{ $akses->isOffline() ? 'Offline Studio Makassar' : 'Online LMS' }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.siswa.sertifikat', $akses->id) }}" class="space-y-4 text-xs">
                    @csrf
                    <div class="space-y-2">
                        <label class="block font-bold text-[#0F172A]">
                            Pilih Predikat Kelulusan (Sesuai Rubrik Penilaian Studio):
                        </label>

                        <div class="grid grid-cols-3 gap-2.5">
                            @foreach([
                                ['predikat' => 'Cukup', 'range' => '60 - 70', 'defaultNilai' => 70],
                                ['predikat' => 'Baik', 'range' => '70 - 80', 'defaultNilai' => 80],
                                ['predikat' => 'Sangat Baik', 'range' => '90 - 100', 'defaultNilai' => 95],
                            ] as $rubrik)
                                <label class="cursor-pointer">
                                    <input type="radio"
                                           name="predikat"
                                           value="{{ $rubrik['predikat'] }}"
                                           {{ $currentPredikat === $rubrik['predikat'] ? 'checked' : '' }}
                                           onchange="document.getElementById('input-nilai-{{ $akses->id }}').value = '{{ $rubrik['defaultNilai'] }}'"
                                           class="sr-only peer">
                                    <div class="p-3 rounded-xl border border-[#CBD5E1] bg-white peer-checked:border-[#D11F2E] peer-checked:bg-[#FFF1F2] text-center transition-all">
                                        <span class="font-extrabold text-[#0F172A] peer-checked:text-[#D11F2E] block text-xs">
                                            {{ $rubrik['predikat'] }}
                                        </span>
                                        <span class="font-mono text-[10px] text-[#64748B] block mt-0.5">
                                            Skor {{ $rubrik['range'] }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="input-nilai-{{ $akses->id }}" class="block font-bold text-[#0F172A]">
                            Angka Nilai Akhir (60 – 100):
                        </label>
                        <input type="number"
                               id="input-nilai-{{ $akses->id }}"
                               name="nilai"
                               value="{{ $currentNilai }}"
                               min="60"
                               max="100"
                               required
                               class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button type="button"
                                onclick="document.getElementById('modal-sertifikat-{{ $akses->id }}').classList.add('hidden')"
                                class="px-4 py-2.5 rounded-xl border border-[#CBD5E1] text-[#334155] font-semibold hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm cursor-pointer">
                            {{ $cert ? 'Simpan Perubahan Nilai' : 'Terbitkan Sertifikat Sekarang' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- MODAL KELOLA HAK AKSES KELAS ONLINE (PER USER) -->
    @foreach($enrollments->pluck('user')->unique('id') as $studentUser)
        @php
            $activeKelasIds = $studentUser->aksesKelas
                ->filter(fn ($ak) => $ak->isAktif())
                ->pluck('kelas_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        @endphp
        <div id="modal-akses-user-{{ $studentUser->id }}"
             class="hidden fixed inset-0 z-50 bg-[#0B0F19]/80 flex items-center justify-center p-4 overflow-y-auto"
             onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 max-w-[540px] w-full shadow-2xl space-y-5 my-8">
                <div class="flex items-center justify-between gap-2 border-b border-[#F1F5F9] pb-3">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                            PENGATURAN HAK AKSES KELAS ONLINE (LMS)
                        </span>
                        <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                            Kelola Akses Kelas Online Siswa
                        </h2>
                    </div>
                    <button type="button"
                            onclick="document.getElementById('modal-akses-user-{{ $studentUser->id }}').classList.add('hidden')"
                            class="text-[#64748B] hover:text-[#D11F2E] p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2 text-xs">
                    <div class="flex justify-between gap-3">
                        <span class="text-[#64748B]">Nama Siswa:</span>
                        <span class="font-bold text-[#0F172A] text-right">{{ $studentUser->name }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-[#64748B]">Email Akun Login:</span>
                        <span class="font-mono font-bold text-[#0F172A] text-right">{{ $studentUser->email }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.siswa.akses', $studentUser->id) }}" class="space-y-4 text-xs">
                    @csrf
                    <div class="space-y-2">
                        <label class="block font-bold text-[#0F172A]">
                            Pilih Kelas Online yang Diizinkan untuk Akun Ini:
                        </label>
                        <p class="text-[11px] text-[#64748B]">
                            Centang kelas online yang boleh ditonton oleh siswa ini. Kelas yang tidak dicentang akan otomatis terkunci di dashboard siswa.
                        </p>

                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1 pt-1">
                            @foreach($kelasList as $k)
                                @php
                                    $isChecked = in_array((int) $k->id, $activeKelasIds, true);
                                @endphp
                                <label class="flex items-center justify-between gap-3 p-3 rounded-xl border border-[#CBD5E1] bg-white hover:border-[#D11F2E]/60 cursor-pointer transition-all">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox"
                                               name="akses_kelas_ids[]"
                                               value="{{ $k->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               class="rounded border-[#CBD5E1] text-[#D11F2E] focus:ring-[#D11F2E]">
                                        <span class="font-bold text-[#0F172A]">{{ $k->judul_kelas }}</span>
                                    </div>
                                    @if($isChecked)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0] shrink-0">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0] shrink-0">
                                            Terkunci
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button type="button"
                                onclick="document.getElementById('modal-akses-user-{{ $studentUser->id }}').classList.add('hidden')"
                                class="px-4 py-2.5 rounded-xl border border-[#CBD5E1] text-[#334155] font-semibold hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm cursor-pointer">
                            Simpan Hak Akses Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- MODAL TAMBAH SISWA OFFLINE / MANUAL -->
    <div id="modal-tambah-siswa"
         class="hidden fixed inset-0 z-50 bg-[#0B0F19]/80 flex items-center justify-center p-4 overflow-y-auto"
         onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 max-w-[600px] w-full shadow-2xl space-y-5 my-8">
            <div class="flex items-center justify-between gap-2 border-b border-[#F1F5F9] pb-3">
                <div>
                    <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                        INPUT PESERTA BARU STUDIO
                    </span>
                    <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                        Daftarkan Siswa Offline / Manual
                    </h2>
                </div>
                <button type="button"
                        onclick="document.getElementById('modal-tambah-siswa').classList.add('hidden')"
                        class="text-[#64748B] hover:text-[#D11F2E] p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.siswa.store') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label for="siswa_name" class="block font-bold text-[#0F172A]">
                            Nama Lengkap Siswa &amp; Gelar (Sesuai yang akan dicetak di Sertifikat)
                        </label>
                        <input type="text"
                               id="siswa_name"
                               name="name"
                               required
                               placeholder="Contoh: Ahmad Fauzi, S.Ars"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-semibold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_email" class="block font-bold text-[#0F172A]">
                            Alamat Email Login Siswa
                        </label>
                        <input type="email"
                               id="siswa_email"
                               name="email"
                               required
                               placeholder="Contoh: ahmad@gmail.com"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_wa" class="block font-bold text-[#0F172A]">
                            Nomor WhatsApp Aktif
                        </label>
                        <input type="text"
                               id="siswa_wa"
                               name="nomor_whatsapp"
                               required
                               placeholder="Contoh: 081234567890"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_kelas" class="block font-bold text-[#0F172A]">
                            Pilih Program Kelas Utama
                        </label>
                        <select id="siswa_kelas"
                                name="kelas_id"
                                required
                                onchange="syncDefaultBonusCheckbox(this.value)"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-semibold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->judul_kelas }} ({{ $k->formattedHarga() }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_tipe" class="block font-bold text-[#0F172A]">
                            Kategori Kelas Peserta
                        </label>
                        <select id="siswa_tipe"
                                name="tipe_kelas"
                                required
                                onchange="toggleBonusOnlineSection(this.value)"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-semibold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            <option value="offline">Kelas Offline Studio (+ Bonus Online Terpilih)</option>
                            <option value="online">Kelas Online LMS</option>
                        </select>
                    </div>

                    <!-- Pilihan Hak Akses Kelas Online (Bonus Siswa Offline) -->
                    <div id="section-bonus-online" class="sm:col-span-2 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2.5">
                        <input type="hidden" name="has_bonus_selection" value="1">
                        <div>
                            <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                                HAK AKSES KELAS ONLINE (BONUS SISWA OFFLINE)
                            </span>
                            <label class="block font-bold text-[#0F172A] mt-0.5">
                                Tentukan Kelas Online yang Bisa Diakses Siswa Ini:
                            </label>
                            <p class="text-[11px] text-[#64748B] mt-0.5">
                                Centang kelas online yang diberikan sebagai bonus sesuai pendaftaran kelas offline-nya.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto pr-1">
                            @foreach($kelasList as $k)
                                <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-[#CBD5E1] bg-white hover:border-[#D11F2E]/60 cursor-pointer transition-all">
                                    <input type="checkbox"
                                           name="bonus_kelas_ids[]"
                                           value="{{ $k->id }}"
                                           {{ $loop->first ? 'checked' : '' }}
                                           class="bonus-kelas-checkbox mt-0.5 rounded border-[#CBD5E1] text-[#D11F2E] focus:ring-[#D11F2E]">
                                    <span class="text-xs font-semibold text-[#0F172A] leading-snug">
                                        {{ $k->judul_kelas }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_profesi" class="block font-bold text-[#0F172A]">
                            Status Profesi (Opsional)
                        </label>
                        <input type="text"
                               id="siswa_profesi"
                               name="status_profesi"
                               placeholder="Contoh: Mahasiswa Arsitektur / Drafter"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="space-y-1.5">
                        <label for="siswa_instansi" class="block font-bold text-[#0F172A]">
                            Instansi / Kampus Asal (Opsional)
                        </label>
                        <input type="text"
                               id="siswa_instansi"
                               name="instansi_asal"
                               placeholder="Contoh: Universitas Hasanuddin"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-[#FFF1F2] border border-[#FECDD3] text-[11px] text-[#9F1239] leading-relaxed">
                    <strong>Info Otomatis:</strong> Jika email siswa belum pernah terdaftar, sistem akan otomatis membuatkan akun dengan password bawaan <code class="font-mono font-bold">password123</code> dan mengaktifkan akses kelas online sesuai pilihan di atas selama 365 hari.
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button"
                            onclick="document.getElementById('modal-tambah-siswa').classList.add('hidden')"
                            class="px-4 py-2.5 rounded-xl border border-[#CBD5E1] text-[#334155] font-semibold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm cursor-pointer">
                        Simpan &amp; Aktifkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function syncDefaultBonusCheckbox(selectedKelasId) {
            const checkboxes = document.querySelectorAll('.bonus-kelas-checkbox');
            checkboxes.forEach((cb) => {
                cb.checked = (cb.value === String(selectedKelasId));
            });
        }

        function toggleBonusOnlineSection(tipe) {
            const section = document.getElementById('section-bonus-online');
            if (!section) return;
            if (tipe === 'offline') {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }
    </script>
@endsection
