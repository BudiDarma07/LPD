@extends('backend.app')
@section('title', 'Tambah Simpanan')
@section('content')
<div class="container-fluid pt-4 px-4">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h2 class="mb-4">Tambah Simpanan</h2>
    <div class="bg-light rounded h-100 p-4">
        <form method="POST" action="{{ route('simpanan.store') }}" enctype="multipart/form-data" id="formSimpanan">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="kodeTransaksiSimpanan" name="kodeTransaksiSimpanan" value="{{ $kodeTransaksiSimpanan }}" readonly>
                <label for="kodeTransaksiSimpanan">Kode Transaksi</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" value="{{ old('tanggal_simpanan') }}" id="tanggal_simpanan" name="tanggal_simpanan">
                <label for="tanggal_simpanan">Tanggal Simpanan</label>
            </div>
            <div class="form-floating mb-3">
                <select id="id_anggota" name="id_anggota" class="form-select">
                    <option value="" selected disabled>Pilih Anggota</option>
                    @foreach($namaNasabah as $nasabah)
                    <option value="{{ $nasabah->id }}" {{ old('id_anggota') == $nasabah->id ? 'selected' : '' }}>{{ $nasabah->name }}</option>
                    @endforeach
                </select>
                <label for="id_anggota">Nama Anggota</label>
            </div>
            <div class="form-floating mb-3">
                <select id="id_jenis_simpanan" name="id_jenis_simpanan" class="form-select">
                    <option value="" selected disabled>Pilih Jenis Simpanan</option>
                    @foreach($jenisSimpanan as $jenis)
                    <option value="{{ $jenis->id }}" data-nominal="{{ $jenis->id == 1 ? 250000 : ($jenis->id == 2 ? 20000 : 0) }}" {{ old('id_jenis_simpanan') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                <label for="id_jenis_simpanan">Jenis Simpanan</label>

            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rupiah-input" value="{{ old('jml_simpanan') }}" id="jml_simpanan" name="jml_simpanan">
                <label for="jml_simpanan">Jumlah Simpanan</label>
            </div>
            <div class="mb-3">
                <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                <input class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" type="file" accept="image/*,application/pdf">
            </div>
            <button type="submit" class="btn btn-outline-primary m-2">Submit</button>
        </form>
    </div>
</div>

<script>
    // FUNGSI FORMAT RUPIAH
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split         = number_string.split(','),
            sisa          = split[0].length % 3,
            rupiah        = split[0].substr(0, sisa),
            ribuan        = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // EVENT KETIK RUPIAH
    var rupiahInputs = document.querySelectorAll('.rupiah-input');
    rupiahInputs.forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var jenisSimpanan = document.getElementById('id_jenis_simpanan');
        var jmlSimpananInput = document.getElementById('jml_simpanan');

        // Fungsi pembantu untuk set nilai
        function adjustNominal() {
            var selectedOption = jenisSimpanan.options[jenisSimpanan.selectedIndex];
            if (selectedOption) {
                var nominalDefault = selectedOption.getAttribute('data-nominal');
                if(nominalDefault !== '0') {
                    // Masukkan dengan format rupiah jika ada nilainya
                    jmlSimpananInput.value = formatRupiah(nominalDefault, 'Rp ');
                    jmlSimpananInput.readOnly = true;
                } else {
                    jmlSimpananInput.value = '';
                    jmlSimpananInput.readOnly = false;
                }
            }
        }

        // Cek nilai awal saat halaman dimuat
        adjustNominal();

        // Event untuk mengatur nilai saat pilihan jenis simpanan berubah
        jenisSimpanan.addEventListener('change', adjustNominal);
    });
</script>
@endsection