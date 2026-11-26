@extends('layouts.admin')

@section('title', 'Moderasi Rating & Testimoni Siswa')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                    Moderasi Rating &amp; Testimoni Siswa
                </h1>
                <p class="text-xs sm:text-sm text-[#64748B]">
                    Kelola ulasan siswa yang telah lulus Quiz Evaluasi Akhir dan pilih testimoni terbaik untuk ditampilkan di Halaman Beranda.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="px-4 py-2.5 rounded-xl bg-white border border-[#E2E8F0] text-xs font-mono">
                    <span class="text-[#64748B]">Rata-rata Rating:</span>
                    <strong class="text-[#F59E0B] ml-1">&#9733; {{ number_format($averageRating, 1) }} / 5.0</strong>
                </div>
                <div class="px-4 py-2.5 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-xs font-mono font-bold text-[#047857]">
                    {{ $featuredCount }} Tampil di Beranda
                </div>
            </div>
        </div>

        <!-- Testimonials Table -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-[#F8FAFC] text-[11px] font-mono text-[#64748B] uppercase border-b border-[#E2E8F0]">
                        <tr>
                            <th class="px-6 py-4">Siswa &amp; Profesi</th>
                            <th class="px-6 py-4">Program Kelas</th>
                            <th class="px-6 py-4">Rating</th>
                            <th class="px-6 py-4">Ulasan Testimoni</th>
                            <th class="px-6 py-4">Status Beranda</th>
                            <th class="px-6 py-4 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($testimonis as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-[#0F172A]">{{ $item->user?->name ?? 'Siswa' }}</div>
                                    <div class="text-xs text-[#64748B]">{{ $item->profesi ?: 'Alumni Studio' }}</div>
                                    <div class="text-[11px] font-mono text-[#94A3B8] mt-0.5">{{ $item->created_at?->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 align-top font-medium text-[#0F172A]">
                                    {{ $item->kelas?->judul ?? '-' }}
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <span class="text-[#F59E0B] tracking-wider text-sm">{{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', max(0, 5 - $item->rating)) }}</span>
                                    <span class="block text-[11px] font-mono text-[#64748B] mt-0.5">{{ $item->rating }} / 5 Bintang</span>
                                </td>
                                <td class="px-6 py-4 align-top max-w-md">
                                    <p class="text-xs sm:text-sm text-[#334155] leading-relaxed">
                                        &ldquo;{{ $item->ulasan }}&rdquo;
                                    </p>
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    @if($item->tampil_beranda)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                                            <span>&#10003; Tampil di Beranda</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold text-[#64748B] bg-[#F8FAFC] border border-[#E2E8F0]">
                                            <span>Disembunyikan</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.testimoni.toggle', $item->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all shadow-sm
                                                        {{ $item->tampil_beranda
                                                            ? 'bg-white text-[#334155] border border-[#CBD5E1] hover:bg-slate-50'
                                                            : 'bg-gradient-to-r from-[#D11F2E] to-[#9E111B] text-white border border-[#BA151C] hover:brightness-105' }}">
                                                {{ $item->tampil_beranda ? 'Sembunyikan dari Beranda' : 'Tampilkan di Beranda' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.testimoni.destroy', $item->id) }}" onsubmit="return confirm('Hapus testimoni siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-2 rounded-xl text-xs font-semibold text-[#D11F2E] bg-[#FEF2F2] hover:bg-[#FEE2E2] border border-[#FECACA] transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-xs text-[#64748B]">
                                    Belum ada ulasan &amp; testimoni yang dikirim oleh siswa setelah menyelesaikan Quiz Evaluasi Akhir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
