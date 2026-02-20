<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // <-- Jangan lupa tambahkan ini

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Logika Redirect Dinamis Setelah Berhasil Login
     * Menggantikan redirectTo() agar bisa memberikan pesan error
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. CEK ADMIN DULU! (Prioritas Tertinggi)
        // Sesuaikan 'Admin' dengan nama role administrator di database Anda
        if ($user->hasRole('Admin')) { 
            return redirect('/home');
        }

        // 2. KEMUDIAN CEK NASABAH
        if ($user->hasRole('Nasabah')) {
            return redirect('/portal-nasabah');
        }

        // 3. JARING PENGAMAN: Jika user tidak punya role sama sekali
        // Jangan biarkan masuk ke /home, paksa logout dan beri pesan error!
        Auth::logout();
        return redirect('/login')->with('error', 'Login gagal: Akun Anda belum memiliki akses (Role) yang sah di sistem. Silakan hubungi Admin.');
    }
}