@extends('layouts.app')

@section('title', 'Dashboard Siswa — Allfirst Rancang Studio')

@section('content')
    <!-- SEKSI KELAS SAYA (fill: #FFFFFF, stroke: #E2E8F0) -->
    <section class="bg-white py-12 sm:py-16 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">
            <!-- Header Kelas Saya -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div class="space-y-1.5">
                    <span class="block text-xs font-mono font-bold tracking-widest text-[#D11F2E] uppercase">
                        PORTAL PEMBELAJARAN SISWA &middot; Area Belajar Siswa
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                        Kelas Saya
                    </h1>
                </div>

                <div class="text-xs font-mono text-[#64748B]">
                    Halo, <span class="font-bold text-[#0F172A]">{{ $user->name }}</span> &middot; {{ $aksesList->where('isAktif', true)->count() }} Kelas Aktif
                </div>
            </div>

            <!-- Kelas Cards List -->
            <div class="space-y-4">
                @forelse($aksesList as $item)
                    @php
                        $kelas = $item['kelas'];
                        $isAktif = $item['isAktif'];
                        $sisaHari = $item['sisaHari'];
                        $progress = $item['progress'];
                        $akses = $item['akses'];
                        $cert = $sertifikats->firstWhere('kelas_id', $kelas->id);
                    @endphp
                    <div class="bg-white rounded-2xl border border-[#F1F5F9] shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <!-- Mini Thumb -->
                        <div class="shrink-0 w-24 h-16 rounded-xl bg-[#111827] flex items-center justify-center text-center p-2 shadow-inner">
                            <span class="text-xs font-mono font-bold text-[#E2E8F0] tracking-wider uppercase">
                                {{ $kelas->kategori }}
                            </span>
                        </div>

                        <!-- Course Progress Info -->
                        <div class="flex-grow space-y-3 w-full">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 class="text-base sm:text-lg font-bold font-heading text-[#0F172A]">
                                    {{ $kelas->judul }}
                                </h2>
                                @if($isAktif)
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                                        Aktif
                                    </span>
                                @elseif($akses->status_akses === 'nonaktif' && $akses->isOffline())
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#334155] bg-[#F1F5F9] border border-[#CBD5E1]">
                                        Tatap Muka Studio (Video Terkunci)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#DC2626] bg-[#FEF2F2] border border-[#FECACA]">
                                        Kedaluwarsa
                                    </span>
                                @endif

                                @if($progress >= 100 || $cert)
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold text-[#D11F2E] bg-[#FFF1F2] border border-[#FFE4E6]">
                                        {{ $progress >= 100 ? 'Tuntas 100%' : 'Sertifikat Terbit' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Progress Track -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-mono">
                                    <span class="text-[#0F172A] font-bold">{{ $progress }}% Selesai</span>
                                    @if($isAktif)
                                        <span class="text-[#64748B]">Sisa {{ $sisaHari }} hari dari 365 hari &middot; Berlaku hingga {{ $akses->berlaku_hingga ? $akses->berlaku_hingga->translatedFormat('d F Y') : '-' }}</span>
                                    @elseif($akses->status_akses === 'nonaktif' && $akses->isOffline())
                                        <span class="text-[#64748B]">Akses video online diatur oleh Admin Studio</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Masa aktif telah berakhir pada {{ $akses->berlaku_hingga ? $akses->berlaku_hingga->translatedFormat('d F Y') : '-' }}</span>
                                    @endif
                                </div>
                                <div class="w-full h-2 rounded-full bg-[#F1F5F9] overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#D11F2E] to-[#9E111B] rounded-full transition-all duration-500"
                                         style="width: {{ $progress }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="shrink-0 flex items-center gap-3 w-full md:w-auto">
                            @if($isAktif)
                                <a href="{{ route('student.classroom', $kelas->slug) }}"
                                   class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                                    <span>Masuk Ruang Belajar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>

                                @if($progress >= 100 || $cert)
                                    <a href="{{ route('student.sertifikat.show', $cert ? $cert->nomor_sertifikat : $kelas->slug) }}"
                                       class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        <span>Lihat Sertifikat</span>
                                    </a>
                                @endif
                            @elseif($akses->status_akses === 'nonaktif' && $akses->isOffline())
                                @if($cert)
                                    <a href="{{ route('student.sertifikat.show', $cert->nomor_sertifikat) }}"
                                       class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        <span>Lihat Sertifikat</span>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('checkout.create', $kelas->slug) }}"
                                   class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] shadow-sm">
                                    <span>Reaktivasi Akses (Diskon 50%)</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-12 text-center space-y-4">
                        <div class="w-12 h-12 rounded-full bg-[#FFF1F2] border border-[#FFE4E6] text-[#D11F2E] flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#0F172A]">Belum Ada Kelas yang Diikuti</h3>
                        <p class="text-xs text-[#64748B] max-w-sm mx-auto">
                            Anda belum memiliki hak akses kelas online. Pilih kelas di katalog untuk memulai belajar.
                        </p>
                        <a href="{{ route('kelas.index') }}"
                           class="inline-block px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B]">
                            Jelajahi Katalog Kelas
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SEKSI TRANSAKSI SAYA (fill: #F8FAFC) -->
    <section class="bg-[#F8FAFC] py-12 sm:py-16 border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-6">
            <h2 class="text-xl sm:text-2xl font-bold font-heading text-[#0F172A]">
                Riwayat Transaksi &amp; Pembayaran
            </h2>

            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#F8FAFC] text-xs font-mono text-[#64748B] uppercase border-b border-[#E2E8F0]">
                            <tr>
                                <th class="px-6 py-4">No. Invoice</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Kelas Online</th>
                                <th class="px-6 py-4">Total Bayar</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @forelse($transaksis as $trx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-[#0F172A]">
                                        {{ $trx->nomor_invoice }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-[#64748B]">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-[#0F172A]">
                                        <span>{{ $trx->kelas->judul }}</span>
                                        @if($trx->jenis_transaksi === 'offline')
                                            <span class="ml-1.5 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3]">
                                                Kelas Offline + Bonus Online
                                            </span>
                                        @elseif($trx->jenis_transaksi === 'reaktivasi')
                                            <span class="ml-1.5 inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Reaktivasi 50%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-[#0F172A]">
                                        {{ $trx->formatted_total }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($trx->status === 'berhasil')
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                                                Berhasil / Aktif
                                            </span>
                                        @elseif($trx->status === 'menunggu_verifikasi')
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($trx->status === 'menunggu_pembayaran')
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold text-[#B45309] bg-[#FFFBEB] border border-[#FDE68A]">
                                                Menunggu Pembayaran
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold text-red-700 bg-red-50 border border-red-200">
                                                Ditolak / Batal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('transaksi.show', $trx->nomor_invoice) }}"
                                           class="text-xs font-semibold text-[#D11F2E] hover:underline">
                                            Lihat Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-xs text-slate-400">
                                        Belum ada riwayat transaksi pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transaksis->hasPages())
                    <div class="p-4 border-t border-[#E2E8F0]">
                        {{ $transaksis->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
