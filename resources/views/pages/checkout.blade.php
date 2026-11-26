@extends('layouts.app')

@section('title', 'Checkout Kelas Online — ' . $kelas->judul)

@section('content')
    <!-- CHECKOUT BODY (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] py-8 sm:py-10 min-h-screen border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-6">
            <!-- Header -->
            <div class="space-y-1.5">
                <span class="block text-xs font-mono font-bold tracking-widest text-[#D11F2E] uppercase">
                    {{ !empty($isOffline) ? 'PENDAFTARAN PELATIHAN TATAP MUKA STUDIO + BONUS KELAS ONLINE' : 'PROSES PEMBELIAN KELAS' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                    {{ !empty($isOffline) ? 'Checkout Kelas Offline (+ Bonus Akses Online)' : 'Checkout Kelas Online' }}
                </h1>
            </div>

            @if(!empty($isOffline))
                <div class="bg-[#ECFDF5] border border-[#A7F3D0] rounded-2xl p-5 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#047857] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-[#047857] leading-relaxed">
                        <strong class="font-bold">Benefit Ganda Pendaftaran Kelas Offline:</strong> Selain terdaftar pada sesi bimbingan tatap muka di studio, akun Anda akan <strong>otomatis mendapatkan Hak Akses Penuh ke Video Pembelajaran Kelas Online selama 1 Tahun (365 Hari)</strong> begitu pembayaran diverifikasi oleh Admin.
                    </p>
                </div>
            @endif

            <!-- Banner Pencegahan Duplikasi (Screen 9: S-02b Checkout - Transaksi Pending) -->
            @if($pendingTransaksi)
                <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-[#B45309] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-[#B45309]">
                                Transaksi Sedang Berjalan: Anda masih memiliki invoice transaksi aktif untuk kelas ini.
                            </h3>
                            <p class="text-xs text-[#B45309] leading-relaxed">
                                Nomor Invoice: <strong class="underline">{{ $pendingTransaksi->nomor_invoice }}</strong> &middot; Nominal: {{ $pendingTransaksi->formatted_total }}. Silakan selesaikan pembayaran invoice ini atau tunggu proses verifikasi admin.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('transaksi.show', $pendingTransaksi->nomor_invoice) }}"
                           class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] shadow-sm whitespace-nowrap">
                            Buka Instruksi Pembayaran &rarr;
                        </a>
                        <a href="{{ route('student.dashboard') }}"
                           class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm whitespace-nowrap">
                            Kembali ke Dashboard Saya
                        </a>
                    </div>
                </div>
            @endif

            @if($isReaktivasi)
                <div class="bg-[#ECFDF5] border border-[#A7F3D0] rounded-2xl p-5 flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#047857] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-[#047857] leading-relaxed">
                        <strong class="font-bold">Diskon Reaktivasi 50% Aktif:</strong> Karena masa aktif kelas ini telah berakhir, Anda berhak melakukan reaktivasi akses 365 hari dengan potongan biaya 50%.
                    </p>
                </div>
            @endif

            <!-- Checkout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left Column (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Ringkasan Kelas Card -->
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-7 space-y-4">
                        <h2 class="text-base font-bold font-heading text-[#0F172A] border-b border-[#F1F5F9] pb-3">
                            Ringkasan Program Kelas
                        </h2>

                        <div class="flex items-start gap-4">
                            <div class="w-16 h-12 rounded-lg bg-[#111827] flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-mono text-[#E2E8F0] font-bold uppercase">{{ $kelas->kategori }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold text-[#D11F2E] bg-[#FFF1F2]">
                                    Akses 365 Hari
                                </span>
                                <h3 class="text-base font-bold text-[#0F172A] leading-snug">
                                    {{ $kelas->judul }}
                                </h3>
                                <p class="text-xs text-[#64748B]">
                                    Kategori: {{ $kelas->kategori }} &middot; Level: {{ $kelas->level }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pemesan (Siswa) Card -->
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-7 space-y-4">
                        <h2 class="text-base font-bold font-heading text-[#0F172A] border-b border-[#F1F5F9] pb-3">
                            Informasi Peserta (Sertifikat)
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="space-y-1">
                                <span class="text-[#64748B] block">Nama Lengkap (Sertifikat):</span>
                                <span class="font-bold text-[#0F172A] text-sm block">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[#64748B] block">Alamat Email:</span>
                                <span class="font-semibold text-[#0F172A] block">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[#64748B] block">Nomor WhatsApp:</span>
                                <span class="font-semibold text-[#0F172A] block">{{ auth()->user()->nomor_whatsapp ?? '-' }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[#64748B] block">Status / Profesi:</span>
                                <span class="font-semibold text-[#0F172A] block">{{ auth()->user()->status_profesi ?? 'Umum' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (lg:col-span-5) -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-7 space-y-5">
                        <h2 class="text-base font-bold font-heading text-[#0F172A] border-b border-[#F1F5F9] pb-3">
                            Ringkasan Pembayaran
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-[#334155]">
                                <span>Harga Normal Kelas</span>
                                <span class="font-mono font-medium">{{ $kelas->formatted_harga }}</span>
                            </div>

                            @if($isReaktivasi)
                                <div class="flex justify-between text-emerald-600 font-medium">
                                    <span>Diskon Reaktivasi (50%)</span>
                                    <span class="font-mono">-Rp {{ number_format($kelas->harga / 2, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-[#334155]">
                                <span>Biaya Layanan &amp; Verifikasi</span>
                                <span class="font-mono font-medium">Rp 0 (Gratis)</span>
                            </div>

                            <div class="pt-3 border-t border-[#E2E8F0] flex justify-between items-baseline">
                                <span class="font-bold text-[#0F172A]">Total Pembayaran</span>
                                <span class="text-2xl font-extrabold font-mono text-[#0F172A]">
                                    Rp {{ number_format($hargaFinal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.store', $kelas->slug) }}">
                            @csrf
                            @if(!empty($isOffline))
                                <input type="hidden" name="mode" value="offline">
                            @endif
                            <button type="submit"
                                    {{ $pendingTransaksi ? 'disabled' : '' }}
                                    class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                Terbitkan Invoice &amp; Lanjut Pembayaran
                            </button>
                        </form>

                        @if($pendingTransaksi)
                            <p class="text-[11px] text-[#64748B] text-center leading-relaxed">
                                Formulir dinonaktifkan guna mencegah duplikasi transaksi untuk kelas yang sama.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
