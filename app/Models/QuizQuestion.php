<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'kelas_id',
    'nomor_urutan',
    'pertanyaan',
    'opsi_a',
    'opsi_b',
    'opsi_c',
    'opsi_d',
    'jawaban_benar',
])]
class QuizQuestion extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nomor_urutan' => 'integer',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Ensure the given course has a populated 10-question assessment bank.
     *
     * @return Collection<int, self>
     */
    public static function ensureForKelas(Kelas $kelas): Collection
    {
        $existing = $kelas->quizQuestions()->orderBy('nomor_urutan')->orderBy('id')->get();
        if ($existing->isNotEmpty()) {
            return $existing;
        }

        $bank = static::defaultBankForCategory((string) $kelas->kategori);
        foreach ($bank as $index => $item) {
            static::query()->create([
                'kelas_id' => $kelas->id,
                'nomor_urutan' => $index + 1,
                'pertanyaan' => $item['pertanyaan'],
                'opsi_a' => $item['opsi_a'],
                'opsi_b' => $item['opsi_b'],
                'opsi_c' => $item['opsi_c'],
                'opsi_d' => $item['opsi_d'],
                'jawaban_benar' => $item['jawaban_benar'],
            ]);
        }

        return $kelas->quizQuestions()->orderBy('nomor_urutan')->orderBy('id')->get();
    }

    /**
     * Built-in 10-question technical assessment bank per software category.
     *
     * @return array<int, array{pertanyaan: string, opsi_a: string, opsi_b: string, opsi_c: string, opsi_d: string, jawaban_benar: string}>
     */
    public static function defaultBankForCategory(string $kategori): array
    {
        $normalized = strtolower(trim($kategori));

        if (str_contains($normalized, 'sketchup')) {
            return [
                [
                    'pertanyaan' => 'Mengapa setiap elemen lantai dan dinding di SketchUp wajib segera dijadikan Group (Make Group) setelah dibuat?',
                    'opsi_a' => 'Agar geometri antar objek tidak saling menempel (sticky geometry) saat dipindahkan atau diedit',
                    'opsi_b' => 'Agar tekstur material otomatis berubah menjadi format V-Ray',
                    'opsi_c' => 'Untuk mengunci file agar tidak bisa dibuka di versi SketchUp lain',
                    'opsi_d' => 'Supaya ukuran satuan gambar otomatis berubah dari meter ke milimeter',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Apa keunggulan utama penggunaan Component dibandingkan Group biasa saat memodelkan elemen berulang seperti jendela atau kolom struktur?',
                    'opsi_a' => 'Component tidak memiliki garis tepi (edges)',
                    'opsi_b' => 'Perubahan pada satu Component otomatis memperbarui seluruh salinan Component yang sama di dalam proyek',
                    'opsi_c' => 'Component tidak dapat diberi material atau warna',
                    'opsi_d' => 'Component hanya bisa digunakan pada tampilan 2D',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Perintah (shortcut) apakah yang digunakan untuk mengekstrusi bidang datar 2D menjadi objek bervolume 3D seperti dinding atau pelat lantai?',
                    'opsi_a' => 'Move (M)',
                    'opsi_b' => 'Rotate (Q)',
                    'opsi_c' => 'Push/Pull (P)',
                    'opsi_d' => 'Scale (S)',
                    'jawaban_benar' => 'C',
                ],
                [
                    'pertanyaan' => 'Alat apakah yang paling tepat digunakan untuk membuat garis bantu ukur (guideline) presisi sebelum melubangi bukaan pintu dan jendela?',
                    'opsi_a' => 'Tape Measure Tool (T)',
                    'opsi_b' => 'Freehand Tool',
                    'opsi_c' => 'Eraser Tool (E)',
                    'opsi_d' => 'Paint Bucket (B)',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Saat memindahkan objek di SketchUp, tombol panah manakah pada keyboard yang berfungsi mengunci pergerakan lurus sejajar sumbu vertikal Biru (Z-Axis)?',
                    'opsi_a' => 'Panah Kanan',
                    'opsi_b' => 'Panah Kiri',
                    'opsi_c' => 'Panah Atas',
                    'opsi_d' => 'Tombol Spasi',
                    'jawaban_benar' => 'C',
                ],
                [
                    'pertanyaan' => 'Fitur apakah yang digunakan untuk membentuk profil 3D menerus mengikuti jalur garis (path) seperti lisplang atap, profil plafon, atau pipa?',
                    'opsi_a' => 'Follow Me Tool',
                    'opsi_b' => 'Offset Tool',
                    'opsi_c' => 'Protractor Tool',
                    'opsi_d' => 'Axes Tool',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Panel manakah yang digunakan untuk mengatur visibilitas kelompok kategori objek (seperti menyembunyikan atap dan furnitur sementara) agar viewport tetap ringan?',
                    'opsi_a' => 'Panel Instructor',
                    'opsi_b' => 'Panel Tags / Outliner',
                    'opsi_c' => 'Panel Fog',
                    'opsi_d' => 'Panel Ruby Console',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Alat apakah yang digunakan untuk memotong model bangunan secara aktif guna melihat tata ruang interior atau membuat gambar potongan arsitektur?',
                    'opsi_a' => 'Section Plane',
                    'opsi_b' => 'Orbit Tool',
                    'opsi_c' => 'Pan Tool',
                    'opsi_d' => 'Zoom Extents',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Langkah optimasi apakah pada menu Model Info > Statistics yang wajib dijalankan untuk menghapus material dan komponen sampah agar ukuran file .skp tidak membengkak?',
                    'opsi_a' => 'Reset Axes',
                    'opsi_b' => 'Purge Unused',
                    'opsi_c' => 'Invert Selection',
                    'opsi_d' => 'Explode All',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Mode kamera apakah yang wajib dipilih saat menyiapkan Scene Tampak Depan atau Potongan Ortogonal agar tidak memiliki titik hilang perspektif?',
                    'opsi_a' => 'Two-Point Perspective',
                    'opsi_b' => 'Parallel Projection',
                    'opsi_c' => 'Field of View 90 Derajat',
                    'opsi_d' => 'Walkthrough Mode',
                    'jawaban_benar' => 'B',
                ],
            ];
        }

        if (str_contains($normalized, 'revit')) {
            return [
                [
                    'pertanyaan' => 'Apa prinsip utama keunggulan Building Information Modeling (BIM) di Autodesk Revit dibandingkan penggambaran CAD 2D konvensional?',
                    'opsi_a' => 'Seluruh elemen 3D memiliki parameter data yang terhubung otomatis antara denah, tampak, potongan, dan tabel volume (Schedule)',
                    'opsi_b' => 'Revit hanya bisa digunakan untuk menggambar garis 2D tanpa ukuran',
                    'opsi_c' => 'Setiap perubahan dinding di denah harus digambar ulang manual di tampak dan potongan',
                    'opsi_d' => 'File Revit tidak menyimpan informasi elevasi lantai',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Elemen datum referensi manakah yang wajib disiapkan paling awal untuk menentukan ketinggian elevasi antar lantai bangunan di Revit?',
                    'opsi_a' => 'Detail Line',
                    'opsi_b' => 'Levels',
                    'opsi_c' => 'Text Note',
                    'opsi_d' => 'Revision Cloud',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Manakah dari elemen berikut yang termasuk ke dalam kategori System Family di Revit (didefinisikan langsung di dalam proyek tanpa memuat file .RFA eksternal)?',
                    'opsi_a' => 'Kursi dan Meja Kerja',
                    'opsi_b' => 'Walls, Floors, dan Roofs',
                    'opsi_c' => 'Kendaraan dan Tanaman',
                    'opsi_d' => 'Lampu Gantung Dekoratif',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Saat memodelkan dinding di atas garis Grid as struktur, pengaturan parameter apakah yang memastikan posisi tengah dinding tepat berada di garis as?',
                    'opsi_a' => 'Location Line: Wall Centerline / Core Centerline',
                    'opsi_b' => 'Unconnected Height',
                    'opsi_c' => 'Cross-Section: Slanted',
                    'opsi_d' => 'Phase Created',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Fitur apakah yang digunakan agar bagian puncak dinding lantai atas otomatis mengikuti kemiringan bawah atap (Roof) tanpa celah?',
                    'opsi_a' => 'Split Element (SL)',
                    'opsi_b' => 'Attach Top/Base',
                    'opsi_c' => 'Array (AR)',
                    'opsi_d' => 'Pin (PN)',
                    'jawaban_benar' => 'B',
                ],
                [
                    'pertanyaan' => 'Pengaturan apakah pada Floor Plan Properties yang mengendalikan ketinggian bidang potong horizontal (misal 1200 mm dari lantai) untuk menampilkan bukaan jendela di denah?',
                    'opsi_a' => 'View Range (Cut Plane)',
                    'opsi_b' => 'Sun Settings',
                    'opsi_c' => 'Project Units (UN)',
                    'opsi_d' => 'Render Appearance',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Fitur manakah di Revit yang digunakan untuk mengekstrak tabel daftar pintu/jendela serta perhitungan volume material secara otomatis dari model BIM?',
                    'opsi_a' => 'Schedules / Quantities',
                    'opsi_b' => 'Decal Placement',
                    'opsi_c' => 'Walkthrough Camera',
                    'opsi_d' => 'Match Type Properties',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Shortcut apakah yang digunakan untuk membuka kotak dialog Visibility/Graphics Overrides guna mengatur ketebalan garis dan visibilitas kategori objek pada sebuah View?',
                    'opsi_a' => 'VG atau VV',
                    'opsi_b' => 'WA',
                    'opsi_c' => 'DR',
                    'opsi_d' => 'WN',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Bagaimana cara menempatkan gambar Denah, Tampak, dan Potongan yang sudah jadi ke dalam lembar kop gambar kerja (Title Block) siap cetak di Revit?',
                    'opsi_a' => 'Menarik (drag-and-drop) View dari Project Browser langsung ke dalam lembar Sheet',
                    'opsi_b' => 'Menggambar ulang denah menggunakan garis Detail Line di dalam Sheet',
                    'opsi_c' => 'Mengekspor ke Paint terlebih dahulu',
                    'opsi_d' => 'Mengubah semua dinding menjadi teks',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Dalam hirarki elemen Revit, jika "Single Flush Door" adalah Family, maka ukuran spesifik seperti "900 x 2100 mm" disebut sebagai apa?',
                    'opsi_a' => 'Discipline',
                    'opsi_b' => 'Type',
                    'opsi_c' => 'Workset',
                    'opsi_d' => 'View Template',
                    'jawaban_benar' => 'B',
                ],
            ];
        }

        if (str_contains($normalized, 'enscape')) {
            return [
                [
                    'pertanyaan' => 'Apa keunggulan utama teknologi Real-Time Rendering Enscape dalam alur kerja studio arsitektur?',
                    'opsi_a' => 'Tersinkronisasi langsung (Live Sync) dengan SketchUp/Revit sehingga perubahan model dan material terlihat seketika',
                    'opsi_b' => 'Hanya bisa merender gambar hitam-putih 2D',
                    'opsi_c' => 'Mengharuskan model diekspor manual setiap kali menggeser kursi',
                    'opsi_d' => 'Tidak mendukung pencahayaan matahari',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Fitur kamera apakah di Enscape Visual Settings yang wajib diaktifkan agar garis-garis kolom dan dinding vertikal bangunan tampak tegak lurus (tidak miring/distorsi)?',
                    'opsi_a' => 'Two-Point Perspective',
                    'opsi_b' => 'Motion Blur Maksimal',
                    'opsi_c' => 'Vignette 100%',
                    'opsi_d' => 'Chromatic Aberration',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Dalam Enscape Material Editor, peta tekstur (map) apakah yang berfungsi memberikan efek tinggi-rendah atau pori-pori kasar pada permukaan batu alam dan kayu?',
                    'opsi_a' => 'Height / Bump / Normal Map',
                    'opsi_b' => 'Tint Color saja',
                    'opsi_c' => 'Self-Illumination',
                    'opsi_d' => 'Watermark Overlay',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Parameter material manakah yang diatur untuk mengendalikan tingkat kekasaran atau ketajaman pantulan cermin pada lantai marmer dan kaca di Enscape?',
                    'opsi_a' => 'Roughness',
                    'opsi_b' => 'Foliage Wind',
                    'opsi_c' => 'Sound Source',
                    'opsi_d' => 'Section Cut',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Format berkas langit 360 derajat apakah yang digunakan pada tab Sky > Horizon Source untuk menghasilkan pencahayaan alami dan pantulan lingkungan yang fotorealistik?',
                    'opsi_a' => 'HDRI (.hdr / .exr)',
                    'opsi_b' => 'Dokumen Word (.docx)',
                    'opsi_c' => 'Tabel Excel (.xlsx)',
                    'opsi_d' => 'Font (.ttf)',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Tipe material khusus apakah di Enscape yang harus dipilih agar bidang datar berwarna hijau otomatis dirender menjadi helai hamparan rumput 3D realistis?',
                    'opsi_a' => 'Grass',
                    'opsi_b' => 'Clearcoat',
                    'opsi_c' => 'Carpet',
                    'opsi_d' => 'Water',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Jenis sumber cahaya apakah di Enscape yang menggunakan profil distribusi cahaya riil dari pabrikan lampu untuk menghasilkan sorot lampu downlight dinding yang dramatis?',
                    'opsi_a' => 'IES Light',
                    'opsi_b' => 'Wireframe Mode',
                    'opsi_c' => 'Polystyrene Mode',
                    'opsi_d' => 'Orthographic Sun',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Berapakah rentang sudut Field of View (FoV) kamera yang ideal untuk rendering ruang interior agar ruangan terlihat proporsional namun tidak mengalami distorsi berlebih di tepi gambar?',
                    'opsi_a' => 'Sekitar 55° hingga 75°',
                    'opsi_b' => '175° (Ultra Fisheye)',
                    'opsi_c' => '5° (Telephoto ekstrem)',
                    'opsi_d' => '0°',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Mengapa penggunaan objek pohon, manusia, dan kendaraan dari Enscape Asset Library sangat disarankan dibandingkan mengimpor model 3D berat dari luar?',
                    'opsi_a' => 'Aset tampil ringan (low-poly placeholder) di viewport kerja namun otomatis dirender detail tinggi di jendela Enscape',
                    'opsi_b' => 'Aset Enscape tidak memiliki warna sama sekali',
                    'opsi_c' => 'Aset Enscape otomatis menghapus dinding bangunan',
                    'opsi_d' => 'Aset Enscape hanya bisa dilihat di mode 2D',
                    'jawaban_benar' => 'A',
                ],
                [
                    'pertanyaan' => 'Dalam Enscape Video Editor, elemen apakah yang ditambahkan sepanjang lintasan kamera untuk mengatur perpindahan posisi sudut pandang dan perubahan jam matahari secara mulus?',
                    'opsi_a' => 'Keyframes',
                    'opsi_b' => 'Hatch Pattern',
                    'opsi_c' => 'Plot Style CTB',
                    'opsi_d' => 'Layer Freeze',
                    'jawaban_benar' => 'A',
                ],
            ];
        }

        // Default: AutoCAD 2D / 3D / DED Engineering Standard (10 Questions)
        return [
            [
                'pertanyaan' => 'Perintah (shortcut) apakah di AutoCAD yang wajib dijalankan pertama kali untuk memastikan satuan gambar kerja menggunakan Milimeter (mm) atau Sentimeter (cm)?',
                'opsi_a' => 'UNITS (UN)',
                'opsi_b' => 'EXPLODE (X)',
                'opsi_c' => 'PURGE (PU)',
                'opsi_d' => 'HATCH (H)',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Apa perbedaan mendasar antara garis yang dibuat menggunakan perintah POLYLINE (PL) dibandingkan perintah LINE (L) biasa?',
                'opsi_a' => 'Segmen garis pada Polyline saling menyatu sebagai satu kesatuan objek utuh sehingga mudah di-offset dan dihitung luasnya',
                'opsi_b' => 'Polyline tidak dapat diberi warna atau ketebalan garis',
                'opsi_c' => 'Line biasa otomatis membentuk objek padat 3D',
                'opsi_d' => 'Polyline hanya dapat menggambar garis vertikal saja',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Tombol fungsi manakah yang digunakan untuk mengaktifkan/menonaktifkan Object Snap (OSNAP) agar kursor dapat menangkap titik Endpoint, Midpoint, dan Intersection secara presisi?',
                'opsi_a' => 'F3',
                'opsi_b' => 'F1',
                'opsi_c' => 'Alt + F4',
                'opsi_d' => 'Caps Lock',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Perintah modifikasi apakah yang paling efisien digunakan untuk menggandakan garis as menjadi dua garis tepi dinding bata dengan jarak sejajar 15 cm?',
                'opsi_a' => 'OFFSET (O)',
                'opsi_b' => 'ROTATE (RO)',
                'opsi_c' => 'SCALE (SC)',
                'opsi_d' => 'ALIGN (AL)',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Kombinasi dua perintah apakah yang digunakan untuk memotong sisa persimpangan garis dinding dan memperpanjang garis yang belum menyentuh garis batas?',
                'opsi_a' => 'TRIM (TR) dan EXTEND (EX)',
                'opsi_b' => 'ARRAY (AR) dan BLOCK (B)',
                'opsi_c' => 'CIRCLE (C) dan ARC (A)',
                'opsi_d' => 'PAN (P) dan ZOOM (Z)',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Mengapa standar gambar kerja konsultan DED mewajibkan pemisahan elemen gambar (As, Kolom, Dinding, Arsir, Kusen, Dimensi) ke dalam Layer (LA) yang berbeda?',
                'opsi_a' => 'Untuk mengontrol visibilitas, warna pena cetak, dan hirarki ketebalan garis (lineweight) secara terstruktur',
                'opsi_b' => 'Karena AutoCAD melarang lebih dari 10 garis berada di layer yang sama',
                'opsi_c' => 'Agar file gambar tidak bisa disimpan ke dalam harddisk',
                'opsi_d' => 'Supaya ukuran teks otomatis menghilang saat dicetak',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Perintah apakah yang digunakan untuk mengisi bidang potongan dinding bata dengan pola arsiran garis miring standar gambar teknik (ANSI32)?',
                'opsi_a' => 'HATCH (H)',
                'opsi_b' => 'CHAMFER (CHA)',
                'opsi_c' => 'FILLET (F)',
                'opsi_d' => 'STRETCH (S)',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Bagaimana cara terbaik menyimpan simbol arsitektur berulang seperti pintu, jendela, dan sanitair agar ukuran file .dwg tetap ringan dan mudah diedit serentak?',
                'opsi_a' => 'Mengubahnya menjadi komponen BLOCK (B)',
                'opsi_b' => 'Memecah seluruh garis dengan EXPLODE berkali-kali',
                'opsi_c' => 'Menebalkan semua garis menjadi 2.00 mm',
                'opsi_d' => 'Menyalinnya sebagai gambar bitmap',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Pada ruang Layout (Paper Space), objek jendela apakah yang digunakan untuk menampilkan gambar dari Model Space dengan skala cetak presisi seperti 1:100 dan 1:20?',
                'opsi_a' => 'Layout Viewport (MV / MVIEW)',
                'opsi_b' => 'Command Line Bar',
                'opsi_c' => 'Grid Snap',
                'opsi_d' => 'UCS Icon',
                'jawaban_benar' => 'A',
            ],
            [
                'pertanyaan' => 'Berkas konfigurasi apakah pada kotak dialog PLOT yang mengatur konversi warna layer di layar menjadi ketebalan garis hitam-putih siap cetak sesuai standar DED?',
                'opsi_a' => 'Plot Style Table (.CTB)',
                'opsi_b' => 'Backup File (.BAK)',
                'opsi_c' => 'Font File (.SHX)',
                'opsi_d' => 'Template Website (.HTML)',
                'jawaban_benar' => 'A',
            ],
        ];
    }
}
