<div class="modal fade" id="buatPinjaman" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Buat Pinjaman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card text-center">
                    <div class="card-header">
                        Informasi
                    </div>
                    <div class="card-body">
                        Jumlah maksimal pinjaman baru adalah Rp {{ number_format($maxPinjamanBaru, 0, ',', '.') }}.
                    </div>
                </div><br>
                <form method="POST" action="{{ route('pinjaman.store') }}" enctype="multipart/form-data">
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
                        <label for="jml_cicilan">Lama/bulan</label>
                        <input type="number" class="form-control @error('jml_cicilan') is-invalid @enderror" id="jml_cicilan" name="jml_cicilan" value="{{ old('jml_cicilan') }}" required>
                        @error('jml_cicilan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jatuh_tempo">Jatuh Tempo</label>
                        <input type="text" class="form-control @error('jatuh_tempo') is-invalid @enderror" id="jatuh_tempo" name="jatuh_tempo" value="{{ old('jatuh_tempo') }}" readonly>
                        @error('jatuh_tempo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bunga_pinjam">Bunga Pinjam (%)</label>
                        <input type="number" class="form-control @error('bunga_pinjam') is-invalid @enderror" id="bunga_pinjam" name="bunga_pinjam" value="{{ old('bunga_pinjam') }}" required>
                        @error('bunga_pinjam')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jml_pinjam">Jumlah Pinjam</label>
                        <input type="text" class="form-control rupiah-input @error('jml_pinjam') is-invalid @enderror" id="jml_pinjam" name="jml_pinjam" value="{{ old('jml_pinjam') }}" required>
                        @error('jml_pinjam')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_anggota">Anggota</label>
                        <select class="form-select @error('id_anggota') is-invalid @enderror" id="id_anggota" name="id_anggota" required>
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
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var rupiahInputsModal = document.querySelectorAll('.rupiah-input');
    rupiahInputsModal.forEach(function(input) {
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