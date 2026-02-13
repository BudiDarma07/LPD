<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home'; // <--- SAYA MATIKAN INI (DEFAULT)

    /**
     * Logika Redirect Dinamis (Ganti $redirectTo dengan function ini)
     */
    public function redirectTo()
    {
        // 1. Cek apakah user yang login punya role 'Nasabah'
        if (auth()->user()->hasRole('Nasabah')) {
            return '/portal-nasabah'; // Arahkan ke Panel Nasabah
        }

        // 2. Jika bukan Nasabah (Admin/Petugas), arahkan ke Dashboard Admin
        return '/home';
    }

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
}