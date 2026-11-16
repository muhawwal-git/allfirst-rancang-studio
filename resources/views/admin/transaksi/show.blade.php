@extends('layouts.admin')

@section('title', 'Pemeriksaan Bukti Pembayaran ' . $transaksi->nomor_invoice . ' — Allfirst Rancang Studio')

@section('content')
    <!-- A-02b Modal Audit Card View (baiBA) -->
    <div class="w-full flex items-center justify-center py-4">
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 max-w-[820px] w-full shadow-xl grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Left Column: Pratinjau Bukti Transfer (fill: #111827) -->
            <div class="md:col-span-5 rounded-xl bg-[#111827] p-6 flex flex-col items-center justify-center text-center min-h-[360px]">
                @if($transaksi->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($transaksi->bukti_pembayaran))
                    <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}"
                         alt="Bukti Transfer {{ $transaksi->nomor_invoice }}"
                         class="max-h-[260px] w-auto object-contain rounded-lg mb-3 border border-slate-700">
                @else
                    <svg class="w-10 h-10 text-[#94A3B8] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <span class="text-xs font-semibold text-white block">
                        [Pratinjau Foto Bukti Transfer Jelas]
                    </span>
                @endif
                <span class="text-[11px] font-mono text-[#94A3B8] uppercase mt-2 block">
                    BCA &middot; {{ strtoupper($transaksi->created_at->format('d M Y')) }} &middot; {{ strtoupper($transaksi->formattedJumlah()) }}
                </span>
            </div>

            <!-- Right Column: Kolom Audit -->
            <div class="md:col-span-7 flex flex-col justify-between space-y-4">
                <!-- Modal Header -->
                <div class="flex items-center justify-between gap-2">
                    <h1 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                        Pemeriksaan Bukti Pembayaran &middot; {{ $transaksi->nomor_invoice }}
                    </h1>
                    <a href="{{ route('admin.transaksi.index') }}"
                       class="text-[#0F172A] hover:text-[#D11F2E] p-1 transition-colors"
                       title="Kembali ke Daftar Transaksi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                <!-- Data Transaksi Box (fill: #F8FAFC, stroke: #E2E8F0) -->
                <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2.5 text-xs">
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="text-[#64748B] shrink-0">Nama Siswa:</span>
                        <span class="font-bold text-[#0F172A] text-right">{{ $transaksi->user->name }}</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="text-[#64748B] shrink-0">Kelas Terdaftar:</span>
                        <span class="font-bold text-[#0F172A] text-right">{{ $transaksi->kelas->judul_kelas }}</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="text-[#64748B] shrink-0">Nominal Ditagihkan:</span>
                        <span class="font-bold font-mono text-[#0F172A] text-right">{{ $transaksi->formattedJumlah() }}</span>
                    </div>
                </div>

                <!-- Alasan Penolakan Field -->
                <div class="space-y-1.5">
                    <label for="alasan_penolakan" class="block text-xs font-bold text-[#0F172A]">
                        Alasan Penolakan (Wajib diisi jika transaksi ditolak):
                    </label>
                    <textarea id="alasan_penolakan"
                              name="alasan_penolakan"
                              form="reject-form"
                              rows="3"
                              required
                              placeholder="Tuliskan alasan penolakan secara jelas agar siswa dapat memperbaikinya (contoh: Foto struk transfer buram / nominal tidak sesuai)."
                              class="w-full p-3 rounded-xl bg-white border border-[#CBD5E1] text-xs text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E] leading-relaxed">{{ old('alasan_penolakan', $transaksi->alasan_penolakan) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2.5">
                    <form method="POST" action="{{ route('admin.transaksi.approve', $transaksi->id) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                            Setujui Pembayaran &amp; Aktifkan Akses
                        </button>
                    </form>

                    <form id="reject-form" method="POST" action="{{ route('admin.transaksi.reject', $transaksi->id) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 px-4 rounded-xl text-xs font-semibold text-[#D11F2E] bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] transition-colors">
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>

                <!-- Footer Note -->
                <p class="text-[11px] text-[#64748B] leading-relaxed">
                    Menyetujui pembayaran akan secara otomatis membuka hak akses belajar siswa ke Classroom selama 365 hari penuh.
                </p>
            </div>
        </div>
    </div>
@endsection
