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
        Schema::create('simpanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kodeTransaksiSimpanan');
            $table->string('tanggal_simpanan');
            $table->unsignedBigInteger('id_anggota')->index('simpanan_id_anggota_foreign');
            $table->unsignedBigInteger('id_jenis_simpanan')->index('simpanan_id_jenis_simpanan_foreign');
            $table->string('jml_simpanan');
            $table->string('bukti_pembayaran');
            $table->unsignedBigInteger('created_by')->index('simpanan_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('simpanan_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
