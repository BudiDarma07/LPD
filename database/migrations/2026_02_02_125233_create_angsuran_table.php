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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kodeTransaksiAngsuran');
            $table->unsignedBigInteger('id_pinjaman')->index('angsuran_id_pinjaman_foreign');
            $table->string('tanggal_angsuran');
            $table->string('jml_angsuran');
            $table->integer('sisa_pinjam');
            $table->integer('cicilan');
            $table->string('status', 25);
            $table->string('keterangan')->nullable();
            $table->string('bukti_pembayaran');
            $table->string('bunga_pinjaman');
            $table->integer('denda');
            $table->unsignedBigInteger('created_by')->index('angsuran_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('angsuran_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
