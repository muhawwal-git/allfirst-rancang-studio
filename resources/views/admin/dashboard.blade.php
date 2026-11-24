@extends('layouts.admin')

@section('title', 'Dashboard Utama Admin')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Top Title Stack (A-01: k7P3F) -->
        <div class="space-y-1">
            <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                Dashboard Utama Admin
                <span class="sr-only">Dashboard Pengelola Allfirst</span>
            </h1>
        </div>

        <!-- 4 Metric Cards Row (A-01: Metrics Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <!-- 1. Perlu Verifikasi (Clickable -> Verifikasi Pembayaran) -->
            <a href="{{ route('admin.transaksi.index', ['status' => 'menunggu_verifikasi']) }}"
               class="block p-6 rounded-2xl bg-[#FEF2F2] hover:bg-[#FEE2E2]/70 border border-[#FECDD3] shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#D11F2E] block">
                        Perlu Verifikasi
                    </span>
                    <span class="text-xs font-bold text-[#D11F2E] group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-[26px] font-extrabold font-mono text-[#D11F2E] tracking-tight">
                    {{ $pendingCount }} Transaksi
                </div>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Transaksi baru yang menunggu audit bukti transfer
                </p>
            </a>

            <!-- 2. Total Siswa Aktif (Clickable -> Data Siswa) -->
            <a href="{{ route('admin.siswa.index') }}"
               class="block p-6 rounded-2xl bg-white hover:bg-slate-50 border border-[#F1F5F9] hover:border-[#CBD5E1] shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#64748B] group-hover:text-[#0F172A] transition-colors block">
                        Total Siswa Aktif
                    </span>
                    <span class="text-xs font-bold text-[#64748B] group-hover:text-[#0F172A] group-hover:translate-x-0.5 transition-all">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-[26px] font-extrabold font-mono text-[#0F172A] tracking-tight">
                    {{ $studentsCount }} Siswa
                </div>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Pengguna aktif terdaftar yang memiliki kelas valid
                </p>
            </a>

            <!-- 3. Kelas Terbit (Clickable -> Kelola Kelas) -->
            <a href="{{ route('admin.kelas.index') }}"
               class="block p-6 rounded-2xl bg-white hover:bg-slate-50 border border-[#F1F5F9] hover:border-[#CBD5E1] shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#64748B] group-hover:text-[#0F172A] transition-colors block">
                        Kelas Terbit
                    </span>
                    <span class="text-xs font-bold text-[#64748B] group-hover:text-[#0F172A] group-hover:translate-x-0.5 transition-all">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-[26px] font-extrabold font-mono text-[#0F172A] tracking-tight">
                    {{ $coursesCount }} Kelas
                </div>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Jumlah kelas aktif yang dapat diakses publik
                </p>
            </a>

            <!-- 4. Total Pendapatan (Static Info Card) -->
            <div class="p-6 rounded-2xl bg-white border border-[#F1F5F9] shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] space-y-2">
                <span class="text-xs font-semibold text-[#64748B] block">
                    Total Pendapatan
                </span>
                <div class="text-2xl sm:text-[26px] font-extrabold font-mono text-[#0F172A] tracking-tight">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    Total akumulasi dana transaksi yang telah disetujui
                </p>
            </div>
        </div>

        <!-- 1. Running Text Pengaturan Card + Countdown Otomatis (Full Width) -->
        <div class="rounded-2xl bg-white border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] space-y-4">
            <form method="POST" action="{{ route('admin.running-text.update') }}" class="space-y-4">
                @csrf
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            
                            <h2 class="text-sm sm:text-base font-extrabold font-heading text-[#0F172A]">
                                Pengaturan Pengumuman Beranda (Running Text &amp; Hitung Mundur Otomatis)
                                <span class="sr-only">Running Text Header</span>
                            </h2>
                        </div>
                        <p class="text-xs text-[#64748B]">
                            Gunakan teks <code class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-[#D11F2E]">(Sisa {sisa_hari} Hari)</code> dan pilih Tanggal Target Batch agar sisa hari berkurang otomatis setiap pergantian tanggal.
                        </p>
                    </div>

                    <label for="running_text_aktif" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold cursor-pointer select-none shrink-0
                        {{ $runningTextAktif ? 'bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857]' : 'bg-slate-100 border border-slate-200 text-slate-600' }}">
                        <input type="checkbox"
                               id="running_text_aktif"
                               name="running_text_aktif"
                               value="1"
                               {{ $runningTextAktif ? 'checked' : '' }}
                               class="sr-only peer">
                        <span class="w-2 h-2 rounded-full {{ $runningTextAktif ? 'bg-[#10B981]' : 'bg-slate-400' }}"></span>
                        <span>Status: {{ $runningTextAktif ? 'Ditampilkan di Beranda' : 'Disembunyikan' }}</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-end">
                    <div class="lg:col-span-7 space-y-1">
                        <label for="running_text" class="block text-[11px] font-bold text-[#334155]">
                            Isi Teks Berjalan (Pisahkan tiap poin dengan simbol ◆)
                        </label>
                        <input type="text"
                               id="running_text"
                               name="running_text"
                               value="{{ old('running_text', $runningText) }}"
                               required
                               placeholder="Masukkan pengumuman berjalan beranda..."
                               class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs sm:text-[13px] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="lg:col-span-3 space-y-1">
                        <label for="running_text_target_date" class="block text-[11px] font-bold text-[#334155]">
                            Tanggal Target Selesai (Hitung Mundur)
                        </label>
                        <input type="date"
                               id="running_text_target_date"
                               name="running_text_target_date"
                               value="{{ old('running_text_target_date', $runningTextTargetDate) }}"
                               class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-xs sm:text-[13px] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="lg:col-span-2">
                        <button type="submit"
                                class="w-full px-5 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan &amp; Tayangkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Pengaturan Rekening Pembayaran & Kontak WhatsApp Studio (Urutan Pertama / Prioritas Transaksi) -->
        <div class="rounded-2xl bg-white border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#F1F5F9] pb-4">
                <div class="space-y-1">
                    <span class="text-[11px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                        REKENING &amp; PEMBAYARAN STUDIO
                    </span>
                    <h2 class="text-sm sm:text-base font-extrabold font-heading text-[#0F172A]">
                        Pengaturan Rekening Transfer Bank &amp; WhatsApp Konfirmasi
                    </h2>
                    <p class="text-xs text-[#64748B]">
                        Kondisi awal menampilkan 1 rekening utama. Klik tombol <strong>+ Tambah Tempat Pembayaran</strong> jika ingin menyediakan opsi bank tambahan bagi siswa.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <button type="button"
                            onclick="addStudioBankSlot()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#D11F2E] bg-[#FFF1F2] hover:bg-[#FFE4E6] border border-[#FECDD3] transition-colors shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Tempat Pembayaran</span>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.rekening.update') }}" class="space-y-4 text-xs">
                @csrf

                <div id="studio-bank-list" class="space-y-3">
                    @foreach($bankSlots as $index => $slot)
                        <div class="bank-slot-row rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 transition-all">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
                                <div class="md:col-span-3 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="block font-bold text-[#0F172A]">
                                            Nama Bank / Metode <span class="bank-slot-number font-mono text-[#D11F2E]">#{{ $index + 1 }}</span>
                                        </label>
                                    </div>
                                    <input type="text"
                                           name="banks[{{ $index }}][bank]"
                                           value="{{ old("banks.$index.bank", $slot['bank']) }}"
                                           required
                                           placeholder="Contoh: BCA / Mandiri / BSI"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] font-semibold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                                </div>

                                <div class="md:col-span-4 space-y-1.5">
                                    <label class="block font-bold text-[#0F172A]">
                                        Nomor Rekening Pembayaran
                                    </label>
                                    <input type="text"
                                           name="banks[{{ $index }}][nomor_rekening]"
                                           value="{{ old("banks.$index.nomor_rekening", $slot['nomor_rekening']) }}"
                                           required
                                           placeholder="Contoh: 816-123-4567"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] font-mono font-extrabold text-[#0F172A] tracking-wider focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                                </div>

                                <div class="md:col-span-4 space-y-1.5">
                                    <label class="block font-bold text-[#0F172A]">
                                        Atas Nama (A/N Pemilik Rekening)
                                    </label>
                                    <input type="text"
                                           name="banks[{{ $index }}][atas_nama]"
                                           value="{{ old("banks.$index.atas_nama", $slot['atas_nama']) }}"
                                           required
                                           placeholder="Contoh: PT Allfirst Rancang Studio"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                                </div>

                                <div class="md:col-span-1 flex justify-end">
                                    @if($index === 0)
                                        <span class="w-full py-2.5 px-2 rounded-xl bg-[#FFF1F2] border border-[#FECDD3] text-[#D11F2E] font-mono text-[10px] font-bold text-center block">
                                            UTAMA
                                        </span>
                                    @else
                                        <button type="button"
                                                onclick="removeStudioBankSlot(this)"
                                                title="Hapus Rekening Ini"
                                                class="w-full py-2.5 px-3 rounded-xl bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] text-[#D11F2E] font-bold text-xs flex items-center justify-center gap-1 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="md:hidden">Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Row: WhatsApp Konfirmasi & Submit Button -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end pt-3 border-t border-[#F1F5F9]">
                    <div class="lg:col-span-8 space-y-1.5">
                        <label for="whatsapp_studio" class="block font-bold text-[#0F172A]">
                            Nomor WhatsApp Konfirmasi Admin (Format: 628...)
                        </label>
                        <input type="text"
                               id="whatsapp_studio"
                               name="whatsapp_studio"
                               value="{{ old('whatsapp_studio', $whatsappStudio) }}"
                               required
                               placeholder="Contoh: 6282212345678"
                               class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>

                    <div class="lg:col-span-4">
                        <button type="submit"
                                class="w-full py-3 px-5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan Rekening &amp; WhatsApp Studio
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 3. Pengaturan Teks Header Beranda & Pop-Up Promo (2 Columns Equal-Height Grid, Zero Gap) -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
            <!-- Left: Pengaturan Teks Header Hero Beranda + Live Preview (5 Cols, Stretched Full Height) -->
            <div class="xl:col-span-5 rounded-2xl bg-white border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="space-y-1 border-b border-[#F1F5F9] pb-3">
                        <span class="text-[11px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                            KONTEN UTAMA BERANDA
                        </span>
                        <h2 class="text-sm sm:text-base font-extrabold font-heading text-[#0F172A]">
                            Pengaturan Teks Header (Hero Beranda)
                        </h2>
                        <p class="text-xs text-[#64748B]">
                            Ubah judul besar dan sub-deskripsi yang tampil di bagian paling atas halaman depan.
                        </p>
                    </div>

                    <form id="heroUpdateForm" method="POST" action="{{ route('admin.hero.update') }}" class="space-y-4 text-xs">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="hero_badge" class="block font-bold text-[#0F172A]">
                                Label Badge Atas
                            </label>
                            <input type="text"
                                   id="hero_badge"
                                   name="hero_badge"
                                   value="{{ old('hero_badge', $heroBadge) }}"
                                   required
                                   oninput="document.getElementById('preview-hero-badge').textContent = this.value"
                                   class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>

                        <div class="space-y-1.5">
                            <label for="hero_title" class="block font-bold text-[#0F172A]">
                                Judul Utama Header (Hero Title)
                            </label>
                            <input type="text"
                                   id="hero_title"
                                   name="hero_title"
                                   value="{{ old('hero_title', $heroTitle) }}"
                                   required
                                   oninput="document.getElementById('preview-hero-title').textContent = this.value"
                                   class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-bold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>

                        <div class="space-y-1.5">
                            <label for="hero_description" class="block font-bold text-[#0F172A]">
                                Deskripsi Pendukung Header
                            </label>
                            <textarea id="hero_description"
                                      name="hero_description"
                                      rows="3"
                                      required
                                      oninput="document.getElementById('preview-hero-desc').textContent = this.value"
                                      class="w-full p-3.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">{{ old('hero_description', $heroDescription) }}</textarea>
                        </div>
                    </form>

                    <!-- Live Mini-Preview Hero Beranda (Fills Vertical Space Harmoniously) -->
                    <div class="rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold text-[#64748B] uppercase tracking-wider">
                                LIVE PREVIEW TAMPILAN HERO BERANDA
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono text-[#047857] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                Real-Time
                            </span>
                        </div>
                        <div class="rounded-xl bg-white border border-[#E2E8F0] p-4 space-y-2 shadow-2xs">
                            <span id="preview-hero-badge" class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#FFF1F2] text-[#D11F2E] border border-[#FFE4E6]">
                                {{ $heroBadge }}
                            </span>
                            <h3 id="preview-hero-title" class="text-sm font-extrabold font-heading text-[#0F172A] leading-snug">
                                {{ $heroTitle }}
                            </h3>
                            <p id="preview-hero-desc" class="text-[11px] text-[#475569] leading-relaxed line-clamp-2">
                                {{ $heroDescription }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            form="heroUpdateForm"
                            class="w-full py-3 px-5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                        Simpan Teks Header Beranda
                    </button>
                </div>
            </div>

            <!-- Right: Pengaturan Pop-Up Promo & Floating Bar (7 Cols, Stretched Full Height) -->
            <div class="xl:col-span-7 rounded-2xl bg-white border border-[#F1F5F9] p-6 shadow-[0_8px_25px_-5px_rgba(15,23,42,0.04)] flex flex-col justify-between">
                <form method="POST" action="{{ route('admin.promo.update') }}" enctype="multipart/form-data" class="space-y-4 text-xs h-full flex flex-col justify-between">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#F1F5F9] pb-3">
                            <div class="space-y-1">
                                <span class="text-[11px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                                    KAMPANYE PROMO &amp; KONVERSI
                                </span>
                                <h2 class="text-sm sm:text-base font-extrabold font-heading text-[#0F172A]">
                                    Pengaturan Pop-Up Promo &amp; Floating Promo Bar
                                </h2>
                                <p class="text-xs text-[#64748B]">
                                    Muncul sebagai Pop-Up di tengah layar dan otomatis menciut menjadi Floating Bar saat ditutup.
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <label for="promo_popup_aktif" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold cursor-pointer select-none
                                    {{ $promoAktif ? 'bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857]' : 'bg-slate-100 border border-slate-200 text-slate-600' }}">
                                    <input type="checkbox"
                                           id="promo_popup_aktif"
                                           name="promo_popup_aktif"
                                           value="1"
                                           {{ $promoAktif ? 'checked' : '' }}
                                           class="sr-only">
                                    <span class="w-2 h-2 rounded-full {{ $promoAktif ? 'bg-[#10B981]' : 'bg-slate-400' }}"></span>
                                    <span>{{ $promoAktif ? 'Status: Promo Aktif' : 'Status: Nonaktif' }}</span>
                                </label>

                                @if($promoAktif)
                                    <button type="submit"
                                            name="action_status"
                                            value="disable"
                                            formnovalidate
                                            class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#D11F2E] bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] transition-colors">
                                        Nonaktifkan Promo
                                    </button>
                                @else
                                    <button type="submit"
                                            name="action_status"
                                            value="enable"
                                            class="px-3.5 py-1.5 rounded-full text-xs font-bold text-[#047857] bg-[#ECFDF5] hover:bg-[#D1FAE5] border border-[#A7F3D0] transition-colors">
                                        Aktifkan Kembali
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="promo_judul" class="block font-bold text-[#0F172A]">
                                    Judul Paket Promo
                                </label>
                                <input type="text"
                                       id="promo_judul"
                                       name="promo_judul"
                                       value="{{ old('promo_judul', $promoJudul) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-bold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_badge" class="block font-bold text-[#0F172A]">
                                    Label Badge Promo Atas
                                </label>
                                <input type="text"
                                       id="promo_badge"
                                       name="promo_badge"
                                       value="{{ old('promo_badge', $promoBadge) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="promo_subjudul" class="block font-bold text-[#0F172A]">
                                Sub-Judul / Keterangan Paket
                            </label>
                            <input type="text"
                                   id="promo_subjudul"
                                   name="promo_subjudul"
                                   value="{{ old('promo_subjudul', $promoSubjudul) }}"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label for="promo_harga_coret" class="block font-bold text-[#0F172A]">
                                    Harga Normal Coret (Rp)
                                </label>
                                <input type="number"
                                       id="promo_harga_coret"
                                       name="promo_harga_coret"
                                       value="{{ old('promo_harga_coret', $promoHargaCoret) }}"
                                       required
                                       min="0"
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_harga_diskon" class="block font-bold text-[#0F172A]">
                                    Harga Promo (Rp)
                                </label>
                                <input type="number"
                                       id="promo_harga_diskon"
                                       name="promo_harga_diskon"
                                       value="{{ old('promo_harga_diskon', $promoHargaDiskon) }}"
                                       required
                                       min="0"
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono font-bold text-[#D11F2E] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_label_hemat" class="block font-bold text-[#0F172A]">
                                    Badge Hemat (Contoh: Hemat 49%)
                                </label>
                                <input type="text"
                                       id="promo_label_hemat"
                                       name="promo_label_hemat"
                                       value="{{ old('promo_label_hemat', $promoLabelHemat) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label for="promo_batas_waktu" class="block font-bold text-[#0F172A]">
                                    Batas Waktu Countdown
                                </label>
                                <input type="datetime-local"
                                       id="promo_batas_waktu"
                                       name="promo_batas_waktu"
                                       value="{{ old('promo_batas_waktu', $promoBatasWaktu) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_cta_text" class="block font-bold text-[#0F172A]">
                                    Teks Tombol CTA
                                </label>
                                <input type="text"
                                       id="promo_cta_text"
                                       name="promo_cta_text"
                                       value="{{ old('promo_cta_text', $promoCtaText) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="promo_cta_url" class="block font-bold text-[#0F172A]">
                                    Link Tujuan Tombol
                                </label>
                                <input type="text"
                                       id="promo_cta_url"
                                       name="promo_cta_url"
                                       value="{{ old('promo_cta_url', $promoCtaUrl) }}"
                                       required
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] font-mono text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            </div>
                        </div>

                        <!-- Upload Foto / Banner Pop-Up Promo -->
                        <div class="space-y-2 pt-1">
                            <label for="promo_gambar" class="block font-bold text-[#0F172A]">
                                Foto / Banner Visual Pop-Up Promo (Opsional &middot; JPG/PNG/WebP Maks 2MB)
                            </label>

                            @if(!empty($promoGambar))
                                <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $promoGambar) }}" alt="Banner Promo" class="w-20 h-12 rounded-lg object-cover border border-[#CBD5E1]">
                                        <div>
                                            <span class="font-bold text-[#0F172A] block">Banner Kustom Terpasang</span>
                                            <span class="text-[11px] font-mono text-[#64748B]">{{ basename($promoGambar) }}</span>
                                        </div>
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#FEF2F2] border border-[#FECACA] text-[#D11F2E] font-semibold cursor-pointer">
                                        <input type="checkbox" name="hapus_promo_gambar" value="1" class="rounded border-[#FECACA] text-[#D11F2E]">
                                        <span>Hapus Foto</span>
                                    </label>
                                </div>
                            @endif

                            <label for="promo_gambar"
                                   class="rounded-xl bg-[#F8FAFC] border border-dashed border-[#CBD5E1] hover:border-[#D11F2E] p-4 flex items-center justify-between gap-4 cursor-pointer transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-white border border-[#E2E8F0] text-[#D11F2E] flex items-center justify-center shrink-0 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span id="promo-gambar-label" class="font-bold text-[#0F172A] block">
                                            Pilih Berkas Foto / Poster Promo dari Komputer
                                        </span>
                                        <span class="text-[11px] font-mono text-[#64748B]">
                                            Kosongkan jika ingin menggunakan latar studio arsitektur bawaan Allfirst
                                        </span>
                                    </div>
                                </div>
                                <span class="px-3.5 py-1.5 rounded-lg bg-white border border-[#E2E8F0] font-semibold text-[#0F172A] shrink-0 shadow-sm">
                                    Pilih Foto
                                </span>
                                <input type="file"
                                       id="promo_gambar"
                                       name="promo_gambar"
                                       accept="image/*"
                                       class="sr-only"
                                       onchange="if(this.files[0]) document.getElementById('promo-gambar-label').textContent = this.files[0].name">
                            </label>
                        </div>
                    </div>

                    <!-- Submit & Nonaktifkan Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4">
                        <button type="submit"
                                name="action_status"
                                value="enable"
                                class="flex-1 py-3 px-5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan &amp; Tayangkan Pop-Up Promo
                        </button>

                        @if($promoAktif)
                            <button type="submit"
                                    name="action_status"
                                    value="disable"
                                    formnovalidate
                                    class="py-3 px-5 rounded-xl text-xs font-bold text-[#D11F2E] bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] transition-colors shrink-0">
                                Nonaktifkan Promo (Promo Berakhir)
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function reindexStudioBankSlots() {
            const container = document.getElementById('studio-bank-list');
            if (!container) return;
            const rows = container.querySelectorAll('.bank-slot-row');
            rows.forEach((row, idx) => {
                const numBadge = row.querySelector('.bank-slot-number');
                if (numBadge) numBadge.textContent = '#' + (idx + 1);

                const inputs = row.querySelectorAll('input');
                if (inputs[0]) inputs[0].name = `banks[${idx}][bank]`;
                if (inputs[1]) inputs[1].name = `banks[${idx}][nomor_rekening]`;
                if (inputs[2]) inputs[2].name = `banks[${idx}][atas_nama]`;
            });
        }

        function addStudioBankSlot() {
            const container = document.getElementById('studio-bank-list');
            if (!container) return;
            const idx = container.querySelectorAll('.bank-slot-row').length;
            if (idx >= 5) {
                alert('Maksimal 5 rekening pembayaran dapat ditambahkan.');
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'bank-slot-row rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 transition-all';
            wrapper.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">
                    <div class="md:col-span-3 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block font-bold text-[#0F172A]">
                                Nama Bank / Metode <span class="bank-slot-number font-mono text-[#D11F2E]">#${idx + 1}</span>
                            </label>
                        </div>
                        <input type="text"
                               name="banks[${idx}][bank]"
                               required
                               placeholder="Contoh: Bank Mandiri / BSI"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] font-semibold text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>
                    <div class="md:col-span-4 space-y-1.5">
                        <label class="block font-bold text-[#0F172A]">
                            Nomor Rekening Pembayaran
                        </label>
                        <input type="text"
                               name="banks[${idx}][nomor_rekening]"
                               required
                               placeholder="Contoh: 140-00-9876543-2"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] font-mono font-extrabold text-[#0F172A] tracking-wider focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>
                    <div class="md:col-span-4 space-y-1.5">
                        <label class="block font-bold text-[#0F172A]">
                            Atas Nama (A/N Pemilik Rekening)
                        </label>
                        <input type="text"
                               name="banks[${idx}][atas_nama]"
                               required
                               placeholder="Contoh: PT Allfirst Rancang Studio"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                    </div>
                    <div class="md:col-span-1 flex justify-end">
                        <button type="button"
                                onclick="removeStudioBankSlot(this)"
                                title="Hapus Rekening Ini"
                                class="w-full py-2.5 px-3 rounded-xl bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] text-[#D11F2E] font-bold text-xs flex items-center justify-center gap-1 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="md:hidden">Hapus</span>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(wrapper);
            const firstInput = wrapper.querySelector('input');
            if (firstInput) firstInput.focus();
        }

        function removeStudioBankSlot(btn) {
            const row = btn.closest('.bank-slot-row');
            if (row) {
                row.remove();
                reindexStudioBankSlots();
            }
        }
    </script>
@endsection
