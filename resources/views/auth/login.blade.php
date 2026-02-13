<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login | LPD Desa Adat Joanyar Kelodan</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0a58ca;
            --bg-gradient: linear-gradient(135deg, #eef2f3 0%, #8e9eab 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('https://source.unsplash.com/1600x900/?bali,architecture,building') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(0, 0, 0, 0.6));
            z-index: -1;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-login {
            border: none;
            border-radius: 20px;
            background: var(--glass-bg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-custom {
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: none;
        }

        .logo-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(13, 110, 253, 0.3));
        }

        .card-body-custom {
            padding: 20px 40px 50px;
        }

        .form-floating > .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding-left: 20px;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .form-floating > label {
            padding-left: 20px;
        }

        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .btn-login {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        }

        #spinner {
            background: #fff;
            z-index: 99999;
        }
        #spinner.hide {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>

<body>
    <div id="spinner" class="position-fixed w-100 h-100 d-flex align-items-center justify-content-center top-0 start-0 show">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="login-container">
        <div class="card-login">
            <div class="card-header-custom">
                <div class="logo-icon">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">LPD Desa Adat</h3>
                <p class="text-primary fw-semibold mb-0" style="letter-spacing: 1px;">JOANYAR KELODAN</p>
                <small class="text-muted d-block mt-2">Silakan masuk untuk melanjutkan</small>
            </div>

            <div class="card-body-custom">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>Login Gagal. Cek email & password.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" 
                            placeholder="name@example.com" required autocomplete="email" autofocus>
                        <label for="email">Alamat Email</label>
                        @error('email')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 password-wrapper">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Password" required>
                            <label for="password">Kata Sandi</label>
                            <i class="fa fa-eye toggle-password" id="btn-toggle"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary small" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-primary small text-decoration-none fw-bold" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                        MASUK SEKARANG <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Belum memiliki akun?</span>
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">
                            Daftar Disini
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Toggle Password
            $("#btn-toggle").click(function() {
                const passwordInput = $("#password");
                const icon = $(this);
                
                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    passwordInput.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });

            // Hide Spinner
            setTimeout(function () {
                if ($('#spinner').length > 0) {
                    $('#spinner').addClass('hide');
                }
            }, 600);
        });
    </script>
</body>
</html>