<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allfirst Rancang Studio — Static Content (Fase 1)
    |--------------------------------------------------------------------------
    |
    | Konten statis halaman publik yang jarang berubah (E-Book, FAQ, Testimoni,
    | opsi profesi). Diatur oleh developer pada Fase 1 dan dapat dipindahkan
    | ke panel admin pada Fase 2.
    |
    */

    'brand_name' => 'Allfirst Rancang Studio',

    'preview_limit_seconds' => 15,

    'access_duration_days' => 365,

    'status_profesi_options' => [
        'Mahasiswa / Pelajar',
        'Arsitek / Drafter / Karyawan Swasta',
        'Freelancer / Praktisi Mandiri',
        'Umum / Pencari Kerja',
    ],

    'kategori_software' => ['AutoCAD', 'SketchUp', 'Revit', 'Enscape'],

    'admin_whatsapp' => '6285190367139',

    'bank_accounts' => [
        [
            'bank' => 'BCA',
            'nomor_rekening' => '816-123-4567',
            'atas_nama' => 'PT Allfirst Rancang Studio',
        ],
    ],

    'ebooks' => [
        [
            'tag' => 'Standar Resmi PUPR',
            'meta' => 'PDF · 13.7 MB · PUPR 2021',
            'title' => 'Standar Kelengkapan Gambar Arsitektur PUPR 2021',
            'description' => 'Pedoman resmi kelengkapan dan format gambar kerja arsitektur DED berstandar Kementerian PUPR untuk konsultan, drafter, dan mahasiswa.',
            'gdrive_url' => 'https://drive.google.com/file/d/129zMz_6kuRt2wXw2vKP1f5GajPx_9LEq/view?usp=sharing',
        ],
        [
            'tag' => 'AHSP Cipta Karya',
            'meta' => 'PDF · 15.8 MB · Bidang Cipta Karya',
            'title' => 'Lampiran VI - AHSP Bidang Cipta Karya & Perumahan',
            'description' => 'Panduan Analisis Harga Satuan Pekerjaan (AHSP) resmi bidang cipta karya dan perumahan untuk estimasi RAB dan spesifikasi teknis proyek.',
            'gdrive_url' => 'https://drive.google.com/file/d/1LzctD8cXptu2LzZos1ShtkT_VcHnNADY/view?usp=sharing',
        ],
        [
            'tag' => 'Folder Lengkap Studio',
            'meta' => 'Google Drive Folder · Akses Publik',
            'title' => 'Akses Seluruh Koleksi Modul & Referensi E-Book Studio',
            'description' => 'Akses langsung ke folder Google Drive utama Allfirst Rancang Studio untuk melihat dan mengunduh seluruh koleksi e-book arsitektur.',
            'gdrive_url' => 'https://drive.google.com/drive/folders/1jAfbtnk5fqRO4u2JrL0VdIvIYANZDzj2?usp=drive_link',
        ],
    ],

    'faqs' => [
        [
            'question' => 'Berapa lama masa aktif akses video setelah membeli kelas online?',
            'answer' => 'Setiap pembelian kelas online memberikan hak akses penuh selama 1 tahun (365 hari) terhitung sejak pembayaran diverifikasi oleh admin. Apabila masa aktif berakhir, Anda dapat memperpanjang akses melalui fitur Reaktivasi Akses dengan potongan biaya 50%.',
        ],
        [
            'question' => 'Bagaimana cara mendaftar pelatihan tatap muka (Kelas Offline) di studio?',
            'answer' => 'Pendaftaran kelas tatap muka dilayani secara personal melalui WhatsApp resmi kami (tanpa checkout website) agar jadwal sesi dan fokus materi dapat dikonsultasikan langsung bersama instruktur.',
        ],
        [
            'question' => 'Apakah peserta mendapatkan sertifikat resmi setelah menyelesaikan kelas?',
            'answer' => 'Ya. Sertifikat kelulusan digital resmi berformat PDF Landscape lengkap dengan nomor registrasi unik dan QR Code verifikasi keaslian terbit otomatis begitu seluruh video materi (100%) selesai ditonton.',
        ],
        [
            'question' => 'Apakah video pembelajaran memiliki fitur lanjut otomatis (Smart Resume)?',
            'answer' => 'Benar. Pemutar video di ruang belajar dilengkapi fitur Smart Resume yang otomatis menyimpan detik terakhir tontonan Anda, dan disajikan secara streaming tanpa perlu mengunduh berkas video.',
        ],
        [
            'question' => 'Bagaimana alur verifikasi pembayaran transfer bank manual?',
            'answer' => 'Setelah Anda mengunggah bukti transfer, sistem otomatis mengirim notifikasi email ke Admin Studio. Anda juga dapat menekan tombol "Konfirmasi Cepat via WhatsApp Admin" agar hak akses kelas segera diaktifkan.',
        ],
    ],

    'testimonials' => [
        [
            'quote' => 'Materi AutoCAD 2D dan standar manajemen layer-nya benar-benar terpakai di kantor konsultan. Penjelasannya runtut sampai siap cetak DED.',
            'name' => 'Rizky Pratama, S.Ars',
            'course' => 'AutoCAD 2D: Dari Dasar sampai Mahir',
        ],
        [
            'quote' => 'Belajar SketchUp di sini bikin tugas studio perancangan jauh lebih cepat. Fitur Smart Resume-nya sangat membantu.',
            'name' => 'Nadia Maharani',
            'course' => 'SketchUp: Pemodelan 3D Arsitektur',
        ],
        [
            'quote' => 'Materinya lengkap dan langsung praktik kasus bangunan nyata. Konsep BIM Revit mudah dipahami pemula.',
            'name' => 'Ahmad Sony, S.T.',
            'course' => 'Revit Architecture: Fundamental BIM',
        ],
        [
            'quote' => 'Hasil render Enscape langsung terlihat profesional untuk presentasi klien. Akses 1 tahun bikin nyaman mengulang materi.',
            'name' => 'Siti Rauzelia',
            'course' => 'Enscape Rendering & Animasi Cepat',
        ],
        [
            'quote' => 'Selesai menonton 100%, sertifikat ber-QR resminya langsung terbit dan saya pakai untuk melamar kerja.',
            'name' => 'Hendra Wijaya, S.T.',
            'course' => 'AutoCAD 3D Solid Modeling',
        ],
        [
            'quote' => 'Materi to the point tanpa bertele-tele. Sangat membantu mahasiswa teknik sipil yang baru belajar gambar kerja.',
            'name' => 'Arif Budiman',
            'course' => 'AutoCAD 2D: Dari Dasar sampai Mahir',
        ],
        [
            'quote' => 'Teknik lighting dan material V-Ray yang diajarkan langsung bisa diterapkan untuk proyek interior klien.',
            'name' => 'Dimas Anggara',
            'course' => 'SketchUp + V-Ray Fotorealistik',
        ],
        [
            'quote' => 'Silabusnya paling terstruktur sesuai alur kerja studio konsultan perencana. Sangat direkomendasikan.',
            'name' => 'Putri Larasati, S.Ars',
            'course' => 'Revit Architecture: Fundamental BIM',
        ],
    ],

];
