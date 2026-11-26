<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKelasController;
use App\Http\Controllers\Admin\AdminKurikulumController;
use App\Http\Controllers\Admin\AdminSiswaController;
use App\Http\Controllers\Admin\AdminTestimoniController;
use App\Http\Controllers\Admin\AdminTransaksiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogKelasController;
use App\Http\Controllers\PreviewVideoController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', HomeController::class)->name('home');
Route::get('/kelas-online', [KatalogKelasController::class, 'index'])->name('kelas.index');
Route::get('/kelas-online/{kelas:slug}', [KatalogKelasController::class, 'show'])->name('kelas.show');
Route::get('/preview/{materi}', PreviewVideoController::class)->name('preview.video');

Route::get('/kelas-offline', [PublicPageController::class, 'offline'])->name('offline');
Route::get('/e-book', [PublicPageController::class, 'ebook'])->name('ebook');
Route::get('/faq', [PublicPageController::class, 'faq'])->name('faq');

// Public Certificate Verification
Route::get('/verifikasi-sertifikat/{hash}', [SertifikatController::class, 'verifikasi'])->name('sertifikat.verifikasi');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/daftar', [RegisterController::class, 'create'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'store'])->name('register.store');

    // Forgot & Reset Password
    Route::get('/lupa-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Authenticated Routes (Students & Admin)
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [LoginController::class, 'destroy'])->name('logout');

    // Checkout & Payment
    Route::get('/checkout/{kelas:slug}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{kelas:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/transaksi/{transaksi:nomor_invoice}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{transaksi:nomor_invoice}/upload-bukti', [TransaksiController::class, 'uploadBukti'])->name('transaksi.upload');

    // Student Dashboard & Classroom
    Route::get('/student/dashboard', StudentDashboardController::class)->name('student.dashboard');
    Route::get('/student/kelas/{kelas:slug}/belajar/{materi?}', [ClassroomController::class, 'show'])->name('student.classroom');
    Route::get('/student/kelas/{kelas:slug}/materi/{materi}/stream', [ClassroomController::class, 'stream'])->name('student.materi.stream');
    Route::post('/student/materi/{materi}/progress', [ClassroomController::class, 'saveProgress'])->name('student.materi.progress');
    Route::post('/student/materi/{materi}/complete', [ClassroomController::class, 'markComplete'])->name('student.materi.complete');
    Route::get('/student/kelas/{kelas:slug}/quiz', [StudentQuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/student/kelas/{kelas:slug}/quiz', [StudentQuizController::class, 'submitQuiz'])->name('student.quiz.submit');
    Route::post('/student/kelas/{kelas:slug}/testimoni', [StudentQuizController::class, 'submitTestimoni'])->name('student.quiz.testimoni');
    Route::get('/student/sertifikat/{sertifikat}', [SertifikatController::class, 'show'])
        ->where('sertifikat', '.*')
        ->name('student.sertifikat.show');
});

// Admin Area Protected with 'admin' middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/running-text', [AdminDashboardController::class, 'updateRunningText'])->name('running-text.update');
    Route::post('/dashboard/hero', [AdminDashboardController::class, 'updateHero'])->name('hero.update');
    Route::post('/dashboard/promo', [AdminDashboardController::class, 'updatePromo'])->name('promo.update');
    Route::post('/dashboard/rekening', [AdminDashboardController::class, 'updateRekening'])->name('rekening.update');

    // Transaksi & Verifikasi Pembayaran
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}', [AdminTransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{transaksi}/approve', [AdminTransaksiController::class, 'approve'])->name('transaksi.approve');
    Route::post('/transaksi/{transaksi}/reject', [AdminTransaksiController::class, 'reject'])->name('transaksi.reject');
    Route::post('/transaksi/{transaksi}/sertifikat', [AdminTransaksiController::class, 'issueSertifikat'])->name('transaksi.sertifikat');

    // Data Siswa (Online & Offline) & Penerbitan Sertifikat
    Route::get('/siswa', [AdminSiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [AdminSiswaController::class, 'store'])->name('siswa.store');
    Route::post('/siswa/pengaturan-sertifikat', [AdminSiswaController::class, 'updateNomorSertifikat'])->name('siswa.pengaturan-sertifikat');
    Route::post('/siswa/{aksesKelas}/sertifikat', [AdminSiswaController::class, 'issueSertifikat'])->name('siswa.sertifikat');
    Route::post('/siswa/{user}/akses', [AdminSiswaController::class, 'updateAksesKelas'])->name('siswa.akses');

    // Manajemen Kelas
    Route::resource('kelas', AdminKelasController::class)->parameters(['kelas' => 'kelas']);

    // Manajemen Modul & Materi (Kurikulum) + Bank Soal Quiz
    Route::get('/kelas/{kelas}/kurikulum', [AdminKurikulumController::class, 'index'])->name('kelas.kurikulum');
    Route::post('/kelas/{kelas}/modul', [AdminKurikulumController::class, 'storeModul'])->name('kelas.modul.store');
    Route::delete('/modul/{modul}', [AdminKurikulumController::class, 'destroyModul'])->name('modul.destroy');
    Route::post('/modul/{modul}/materi', [AdminKurikulumController::class, 'storeMateri'])->name('modul.materi.store');
    Route::delete('/materi/{materi}', [AdminKurikulumController::class, 'destroyMateri'])->name('materi.destroy');
    Route::post('/kelas/{kelas}/quiz', [AdminKurikulumController::class, 'storeQuiz'])->name('kelas.quiz.store');
    Route::delete('/quiz/{quizQuestion}', [AdminKurikulumController::class, 'destroyQuiz'])->name('quiz.destroy');

    // Moderasi Rating & Testimoni Siswa
    Route::get('/testimoni', [AdminTestimoniController::class, 'index'])->name('testimoni.index');
    Route::post('/testimoni/{testimoni}/toggle-beranda', [AdminTestimoniController::class, 'toggleBeranda'])->name('testimoni.toggle');
    Route::delete('/testimoni/{testimoni}', [AdminTestimoniController::class, 'destroy'])->name('testimoni.destroy');
});
