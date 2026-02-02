<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LPD Desa Adat Joanyar Kelodan | Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Inter:wght@400;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #009CFF;
            --secondary-color: #757575;
            --bg-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        body {
            background: var(--bg-gradient);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-login {
            animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-custom {
            background: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .card-body-custom {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            font-size: 0.9rem;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .input-group-text {
            background-color: #f1f3f5;
            border: 1px solid #ced4da;
            border-right: none;
            color: var(--primary-color);
        }

        .form-control {
            border: 1px solid #ced4da;
            border-left: none;
            padding: 12px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
            background-color: #fff;
        }

        .toggle-password {
            background-color: #fff !important;
            border-left: none !important;
            cursor: pointer;
            color: var(--secondary-color) !important;
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #0086db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 156, 255, 0.3);
        }

        #spinner {
            opacity: 0;
            visibility: hidden;
            transition: opacity .5s ease-out, visibility 0s linear .5s;
            z-index: 99999;
        }

        #spinner.show {
            transition: opacity .5s ease-out, visibility 0s linear 0s;
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="login-container">
        <div class="card-login">
            <div class="card-header-custom">
                <h3 class="text-primary fw-bold mb-1">LPD DESA ADAT</h3>
                <h5 class="text-dark">Joanyar Kelodan</h5>
                <p class="text-muted small mb-0">Sistem Informasi Keuangan LPD</p>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password"
                                placeholder="Masukkan password" style="border-right: none;">
                            <span class="input-group-text toggle-password" id="btn-toggle">
                                <i class="fa fa-eye" id="eye-icon"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                        <i class="fa fa-sign-in-alt me-2"></i> Masuk Ke Sistem
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Logika Toggle Password
            $("#btn-toggle").click(function() {
                const passwordInput = $("#password");
                const eyeIcon = $("#eye-icon");
                
                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    passwordInput.attr("type", "password");
                    eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });

            // Menghilangkan spinner
            setTimeout(function () {
                if ($('#spinner').length > 0) {
                    $('#spinner').removeClass('show');
                }
            }, 500);
        });
    </script>
</body>

</html>