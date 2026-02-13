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
        Schema::table('riwayat_pinjaman', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pinjaman'])->references(['id'])->on('pinjaman')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pinjaman', function (Blueprint $table) {
            $table->dropForeign('riwayat_pinjaman_created_by_foreign');
            $table->dropForeign('riwayat_pinjaman_id_pinjaman_foreign');
            $table->dropForeign('riwayat_pinjaman_updated_by_foreign');
        });
    }
};
