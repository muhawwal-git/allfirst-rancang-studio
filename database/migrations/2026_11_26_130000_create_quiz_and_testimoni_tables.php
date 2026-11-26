<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->unsignedInteger('nomor_urutan')->default(1);
            $table->text('pertanyaan');
            $table->string('opsi_a');
            $table->string('opsi_b');
            $table->string('opsi_c');
            $table->string('opsi_d');
            $table->string('jawaban_benar', 1)->default('A');
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->unsignedInteger('skor');
            $table->unsignedInteger('jumlah_benar');
            $table->unsignedInteger('total_soal');
            $table->boolean('lulus')->default(false);
            $table->json('jawaban_siswa')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonis', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('profesi')->nullable();
            $table->text('ulasan');
            $table->boolean('tampil_beranda')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'kelas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonis');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
    }
};
