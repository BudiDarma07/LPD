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
        Schema::create('jenis_simpanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('deskripsi');
            $table->unsignedBigInteger('created_by')->index('jenis_simpanan_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('jenis_simpanan_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_simpanan');
    }
};
