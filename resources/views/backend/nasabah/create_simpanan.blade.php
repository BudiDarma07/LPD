@extends('backend.app')
@section('title', 'Setor Simpanan')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-xl-8">
            <div class="bg-light rounded h-100 p-4 shadow-sm">
                
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-primary"><i class="fa fa-wallet me-2"></i>Form Setor Simpanan</h5>
                    <a href="{{ route('nasabah.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3 mb-4" role="alert">
                        <strong><i class="fa fa-exclamation-circle me-2"></i> Gagal!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('nasabah.simpanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="id_jenis_simpanan" class="form-label fw-bold">Jenis Simpanan</label>
                        <select class="form-select @error('id_jenis_simpanan') is-invalid @enderror" 
                            name="id_jenis_simpanan" id="id_jenis_simpanan" required>
                            <option value="" selected disabled>-- Pilih Jenis Simpanan --</option>
                            @foreach($jenisSimpanan as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis_simpanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jml_simpanan" class="form-label fw-bold">Jumlah Setoran (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('jml_simpanan') is-invalid @enderror" 
                                id="jml_simpanan" name="jml_simpanan" placeholder="Contoh: 50000" min="10000" required>
                        </div>
                        <div class="form-text text-muted" id="simpanan-hint">
                            Silakan isi nominal. Jika Simpanan Pokok/Wajib, nominal akan terisi otomatis.
                        </div>
                        @error('jml_simpanan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="form-label fw-bold">Bukti Transfer/Pembayaran</label>
                        <input class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                            type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
                        <div class="form-text text-muted">Format file: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success py-2 text-uppercase fw-bold">
                            <i class="fa fa-upload me-2"></i> Kirim Setoran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill nominal sesuai jenis simpanan (meniru rule Admin)
document.getElementById('id_jenis_simpanan').addEventListener('change', function() {
    let type = this.options[this.selectedIndex].text.toLowerCase();
    let nominalInput = document.getElementById('jml_simpanan');
    let hint = document.getElementById('simpanan-hint');
    
    if(type.includes('pokok')) {
        nominalInput.value = 250000;
        nominalInput.readOnly = true;
        hint.innerText = "Nominal Simpanan Pokok telah ditentukan (Rp 250.000).";
    } else if(type.includes('wajib')) {
        nominalInput.value = 20000;
        nominalInput.readOnly = true;
        hint.innerText = "Nominal Simpanan Wajib telah ditentukan (Rp 20.000).";
    } else {
        nominalInput.value = '';
        nominalInput.readOnly = false;
        hint.innerText = "Silakan isi nominal untuk jenis simpanan ini.";
    }
});
</script>
@endsection