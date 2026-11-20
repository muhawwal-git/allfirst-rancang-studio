@extends('layouts.admin')

@section('title', 'Manajemen Kelas Online — Allfirst Rancang Studio')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Header Row (A-03: iboRY) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
                    Manajemen Kelas Online &amp; Offline
                </h1>
                <p class="text-xs text-[#64748B] mt-1">
                    Kelola katalog kelas video online (LMS) maupun katalog program pelatihan tatap muka studio (Offline).
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.kelas.create', ['jenis' => 'offline']) }}"
                   class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0F172A] bg-white hover:bg-slate-50 border border-[#CBD5E1] shadow-2xs transition-all">
                    + Tambah Kelas Offline
                </a>
                <a href="{{ route('admin.kelas.create', ['jenis' => 'online']) }}"
                   class="inline-flex items-center px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                    + Tambah Kelas Baru
                </a>
            </div>
        </div>

        <!-- Filter Bar Card (Konsisten dengan halaman Admin lain) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] px-5 py-3.5 flex flex-wrap items-center justify-between gap-4 shadow-2xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-[#0F172A] mr-1">Filter Katalog:</span>
                @php
                    $activeJenis = $currentJenis ?? 'semua';
                    $tabs = [
                        'semua' => 'Semua Katalog (' . ($totalCount ?? $courses->total()) . ')',
                        'online' => 'Katalog Kelas Online (' . ($onlineCount ?? 0) . ')',
                        'offline' => 'Katalog Kelas Offline Studio (' . ($offlineCount ?? 0) . ')',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <a href="{{ route('admin.kelas.index', ['jenis' => $key]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs transition-all
                           {{ $activeJenis === $key
                               ? 'bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3] font-bold'
                               : 'bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0] hover:bg-[#E2E8F0]/70 font-medium' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Tabel Kelas (5 Columns: Judul Kelas, Kategori Software, Harga Normal, Status Publikasi, Tindakan) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#F1F5F9] text-[#334155] border-b border-[#E2E8F0] font-bold">
                        <tr>
                            <th class="px-6 py-4">Judul Kelas</th>
                            <th class="px-6 py-4">Kategori Software</th>
                            <th class="px-6 py-4">Harga Normal</th>
                            <th class="px-6 py-4">Status Publikasi</th>
                            <th class="px-6 py-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($courses as $course)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- 1. Judul Kelas -->
                                <td class="px-6 py-4 max-w-[280px] leading-relaxed">
                                    <div class="font-bold text-[#0F172A] text-xs sm:text-[13px]">{{ $course->judul_kelas }}</div>
                                    <div class="mt-1">
                                        @if($course->isOfflineCatalog())
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FFF1F2] text-[#D11F2E] border border-[#FECDD3]">
                                                KATALOG OFFLINE STUDIO
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EFF6FF] text-[#1D4ED8] border border-[#BFDBFE]">
                                                KATALOG ONLINE LMS
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- 2. Kategori Software -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0]">
                                        {{ $course->kategori }}
                                    </span>
                                </td>

                                <!-- 3. Harga Normal -->
                                <td class="px-6 py-4 font-mono font-bold text-[#0F172A] whitespace-nowrap">
                                    {{ $course->formattedHarga() }}
                                </td>

                                <!-- 4. Status Publikasi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($course->status_publikasi === 'published')
                                        <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]">
                                            Terbit (Aktif)
                                        </span>
                                    @else
                                        <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-semibold bg-[#F1F5F9] text-[#334155] border border-[#E2E8F0]">
                                            Draf (Disimpan)
                                        </span>
                                    @endif
                                </td>

                                <!-- 5. Tindakan (Edit Informasi, Kelola Kurikulum, Hapus Kelas) -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.kelas.edit', $course->id) }}"
                                           class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] transition-colors">
                                            Edit Informasi
                                        </a>

                                        <a href="{{ route('admin.kelas.kurikulum', $course->id) }}"
                                           class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#0F172A] hover:bg-[#1E293B] text-white border border-[#0F172A] transition-colors">
                                            Kelola Kurikulum
                                        </a>

                                        <form method="POST" action="{{ route('admin.kelas.destroy', $course->id) }}" onsubmit="return confirm('Hapus kelas ini beserta seluruh modul dan pelajaran video di dalamnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#D11F2E] border border-[#FECACA] transition-colors">
                                                Hapus Kelas
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-[#64748B]">
                                    Belum ada kelas di database. Klik tombol "+ Tambah Kelas Baru" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] flex justify-center">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
