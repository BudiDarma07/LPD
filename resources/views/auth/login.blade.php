<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Aplikasi Koperasi | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ url('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6, #e0e7ff);
            font-family: 'Heebo', sans-serif;
        }
        .card-login {
            animation: fadeInUp 0.5s ease-in-out;
            border: none;
            border-radius: 20px;
            padding: 2rem;
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #4e73df;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Login Card Start -->
        <div class="card-login" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h3 class="text-success"></i> Aplikasi Koperasi</h3>
                <h5 class="fw-bold">Login ke Akun Anda</h5>
                <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope text-primary"></i></span>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus placeholder="you@example.com">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock text-primary"></i></span>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Login</button>


            </form>
        </div>
        <!-- Login Card End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets/lib/chart/chart.min.js') }}"></script>
    <script src="{{ url('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ url('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ url('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ url('assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ url('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ url('assets/js/main.js') }}"></script>
</body>

</html>
