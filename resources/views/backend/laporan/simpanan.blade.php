<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Simpanan LPD Joanyar Kelodan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
        }

        h1, h2, h4, h3, h5, p, h6 {
            text-align: center;
            margin: 0;
            line-height: 1.2;
        }

        .row {
            display: flex;
            margin-top: 10px;
        }

        .keclogo {
            font-size: 3vw;
        }

        .kablogo {
            font-size: 2vw;
        }

        .alamatlogo {
            font-size: 1.5vw;
        }

        .kodeposlogo {
            font-size: 1.7vw;
        }

        .garis1 {
            border-top: 3px solid black;
            height: 2px;
            border-bottom: 1px solid black;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        #logo {
            width: 140px;
            height: 160px;
        }

        #tls {
            text-align: right;
        }

        .alamat-tujuan {
            margin-left: 50%;
        }

        #tempat-tgl {
            margin-left: 120px;
        }

        #camat {
            text-align: center;
        }

        #nama-camat {
            margin-top: 50px;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
        }

        #laporan-title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
            line-height: 1;
        }
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
                        <h3>LAPORAN SIMPANAN</h3>
                        <h4>LEMBAGA PERKREDITAN DESA ADAT JOANYAR KELODAN</h4>
                        <p class="alamatlogo">Desa Adat Joanyar Kelodan, Kec. Seririt, Kab. Buleleng</p>
                        <p class="kodeposlogo">Bali 81153</p>
                    </td>
                    <td width="15%" align="center">
    <img src="{{ public_path('assets/img/logo-bali.png') }}" width="90%" alt="Logo LPD">
</td>
                </tr>
            </table>
            <hr class="garis1">
        </header><br>
        
        <div id="laporan-title">
            <h4>Laporan Simpanan</h4>
            <p>Periode: {{ tanggal_indonesia($startDate, false) }} - {{ tanggal_indonesia($endDate, false) }}</p>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Kode Transaksi</th>
                    <th>Transaksi</th>
                    <th>Jenis Simpanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($simpanan as $tabungan)
                <tr>
                    <td>{{ tanggal_indonesia($tabungan->tanggal_simpanan, false) }}</td>
                    <td>{{ $tabungan->anggota_name }}</td>
                    <td>{{ $tabungan->kodeTransaksiSimpanan }}</td>
                    <td>Rp {{ number_format($tabungan->jml_simpanan, 2, ',', '.') }}</td>
                    <td>{{ $tabungan->jenis_simpanan_nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table><br>
        
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