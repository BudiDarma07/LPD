@extends('backend.app')
@section('title', 'Tambah Pinjaman')
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

    <h2 class="mb-4">Tambah Pinjaman</h2>
    <div class="bg-light rounded h-100 p-4">
        <div class="alert alert-info">
            <strong>Informasi:</strong> Jumlah maksimal pinjaman baru adalah Rp {{ number_format($maxPinjamanBaru, 0, ',', '.') }}.
        </div>
        <form method="POST" action="{{ route('pinjaman.store') }}">
            @csrf

            <div class="form-group">
                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" required>
                @error('tanggal_pinjam')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jml_pinjam">Jumlah Pinjam</label>
                <input type="text" class="form-control rupiah-input @error('jml_pinjam') is-invalid @enderror" id="jml_pinjam" name="jml_pinjam" value="{{ old('jml_pinjam') }}" placeholder="Contoh: Rp 500.000" required>
                @error('jml_pinjam')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jml_cicilan">Lama/bulan</label>
                <input type="number" class="form-control @error('jml_cicilan') is-invalid @enderror" id="jml_cicilan" name="jml_cicilan" value="{{ old('jml_cicilan') }}" required>
                @error('jml_cicilan')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_anggota">Anggota</label>
                <select class="form-control @error('id_anggota') is-invalid @enderror" id="id_anggota" name="id_anggota" required>
                    <option value="">Pilih Anggota</option>
                    @foreach($anggota as $member)
                    <option value="{{ $member->id }}" {{ old('id_anggota') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                    @endforeach
                </select>
                @error('id_anggota')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</div>

<script>
    var rupiahInputs = document.querySelectorAll('.rupiah-input');
    rupiahInputs.forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });
    });

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
</script>
@endsection