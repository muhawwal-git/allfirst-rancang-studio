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
    public function index(): View
    {
        $courses = Kelas::query()
            ->withCount(['moduls', 'materis', 'aksesKelas'])
            ->orderBy('id')
            ->paginate(10);

        return view('admin.kelas.index', [
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        return view('admin.kelas.create', [
            'categories' => config('allfirst.kategori_software', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_kelas' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
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

        Kelas::create([
            'judul_kelas' => $validated['judul_kelas'],
            'slug' => $slug,
            'kategori' => $validated['kategori'],
            'harga' => (int) $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
            'manfaat' => [
                'Pembelajaran Mandiri Berbasis Video HD',
                'Masa Aktif Akses Penuh 1 Tahun (365 Hari)',
                'Sertifikat Kelulusan Digital Resmi Ber-QR Code',
            ],
            'gambar_sampul' => $imagePath,
            'status_publikasi' => $validated['status_publikasi'],
            'is_unggulan' => $request->has('is_unggulan'),
        ]);

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
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
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

        $kelas->update([
            'judul_kelas' => $validated['judul_kelas'],
            'kategori' => $validated['kategori'],
            'harga' => (int) $validated['harga'],
            'deskripsi' => $validated['deskripsi'],
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
