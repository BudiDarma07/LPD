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
        Schema::create('riwayat_pinjaman', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kodeTransaksiPinjaman');
            $table->unsignedBigInteger('id_pinjaman')->index('riwayat_pinjaman_id_pinjaman_foreign');
            $table->string('tanggal_pinjam');
            $table->string('jatuh_tempo');
            $table->integer('jml_pinjam');
            $table->integer('sisa_pinjam');
            $table->string('jml_cicilan');
            $table->string('status_pengajuan');
            $table->string('keterangan_ditolak_pengajuan');
            $table->unsignedBigInteger('created_by')->index('riwayat_pinjaman_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('riwayat_pinjaman_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pinjaman');
    }
};
