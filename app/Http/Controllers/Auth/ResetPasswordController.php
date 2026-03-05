<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home';

    // 1. Tampilkan form verifikasi NIK dan Kontak
    public function showCustomRequestForm()
    {
        return view('auth.passwords.custom_request');
    }

    // 2. Proses pencocokan NIK dan Email/No. HP di database
    public function verifyCustomUser(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|digits:16',
            'kontak' => 'required|string', 
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'kontak.required' => 'Email atau Nomor HP wajib diisi.',
        ]);

        // Cari user yang NIK-nya cocok, DAN (Email ATAU No HP) juga cocok
        $user = DB::table('_anggota')
            ->join('users', 'users.id', '=', '_anggota.user_id')
            ->select('users.id')
            ->where('_anggota.nik', $request->nik)
            ->where(function ($query) use ($request) {
                $query->where('users.email', $request->kontak)
                      ->orWhere('_anggota.telphone', $request->kontak);
            })
            ->first();

        if ($user) {
            // Jika cocok, simpan ID user ke session sementara dan arahkan ke form ganti password
            session(['reset_user_id' => $user->id]);
            return redirect()->route('password.custom.reset');
        }

        // Jika tidak ada data yang cocok
        return back()->with('error', 'Gagal: Data NIK dan Email/Nomor HP tidak cocok atau tidak ditemukan!');
    }

    // 3. Tampilkan form input password baru
    public function showCustomResetForm()
    {
        // Pastikan user sudah melewati tahap verifikasi sebelumnya
        if (!session('reset_user_id')) {
            return redirect()->route('password.custom.request')->with('error', 'Sesi tidak valid. Silakan verifikasi data Anda terlebih dahulu.');
        }
        return view('auth.passwords.custom_reset');
    }

    // 4. Proses simpan password baru
    public function updateCustomPassword(Request $request)
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.custom.request');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('reset_user_id');

        // Update password di tabel users
        User::where('id', $userId)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus session reset karena proses sudah selesai
        session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Berhasil! Password Anda telah diperbarui. Silakan login.');
    }
}