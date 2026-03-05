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

        $request->validate([
            // Karena pakai format string Rupiah di form, hapus validasi numeric di sini
            'jumlah_pinjaman' => 'required',
            'lama_angsuran'   => 'required|numeric', 
        ]);

        try {
            // PERBAIKAN: Bersihkan format Rupiah ("Rp 200.000" -> 200000)
            $jumlah_pinjaman_bersih = str_replace(['Rp ', '.'], '', $request->jumlah_pinjaman);
            $jumlah_pinjaman_bersih = (int) $jumlah_pinjaman_bersih;

            // Validasi manual batas minimal setelah dibersihkan formatnya
            if ($jumlah_pinjaman_bersih < 200000) {
                return redirect()->back()->with('error', 'Minimal pengajuan pinjaman adalah Rp 200.000.')->withInput();
            }

            // Generate Kode Transaksi Otomatis seperti Admin (PNJ-0001 dst)
            $lastTransaction = DB::table('pinjaman')->orderBy('id', 'desc')->first();
            $newTransactionNumber = $lastTransaction ? (int) substr($lastTransaction->kodeTransaksiPinjaman, 4) + 1 : 1;
            $kodeTransaksi = 'PNJ-' . str_pad($newTransactionNumber, 4, '0', STR_PAD_LEFT);

            // Hitung Tanggal & Jatuh Tempo
            $tanggalPinjam = Carbon::now();
            $jatuhTempo = Carbon::now()->addMonths((int) $request->lama_angsuran);

            // Simpan ke Database menggunakan Query Builder
            DB::table('pinjaman')->insert([
                'kodeTransaksiPinjaman'        => $kodeTransaksi,
                'id_anggota'                   => $anggota->id, 
                'tanggal_pinjam'               => $tanggalPinjam->format('Y-m-d'),
                'jatuh_tempo'                  => $jatuhTempo->format('Y-m-d'),
                'jml_pinjam'                   => $jumlah_pinjaman_bersih, // Gunakan variabel yang sudah bersih
                'jml_cicilan'                  => $request->lama_angsuran, 
                'status_pengajuan'             => 0,
                'bunga_pinjam'                 => 0, 
                'keterangan_ditolak_pengajuan' => '', 
                'created_by'                   => $userId,
                'updated_by'                   => $userId,
                'created_at'                   => Carbon::now(),
                'updated_at'                   => Carbon::now(),
            ]);

            return redirect()->route('nasabah.dashboard')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan Admin.');
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengajukan pinjaman: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan Form Setor Simpanan
     */
    public function createSimpanan()
    {
        $userId = Auth::id();
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        if (!$anggota) {
            return redirect()->route('nasabah.dashboard')->with('error', 'Data anggota tidak valid.');
        }

        $jenisSimpanan = DB::table('jenis_simpanan')->get();

        return view('backend.nasabah.create_simpanan', compact('jenisSimpanan', 'anggota'));
    }

    /**
     * Proses Simpan Data Simpanan Nasabah
     */
    public function storeSimpanan(Request $request)
    {
        $userId = Auth::id();
        $anggota = DB::table('_anggota')->where('user_id', $userId)->first();

        $request->validate([
            'id_jenis_simpanan' => 'required|exists:jenis_simpanan,id',
            // Hapus validasi numeric karena input bisa berupa string "Rp 50.000"
            'jml_simpanan'      => 'required', 
            'bukti_pembayaran'  => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $jenis = DB::table('jenis_simpanan')->where('id', $request->id_jenis_simpanan)->first();
            
            // PERBAIKAN: Bersihkan string Rupiah ("Rp 50.000" -> 50000)
            $jml_simpanan = str_replace(['Rp ', '.'], '', $request->jml_simpanan);
            $jml_simpanan = (int) $jml_simpanan;

            // Validasi manual batas minimal simpanan setelah dibersihkan
            if ($jml_simpanan < 10000 && !in_array($jenis->id, [1, 2])) {
                 return redirect()->back()->with('error', 'Setoran minimal adalah Rp 10.000.')->withInput();
            }

            // Validasi Aturan Nominal Simpanan Pokok & Wajib sesuai sistem Admin
            if ($jenis->id == 1) { // Simpanan Pokok
                $exists = DB::table('simpanan')->where('id_anggota', $anggota->id)->where('id_jenis_simpanan', 1)->exists();
                if ($exists) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki Simpanan Pokok.');
                }
                $jml_simpanan = 250000; // Force override meskipun user iseng memanipulasi inspect element HTML
            } elseif ($jenis->id == 2) { // Simpanan Wajib
                $exists = DB::table('simpanan')->where('id_anggota', $anggota->id)->where('id_jenis_simpanan', 2)->exists();
                if ($exists) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki Simpanan Wajib.');
                }
                $jml_simpanan = 20000;
            }

            // Upload Bukti Pembayaran
            $image = $request->file('bukti_pembayaran');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);

            // Buat Kode Transaksi
            $lastTransaction = DB::table('simpanan')->where('kodeTransaksiSimpanan', 'LIKE', 'SMP-%')->orderBy('id', 'desc')->first();
            $newTransactionNumber = $lastTransaction ? (int) substr($lastTransaction->kodeTransaksiSimpanan, 4) + 1 : 1;
            $kodeTransaksi = 'SMP-' . str_pad($newTransactionNumber, 4, '0', STR_PAD_LEFT);

            // Eksekusi Database
            DB::transaction(function () use ($request, $anggota, $kodeTransaksi, $jml_simpanan, $imageName) {
                DB::table('simpanan')->insert([
                    'kodeTransaksiSimpanan' => $kodeTransaksi,
                    'tanggal_simpanan'      => now()->format('Y-m-d'),
                    'id_anggota'            => $anggota->id,
                    'id_jenis_simpanan'     => $request->id_jenis_simpanan,
                    'jml_simpanan'          => $jml_simpanan,
                    'bukti_pembayaran'      => 'assets/img/' . $imageName,
                    'created_by'            => Auth::id(),
                    'updated_by'            => Auth::id(),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

                // Hitung dan Update Total Saldo Anggota
                $totalSimpanan = DB::table('simpanan')->where('id_anggota', $anggota->id)->sum('jml_simpanan');
                
                DB::table('_anggota')->where('id', $anggota->id)->update([
                    'saldo' => $totalSimpanan,
                    'status_anggota' => $totalSimpanan > 0 ? 1 : 0,
                ]);

                DB::table('total_saldo_anggota')->updateOrInsert(
                    [],
                    ['gradesaldo' => $totalSimpanan, 'updated_at' => now()]
                );
            });

            return redirect()->route('nasabah.dashboard')->with('success', 'Setoran simpanan berhasil! Saldo Anda telah bertambah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses simpanan: ' . $e->getMessage())->withInput();
        }
    }
}