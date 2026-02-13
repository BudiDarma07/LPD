@extends('backend.app')

@section('title', 'Pinjaman')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h2 class="mb-4">Data Pinjaman</h2>

    @if(Session::has('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
        <h5 class="alert-heading"><i class="icon fas fa-check-circle"></i> Sukses!</h5>
        {{ Session('success') }}
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
            <div class="mb-3 d-flex justify-content-between flex-wrap gap-2">
                
                <div class="d-flex">
                    @can('pinjaman-create')
                    <button type="button" class="btn btn-outline-primary rounded-pill me-3" data-bs-toggle="modal" data-bs-target="#buatPinjaman">
                        <i class="fas fa-dollar-sign"></i> Tambah
                    </button>
                    @endcan
                    
                    {{-- Pastikan file modal ini tidak memiliki ID element yang bentrok --}}
                    @include('backend.pinjaman.modal.modalCreate')
                    @include('backend.pinjaman.modal.modalEdit')
                </div>

                <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                    
                    <form id="reportForm" action="{{ route('pinjaman') }}" method="GET" class="d-flex align-items-center me-2">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <span class="me-2">Report</span>
                        <input type="date" name="start_date" class="form-control me-2" value="{{ request()->get('start_date') }}" onchange="document.getElementById('reportForm').submit()">
                        <span class="me-2">To</span>
                        <input type="date" name="end_date" class="form-control me-2" value="{{ request()->get('end_date') }}" onchange="document.getElementById('reportForm').submit()">
                    </form>

                    @can('laporan_pinjaman')
                    <a href="{{ route('pinjaman.cetak', ['start_date' => request()->get('start_date'), 'end_date' => request()->get('end_date'), 'search' => request()->get('search')]) }}" class="btn btn-primary me-2" target="_blank">
                        <i class="fas fa-print"></i>
                    </a>
                    @endcan

                    <form id="searchForm" action="{{ route('pinjaman') }}" method="GET" class="input-group" style="max-width: 250px;">
                        @if(request('start_date'))
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        @endif
                        @if(request('end_date'))
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @endif
                        <input id="search-focus" type="search" name="search" class="form-control" placeholder="Search" value="{{ request()->get('search') }}" />
                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Kode Pinjam</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nasabah</th>
                        <th scope="col">Jumlah Dipinjam</th>
                        <th scope="col">Durasi</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $pinjam)
                    <tr>
                        <td>{{ $pinjam->kodeTransaksiPinjaman }}</td>
                        <td>{{ tanggal_indonesia($pinjam->tanggal_pinjam, false) }}</td>
                        <td>{{ $pinjam->anggota_name }}</td>
                        <td>Rp {{ number_format($pinjam->jml_pinjam, 2, ',', '.') }}</td>
                        <td>{{ $pinjam->jml_cicilan }} Bulan</td>
                        <td>
                            @if ($pinjam->status_pengajuan == 0)
                            <span class="text-primary">Dibuat</span>
                            @elseif ($pinjam->status_pengajuan == 1)
                            <span class="text-success">Disetujui</span>
                            @elseif ($pinjam->status_pengajuan == 3)
                            <span class="text-info">Selesai</span>
                            @else
                            <span class="text-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @can('pinjaman-edit')
                            <button type="button" class="btn btn-outline-warning btn-sm m-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editPinjaman" 
                                data-id="{{ $pinjam->pinjaman_id }}" 
                                data-tanggal_pinjam="{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('Y-m-d') }}" 
                                data-jml_pinjam="{{ $pinjam->jml_pinjam }}" 
                                data-jml_cicilan="{{ $pinjam->jml_cicilan }}" 
                                data-jatuh_tempo="{{ \Carbon\Carbon::parse($pinjam->jatuh_tempo)->format('Y-m-d') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endcan
                            
                            @can('pinjaman-detail')
                            <a href="{{ route('pinjaman.show', $pinjam->pinjaman_id) }}" class="btn btn-outline-info btn-sm m-1" title="Show">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endcan
                            
                            @can('laporan_angsuran')
                            <a href="{{ route('laporan.angsuran', $pinjam->pinjaman_id) }}" class="btn btn-outline-primary btn-sm m-1" title="cetak">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                            @endcan
                            
                            @can('pinjaman-delete')
                            <form action="{{ route('pinjaman.destroy', $pinjam->pinjaman_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm m-1" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($pinjaman->isEmpty())
            <p class="text-center mt-4">Tidak Ada Transaksi Pinjaman</p>
            @endif

            <div class="d-flex justify-content-end mt-3">
                {{ $pinjaman->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Auto Close Alerts
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                // Pastikan bootstrap sudah terload
                if(typeof bootstrap !== 'undefined'){
                    new bootstrap.Alert(alert).close();
                }
            });
        }, 5000);

        // 2. Animasi Alert
        const alerts = document.querySelectorAll(".custom-alert");
        alerts.forEach((alert, index) => {
             // Menggunakan CSS transition atau library jQuery jika perlu, 
             // tapi vanilla JS sederhana cukup dengan opacity jika CSS mendukung
             alert.style.opacity = 1; 
        });

        // 3. Hitung Jatuh Tempo
        // PENTING: ID 'tanggal_pinjam', 'jml_cicilan', dan 'jatuh_tempo'
        // harus unik. Jika modal create dan edit pakai ID yang sama,
        // kode di bawah ini hanya akan jalan di salah satu modal.
        // Solusi terbaik: Gunakan class atau ID berbeda untuk modal create vs edit.
        
        const inputs = [
            { tgl: 'tanggal_pinjam', cicil: 'jml_cicilan', tempo: 'jatuh_tempo' }
            // Jika Anda punya ID berbeda untuk edit, tambahkan disini, misal:
            // { tgl: 'edit_tanggal_pinjam', cicil: 'edit_jml_cicilan', tempo: 'edit_jatuh_tempo' }
        ];

        inputs.forEach(ids => {
            const tglInput = document.getElementById(ids.tgl);
            const cicilInput = document.getElementById(ids.cicil);
            const tempoInput = document.getElementById(ids.tempo);

            if (tglInput && cicilInput && tempoInput) {
                function updateJatuhTempo() {
                    const tglVal = tglInput.value;
                    const cicilVal = parseInt(cicilInput.value);

                    if (tglVal && cicilVal) {
                        const d = new Date(tglVal);
                        d.setMonth(d.getMonth() + cicilVal);

                        const year = d.getFullYear();
                        const month = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        
                        tempoInput.value = `${year}-${month}-${day}`;
                    }
                }

                tglInput.addEventListener('change', updateJatuhTempo);
                cicilInput.addEventListener('input', updateJatuhTempo);
            }
        });
    });
</script>
@endsection