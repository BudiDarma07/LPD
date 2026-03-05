<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LPD Desa Adat Joanyar Kelodan - Solusi Keuangan Krama Desa</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1537953773345-d172ccf13cf1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center/cover no-repeat;
            color: white;
            padding: 120px 0;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .feature-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background-color: #e9ecef;
            color: #0d6efd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 20px;
        }
        .section-title {
            position: relative;
            margin-bottom: 50px;
            font-weight: 700;
        }
        .section-title::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #0d6efd;
        }
        .footer-custom {
            background-color: #212529;
            color: #adb5bd;
            padding: 40px 0 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/logo-lpd.png') }}" alt="Logo" height="40" class="me-2" onerror="this.style.display='none'">
                <span class="fw-bold text-primary">LPD Joanyar Kelodan</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-medium">
                    <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                </ul>
                <div class="ms-lg-4 mt-3 mt-lg-0 d-flex gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-primary px-4">Ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary px-4">Daftar Nasabah</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1>
                        <span class="text-warning">Selamat Datang di</span><br>
                        <span class="text-warning">LPD Desa Adat Joanyar Kelodan</span>
                    </h1>
                    <p class="lead mb-4">Lembaga keuangan mandiri milik Krama Desa Adat Joanyar Kelodan. Solusi permodalan dan investasi yang aman, berbasis hukum adat, untuk memajukan kesejahteraan bersama.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-2">Buka Rekening Sekarang</a>
                        @endif
                        <a href="#keunggulan" class="btn btn-outline-light btn-lg px-4 py-2">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0 d-none d-lg-block text-center">
                    <img src="{{ asset('assets/img/logo-lpd.png') }}" alt="Logo LPD Joanyar Kelodan" class="img-fluid" style="max-height: 350px;">
                </div>
            </div>
        </div>
    </section>

    <section id="keunggulan" class="py-5 bg-white">
        <div class="container py-4">
            <h2 class="text-center section-title">Mengapa Memilih Kami?</h2>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="feature-box text-center border">
                        <div class="icon-circle mx-auto">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold">Aman & Terpercaya</h4>
                        <p class="text-muted">Diatur berdasarkan Perda dan Pararem Desa Adat Joanyar Kelodan. Keamanan dana Krama adalah prioritas utama kami.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center border">
                        <div class="icon-circle mx-auto">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="fw-bold">Bunga Kompetitif</h4>
                        <p class="text-muted">Dapatkan suku bunga tabungan dan deposito yang menguntungkan, serta pinjaman dengan proses yang cepat dan bersaing.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center border">
                        <div class="icon-circle mx-auto">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4 class="fw-bold">Membangun Desa</h4>
                        <p class="text-muted">Sebagian keuntungan LPD dikembalikan kepada Desa Adat untuk mendukung pembangunan Pura dan pemberdayaan Krama.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-5 bg-light">
        <div class="container py-4">
            <h2 class="text-center section-title">Layanan Utama Kami</h2>
            
            <div class="row g-4 align-items-center mb-5 mt-4">
                <div class="col-md-6 order-2 order-md-1">
                    <h3 class="fw-bold">Simpanan</h3>
                    <p class="text-muted mt-3">Mulai langkah investasi Anda yang aman. Kami menyediakan berbagai produk simpanan yang dirancang khusus untuk memenuhi kebutuhan dan rencana masa depan Krama.</p>
                    <ul class="list-unstyled text-muted mt-3">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Tabungan tanpa biaya administrasi bulanan</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Dapat dipantau secara real-time melalui panel nasabah</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Bunga yang menguntungkan</li>
                    </ul>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-3">Buka Tabungan</a>
                    @endif
                </div>
                <div class="col-md-6 order-1 order-md-2 text-center">
                    <img src="https://images.unsplash.com/photo-1616514197671-15d99ce7a6f8?auto=format&fit=crop&w=800&q=80" alt="Simpanan LPD" class="img-fluid rounded shadow-sm" style="max-height: 350px; width: 100%; object-fit: cover; filter: grayscale(100%);">
                </div>
            </div>

            <div class="row g-4 align-items-center mt-5">
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80" alt="Kredit LPD" class="img-fluid rounded shadow-sm" style="max-height: 350px; width: 100%; object-fit: cover; filter: grayscale(100%);">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold">Pinjaman (Kredit)</h3>
                    <p class="text-muted mt-3">Solusi pembiayaan untuk berbagai kebutuhan Krama, mulai dari modal usaha (UMKM), pembangunan, hingga keperluan yadnya dan konsumtif lainnya.</p>
                    <ul class="list-unstyled text-muted mt-3">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Proses pengajuan dan persetujuan cepat</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Persyaratan mudah bagi Krama Desa Adat Joanyar Kelodan</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Kemudahan pembayaran angsuran</li>
                    </ul>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-3">Ajukan Pinjaman</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white text-center">
        <div class="container py-4">
            <h2 class="fw-bold mb-3">Siap Menjadi Bagian dari LPD Desa Adat Joanyar Kelodan?</h2>
            <p class="lead mb-4">Mari bergabung, wujudkan kemandirian finansial Anda, dan jadilah penggerak ekonomi untuk desa kita sendiri.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold text-dark px-5 rounded-pill shadow">Daftar Nasabah Baru</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-warning btn-lg fw-bold text-dark px-5 rounded-pill shadow">Masuk ke Panel Nasabah</a>
            @endif
        </div>
    </section>

    <footer class="footer-custom">
        <div class="container text-center text-md-start">
            <div class="row mt-3">
                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
                    <h5 class="text-uppercase fw-bold mb-4 text-white">LPD Joanyar Kelodan</h5>
                    <p>
                        Lembaga keuangan mikro yang melayani Krama Desa Adat Joanyar Kelodan dengan integritas, profesionalisme, dan berpegang teguh pada nilai-nilai luhur adat budaya Bali.
                    </p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-4">
                    <h5 class="text-uppercase fw-bold mb-4 text-white">Tautan Cepat</h5>
                    <p><a href="#beranda" class="text-reset text-decoration-none">Beranda</a></p>
                    <p><a href="#layanan" class="text-reset text-decoration-none">Produk & Layanan</a></p>
                    <p><a href="{{ route('login') }}" class="text-reset text-decoration-none">Portal Nasabah</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h5 class="text-uppercase fw-bold mb-4 text-white">Hubungi Kami</h5>
                    <p><i class="fas fa-home me-3"></i> Desa Adat Joanyar Kelodan, Buleleng, Bali</p>
                    <p><i class="fas fa-envelope me-3"></i> info@lpdjoanyarkelodan.com</p>
                    <p><i class="fas fa-clock me-3"></i> Senin - Jumat: 08:00 - 15:00 WITA</p>
                </div>
            </div>
            <hr>
            <div class="text-center pt-2 pb-3">
                © {{ date('Y') }} Hak Cipta: <strong>LPD Desa Adat Joanyar Kelodan</strong>.
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>