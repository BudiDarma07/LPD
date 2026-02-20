@extends('backend.app')
@section('title', 'Ajukan Pinjaman')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-xl-8">
            <div class="bg-light rounded h-100 p-4 shadow-sm">
                
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-primary"><i class="fa fa-hand-holding-usd me-2"></i>Form Pengajuan Pinjaman</h5>
                    <a href="{{ route('nasabah.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('nasabah.pinjaman.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="jumlah_pinjaman" class="form-label fw-bold">Jumlah Pinjaman (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('jumlah_pinjaman') is-invalid @enderror" 
                                id="jumlah_pinjaman" name="jumlah_pinjaman" 
                                placeholder="Contoh: 200000" min="200000" max="{{ $totalSimpanan }}" required>
                        </div>
                        <div class="form-text text-muted">
                            Minimal pengajuan Rp 200.000. Maksimal pengajuan Rp {{ number_format($totalSimpanan, 0, ',', '.') }}.
                        </div>
                        @error('jumlah_pinjaman')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lama_angsuran" class="form-label fw-bold">Jangka Waktu (Bulan)</label>
                        <select class="form-select @error('lama_angsuran') is-invalid @enderror" 
                            name="lama_angsuran" id="lama_angsuran" required>
                            <option value="" selected disabled>-- Pilih Jangka Waktu --</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                            <option value="24">24 Bulan</option>
                            <option value="36">36 Bulan</option>
                        </select>
                        @error('lama_angsuran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="form-label fw-bold">Keperluan Pinjaman</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                            name="keterangan" id="keterangan" rows="3" 
                            placeholder="Contoh: Untuk tambahan modal usaha warung sembako" required></textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 text-uppercase fw-bold">
                            <i class="fa fa-paper-plane me-2"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection