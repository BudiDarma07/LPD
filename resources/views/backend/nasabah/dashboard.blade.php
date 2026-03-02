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
    
    @if(session('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fa fa-info-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-6">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 border-start border-4 border-primary shadow-sm">
                <div class="ms-3">
                    <p class="mb-2 text-muted fw-bold">Total Tabungan Saya</p>
                    <h4 class="mb-0 text-primary fw-bold">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h4>
                </div>
                <i class="fa fa-wallet fa-3x text-primary opacity-25"></i>
            </div>
        </div>

        <div class="col-sm-6 col-xl-6">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 border-start border-4 border-danger shadow-sm">
                <div class="ms-3">
                    <p class="mb-2 text-muted fw-bold">Sisa Tagihan Pinjaman</p>
                    <h4 class="mb-0 text-danger fw-bold">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h4>
                    @if(isset($pinjamanAktif) && $pinjamanAktif)
                        <small class="text-secondary">Pokok Awal: Rp {{ number_format($pinjamanAktif->jml_pinjam, 0, ',', '.') }}</small>
                    @endif
                </div>
                <i class="fa fa-file-invoice-dollar fa-3x text-danger opacity-25"></i>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 text-center shadow-sm">
                
                {{-- Pemberitahuan Jika Ada Pengajuan Menunggu --}}
                @if(isset($pengajuanMenunggu) && $pengajuanMenunggu)
                    <div class="alert alert-warning mb-4 text-start">
                        <i class="fa fa-clock me-2"></i> Pengajuan pinjaman Anda sebelumnya saat ini sedang <strong>diproses</strong> oleh Admin/Ketua LPD. Mohon menunggu persetujuan.
                    </div>
                @endif

                {{-- Pemberitahuan Jika Ada Pinjaman Berjalan --}}
                @if(isset($pinjamanAktif) && $pinjamanAktif)
                    <div class="alert alert-info mb-4 text-start">
                        <i class="fa fa-info-circle me-2"></i> Anda memiliki pinjaman yang sedang berjalan.
                    </div>
                @endif
                    
                {{-- Blok Peringatan Jika Pinjaman Terakhir Ditolak --}}
                @if(isset($pinjamanDitolak) && $pinjamanDitolak)
                    <div class="alert alert-danger text-start mb-4">
                        <h6 class="alert-heading fw-bold mb-2"><i class="fa fa-times-circle me-2"></i>Pengajuan Pinjaman Sebelumnya Ditolak</h6>
                        <p class="mb-1 text-dark"><strong>Kode Pengajuan:</strong> {{ $pinjamanDitolak->kodeTransaksiPinjaman }}</p>
                        <p class="mb-0 text-dark"><strong>Alasan:</strong> 
                            @php
                                $catatan = json_decode($pinjamanDitolak->keterangan_ditolak_pengajuan, true);
                            @endphp
                            
                            @if(is_array($catatan) && count($catatan) > 0)
                                {{ end($catatan) }}
                            @else
                                {{ $pinjamanDitolak->keterangan_ditolak_pengajuan ?: 'Tidak ada keterangan spesifik dari Admin.' }}
                            @endif
                        </p>
                    </div>
                @endif

                {{-- Tombol Layanan Nasabah Selalu Muncul --}}
                <h5 class="mb-3">Layanan Nasabah</h5>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('nasabah.simpanan.create') }}" class="btn btn-success py-2 px-4 fw-bold">
                        <i class="fa fa-wallet me-2"></i> Setor Simpanan (Nabung)
                    </a>
                    
                    <a href="{{ route('nasabah.pinjaman.create') }}" class="btn btn-primary py-2 px-4 fw-bold">
                        <i class="fa fa-hand-holding-usd me-2"></i> Ajukan Pinjaman
                    </a>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4 shadow-sm">
            <h6 class="mb-4"><i class="fa fa-history me-2"></i>Riwayat Simpanan Terakhir</h6>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead class="table-primary">
                        <tr class="text-dark">
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
                            <td><span class="text-primary fw-bold">{{ $data->kodeTransaksiSimpanan ?? '-' }}</span></td>
                            <td class="text-success fw-bold">+ Rp {{ number_format($data->jml_simpanan, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success">Berhasil</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-2x mb-2 opacity-50 d-block"></i>
                                Belum ada riwayat transaksi simpanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection