<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';
    protected $guarded = []; // Ini artinya semua kolom boleh diisi (Mass Assignment OK)

    // Relasi ke User (Anggota)
    public function anggota() // Sebaiknya namanya 'anggota' agar lebih jelas, atau 'user' juga boleh
    {
        // PERBAIKAN: Foreign Key Anda adalah 'id_anggota', BUKAN 'user_id'
        return $this->belongsTo(User::class, 'id_anggota');
    }

    // Relasi ke Angsuran
    public function angsuran()
    {
        // Pastikan di tabel angsuran nama kolomnya 'id_pinjaman' (sesuaikan dengan migration)
        return $this->hasMany(Angsuran::class, 'id_pinjaman'); 
    }
}