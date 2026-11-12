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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_whatsapp', 30)->after('email');
            $table->string('role', 20)->default('student')->after('password');
            $table->string('status_profesi', 60)->default('Umum / Pencari Kerja')->after('role');
            $table->string('instansi_asal', 150)->nullable()->after('status_profesi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nomor_whatsapp', 'role', 'status_profesi', 'instansi_asal']);
        });
    }
};
