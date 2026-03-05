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
            
            // EMAIL: Ditambahkan unique:users,email
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // NIK: Harus angka, tepat 16 digit, dan tidak boleh sama (unik) di tabel _anggota
            'nik' => ['required', 'numeric', 'digits:16', 'unique:_anggota,nik'], 
            
            // NOMOR HP: Harus angka, min 10 max 12 digit, diawali 08/62, dan tidak boleh sama (unik)
            'telphone' => ['required', 'numeric', 'digits_between:10,12', 'starts_with:08,62', 'unique:_anggota,telphone'],
            
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'pekerjaan' => ['required', 'string', 'max:100'],

        ], [
            // Pesan Error Kustom agar lebih mudah dipahami User
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem kami.',
            'nik.digits' => 'NIK harus berjumlah persis 16 digit angka.',
            'telphone.unique' => 'Nomor HP ini sudah terdaftar.',
            'telphone.digits_between' => 'Nomor HP minimal 10 digit dan maksimal 12 digit.',
            'telphone.starts_with' => 'Nomor HP harus diawali dengan 08 atau 62.',
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