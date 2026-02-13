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
            
            // Cek satu per satu, jika belum ada baru dibuat
            
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->default('-');
            }

            if (!Schema::hasColumn('users', 'telphone')) {
                $table->string('telphone')->nullable()->default('-');
            }

            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            }

            if (!Schema::hasColumn('users', 'agama')) {
                $table->string('agama')->default('Hindu');
            }

            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }

            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable();
            }

            if (!Schema::hasColumn('users', 'status_anggota')) {
                $table->string('status_anggota')->default('Aktif');
            }

            if (!Schema::hasColumn('users', 'tgl_gabung')) {
                $table->date('tgl_gabung')->nullable();
            }

            // Ini yang bikin error tadi (image), kita cek dulu
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            $columns = [
                'nip', 'telphone', 'jenis_kelamin', 'agama', 
                'alamat', 'pekerjaan', 'status_anggota', 
                'tgl_gabung', 'image'
            ];
            
            $table->dropColumn($columns);
        });
    }
};