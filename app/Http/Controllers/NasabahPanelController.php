<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan Facade DB
use Illuminate\Support\Str; 
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran; 
use Carbon\Carbon; 

class NasabahPanelController extends Controller
{
    /**
     * Menampilkan Dashboard Nasabah (Saldo, Tagihan, Riwayat)
     */
    public function index()
    {
        $userId = Auth::id(); 
        
        // [PENTING] Ambil Data Anggota berdasarkan User Login
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        // Jika user login tapi data anggota tidak ditemukan (antisipasi error)
        if (!$anggota) {
            return redirect('/')->with('error', 'Data anggota tidak ditemukan.');
        }

        // 1. Hitung Total Simpanan (Gunakan $anggota->id, BUKAN $userId)
        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id)->sum('jml_simpanan');

        // 2. Ambil Riwayat Simpanan Terakhir
        $riwayatSimpanan = Simpanan::where('id_anggota', $anggota->id)
                            ->select('simpanan.*', 'jenis_simpanan.nama as jenis_simpanan_nama') // Join untuk nama jenis
                            ->join('jenis_simpanan', 'jenis_simpanan.id', '=', 'simpanan.id_jenis_simpanan')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // 3. Cek Pinjaman Aktif (Hanya yang statusnya 1 / Belum Lunas)
        $pinjamanAktif = Pinjaman::where('id_anggota', $anggota->id)
                            ->where('status_pengajuan', '1') 
                            ->first();
        
        $sisaTagihan = 0;
        if ($pinjamanAktif) {
            // Hitung total yang sudah dibayar di tabel angsuran
            // Pastikan nama kolomnya jml_angsuran (bukan jumlah_angsuran)
            $totalDibayar = DB::table('angsuran')
                            ->where('id_pinjaman', $pinjamanAktif->id)
                            ->sum('jml_angsuran'); 
            
            // Perhitungan Sederhana: Pokok - Sudah Bayar (Bisa disesuaikan jika ada bunga)
            $sisaTagihan = $pinjamanAktif->jml_pinjam - $totalDibayar;
            
            // Pastikan tidak minus
            if($sisaTagihan < 0) $sisaTagihan = 0;
        }

        // 4. Cek Pengajuan Menunggu (Status 0)
        $pengajuanMenunggu = Pinjaman::where('id_anggota', $anggota->id)
                                ->where('status_pengajuan', '0')
                                ->exists();

        return view('backend.nasabah.dashboard', compact(
            'anggota',
            'totalSimpanan', 
            'riwayatSimpanan', 
            'sisaTagihan', 
            'pinjamanAktif',
            'pengajuanMenunggu'
        ));
    }

    /**
     * Menampilkan Form Pengajuan Pinjaman
     */
    public function createPinjaman()
    {
        $userId = Auth::id();
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        if (!$anggota) {
            return redirect()->route('nasabah.dashboard')->with('error', 'Data anggota tidak valid.');
        }
        
        // Cek apakah ada pinjaman yang belum lunas (1) atau sedang diajukan (0)
        $cekPinjaman = Pinjaman::where('id_anggota', $anggota->id)
                        ->whereIn('status_pengajuan', ['0', '1'])
                        ->exists();

        if($cekPinjaman) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Anda masih memiliki pinjaman aktif atau pengajuan yang sedang diproses.');
        }

        // HITUNG TOTAL SIMPANAN NASABAH
        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id)->sum('jml_simpanan');

        // Jika simpanan kurang dari minimal pinjaman (200.000), blokir pengajuan
        if ($totalSimpanan < 200000) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Total simpanan Anda (Rp ' . number_format($totalSimpanan, 0, ',', '.') . ') kurang dari syarat minimal pengajuan pinjaman (Rp 200.000).');
        }

        // Lempar totalSimpanan ke view
        return view('backend.nasabah.create_pinjaman', compact('totalSimpanan'));
    }

    /**
     * Proses Simpan Pengajuan ke Database
     */
    public function storePinjaman(Request $request)
    {
        $userId = Auth::id();
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        // Validasi Input (Hanya membatasi minimal, tidak ada batas maksimal)
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:200000',
            'lama_angsuran'   => 'required|numeric', 
            'keterangan'      => 'required|string|max:255',
        ], [
            // Kustomisasi pesan error jika kurang dari minimal
            'jumlah_pinjaman.min' => 'Minimal pengajuan pinjaman adalah Rp 200.000.'
        ]);

        try {
            // Hitung Tanggal
            $tanggalPinjam = Carbon::now();
            $jatuhTempo = Carbon::now()->addMonths((int) $request->lama_angsuran);

            // Generate Kode Transaksi Otomatis (PNJ-TIMESTAMP)
            $kodeTransaksi = 'PNJ-' . time();

            // Simpan ke Database
            Pinjaman::create([
                'kodeTransaksiPinjaman' => $kodeTransaksi,
                'id_anggota'            => $anggota->id, // [PENTING] Gunakan ID Anggota
                'tanggal_pinjam'        => $tanggalPinjam,
                'jatuh_tempo'           => $jatuhTempo,
                'jml_pinjam'            => $request->jumlah_pinjaman,
                'lama_angsuran'         => $request->lama_angsuran,
                'keterangan'            => $request->keterangan, 
                
                // Status Default (0 = Menunggu)
                'status_pengajuan'      => '0',
                'bunga_pinjam'          => 0, // Akan diisi admin saat ACC
                'jml_cicilan'           => 0, // Akan diisi admin saat ACC
                'keterangan_ditolak_pengajuan' => '-', 

                // Audit Trail
                'created_by'            => $userId,
                'updated_by'            => $userId,
            ]);

            return redirect()->route('nasabah.dashboard')->with('message', 'Pengajuan berhasil dikirim! Menunggu persetujuan Admin.');
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengajukan pinjaman: ' . $e->getMessage());
        }
    }
}