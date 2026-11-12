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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
            $table->string('judul_materi');
            $table->string('path_video');
            $table->string('durasi_tampil', 10)->default('00:00');
            $table->unsignedInteger('durasi_detik')->default(0);
            $table->unsignedInteger('nomor_urutan')->default(1);
            $table->boolean('cuplikan_gratis')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
