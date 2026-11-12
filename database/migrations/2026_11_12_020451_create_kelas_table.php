<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kelas');
            $table->string('slug')->unique();
            $table->string('kategori', 50)->index();
            $table->unsignedInteger('harga');
            $table->text('deskripsi');
            $table->json('manfaat')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->string('status_publikasi', 20)->default('draft')->index();
            $table->boolean('is_unggulan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
