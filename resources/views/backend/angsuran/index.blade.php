@extends('backend.app')

@section('title', 'Data Angsuran')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h2 class="mb-4">Data Angsuran</h2>

    @if(Session::has('message'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
        <h5 class="alert-heading"><i class="icon fas fa-check-circle"></i> Sukses!</h5>
        {{ Session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(Session::has('error'))
    <div id="errorAlert" class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
        <h5 class="alert-heading"><i class="icon fas fa-times-circle"></i> Error!</h5>
        {{ Session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="bg-light rounded h-100 p-4">
        <div class="table-responsive">
            <div class="mb-3 d-flex justify-content-between">
                
                <div class="d-flex align-items-center ms-2">
                    <span class="me-2">Report</span>
                    <form id="reportForm" action="{{ route('angsuran') }}" method="GET" class="d-flex align-items-center">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ request()->get('start_date') }}" onchange="document.getElementById('reportForm').submit()">
                        <span class="me-2">To</span>
                        <input type="date" name="end_date" class="form-control me-2" value="{{ request()->get('end_date') }}" onchange="document.getElementById('reportForm').submit()">
                    </form>
                    </div>

                <div class="d-flex align-items-center ms-2">
                    <form action="{{ route('angsuran') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Kode Transaksi/Nama" value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Kode Angsuran</th>
                        <th scope="col">Cicilan ke</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nasabah</th>
                        <th scope="col">Pinjaman Pokok</th>
                        <th scope="col">Jumlah Angsuran</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($angsuran as $angs)
                    <tr>
                        <td>{{ $angs->kode_transaksi_angsuran }}</td>
                        <td>{{ $angs->angsuran_ke }}</td>
                        <td>{{ \Carbon\Carbon::parse($angs->tanggal_angsuran)->format('d-m-Y') }}</td>
                        <td>{{ $angs->nasabah }}</td>
                        <td>Rp {{ number_format($angs->pinjaman_pokok, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($angs->jml_angsuran, 0, ',', '.') }}</td>
                        <td>
                            @if ($angs->status == 0)
                            <span class="text-warning fw-bold">Belum Lunas</span>
                            @elseif ($angs->status == 1)
                            <span class="text-success fw-bold">Lunas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('laporan.angsuran', $angs->id_pinjaman) }}" class="btn btn-outline-primary btn-sm" title="Download Laporan PDF" target="_blank">
                                <i class="fas fa-download"></i> PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($angsuran->isEmpty())
            <p class="text-center text-muted mt-3">Tidak Ada Data Angsuran</p>
            @endif

            <div class="d-flex justify-content-end mt-3">
                {{ $angsuran->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Menutup alert secara otomatis setelah 5 detik
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            if(typeof bootstrap !== 'undefined'){
                new bootstrap.Alert(alert).close();
            }
        });
    }, 5000); // 5000 milidetik = 5 detik

    // Membuat animasi alert muncul di depan tabel
    $(document).ready(function() {
        $(".custom-alert").each(function(index) {
            $(this).delay(300 * index).fadeIn("slow");
        });
    });
</script>
@endsection