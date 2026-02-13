<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanan';
    protected $guarded = [];

    // Relasi ke User (Anggota)
    public function user()
    {
        // Sesuaikan dengan nama kolom foreign key di database Anda ('id_anggota')
        return $this->belongsTo(User::class, 'id_anggota', 'id');
    }

    /* * SAYA MENONAKTIFKAN BAGIAN INI SEMENTARA
     * AGAR TIDAK ERROR "Unknown Class JenisSimpanan"
     */
    // public function jenis()
    // {
    //     return $this->belongsTo(JenisSimpanan::class, 'id_jenis_simpanan');
    // }
}