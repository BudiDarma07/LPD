@extends('backend.app')
@section('title', 'Panel Nasabah')

@section('content')
<div class="container-fluid pt-4 px-4">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-6">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 border-start border-4 border-primary">
                <div class="ms-3">
                    <p class="mb-2 text-muted">Total Tabungan Saya</p>
                    <h4 class="mb-0 text-primary fw-bold">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h4>
                </div>
                <i class="fa fa-wallet fa-3x text-primary opacity-25"></i>
            </div>
        </div>

        <div class="col-sm-6 col-xl-6">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 border-start border-4 border-danger">
                <div class="ms-3">
                    <p class="mb-2 text-muted">Sisa Tagihan Pinjaman</p>
                    <h4 class="mb-0 text-danger fw-bold">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h4>
                </div>
                <i class="fa fa-file-invoice-dollar fa-3x text-danger opacity-25"></i>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 text-center">
                @if(isset($pengajuanMenunggu) && $pengajuanMenunggu)
                    <div class="alert alert-warning mb-0">
                        <i class="fa fa-clock me-2"></i> Pengajuan pinjaman Anda sedang <strong>diproses</strong> oleh Admin. Mohon menunggu.
                    </div>
                @elseif(isset($pinjamanAktif) && $pinjamanAktif)
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle me-2"></i> Anda memiliki pinjaman yang sedang berjalan. Silakan lunasi tagihan untuk mengajukan kembali.
                    </div>
                @else
                    <h5 class="mb-3">Butuh Dana Cepat?</h5>
                    <a href="{{ route('nasabah.pinjaman.create') }}" class="btn btn-primary py-2 px-4">
                        <i class="fa fa-plus-circle me-2"></i> Ajukan Pinjaman Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Riwayat Simpanan Terakhir</h6>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark bg-white">
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kode Transaksi</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSimpanan as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</td>
                            
                            <td>{{ $data->kodeTransaksiSimpanan ?? '-' }}</td>
                            
                            <td>Rp {{ number_format($data->jml_simpanan, 0, ',', '.') }}</td>
                            
                            <td><span class="badge bg-success">Berhasil</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection