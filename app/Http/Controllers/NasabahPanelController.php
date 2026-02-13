<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // 1. Hitung Total Simpanan
        $totalSimpanan = Simpanan::where('id_anggota', $userId)->sum('jml_simpanan');

        // 2. Ambil Riwayat Simpanan Terakhir
        $riwayatSimpanan = Simpanan::where('id_anggota', $userId)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // 3. Cek Pinjaman Aktif
        $pinjamanAktif = Pinjaman::where('id_anggota', $userId)
                            ->where('status_pengajuan', 'Diterima') 
                            ->first();
        
        $sisaTagihan = 0;
        if ($pinjamanAktif) {
            $totalDibayar = Angsuran::where('id_pinjaman', $pinjamanAktif->id)->sum('jml_bayar');
            $sisaTagihan = $pinjamanAktif->jml_pinjam - $totalDibayar;
        }

        // 4. Cek Pengajuan Menunggu
        $pengajuanMenunggu = Pinjaman::where('id_anggota', $userId)
                                ->where('status_pengajuan', 'Menunggu')
                                ->exists();

        return view('backend.nasabah.dashboard', compact(
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
        
        $cekPinjaman = Pinjaman::where('id_anggota', $userId)
                        ->whereIn('status_pengajuan', ['Menunggu', 'Diterima'])
                        ->exists();

        if($cekPinjaman) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Anda masih memiliki pinjaman aktif atau pengajuan yang sedang diproses.');
        }

        return view('backend.nasabah.create_pinjaman');
    }

    /**
     * Proses Simpan Pengajuan ke Database
     */
    public function storePinjaman(Request $request)
    {
        // Validasi Input
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:500000',
            'lama_angsuran'   => 'required|numeric', 
            'keterangan'      => 'required|string|max:255',
        ]);

        // Hitung Tanggal
        $tanggalPinjam = Carbon::now();
        $jatuhTempo = Carbon::now()->addMonths((int) $request->lama_angsuran);

        // Generate Kode Transaksi Otomatis
        $kodeTransaksi = 'PNJ-' . mt_rand(10000, 99999);

        // Simpan ke Database
        Pinjaman::create([
            'kodeTransaksiPinjaman' => $kodeTransaksi,
            'id_anggota'            => Auth::id(), // ID User = ID Anggota
            'tanggal_pinjam'        => $tanggalPinjam,
            'jatuh_tempo'           => $jatuhTempo,
            'jml_pinjam'            => $request->jumlah_pinjaman,
            
            // --- PERBAIKAN: JANGAN LUPA SIMPAN KETERANGAN ---
            'keterangan'            => $request->keterangan, 
            // ------------------------------------------------
            
            'status_pengajuan'      => 'Menunggu',
            'bunga_pinjam'          => 0, 
            'jml_cicilan'           => 0, 
            'keterangan_ditolak_pengajuan' => '-', 

            'created_by'            => Auth::id(),
            'updated_by'            => Auth::id(),
        ]);

        return redirect()->route('nasabah.dashboard')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan Admin.');
    }
}