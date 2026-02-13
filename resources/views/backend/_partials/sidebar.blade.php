<style>
    /* Styling Tambahan agar Sidebar Rapi */
    .sidebar {
        background: #ffffff;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
        z-index: 1000;
    }
    .nav-item.nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 10px 20px;
        margin: 2px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }
    .nav-item.nav-link:hover, .nav-item.nav-link.active {
        background-color: #e9f2ff;
        color: #0d6efd; /* Primary Blue */
        transform: translateX(5px);
    }
    .nav-item.nav-link.active i {
        color: #0d6efd !important;
    }
    .menu-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
        margin: 20px 25px 10px;
        letter-spacing: 0.5px;
    }
</style>

<div class="sidebar pe-0 pb-3 h-100 overflow-auto bg-white">
    <nav class="navbar bg-white navbar-light p-0 d-block">
        
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center px-4 py-3 border-bottom mb-3">
            <div class="bg-primary rounded px-2 py-1 me-2">
                <i class="fa fa-landmark text-white"></i>
            </div>
            <h5 class="m-0 text-primary fw-bold">LPD SYSTEM</h5>
        </a>

        <div class="d-flex align-items-center px-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle shadow-sm" 
                     src="{{ asset('assets/backend/img/' . (auth()->user()->image ?? 'user.jpg')) }}" 
                     alt="" style="width: 40px; height: 40px; object-fit: cover;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3 overflow-hidden">
                <h6 class="mb-0 fw-bold text-dark">{{ auth()->user()->name }}</h6>
                <small class="text-muted">{{ auth()->user()->roles->pluck('name')->first() }}</small>
            </div>
        </div>

        <div class="navbar-nav w-100">
            
            {{-- ======================================================== --}}
            {{-- MENU KHUSUS ADMIN & PETUGAS                              --}}
            {{-- ======================================================== --}}
            @hasanyrole('Admin|Petugas')
                <div class="menu-label">Menu Admin</div>

                <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa fa-tachometer-alt me-2" style="width: 20px;"></i> Dashboard
                </a>
                
                <a href="{{ route('user') }}" class="nav-item nav-link {{ request()->routeIs('user*') ? 'active' : '' }}">
                    <i class="fa fa-users-cog me-2" style="width: 20px;"></i> Data User
                </a>

                <a href="{{ route('nasabah') }}" class="nav-item nav-link {{ request()->routeIs('nasabah') ? 'active' : '' }}">
                    <i class="fa fa-user-friends me-2" style="width: 20px;"></i> Data Nasabah
                </a>

                <div class="menu-label">Transaksi</div>
                <a href="{{ route('simpanan') }}" class="nav-item nav-link {{ request()->routeIs('simpanan*') ? 'active' : '' }}">
                    <i class="fa fa-save me-2" style="width: 20px;"></i> Simpanan
                </a>
                <a href="{{ route('pinjaman') }}" class="nav-item nav-link {{ request()->routeIs('pinjaman*') ? 'active' : '' }}">
                    <i class="fa fa-hand-holding-usd me-2" style="width: 20px;"></i> Pinjaman
                </a>
                <a href="{{ route('angsuran') }}" class="nav-item nav-link {{ request()->routeIs('angsuran*') ? 'active' : '' }}">
                    <i class="fa fa-file-invoice-dollar me-2" style="width: 20px;"></i> Angsuran
                </a>
                <a href="{{ route('penarikan') }}" class="nav-item nav-link {{ request()->routeIs('penarikan*') ? 'active' : '' }}">
                    <i class="fa fa-money-bill-wave me-2" style="width: 20px;"></i> Penarikan
                </a>

                <div class="menu-label">Laporan</div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle nav-item" data-bs-toggle="dropdown">
                        <i class="far fa-file-alt me-2" style="width: 20px;"></i> Laporan
                    </a>
                    <div class="dropdown-menu bg-white border-0 ms-4 shadow-sm">
                        <a href="{{route('laporanSimpanan')}}" class="dropdown-item py-2">Lap. Simpanan</a>
                        <a href="{{route('laporanPinjaman')}}" class="dropdown-item py-2">Lap. Pinjaman</a>
                        <a href="{{route('laporanPenarikan')}}" class="dropdown-item py-2">Lap. Penarikan</a>
                    </div>
                </div>

                <div class="menu-label">Pengaturan</div>
                <a href="{{ url('show-roles') }}" class="nav-item nav-link {{ request()->is('show-roles*') ? 'active' : '' }}">
                    <i class="fa fa-user-shield me-2" style="width: 20px;"></i> Manajemen Role
                </a>
            @endhasanyrole


            {{-- ======================================================== --}}
            {{-- MENU KHUSUS NASABAH (Sesuai Permintaan Anda)             --}}
            {{-- ======================================================== --}}
            @role('Nasabah')
                <div class="menu-label text-primary">Panel Nasabah</div>
                
                <a href="{{ route('nasabah.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('nasabah.dashboard') ? 'active' : '' }}">
                    <i class="fa fa-wallet me-2" style="width: 20px;"></i> Keuangan Saya
                </a>
                
                <a href="{{ route('nasabah.pinjaman.create') }}" class="nav-item nav-link {{ request()->routeIs('nasabah.pinjaman.create') ? 'active' : '' }}">
                    <i class="fa fa-hand-holding-usd me-2" style="width: 20px;"></i> Ajukan Pinjaman
                </a>

                <div class="alert alert-info m-3 p-2 small">
                    <i class="fa fa-info-circle me-1"></i>
                    Status Akun: <strong>Aktif</strong><br>
                    Role: Nasabah
                </div>
            @endrole

        </div>
    </nav>
</div>