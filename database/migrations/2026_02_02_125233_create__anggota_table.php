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
        Schema::create('_anggota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('_anggota_user_id_foreign');
            $table->string('nip');
            $table->string('name');
            $table->string('telphone');
            $table->string('agama')->nullable();
            $table->string('jenis_kelamin');
            $table->date('tgl_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('image')->nullable();
            $table->string('status_anggota')->nullable();
            $table->decimal('saldo', 10)->default(0);
            $table->date('tgl_gabung');
            $table->unsignedBigInteger('created_by')->index('_anggota_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('_anggota_updated_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_anggota');
    }
};
