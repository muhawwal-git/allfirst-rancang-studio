@extends('layouts.admin')

@section('title', 'Formulir Data Kelas — Allfirst Rancang Studio')

@section('content')
    <div class="space-y-6 w-full">
        <!-- Title (A-03b: A88Ol) -->
        <h1 class="text-2xl sm:text-[28px] font-extrabold font-heading text-[#0F172A] tracking-tight">
            Formulir Data Kelas
        </h1>

        <!-- Form Card (fill: #FFFFFF, stroke: #E2E8F0) -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.kelas.update', $kelas->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-xs">
                @csrf
                @method('PUT')
                @if($kelas->is_unggulan)
                    <input type="hidden" name="is_unggulan" value="1">
                @endif

                <!-- Kolom Input Kiri (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- 0. Jenis Katalog Kelas (Online vs Offline Studio) -->
                    @php
                        $selectedJenis = old('jenis_kelas', $kelas->jenis_kelas ?? 'online');
                    @endphp
                    <div class="space-y-1.5">
                        <label for="jenis_kelas" class="block font-bold text-[#0F172A]">
                            Jenis Katalog Kelas
                        </label>
                        <select id="jenis_kelas"
                                name="jenis_kelas"
                                class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] font-semibold text-[#0F172A] text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            <option value="online" {{ $selectedJenis === 'online' ? 'selected' : '' }}>Katalog Kelas Online (Pembelajaran Video LMS)</option>
                            <option value="offline" {{ $selectedJenis === 'offline' ? 'selected' : '' }}>Katalog Kelas Offline (Pelatihan Tatap Muka Studio)</option>
                        </select>
                    </div>

                    <!-- 1. Judul Kelas -->
                    <div class="space-y-1.5">
                        <label for="judul_kelas" class="block font-bold text-[#0F172A]">
                            Judul Kelas
                        </label>
                        <input type="text"
                               id="judul_kelas"
                               name="judul_kelas"
                               value="{{ old('judul_kelas', $kelas->judul_kelas) }}"
                               required
                               placeholder="Masukkan judul kelas lengkap dan menarik"
                               class="w-full px-4 py-3 rounded-xl bg-white border text-[#0F172A] placeholder-[#64748B] text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E] {{ $errors->has('judul_kelas') ? 'border-red-500' : 'border-[#CBD5E1]' }}">
                        @error('judul_kelas')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 2. Kategori Software -->
                    <div class="space-y-1.5">
                        <label for="kategori" class="block font-bold text-[#0F172A]">
                            Kategori Software
                        </label>
                        <select id="kategori"
                                name="kategori"
                                required
                                class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('kategori', $kelas->kategori) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. Harga Normal (Rp) -->
                    <div class="space-y-1.5">
                        <label for="harga" class="block font-bold text-[#0F172A]">
                            Harga Normal (Rp)
                        </label>
                        <input type="number"
                               id="harga"
                               name="harga"
                               value="{{ old('harga', $kelas->harga) }}"
                               required
                               min="0"
                               placeholder="Masukkan angka nominal tagihan (contoh: 350000)"
                               class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        @error('harga')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Deskripsi & Manfaat Kelas -->
                    <div class="space-y-1.5">
                        <label for="deskripsi" class="block font-bold text-[#0F172A]">
                            Deskripsi &amp; Manfaat Kelas
                        </label>
                        <textarea id="deskripsi"
                                  name="deskripsi"
                                  rows="5"
                                  required
                                  placeholder="Tuliskan gambaran kurikulum, target kompetensi siswa, dan manfaat yang didapatkan."
                                  class="w-full p-4 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] text-xs sm:text-[13px] leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 5. Tautan Software & Bahan Praktik (Google Drive — Opsional) -->
                    <div class="space-y-1.5">
                        <label for="tautan_software" class="block font-bold text-[#0F172A]">
                            Tautan Software &amp; Bahan Praktik (Google Drive — Opsional)
                        </label>
                        <input type="url"
                               id="tautan_software"
                               name="tautan_software"
                               value="{{ old('tautan_software', $kelas->tautan_software) }}"
                               placeholder="Contoh: https://drive.google.com/drive/folders/..."
                               class="w-full px-4 py-3 rounded-xl bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#64748B] font-mono text-xs focus:outline-none focus:ring-2 focus:ring-[#D11F2E]/20 focus:border-[#D11F2E]">
                        <p class="text-[11px] text-[#64748B]">
                            Jika diisi, tombol <strong>Dapatkan Software &amp; Bahan Praktik</strong> akan tampil di dalam Area Belajar (Classroom) khusus bagi siswa yang sudah memiliki akses kelas ini.
                        </p>
                        @error('tautan_software')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 6. Form Buttons -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-6 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all">
                            Simpan Perubahan Kelas
                        </button>
                        <a href="{{ route('admin.kelas.index') }}"
                           class="px-5 py-3 rounded-xl text-xs font-semibold bg-white hover:bg-slate-50 text-[#0F172A] border border-[#E2E8F0] transition-colors">
                            Batal &amp; Kembali
                        </a>
                    </div>
                </div>

                <!-- Kolom Input Kanan (5 Cols) -->
                <div class="lg:col-span-5 space-y-5">
                    <!-- Thumbnail Kelas (Rasio Wajib 16:9) -->
                    <div class="space-y-2">
                        <span class="block font-bold text-[#0F172A]">
                            Thumbnail Kelas (Rasio Wajib 16:9)
                        </span>
                        <label for="gambar_sampul"
                               class="rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] p-6 min-h-[195px] flex flex-col items-center justify-center text-center cursor-pointer hover:border-[#D11F2E]/50 transition-colors">
                            <svg class="w-7 h-7 text-[#D11F2E] mb-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <span class="px-4 py-2 rounded-lg bg-white border border-[#E2E8F0] text-xs font-semibold text-[#0F172A] shadow-sm">
                                Pilih Berkas Gambar Thumbnail
                            </span>
                            <span id="thumbnail-filename" class="text-[11px] font-mono text-[#64748B] uppercase mt-2.5 block">
                                16:9 RATIO &middot; JPG/PNG
                            </span>
                            <input type="file"
                                   id="gambar_sampul"
                                   name="gambar_sampul"
                                   accept="image/*"
                                   class="sr-only"
                                   onchange="if(this.files[0]) document.getElementById('thumbnail-filename').textContent = this.files[0].name">
                        </label>
                        @error('gambar_sampul')
                            <p class="text-[#D11F2E] mt-1">&times; {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Publikasi -->
                    <div class="space-y-2.5">
                        <span class="block font-bold text-[#0F172A]">
                            Status Publikasi
                        </span>
                        @php
                            $selectedStatus = old('status_publikasi', $kelas->status_publikasi);
                        @endphp
                        <div class="space-y-2.5" id="status-publikasi-options">
                            <!-- Option 1: Terbit -->
                            <label class="flex items-center gap-3 px-4 py-3.5 rounded-xl border cursor-pointer transition-all
                                {{ $selectedStatus === 'published' ? 'bg-[#FEF2F2] border-[#FECACA] text-[#D11F2E] font-bold' : 'bg-[#F8FAFC] border-[#E2E8F0] text-[#334155] font-medium' }}">
                                <input type="radio"
                                       name="status_publikasi"
                                       value="published"
                                       {{ $selectedStatus === 'published' ? 'checked' : '' }}
                                       class="sr-only"
                                       onchange="updateStatusCards(this.value)">
                                <svg class="w-4 h-4 shrink-0 {{ $selectedStatus === 'published' ? 'text-[#D11F2E]' : 'text-[#64748B]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Terbit (Tampil di Katalog Publik)</span>
                            </label>

                            <!-- Option 2: Draf -->
                            <label class="flex items-center gap-3 px-4 py-3.5 rounded-xl border cursor-pointer transition-all
                                {{ $selectedStatus === 'draft' ? 'bg-[#FEF2F2] border-[#FECACA] text-[#D11F2E] font-bold' : 'bg-[#F8FAFC] border-[#E2E8F0] text-[#334155] font-medium' }}">
                                <input type="radio"
                                       name="status_publikasi"
                                       value="draft"
                                       {{ $selectedStatus === 'draft' ? 'checked' : '' }}
                                       class="sr-only"
                                       onchange="updateStatusCards(this.value)">
                                <svg class="w-4 h-4 shrink-0 {{ $selectedStatus === 'draft' ? 'text-[#D11F2E]' : 'text-[#64748B]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                </svg>
                                <span>Draf (Simpan Sebagai Konsep)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateStatusCards(val) {
            const labels = document.querySelectorAll('#status-publikasi-options label');
            labels.forEach((lbl) => {
                const input = lbl.querySelector('input[type="radio"]');
                if (input.value === val) {
                    lbl.className = 'flex items-center gap-3 px-4 py-3.5 rounded-xl border cursor-pointer transition-all bg-[#FEF2F2] border-[#FECACA] text-[#D11F2E] font-bold';
                } else {
                    lbl.className = 'flex items-center gap-3 px-4 py-3.5 rounded-xl border cursor-pointer transition-all bg-[#F8FAFC] border-[#E2E8F0] text-[#334155] font-medium';
                }
            });
        }
    </script>
@endsection
