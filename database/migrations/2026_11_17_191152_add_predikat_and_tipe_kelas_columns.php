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
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->string('predikat', 30)->default('Baik')->after('nomor_sertifikat');
            $table->unsignedTinyInteger('nilai')->default(80)->after('predikat');
        });

        Schema::table('akses_kelas', function (Blueprint $table) {
            $table->string('tipe_kelas', 20)->default('online')->after('transaksi_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->dropColumn(['predikat', 'nilai']);
        });

        Schema::table('akses_kelas', function (Blueprint $table) {
            $table->dropColumn('tipe_kelas');
        });
    }
};
