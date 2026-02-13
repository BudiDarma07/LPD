<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * (Menambahkan kolom saat perintah migrate dijalankan)
     */
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            // Kita cek dulu biar aman, kalau belum ada baru dibuat
            if (!Schema::hasColumn('pinjaman', 'keterangan')) {
                // Menambahkan kolom 'keterangan' (tipe Text) setelah kolom 'jml_pinjam'
                $table->text('keterangan')->nullable()->after('jml_pinjam');
            }
        });
    }

    /**
     * Reverse the migrations.
     * (Menghapus kolom saat perintah migrate:rollback dijalankan)
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            if (Schema::hasColumn('pinjaman', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }
};