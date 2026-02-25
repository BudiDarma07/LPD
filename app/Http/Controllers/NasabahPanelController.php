<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        if (!$anggota) {
            return redirect('/')->with('error', 'Data anggota tidak ditemukan.');
        }

        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id)->sum('jml_simpanan');

        $riwayatSimpanan = Simpanan::where('id_anggota', $anggota->id)
                            ->select('simpanan.*', 'jenis_simpanan.nama as jenis_simpanan_nama')
                            ->join('jenis_simpanan', 'jenis_simpanan.id', '=', 'simpanan.id_jenis_simpanan')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        $pinjamanAktif = Pinjaman::where('id_anggota', $anggota->id)
                            ->where('status_pengajuan', '1') 
                            ->first();
        
        $sisaTagihan = 0;
        if ($pinjamanAktif) {
            $totalDibayar = DB::table('angsuran')
                            ->where('id_pinjaman', $pinjamanAktif->id)
                            ->sum('jml_angsuran'); 
            
            $sisaTagihan = $pinjamanAktif->jml_pinjam - $totalDibayar;
            
            if($sisaTagihan < 0) $sisaTagihan = 0;
        }

        $pengajuanMenunggu = Pinjaman::where('id_anggota', $anggota->id)
                                ->where('status_pengajuan', '0')
                                ->exists();

        // TAMBAHAN: Ambil data pinjaman yang DITOLAK (status = 2) untuk ditampilkan pesannya
        $pinjamanDitolak = Pinjaman::where('id_anggota', $anggota->id)
                                ->where('status_pengajuan', '2')
                                ->orderBy('id', 'desc') // Ambil yang paling terbaru ditolak
                                ->first();

        return view('backend.nasabah.dashboard', compact(
            'anggota',
            'totalSimpanan', 
            'riwayatSimpanan', 
            'sisaTagihan', 
            'pinjamanAktif',
            'pengajuanMenunggu',
            'pinjamanDitolak' // Pastikan ini dikirim ke view
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
        
        $cekPinjaman = Pinjaman::where('id_anggota', $anggota->id)
                        ->whereIn('status_pengajuan', ['0', '1'])
                        ->exists();

        if($cekPinjaman) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Anda masih memiliki pinjaman aktif atau pengajuan yang sedang diproses.');
        }

        $totalSimpanan = Simpanan::where('id_anggota', $anggota->id)->sum('jml_simpanan');

        if ($totalSimpanan < 200000) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', 'Total simpanan Anda (Rp ' . number_format($totalSimpanan, 0, ',', '.') . ') kurang dari syarat minimal pengajuan pinjaman (Rp 200.000).');
        }

        return view('backend.nasabah.create_pinjaman', compact('totalSimpanan'));
    }

    /**
     * Proses Simpan Pengajuan ke Database
     */
    public function storePinjaman(Request $request)
    {
        $userId = Auth::id();
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        // Validasi Input
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:200000',
            'lama_angsuran'   => 'required|numeric', 
        ], [
            'jumlah_pinjaman.min' => 'Minimal pengajuan pinjaman adalah Rp 200.000.'
        ]);

        try {
            // Generate Kode Transaksi Otomatis seperti Admin (PNJ-0001 dst)
            $lastTransaction = DB::table('pinjaman')->orderBy('id', 'desc')->first();
            $newTransactionNumber = $lastTransaction ? (int) substr($lastTransaction->kodeTransaksiPinjaman, 4) + 1 : 1;
            $kodeTransaksi = 'PNJ-' . str_pad($newTransactionNumber, 4, '0', STR_PAD_LEFT);

            // Hitung Tanggal & Jatuh Tempo
            $tanggalPinjam = Carbon::now();
            $jatuhTempo = Carbon::now()->addMonths((int) $request->lama_angsuran);

            // Simpan ke Database menggunakan Query Builder (Sesuai dengan tabel database)
            DB::table('pinjaman')->insert([
                'kodeTransaksiPinjaman'        => $kodeTransaksi,
                'id_anggota'                   => $anggota->id, 
                'tanggal_pinjam'               => $tanggalPinjam->format('Y-m-d'),
                'jatuh_tempo'                  => $jatuhTempo->format('Y-m-d'),
                'jml_pinjam'                   => $request->jumlah_pinjaman,
                'jml_cicilan'                  => $request->lama_angsuran, // Masuk ke jml_cicilan
                'status_pengajuan'             => 0,
                'bunga_pinjam'                 => 0, 
                'keterangan_ditolak_pengajuan' => '', 
                'created_by'                   => $userId,
                'updated_by'                   => $userId,
                'created_at'                   => Carbon::now(),
                'updated_at'                   => Carbon::now(),
            ]);

            // Catatan: Kolom 'keterangan' dihiraukan/dibuang karena tidak ada di tabel pinjaman.

            return redirect()->route('nasabah.dashboard')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan Admin.');
        
        } catch (\Exception $e) {
            // Jika error, akan kembali ke form dan memunculkan pesan gagal di layar
            return redirect()->back()->with('error', 'Gagal mengajukan pinjaman: ' . $e->getMessage());
        }
    }
}