<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/portal-nasabah'; // Redirect ke dashboard nasabah

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // --- PERBAIKAN DI SINI ---
            // 'unique:_anggota,nik' artinya cek keunikan di tabel '_anggota' pada kolom 'nik'
            'nik' => ['required', 'numeric', 'digits:16', 'unique:_anggota,nik'], 
            
            // Validasi No HP (08xx atau 62xx, 10-13 digit)
            'telphone' => ['required', 'numeric', 'digits_between:10,13', 'starts_with:08,62'],
            
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'pekerjaan' => ['required', 'string', 'max:100'],

        ], [
            // Pesan Error Custom (Opsional)
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'telphone.starts_with' => 'Nomor HP harus diawali 08 atau 62.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat User Login (Tabel users)
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // 2. Berikan Role Nasabah
            $user->assignRole('Nasabah');

            // 3. Simpan ke tabel _anggota
            // PERBAIKAN: Gunakan nama kolom 'nik' sesuai database
            DB::table('_anggota')->insert([
                'user_id' => $user->id,
                'name' => $data['name'],
                
                'nik' => $data['nik'], 
                
                'telphone' => $data['telphone'], 
                'jenis_kelamin' => $data['jenis_kelamin'],
                'pekerjaan' => $data['pekerjaan'],
                
                // Data Default
                'agama' => 'Hindu', // Default, atau tambahkan input di form jika perlu
                'alamat' => '-',
                'status_anggota' => '1', // Aktif
                'saldo' => 0,
                'tgl_gabung' => now(),
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $user;
        });
    }
}