<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Angsuran LPD Joanyar Kelodan</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; }
        h1, h2, h4, h3, h5, p, h6 { text-align: center; margin: 0; line-height: 1.2; }
        .row { display: flex; margin-top: 10px; }
        .keclogo { font-size: 3vw; }
        .kablogo { font-size: 2vw; }
        .alamatlogo { font-size: 1.5vw; }
        .kodeposlogo { font-size: 1.7vw; }
        .garis1 { border-top: 3px solid black; height: 2px; border-bottom: 1px solid black; margin-top: 10px; margin-bottom: 10px; }
        #logo { width: 140px; height: 160px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table, .table th, .table td { border: 1px solid black; font-size: 14px; }
        .table th, .table td { padding: 10px; text-align: center; }
        #laporan-title { text-align: center; margin-top: 10px; margin-bottom: 10px; line-height: 1; }
        .info { margin-bottom: 20px; font-size: 10pt; }
        .info table { width: 100%; border: none; }
        .info th, .info td { text-align: left; padding: 5px; border: none; font-size: 14px; }
        .info th { width: 200px; }
        .total { text-align: left; font-size: 14px; margin-top: 15px; }
        .total strong { font-size: 16px; }
    </style>
</head>
<body>
    <div>
        <header>
            <table width="100%">
                <tr>
                    <td width="15%" align="center">
                        <img src="{{ public_path('assets/img/logo-lpd.png') }}" width="90%" alt="Logo LPD">
                    </td>
                    <td width="70%" align="center">
                        <h3>LAPORAN ANGSURAN PINJAMAN</h3>
                        <h4>LEMBAGA PERKREDITAN DESA ADAT JOANYAR KELODAN</h4>
                        <p class="alamatlogo">Desa Adat Joanyar Kelodan, Kec. Seririt, Kab. Buleleng</p>
                        <p class="kodeposlogo">Bali 81153</p>
                    </td>
                    <td width="15%" align="center">
                        <img src="{{ public_path('assets/img/logo-bali.png') }}" width="90%" alt="Logo Bali">
                    </td>
                </tr>
            </table>
            <hr class="garis1">
        </header><br>

        <div class="info">
            <table>
                <tr>
                    <th>NO. ANGGOTA</th>
                    <td>: {{ $anggota->anggota_id }}</td>
                </tr>
                <tr>
                    <th>NAMA ANGGOTA</th>
                    <td>: {{ $anggota->anggota_name }}</td>
                </tr>
                <tr>
                    <th>ALAMAT</th>
                    <td>: {{ $anggota->anggota_alamat }}</td>
                </tr>
                <tr>
                    <th>BESAR PINJAMAN</th>
                    <td>: Rp {{ number_format($pinjaman->jml_pinjam, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>TANGGAL PENCAIRAN</th>
                    <td>: {{ tanggal_indonesia($pinjaman->tanggal_pinjam, false) }}</td>
                </tr>
                <tr>
                    <th>TANGGAL JATUH TEMPO</th>
                    <td>: {{ tanggal_indonesia($pinjaman->jatuh_tempo, false) }}</td>
                </tr>
                <tr>
                    <th>JATUH TEMPO</th>
                    <td>: {{ $pinjaman->jml_cicilan }} Bulan</td>
                </tr>
            </table>
        </div>

        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Angsuran Ke-</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Angsuran Pokok</th>
                        <th>Bunga</th>
                        <th>Denda</th>
                        <th>Sisa Hutang Pokok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $index => $laporanItem)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ tanggal_indonesia($laporanItem->tanggal_angsuran, false) }}</td>
                        <td>Rp {{ number_format($laporanItem->jml_angsuran, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($laporanItem->bunga_pinjaman, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($laporanItem->denda, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($laporanItem->sisa_angsuran, 2, ',', '.') }}</td>
                        <td>
                            @if ($laporanItem->status_angsuran == 0)
                            <span>Belum Lunas</span>
                            @elseif ($laporanItem->status_angsuran == 1)
                            <span>Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            <p>Jumlah Total Pembayaran Angsuran: <strong>Rp {{ number_format($totalAngsuran, 2, ',', '.') }}</strong></p>
        </div>
        <br>

        <table width="100%">
            <tr>
                <td width="15%" align="center"><img src="" width="90%"></td>
                <td width="55%" align="center"><img src="" width="90%"></td>
                <td width="40%" align="center">
                    <p class="alamatlogo">Buleleng, {{ tanggal_indonesia(\Carbon\Carbon::now(), false) }}</p>
                    <p class="kodeposlogo">Kepala LPD</p>
                    <br><br><br>
                    <p class="kodeposlogo">{{ auth()->user()->name }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>