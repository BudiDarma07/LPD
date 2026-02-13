<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Daftar Akun | LPD Desa Adat Joanyar Kelodan</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0a58ca;
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

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .card-auth {
            border: none;
            border-radius: 20px;
            background: var(--glass-bg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-custom {
            padding: 35px 30px 20px;
            text-align: center;
            border-bottom: none;
        }

        .logo-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .card-body-custom {
            padding: 10px 40px 40px;
        }

        /* Floating Labels Styling */
        .form-floating > .form-control,
        .form-floating > .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding-left: 20px;
        }
        
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .form-floating > label {
            padding-left: 20px;
        }

        .password-wrapper { position: relative; }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .btn-primary-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        }

        #spinner.hide { opacity: 0; visibility: hidden; }
    </style>
</head>

<body>
    <div id="spinner" class="position-fixed w-100 h-100 d-flex align-items-center justify-content-center top-0 start-0 show bg-white" style="z-index: 9999;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div class="auth-container">
        <div class="card-auth">
            <div class="card-header-custom">
                <div class="logo-icon"><i class="fa-solid fa-user-plus"></i></div>
                <h3 class="fw-bold text-dark mb-1">Registrasi Akun</h3>
                <p class="text-muted small mb-0">Lengkapi data diri Anda untuk bergabung</p>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control @error('nik') is-invalid @enderror" 
                            id="nik" name="nik" value="{{ old('nik') }}" 
                            placeholder="Nomor Induk Kependudukan" required autocomplete="nik" autofocus>
                        <label for="nik">NIK (No. KTP - 16 Digit)</label>
                        @error('nik')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Nama Lengkap" required autocomplete="name">
                        <label for="name">Nama Lengkap</label>
                        @error('name')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" 
                            placeholder="name@example.com" required autocomplete="email">
                        <label for="email">Alamat Email</label>
                        @error('email')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control @error('telphone') is-invalid @enderror" 
                            id="telphone" name="telphone" value="{{ old('telphone') }}" 
                            placeholder="08123456789" required autocomplete="telphone">
                        <label for="telphone">No HP (WhatsApp)</label>
                        @error('telphone')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                            id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" 
                            id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" 
                            placeholder="Pekerjaan" required autocomplete="pekerjaan">
                        <label for="pekerjaan">Pekerjaan</label>
                        @error('pekerjaan')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 password-wrapper">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Password" required autocomplete="new-password">
                            <label for="password">Kata Sandi</label>
                            <i class="fa fa-eye toggle-password" id="toggle-pass"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 password-wrapper">
                        <div class="form-floating">
                            <input type="password" class="form-control" 
                                id="password-confirm" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            <label for="password-confirm">Konfirmasi Kata Sandi</label>
                            <i class="fa fa-eye toggle-password" id="toggle-conf-pass"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-primary-custom w-100 text-white mb-3">
                        DAFTAR SEKARANG <i class="fa-solid fa-paper-plane ms-2"></i>
                    </button>

                    <div class="text-center">
                        <span class="text-muted small">Sudah memiliki akun?</span>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">
                            Masuk Disini
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
            $(".toggle-password").click(function() {
                const input = $(this).siblings("input");
                const icon = $(this);
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });

            setTimeout(function () { $('#spinner').addClass('hide'); }, 500);
        });
    </script>
</body>
</html>