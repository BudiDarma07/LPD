@extends('layouts.app') 

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center fw-bold py-3">
                    Reset Password
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">Silakan masukkan NIK dan Email/Nomor HP yang terdaftar untuk melakukan verifikasi akun.</p>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.custom.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nik" class="form-label fw-bold">NIK (Nomor Induk Kependudukan)</label>
                            <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" required autofocus placeholder="Masukkan 16 digit NIK">
                            @error('nik')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="kontak" class="form-label fw-bold">Email atau Nomor HP</label>
                            <input id="kontak" type="text" class="form-control @error('kontak') is-invalid @enderror" name="kontak" value="{{ old('kontak') }}" required placeholder="Contoh: 0812xxx atau budi@email.com">
                            @error('kontak')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                Verifikasi Data Saya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection