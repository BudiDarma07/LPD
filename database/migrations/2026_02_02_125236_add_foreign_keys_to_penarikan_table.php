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
        Schema::table('penarikan', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_anggota'])->references(['id'])->on('_anggota')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['updated_by'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penarikan', function (Blueprint $table) {
            $table->dropForeign('penarikan_created_by_foreign');
            $table->dropForeign('penarikan_id_anggota_foreign');
            $table->dropForeign('penarikan_updated_by_foreign');
        });
    }
};
