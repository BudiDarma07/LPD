<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>

    <div class="navbar-nav align-items-center ms-auto">
        
        @role('Admin')
            @php
                // Mengambil data pinjaman yang berstatus 0 (Menunggu Persetujuan)
                $notifPinjaman = \Illuminate\Support\Facades\DB::table('pinjaman')
                    ->join('_anggota', '_anggota.id', '=', 'pinjaman.id_anggota')
                    ->select('pinjaman.id', 'pinjaman.kodeTransaksiPinjaman', '_anggota.name', 'pinjaman.created_at')
                    ->where('pinjaman.status_pengajuan', 0)
                    ->orderBy('pinjaman.id', 'desc')
                    ->get();
                    
                $countNotif = $notifPinjaman->count();
            @endphp

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle position-relative" data-bs-toggle="dropdown">
                    <i class="fa fa-bell me-lg-2"></i>
                    @if($countNotif > 0)
                        <span class="badge bg-danger rounded-circle position-absolute" style="top: 5px; right: 10px; font-size: 0.65rem; padding: 0.25em 0.4em;">
                            {{ $countNotif }}
                        </span>
                    @endif
                    <span class="d-none d-lg-inline-flex"></span>
                </a>
                
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 shadow" style="min-width: 250px;">
                    @if($countNotif > 0)
                        <h6 class="dropdown-header text-primary fw-bold">Ada {{ $countNotif }} Pengajuan Baru</h6>
                        <hr class="dropdown-divider">
                        
                        {{-- Tampilkan maksimal 5 notifikasi terbaru di dropdown --}}
                        @foreach($notifPinjaman->take(5) as $notif)
                            <a href="{{ route('pinjaman.show', $notif->id) }}" class="dropdown-item py-2">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">{{ $notif->name }}</h6>
                                        <small class="text-muted">{{ $notif->kodeTransaksiPinjaman }}</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                        @endforeach
                        
                        <a href="{{ route('pinjaman') }}" class="dropdown-item text-center fw-bold text-primary">Lihat Semua Pengajuan</a>
                    @else
                        <a href="#" class="dropdown-item text-center text-muted py-3">Tidak ada pengajuan baru</a>
                    @endif
                </div>
            </div>
        @endrole
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="{{ asset('assets/backend/img/' . auth()->user()->image) }}" alt="" style="width: 40px; height: 40px;">
                <span class="d-none d-lg-inline-flex">{{ auth()->user()->name}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();" class="nav-link">
                    <p class="mb-0">
                        Logout
                    </p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

    </div>
</nav>