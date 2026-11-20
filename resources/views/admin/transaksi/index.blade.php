@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Siswa — Allfirst Rancang Studio')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Title (A-02: KLytB) -->
        <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
            Verifikasi Pembayaran Siswa
        </h1>

        <!-- Filter Bar Card (fill: #FFFFFF, stroke: #E2E8F0) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] px-5 py-3.5 flex flex-wrap items-center justify-between gap-4 shadow-sm">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="text-xs font-bold text-[#0F172A] mr-1">Filter Status:</span>
                @php
                    $tabs = [
                        'semua' => 'Semua Status',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'berhasil' => 'Berhasil',
                        'ditolak' => 'Ditolak',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <a href="{{ route('admin.transaksi.index', ['status' => $key]) }}"
                       class="px-4 py-1.5 rounded-full text-xs transition-all
                           {{ $currentStatus === $key
                               ? 'bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3] font-bold'
                               : 'bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0] hover:bg-[#E2E8F0]/70 font-medium' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if(request('search'))
                <a href="{{ route('admin.transaksi.index', ['status' => $currentStatus]) }}" class="text-xs text-[#D11F2E] font-semibold hover:underline">
                    Reset Pencarian ("{{ request('search') }}")
                </a>
            @endif
        </div>

        <!-- Tabel Transaksi (7 Columns: No. Invoice, Nama Siswa, Nama Kelas, Nominal Transfer, Tanggal Transaksi, Status, Tindakan) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#F1F5F9] text-[#334155] border-b border-[#E2E8F0] font-bold">
                        <tr>
                            <th class="px-6 py-4">No. Invoice</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Nominal Transfer</th>
                            <th class="px-6 py-4">Tanggal Transaksi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($transaksis as $trx)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- 1. No. Invoice -->
                                <td class="px-6 py-4 font-mono font-bold text-[#0F172A] whitespace-nowrap">
                                    {{ $trx->nomor_invoice }}
                                </td>

                                <!-- 2. Nama Siswa -->
                                <td class="px-6 py-4 font-semibold text-[#0F172A] whitespace-nowrap">
                                    {{ $trx->user->name }}
                                </td>

                                <!-- 3. Nama Kelas -->
                                <td class="px-6 py-4 text-[#334155] max-w-[260px] leading-relaxed">
                                    <span>{{ $trx->kelas->judul_kelas }}</span>
                                    @if($trx->jenis_transaksi === 'offline')
                                        <span class="ml-1.5 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3]">
                                            OFFLINE + BONUS ONLINE
                                        </span>
                                    @endif
                                </td>

                                <!-- 4. Nominal Transfer -->
                                <td class="px-6 py-4 font-mono font-bold text-[#0F172A] whitespace-nowrap">
                                    {{ $trx->formattedJumlah() }}
                                </td>

                                <!-- 5. Tanggal Transaksi -->
                                <td class="px-6 py-4 text-[#64748B] whitespace-nowrap">
                                    {{ $trx->created_at->format('d M Y') }}
                                </td>

                                <!-- 6. Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trx->status_pembayaran === 'berhasil')
                                        <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]">
                                            Berhasil
                                        </span>
                                    @elseif($trx->status_pembayaran === 'ditolak')
                                        <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#FFF1F2] text-[#D11F2E] border border-[#FFE4E6]">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#B45309] border border-[#FDE68A]">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>

                                <!-- 7. Tindakan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(in_array($trx->status_pembayaran, ['menunggu_verifikasi', 'menunggu_pembayaran']))
                                        <button type="button"
                                                onclick="document.getElementById('modal-audit-{{ $trx->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                            Periksa Bukti
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="document.getElementById('modal-audit-{{ $trx->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] transition-colors">
                                            Lihat Detail
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-[#64748B]">
                                    Tidak ada data transaksi yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksis->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] flex justify-center">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL VERIFIKASI PEMBAYARAN OVERLAYS (A-02b: baiBA) -->
    @foreach($transaksis as $trx)
        <div id="modal-audit-{{ $trx->id }}"
             class="hidden fixed inset-0 z-50 bg-[#0B0F19]/80 flex items-center justify-center p-4 overflow-y-auto"
             onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 max-w-[820px] w-full shadow-2xl grid grid-cols-1 md:grid-cols-12 gap-6 my-8">
                <!-- Left Column: Pratinjau Bukti Transfer (fill: #111827) -->
                <div class="md:col-span-5 rounded-xl bg-[#111827] p-6 flex flex-col items-center justify-center text-center min-h-[340px]">
                    @if($trx->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($trx->bukti_pembayaran))
                        <img src="{{ asset('storage/' . $trx->bukti_pembayaran) }}"
                             alt="Bukti Transfer {{ $trx->nomor_invoice }}"
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
                        BCA &middot; {{ strtoupper($trx->created_at->format('d M Y')) }} &middot; {{ strtoupper($trx->formattedJumlah()) }}
                    </span>
                </div>

                <!-- Right Column: Kolom Audit -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-4">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-base sm:text-lg font-extrabold font-heading text-[#0F172A]">
                            Pemeriksaan Bukti Pembayaran &middot; {{ $trx->nomor_invoice }}
                        </h2>
                        <button type="button"
                                onclick="document.getElementById('modal-audit-{{ $trx->id }}').classList.add('hidden')"
                                class="text-[#0F172A] hover:text-[#D11F2E] p-1 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Data Transaksi Box (fill: #F8FAFC, stroke: #E2E8F0) -->
                    <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 space-y-2.5 text-xs">
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="text-[#64748B] shrink-0">Nama Siswa:</span>
                            <span class="font-bold text-[#0F172A] text-right">{{ $trx->user->name }}</span>
                        </div>
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="text-[#64748B] shrink-0">Kelas Terdaftar:</span>
                            <span class="font-bold text-[#0F172A] text-right">{{ $trx->kelas->judul_kelas }}</span>
                        </div>
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="text-[#64748B] shrink-0">Nominal Ditagihkan:</span>
                            <span class="font-bold font-mono text-[#0F172A] text-right">{{ $trx->formattedJumlah() }}</span>
                        </div>
                    </div>

                    <!-- Alasan Penolakan Field -->
                    <div class="space-y-1.5">
                        <label for="alasan-penolakan-{{ $trx->id }}" class="block text-xs font-bold text-[#0F172A]">
                            Alasan Penolakan (Wajib diisi jika transaksi ditolak):
                        </label>
                        <textarea id="alasan-penolakan-{{ $trx->id }}"
                                  name="alasan_penolakan"
                                  form="reject-form-{{ $trx->id }}"
                                  rows="3"
                                  required
                                  placeholder="Tuliskan alasan penolakan secara jelas agar siswa dapat memperbaikinya (contoh: Foto struk transfer buram / nominal tidak sesuai)."
                                  class="w-full p-3 rounded-xl bg-white border border-[#CBD5E1] text-xs text-[#0F172A] placeholder-[#64748B] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E] leading-relaxed">{{ $trx->alasan_penolakan }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2.5">
                        <form method="POST" action="{{ route('admin.transaksi.approve', $trx->id) }}" class="space-y-2.5">
                            @csrf
                            @if($trx->jenis_transaksi === 'offline' && isset($kelasList) && $kelasList->isNotEmpty())
                                <div class="rounded-xl bg-[#FFF1F2]/60 border border-[#FECDD3] p-3.5 space-y-2 text-xs">
                                    <input type="hidden" name="has_bonus_selection" value="1">
                                    <div>
                                        <span class="text-[10px] font-mono font-bold text-[#D11F2E] uppercase tracking-wider block">
                                            PENGATURAN BONUS KELAS ONLINE (SISWA OFFLINE)
                                        </span>
                                        <p class="text-[11px] text-[#334155] mt-0.5">
                                            Pilih kelas online yang diizinkan untuk diakses oleh siswa offline ini:
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 max-h-32 overflow-y-auto pr-1">
                                        @foreach($kelasList as $k)
                                            <label class="flex items-start gap-2 p-2 rounded-lg border border-[#FECDD3] bg-white hover:border-[#D11F2E] cursor-pointer transition-colors">
                                                <input type="checkbox"
                                                       name="bonus_kelas_ids[]"
                                                       value="{{ $k->id }}"
                                                       {{ (int) $k->id === (int) $trx->kelas_id ? 'checked' : '' }}
                                                       class="mt-0.5 rounded border-[#CBD5E1] text-[#D11F2E] focus:ring-[#D11F2E]">
                                                <span class="text-[11px] font-semibold text-[#0F172A] leading-tight">
                                                    {{ $k->judul_kelas }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <button type="submit"
                                    class="w-full py-3 px-4 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all cursor-pointer">
                                Setujui Pembayaran &amp; Aktifkan Akses
                            </button>
                        </form>

                        <form id="reject-form-{{ $trx->id }}" method="POST" action="{{ route('admin.transaksi.reject', $trx->id) }}">
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
    @endforeach
@endsection
