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
        Schema::create('penarikan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kodeTransaksiPenarikan');
            $table->unsignedBigInteger('id_anggota')->index('penarikan_id_anggota_foreign');
            $table->date('tanggal_penarikan');
            $table->decimal('jumlah_penarikan', 12);
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->index('penarikan_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('penarikan_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan');
    }
};
