<?php

namespace Database\Seeders;

use App\Models\AksesKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\PengaturanSitus;
use App\Models\ProgresMateri;
use App\Models\Sertifikat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PengaturanSitus::setValue(
            'running_text',
            'Batch Tatap Muka Studio Sedang Berjalan (Sisa 4 Hari) ◆ Pendaftaran Batch Berikutnya Dibuka via WhatsApp ◆ E-Book Gratis Tersedia'
        );
        PengaturanSitus::setValue('running_text_aktif', '1');
        PengaturanSitus::setValue('whatsapp_studio', '6285190367139');
        PengaturanSitus::setValue('rekening_bank', 'Bank Central Asia (BCA)');
        PengaturanSitus::setValue('rekening_nomor', '1234567890');
        PengaturanSitus::setValue('rekening_atas_nama', 'Allfirst Rancang Studio');

        $admin = User::query()->updateOrCreate(
            ['email' => 'allfirst.go@gmail.com'],
            [
                'name' => 'Asrul (Admin Allfirst Studio)',
                'nomor_whatsapp' => '6285190367139',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status_profesi' => 'Founder & Lead Instructor',
                'instansi_asal' => 'Allfirst Rancang Studio Makassar',
            ]
        );
        $admin->markEmailAsVerified();

        $budi = User::query()->updateOrCreate(
            ['email' => 'budi@student.id'],
            [
                'name' => 'Budi Santoso',
                'nomor_whatsapp' => '081298765432',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status_profesi' => 'Mahasiswa / Pelajar',
                'instansi_asal' => 'Universitas Hasanuddin',
            ]
        );
        $budi->markEmailAsVerified();

        $coursesData = [
            [
                'judul_kelas' => 'AutoCAD 2D: Dari Dasar sampai Gambar Kerja Mahir',
                'slug' => 'autocad-2d-gambar-kerja-mahir',
                'kategori' => 'AutoCAD',
                'harga' => 350000,
                'is_unggulan' => true,
                'deskripsi' => 'Kelas ini disusun secara komprehensif bagi Anda yang ingin menguasai AutoCAD dari nol hingga mampu memproduksi dokumen gambar kerja arsitektur yang presisi. Anda akan dipandu memahami antarmuka software, perintah drafting mendasar, teknik modifikasi objek cepat, hingga pembuatan denah, potongan, dan tata letak skala cetak standar industri enjiniring.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Pengenalan Interface & Pengaturan Dasar',
                        'materis' => [
                            ['judul_materi' => 'Mengenal Ruang Kerja, Command Line & Shortcut Penting', 'durasi_tampil' => '05:20', 'durasi_detik' => 320, 'cuplikan_gratis' => true],
                            ['judul_materi' => 'Pengaturan Satuan Ukuran (Units), Batas Gambar & Perintah Dasar', 'durasi_tampil' => '08:45', 'durasi_detik' => 525, 'cuplikan_gratis' => false],
                        ],
                    ],
                    [
                        'judul_modul' => 'Modul 2: Perintah Gambar & Modifikasi (Drawing Tools)',
                        'materis' => [
                            ['judul_materi' => 'Presisi Garis: Menguasai Line, Polyline, Circle & Arc', 'durasi_tampil' => '10:10', 'durasi_detik' => 610, 'cuplikan_gratis' => false],
                            ['judul_materi' => 'Teknik Modifikasi Cepat: Offset, Trim, Extend, Mirror & Rotate', 'durasi_tampil' => '17:35', 'durasi_detik' => 1055, 'cuplikan_gratis' => false],
                        ],
                    ],
                    [
                        'judul_modul' => 'Modul 3: Manajemen Layer, Skala, & Layout Cetak',
                        'materis' => [
                            ['judul_materi' => 'Standarisasi Warna Garis, Ketebalan Layer, dan Notasi Dimensi', 'durasi_tampil' => '09:30', 'durasi_detik' => 570, 'cuplikan_gratis' => false],
                            ['judul_materi' => 'Pengaturan Viewport, Skala Gambar, dan Ekspor ke Format PDF Siap Cetak', 'durasi_tampil' => '11:25', 'durasi_detik' => 685, 'cuplikan_gratis' => false],
                        ],
                    ],
                ],
            ],
            [
                'judul_kelas' => 'SketchUp: Pemodelan 3D Arsitektur & Desain Interior',
                'slug' => 'sketchup-pemodelan-3d-arsitektur',
                'kategori' => 'SketchUp',
                'harga' => 400000,
                'is_unggulan' => true,
                'deskripsi' => 'Pelajari teknik modeling ruang 3D yang rapi, pengorganisasian Group & Component berstandar studio, hingga visualisasi spasial yang ringan dan efisien untuk presentasi arsitektur maupun interior.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Fondasi Modeling 3D & Manajemen Outliner',
                        'materis' => [
                            ['judul_materi' => 'Navigasi Kamera, Axes Presisi & Pengaturan Template Studio', 'durasi_tampil' => '06:15', 'durasi_detik' => 375, 'cuplikan_gratis' => true],
                            ['judul_materi' => 'Disiplin Group vs Component agar Proyek Tidak Berat', 'durasi_tampil' => '09:40', 'durasi_detik' => 580, 'cuplikan_gratis' => false],
                        ],
                    ],
                    [
                        'judul_modul' => 'Modul 2: Eksekusi Modeling Rumah Tinggal 2 Lantai',
                        'materis' => [
                            ['judul_materi' => 'Konversi Denah CAD Menjadi Dinding & Bukaan Pintu Jendela 3D', 'durasi_tampil' => '14:20', 'durasi_detik' => 860, 'cuplikan_gratis' => false],
                            ['judul_materi' => 'Detailing Atap, Tangga, Fasad & Penataan Furnitur Interior', 'durasi_tampil' => '16:05', 'durasi_detik' => 965, 'cuplikan_gratis' => false],
                        ],
                    ],
                ],
            ],
            [
                'judul_kelas' => 'Revit Architecture: Fundamental Pemodelan BIM',
                'slug' => 'revit-architecture-fundamental-bim',
                'kategori' => 'Revit',
                'harga' => 450000,
                'is_unggulan' => true,
                'deskripsi' => 'Pahami konsep Building Information Modeling (BIM) secara menyeluruh, integrasi elemen struktural & arsitektural, penjadwalan kuantitas material (BOQ), hingga dokumentasi lembar kerja otomatis.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Konsep Dasar BIM, Grid & Level Bangunan',
                        'materis' => [
                            ['judul_materi' => 'Perbedaan Alur Kerja CAD Konvensional vs BIM di Revit', 'durasi_tampil' => '07:10', 'durasi_detik' => 430, 'cuplikan_gratis' => true],
                            ['judul_materi' => 'Pembuatan Grid Struktur dan Datum Level Proyek', 'durasi_tampil' => '11:00', 'durasi_detik' => 660, 'cuplikan_gratis' => false],
                        ],
                    ],
                ],
            ],
            [
                'judul_kelas' => 'Enscape Rendering: Visualisasi Arsitektur & Animasi Cepat',
                'slug' => 'enscape-rendering-visualisasi-arsitektur',
                'kategori' => 'Enscape',
                'harga' => 380000,
                'is_unggulan' => false,
                'deskripsi' => 'Pelajari pengaturan pencahayaan alami (Real-Time Sun & Sky), material PBR realistis, penataan vegetasi lanskap, serta produksi animasi video walkthrough arsitektur berkualitas tinggi.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Sinkronisasi Model & Setup Pencahayaan Realistis',
                        'materis' => [
                            ['judul_materi' => 'LiveSync SketchUp ke Enscape & Penataan Kamera Komposisi', 'durasi_tampil' => '06:50', 'durasi_detik' => 410, 'cuplikan_gratis' => true],
                        ],
                    ],
                ],
            ],
            [
                'judul_kelas' => 'AutoCAD 3D Modeling: Transformasi Gambar 2D ke Model Solid',
                'slug' => 'autocad-3d-modeling-solid',
                'kategori' => 'AutoCAD',
                'harga' => 380000,
                'is_unggulan' => false,
                'deskripsi' => 'Bangun objek solid 3D presisi, teknik ekstrusi elemen bangunan dan komponen mekanikal, serta penyajian potongan perspektif teknik langsung dari AutoCAD.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Workspace 3D Modeling & UCS Dinamis',
                        'materis' => [
                            ['judul_materi' => 'Menguasai Extrude, Revolve, Sweep & Boolean Operations', 'durasi_tampil' => '09:15', 'durasi_detik' => 555, 'cuplikan_gratis' => true],
                        ],
                    ],
                ],
            ],
            [
                'judul_kelas' => 'SketchUp + V-Ray: Teknik Rendering Pencahayaan Fotorealistik',
                'slug' => 'sketchup-vray-rendering-fotorealistik',
                'kategori' => 'SketchUp',
                'harga' => 420000,
                'is_unggulan' => false,
                'deskripsi' => 'Atur studio lighting interior & eksterior, racik material PBR V-Ray, serta optimalkan V-Ray Frame Buffer post-processing untuk hasil presentasi klien.',
                'moduls' => [
                    [
                        'judul_modul' => 'Modul 1: Asset Editor & Pencahayaan Interior Alami',
                        'materis' => [
                            ['judul_materi' => 'Mengatur Dome Light, Rectangle Light & Exposure Kamera', 'durasi_tampil' => '08:20', 'durasi_detik' => 500, 'cuplikan_gratis' => true],
                        ],
                    ],
                ],
            ],
        ];

        $createdKelas = [];
        foreach ($coursesData as $cData) {
            $kelas = Kelas::query()->updateOrCreate(
                ['slug' => $cData['slug']],
                [
                    'judul_kelas' => $cData['judul_kelas'],
                    'kategori' => $cData['kategori'],
                    'harga' => $cData['harga'],
                    'deskripsi' => $cData['deskripsi'],
                    'manfaat' => [
                        'Pembelajaran Mandiri Berbasis Video HD (Tanpa File Unduhan)',
                        'Masa Aktif Akses Setahun (365 Hari) Sejak Verifikasi Admin',
                        'Sertifikat Kelulusan Digital Resmi Ber-QR Code Otomatis (100% Progres)',
                    ],
                    'tautan_software' => 'https://drive.google.com/drive/folders/1jAfbtnk5fqRO4u2JrL0VdIvIYANZDzj2?usp=drive_link',
                    'status_publikasi' => 'published',
                    'is_unggulan' => $cData['is_unggulan'],
                ]
            );

            $createdKelas[$cData['slug']] = $kelas;

            if ($kelas->moduls()->count() === 0) {
                foreach ($cData['moduls'] as $mIdx => $mData) {
                    $modul = Modul::query()->create([
                        'kelas_id' => $kelas->id,
                        'judul_modul' => $mData['judul_modul'],
                        'nomor_urutan' => $mIdx + 1,
                    ]);

                    foreach ($mData['materis'] as $vIdx => $vData) {
                        Materi::query()->create([
                            'modul_id' => $modul->id,
                            'judul_materi' => $vData['judul_materi'],
                            'path_video' => 'https://www.youtube.com/watch?v=VtLXKU1PpRU',
                            'durasi_tampil' => $vData['durasi_tampil'],
                            'durasi_detik' => $vData['durasi_detik'],
                            'nomor_urutan' => $vIdx + 1,
                            'cuplikan_gratis' => $vData['cuplikan_gratis'],
                        ]);
                    }
                }
            }
        }

        // Seed realistic student demo state for Budi Santoso
        $autocad = $createdKelas['autocad-2d-gambar-kerja-mahir'];
        $sketchup = $createdKelas['sketchup-pemodelan-3d-arsitektur'];
        $revit = $createdKelas['revit-architecture-fundamental-bim'];

        if (Transaksi::query()->count() === 0) {
            $txPaid1 = Transaksi::query()->create([
                'nomor_invoice' => 'INV-0001',
                'user_id' => $budi->id,
                'kelas_id' => $autocad->id,
                'jenis_transaksi' => 'baru',
                'jumlah_pembayaran' => $autocad->harga,
                'bukti_pembayaran' => 'bukti/demo-bca-0001.jpg',
                'status_pembayaran' => 'berhasil',
                'verified_at' => now()->subDays(120),
            ]);

            AksesKelas::query()->create([
                'user_id' => $budi->id,
                'kelas_id' => $autocad->id,
                'transaksi_id' => $txPaid1->id,
                'tanggal_mulai' => now()->subDays(120),
                'tanggal_kedaluwarsa' => now()->addDays(245),
                'status_akses' => 'aktif',
            ]);

            // Mark first 2 videos finished & 3rd paused at 03:24 (204s) for Smart Resume demo
            $autocadMateris = $autocad->materis()->orderBy('materis.id')->get();
            foreach ($autocadMateris->take(2) as $doneMateri) {
                ProgresMateri::query()->create([
                    'user_id' => $budi->id,
                    'materi_id' => $doneMateri->id,
                    'sudah_selesai' => true,
                    'detik_terakhir_ditonton' => $doneMateri->durasi_detik,
                ]);
            }
            if (isset($autocadMateris[2])) {
                ProgresMateri::query()->create([
                    'user_id' => $budi->id,
                    'materi_id' => $autocadMateris[2]->id,
                    'sudah_selesai' => false,
                    'detik_terakhir_ditonton' => 204,
                ]);
            }

            // Expired class demo (Revit) for Reaktivasi 50% flow
            $txOldRevit = Transaksi::query()->create([
                'nomor_invoice' => 'INV-0002',
                'user_id' => $budi->id,
                'kelas_id' => $revit->id,
                'jenis_transaksi' => 'baru',
                'jumlah_pembayaran' => $revit->harga,
                'bukti_pembayaran' => 'bukti/demo-bca-0002.jpg',
                'status_pembayaran' => 'berhasil',
                'verified_at' => now()->subDays(400),
            ]);

            AksesKelas::query()->create([
                'user_id' => $budi->id,
                'kelas_id' => $revit->id,
                'transaksi_id' => $txOldRevit->id,
                'tanggal_mulai' => now()->subDays(400),
                'tanggal_kedaluwarsa' => now()->subDays(35),
                'status_akses' => 'kedaluwarsa',
            ]);

            // Pending verification demo (SketchUp) for Admin Verification queue
            Transaksi::query()->create([
                'nomor_invoice' => 'INV-0003',
                'user_id' => $budi->id,
                'kelas_id' => $sketchup->id,
                'jenis_transaksi' => 'baru',
                'jumlah_pembayaran' => $sketchup->harga,
                'bukti_pembayaran' => 'bukti/demo-bca-0003.jpg',
                'status_pembayaran' => 'menunggu_verifikasi',
                'batas_waktu_bayar' => now()->addHours(20),
            ]);
        }

        // Dedicated Alumni Student with 100% completed class & issued certificate
        $alumni = User::query()->updateOrCreate(
            ['email' => 'alumni@student.id'],
            [
                'name' => 'Ahmad Fauzi, S.Ars',
                'nomor_whatsapp' => '6281298765432',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status_profesi' => 'Arsitek / Drafter / Karyawan Swasta',
                'instansi_asal' => 'Studio Rancang Utama',
            ]
        );
        $alumni->markEmailAsVerified();

        $txAlumni = Transaksi::query()->updateOrCreate(
            ['nomor_invoice' => 'ARS-INV-ALUMNI-01'],
            [
                'user_id' => $alumni->id,
                'kelas_id' => $autocad->id,
                'jenis_transaksi' => 'baru',
                'jumlah_pembayaran' => $autocad->harga,
                'status_pembayaran' => 'berhasil',
                'verified_at' => now()->subDays(60),
            ]
        );

        AksesKelas::query()->updateOrCreate(
            ['user_id' => $alumni->id, 'kelas_id' => $autocad->id],
            [
                'transaksi_id' => $txAlumni->id,
                'tanggal_mulai' => now()->subDays(60),
                'tanggal_kedaluwarsa' => now()->addDays(305),
                'status_akses' => 'aktif',
            ]
        );

        foreach ($autocad->materis as $m) {
            ProgresMateri::query()->updateOrCreate(
                ['user_id' => $alumni->id, 'materi_id' => $m->id],
                [
                    'sudah_selesai' => true,
                    'detik_terakhir_ditonton' => $m->durasi_detik,
                ]
            );
        }

        Sertifikat::query()->updateOrCreate(
            ['user_id' => $alumni->id, 'kelas_id' => $autocad->id],
            [
                'nomor_sertifikat' => sprintf('013-ARS%s-%s/MKS/%s', date('y'), date('m'), date('Y')),
                'predikat' => 'Baik',
                'nilai' => 80,
                'hash_kode_qr' => 'allfirst-cert-alumni-cad-00108',
                'tanggal_terbit' => now()->subDays(10),
            ]
        );

        // Demo Offline Studio Student (Tatap Muka Makassar)
        $siswaOffline = User::query()->updateOrCreate(
            ['email' => 'offline@student.id'],
            [
                'name' => 'Muh. Rizky Pratama, S.T.',
                'nomor_whatsapp' => '6285244556677',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status_profesi' => 'Mahasiswa Teknik Sipil / Drafter',
                'instansi_asal' => 'Universitas Hasanuddin Makassar',
            ]
        );
        $siswaOffline->markEmailAsVerified();

        $txOffline = Transaksi::query()->updateOrCreate(
            ['nomor_invoice' => 'ARS-OFF-STUDIO-01'],
            [
                'user_id' => $siswaOffline->id,
                'kelas_id' => $sketchup->id,
                'jenis_transaksi' => 'offline',
                'jumlah_pembayaran' => $sketchup->harga,
                'status_pembayaran' => 'berhasil',
                'verified_at' => now()->subDays(14),
            ]
        );

        AksesKelas::query()->updateOrCreate(
            ['user_id' => $siswaOffline->id, 'kelas_id' => $sketchup->id],
            [
                'transaksi_id' => $txOffline->id,
                'tipe_kelas' => 'offline',
                'tanggal_mulai' => now()->subDays(14),
                'tanggal_kedaluwarsa' => now()->addDays(351),
                'status_akses' => 'aktif',
            ]
        );
    }
}
