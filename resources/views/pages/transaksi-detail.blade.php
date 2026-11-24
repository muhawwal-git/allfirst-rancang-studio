@extends('layouts.app')

@section('title', 'Instruksi Pembayaran — Invoice ' . $transaksi->nomor_invoice)

@section('content')
    <!-- PEMBAYARAN BODY (fill: #F8FAFC) -->
    <div class="bg-[#F8FAFC] py-12 sm:py-16 min-h-screen border-b border-[#E2E8F0]">
        <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20 space-y-8">
            <!-- Header Row -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#E2E8F0] pb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#0F172A]">
                        Instruksi Pembayaran Manual
                    </h1>
                    <p class="text-xs text-[#64748B] mt-1 font-mono">
                        Program Kelas: <strong class="text-[#0F172A]">{{ $transaksi->kelas->judul }}</strong>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-mono font-bold text-[#0F172A]">
                        No. Invoice: {{ $transaksi->nomor_invoice }}
                    </span>

                    @if($transaksi->status === 'menunggu_pembayaran')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#B45309] bg-[#FFFBEB] border border-[#FDE68A]">
                            Status: Menunggu Pembayaran
                        </span>
                    @elseif($transaksi->status === 'menunggu_verifikasi')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200">
                            Status: Menunggu Verifikasi Admin
                        </span>
                    @elseif($transaksi->status === 'berhasil')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-[#047857] bg-[#ECFDF5] border border-[#A7F3D0]">
                            Status: Pembayaran Berhasil / Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-red-700 bg-red-50 border border-red-200">
                            Status: Ditolak / Dibatalkan
                        </span>
                    @endif
                </div>
            </div>

            @if($transaksi->status === 'berhasil')
                <div class="bg-[#ECFDF5] border border-[#A7F3D0] rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shrink-0">
                            ✓
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[#047857]">Pembayaran Berhasil Diverifikasi!</h3>
                            <p class="text-xs text-[#047857]">Hak akses video kelas 365 hari telah aktif di akun Anda.</p>
                        </div>
                    </div>
                    <a href="{{ route('student.classroom', $transaksi->kelas->slug) }}"
                       class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] shadow-sm whitespace-nowrap">
                        Mulai Belajar Sekarang &rarr;
                    </a>
                </div>
            @elseif($transaksi->status === 'ditolak')
                <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-2xl p-5 sm:p-6 flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-[#D11F2E] text-white flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm sm:text-base font-bold font-heading text-[#991B1B]">
                            Bukti Pembayaran Ditolak &mdash; Silakan Unggah Ulang Bukti Transfer
                        </h3>
                        <p class="text-xs sm:text-sm text-[#B91C1C] leading-relaxed">
                            <strong>Alasan Penolakan Admin:</strong> {{ $transaksi->alasan_penolakan ?: 'Bukti transfer belum sesuai atau tidak terbaca dengan jelas.' }}
                        </p>
                        <p class="text-[11px] text-[#7F1D1D] pt-0.5">
                            Anda dapat langsung mengunggah ulang foto bukti transfer yang jelas (JPG/PNG) melalui formulir di sebelah kanan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Payment Steps Row (2 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Langkah 1: Rekening Bank (lg:col-span-6) -->
                @php
                    $activeBankList = $bankAccounts ?? \App\Models\PengaturanSitus::getBankAccounts();
                @endphp
                <div class="lg:col-span-6 bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between border-b border-[#F1F5F9] pb-3">
                        <h2 class="text-lg font-bold font-heading text-[#0F172A]">
                            Pilihan Rekening Resmi Studio
                        </h2>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-mono font-semibold bg-[#F8FAFC] border border-[#E2E8F0] text-[#334155]">
                            {{ count($activeBankList) }} {{ count($activeBankList) > 1 ? 'Metode Tersedia' : 'Rekening Utama' }}
                        </span>
                    </div>

                    <!-- Bank Accounts -->
                    <div class="space-y-3">
                        @foreach($activeBankList as $bank)
                            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-4 space-y-2">
                                <span class="text-xs font-semibold text-[#64748B] uppercase tracking-wider block">
                                    {{ $bank['bank'] }}
                                </span>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-lg sm:text-xl font-mono font-extrabold text-[#0F172A] tracking-wider" id="rek-{{ $loop->index }}">
                                        {{ $bank['nomor_rekening'] }}
                                    </span>
                                    <button type="button"
                                            onclick="navigator.clipboard.writeText('{{ $bank['nomor_rekening'] }}'); alert('Nomor rekening disalin: {{ $bank['nomor_rekening'] }}');"
                                            class="px-3 py-1 text-xs font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] rounded-lg hover:bg-slate-100 transition-colors shadow-sm">
                                        Salin
                                    </button>
                                </div>
                                <span class="text-xs text-[#64748B] block">
                                    a.n. <strong class="text-[#0F172A]">{{ $bank['atas_nama'] }}</strong>
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Nominal Pas Box -->
                    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-xl p-5 space-y-1">
                        <span class="text-xs font-bold text-[#D11F2E] uppercase tracking-wider block">
                            Nominal Transfer Pas (Tepat):
                        </span>
                        <div class="text-3xl font-extrabold font-mono text-[#D11F2E]">
                            {{ $transaksi->formatted_total }}
                        </div>
                    </div>

                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Pastikan Anda mentransfer nominal tepat sesuai angka di atas guna mempermudah proses pencocokan mutasi dan verifikasi admin.
                    </p>
                </div>

                <!-- Langkah 2: Unggah Bukti (lg:col-span-6) -->
                <div class="lg:col-span-6 bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-8 space-y-6">
                    <h2 class="text-lg font-bold font-heading text-[#0F172A] border-b border-[#F1F5F9] pb-3">
                        2. Unggah Bukti Transfer
                    </h2>

                    @if($transaksi->bukti_pembayaran)
                        <div class="space-y-3">
                            <span class="text-xs font-medium text-[#64748B] block">Bukti transfer yang telah Anda kirimkan:</span>
                            <div class="p-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-center">
                                <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank" class="inline-block text-xs font-semibold text-[#D11F2E] hover:underline">
                                    Lihat Berkas Bukti Transfer Terunggah &nearr;
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($transaksi->status !== 'berhasil')
                        <form method="POST" action="{{ route('transaksi.upload', $transaksi->nomor_invoice) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div class="space-y-2">
                                <label for="bukti_transfer" class="block text-xs font-semibold text-[#0F172A]">
                                    Pilih Berkas Bukti Transfer (JPG atau PNG maks 2 MB)
                                </label>
                                <input type="file"
                                       id="bukti_transfer"
                                       name="bukti_transfer"
                                       required
                                       accept=".jpg,.jpeg,.png"
                                       class="w-full text-xs text-[#64748B] file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FFF1F2] file:text-[#D11F2E] hover:file:bg-[#FFE4E6] file:cursor-pointer border border-[#CBD5E1] rounded-xl p-2 bg-white">
                                @error('bukti_transfer')
                                    <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-md transition-all">
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif

                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Setelah bukti transfer terkirim, status pembayaran Anda akan otomatis diperbarui menjadi "Menunggu Verifikasi Admin".
                    </p>

                    <!-- Konfirmasi WA Box -->
                    <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-4 space-y-3">
                        <p class="text-xs text-[#334155] leading-relaxed">
                            Notifikasi otomatis dikirim ke Email Admin saat bukti diunggah. Ingin aktivasi lebih cepat? Konfirmasi langsung ke Admin WhatsApp kami.
                        </p>
                        <a href="{{ $waConfirmUrl ?? ('https://wa.me/' . \App\Models\PengaturanSitus::getAdminWhatsapp() . '?text=' . urlencode('Halo Admin Allfirst Studio, saya sudah melakukan pembayaran transfer untuk Invoice: ' . $transaksi->nomor_invoice . ' sebesar ' . $transaksi->formatted_total . '. Mohon bantu verifikasi.')) }}"
                           target="_blank"
                           rel="noopener"
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span>Konfirmasi Cepat via WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
