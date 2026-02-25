<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pinjaman LPD Joanyar Kelodan</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; }
        h1, h2, h4, h3, h5, p, h6 { text-align: center; margin: 0; line-height: 1.2; }
        .row { display: flex; margin-top: 10px; }
        .keclogo { font-size: 3vw; }
        .kablogo { font-size: 2vw; }
        .alamatlogo { font-size: 1.5vw; }
        .kodeposlogo { font-size: 1.7vw; }
        .garis1 { border-top: 3px solid black; height: 2px; border-bottom: 1px solid black; margin-top: 10px; margin-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table, .table th, .table td { border: 1px solid black; }
        .table th, .table td { padding: 8px; text-align: left; font-size: 14px; }
        #laporan-title { text-align: center; margin-top: 10px; margin-bottom: 15px; }
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
                        <h3>LAPORAN DATA PINJAMAN KREDIT</h3>
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
        </header>

        <div id="laporan-title">
            <h4>Laporan Rekapitulasi Pinjaman</h4>
            @if($startDate && $endDate)
                <p>Periode: {{ tanggal_indonesia($startDate, false) }} - {{ tanggal_indonesia($endDate, false) }}</p>
            @else
                <p>Semua Periode</p>
            @endif
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th>Tanggal Pinjam</th>
                    <th>Nama Nasabah</th>
                    <th>Kode Transaksi</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Tenor</th>
                    <th>Bunga</th>
                    <th>Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjaman as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ tanggal_indonesia($item->tanggal_pinjam, false) }}</td>
                    <td>{{ $item->anggota_name }}</td>
                    <td>{{ $item->kodeTransaksiPinjaman }}</td>
                    <td>Rp {{ number_format($item->jml_pinjam, 2, ',', '.') }}</td>
                    <td>{{ $item->jml_cicilan }} Bulan</td>
                    <td>{{ $item->bunga_pinjam }} %</td>
                    <td>{{ tanggal_indonesia($item->jatuh_tempo, false) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>

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