<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminKelasController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = (string) $request->query('jenis', 'semua');

        $courses = Kelas::query()
            ->withCount(['moduls', 'materis', 'aksesKelas'])
            ->when(in_array($jenis, ['online', 'offline'], true), fn ($q) => $q->where('jenis_kelas', $jenis))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $totalCount = Kelas::query()->count();
        $onlineCount = Kelas::query()->where('jenis_kelas', 'online')->count();
        $offlineCount = Kelas::query()->where('jenis_kelas', 'offline')->count();

        return view('admin.kelas.index', [
            'courses' => $courses,
            'currentJenis' => $jenis,
            'totalCount' => $totalCount,
            'onlineCount' => $onlineCount,
            'offlineCount' => $offlineCount,
        ]);
    }

    public function create(Request $request): View
    {
        $defaultJenis = $request->query('jenis') === 'offline' ? 'offline' : 'online';

        return view('admin.kelas.create', [
            'categories' => config('allfirst.kategori_software', []),
            'defaultJenis' => $defaultJenis,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_kelas' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:50'],
            'jenis_kelas' => ['nullable', 'string', 'in:online,offline'],
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'modul_offline_text' => ['nullable', 'string'],
            'gambar_sampul' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status_publikasi' => ['required', 'in:published,draft'],
            'is_unggulan' => ['nullable'],
        ]);

        $slugBase = Str::slug($validated['judul_kelas']);
        $slug = $slugBase;
        $counter = 1;
        while (Kelas::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('gambar_sampul')) {
            $imagePath = $request->file('gambar_sampul')->store('sampul_kelas', 'public');
        }

        $jenisKelas = $validated['jenis_kelas'] ?? 'online';
        $modulOffline = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['modul_offline_text'] ?? '')))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();

        $manfaat = $jenisKelas === 'offline'
            ? [
                'Bimbingan Tatap Muka Intensif di Studio Makassar',
                'Evaluasi Gambar Kerja & Praktik Proyek Langsung',
                'Bonus Akses Kelas Online Terpilih & Sertifikat Resmi',
            ]
            : [
                'Pembelajaran Mandiri Berbasis Video HD',
                'Masa Aktif Akses Penuh 1 Tahun (365 Hari)',
                'Sertifikat Kelulusan Digital Resmi Ber-QR Code',
            ];

        $kelas = Kelas::create([
            'judul_kelas' => $validated['judul_kelas'],
            'slug' => $slug,
            'kategori' => $validated['kategori'],
            'jenis_kelas' => $jenisKelas,
            'harga' => (int) $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'manfaat' => $manfaat,
            'modul_offline' => ! empty($modulOffline) ? $modulOffline : null,
            'gambar_sampul' => $imagePath,
            'status_publikasi' => $validated['status_publikasi'],
            'is_unggulan' => $request->has('is_unggulan'),
        ]);

        if ($jenisKelas === 'offline' && ! empty($modulOffline)) {
            foreach ($modulOffline as $idx => $judulModul) {
                $kelas->moduls()->create([
                    'judul_modul' => $judulModul,
                    'nomor_urutan' => $idx + 1,
                ]);
            }
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas): View
    {
        return view('admin.kelas.edit', [
            'kelas' => $kelas,
            'categories' => config('allfirst.kategori_software', []),
        ]);
    }

    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'judul_kelas' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:50'],
            'jenis_kelas' => ['nullable', 'string', 'in:online,offline'],
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'modul_offline_text' => ['nullable', 'string'],
            'gambar_sampul' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status_publikasi' => ['required', 'in:published,draft'],
            'is_unggulan' => ['nullable'],
        ]);

        $imagePath = $kelas->gambar_sampul;
        if ($request->hasFile('gambar_sampul')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('gambar_sampul')->store('sampul_kelas', 'public');
        }

        $jenisKelas = $validated['jenis_kelas'] ?? ($kelas->jenis_kelas ?: 'online');
        $modulOffline = $request->has('modul_offline_text')
            ? collect(preg_split('/\r\n|\r|\n/', (string) ($validated['modul_offline_text'] ?? '')))
                ->map(fn (string $line) => trim($line))
                ->filter()
                ->values()
                ->all()
            : $kelas->modul_offline;

        $kelas->update([
            'judul_kelas' => $validated['judul_kelas'],
            'kategori' => $validated['kategori'],
            'jenis_kelas' => $jenisKelas,
            'harga' => (int) $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'modul_offline' => ! empty($modulOffline) ? $modulOffline : null,
            'gambar_sampul' => $imagePath,
            'status_publikasi' => $validated['status_publikasi'],
            'is_unggulan' => $request->has('is_unggulan'),
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Informasi kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        if ($kelas->gambar_sampul && Storage::disk('public')->exists($kelas->gambar_sampul)) {
            Storage::disk('public')->delete($kelas->gambar_sampul);
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
