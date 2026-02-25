<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countSimpanan = DB::table('_anggota')->sum('saldo');
        $countpenarikan = DB::table('penarikan')->sum('jumlah_penarikan');
        
        // PERBAIKAN: Hanya hitung pinjaman yang Disetujui (1) atau Selesai/Lunas (3)
        $countpinjaman = DB::table('pinjaman')
            ->whereIn('status_pengajuan', [1, 3])
            ->sum('jml_pinjam');
            
        $counttotalanggota = DB::table('_anggota')->get('id')->count();

        //donat chart
        // Menghitung jumlah anggota aktif dan non-aktif menggunakan Query Builder
        $aktif = DB::table('_anggota')->where('status_anggota', 1)->count();
        $nonAktif = DB::table('_anggota')->where('status_anggota', 0)->count();

        //balok chart
        // Fetch yearly data for Simpanan, Pinjaman, and Penarikan
        $years = range(2022, 2030);

        $simpananData = DB::table('simpanan')
        ->select(DB::raw('YEAR(tanggal_simpanan) as year'), DB::raw('SUM(jml_simpanan) as total'))
        ->whereIn(DB::raw('YEAR(tanggal_simpanan)'), $years)
            ->groupBy('year')
            ->pluck('total', 'year')->toArray();

        // PERBAIKAN GRAFIK: Tambahkan filter status_pengajuan untuk data chart
        $pinjamanData = DB::table('pinjaman')
        ->select(DB::raw('YEAR(tanggal_pinjam) as year'), DB::raw('SUM(jml_pinjam) as total'))
        ->whereIn('status_pengajuan', [1, 3])
        ->whereIn(DB::raw('YEAR(tanggal_pinjam)'), $years)
            ->groupBy('year')
            ->pluck('total', 'year')->toArray();

        $penarikanData = DB::table('penarikan')
        ->select(DB::raw('YEAR(tanggal_penarikan) as year'), DB::raw('SUM(jumlah_penarikan) as total'))
        ->whereIn(DB::raw('YEAR(tanggal_penarikan)'), $years)
            ->groupBy('year')
            ->pluck('total', 'year')->toArray();

        // Prepare data for the chart
        $chartData = [
            'years' => $years,
            'simpanan' => array_map(function ($year) use ($simpananData) {
                return $simpananData[$year] ?? 0;
            }, $years),
            'pinjaman' => array_map(function ($year) use ($pinjamanData) {
                return $pinjamanData[$year] ?? 0;
            }, $years),
            'penarikan' => array_map(function ($year) use ($penarikanData) {
                return $penarikanData[$year] ?? 0;
            }, $years)
        ];

        return view('backend.home.index', compact(
            'countSimpanan',
            'countpenarikan',
            'countpinjaman',
            'counttotalanggota',
            'aktif',
            'nonAktif',
            'chartData'
        ));
    }
}