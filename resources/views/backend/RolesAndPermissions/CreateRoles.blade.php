@extends('backend.app')
@section('title', 'Buat Role Baru')

@section('head')
<style>
    /* Styling Checkbox Kustom */
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .permission-group {
        border: 1px solid #e9ecef;
        padding: 15px;
        border-radius: 8px;
        height: 100%;
        background: #f8f9fa;
    }
    
    .permission-label {
        font-weight: 500;
        cursor: pointer;
        color: #495057;
    }

    /* Highlight khusus permission Nasabah */
    .highlight-nasabah {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        padding: 5px 10px;
        border-radius: 5px;
        display: block;
        margin-bottom: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0 text-primary"><i class="fa fa-user-shield me-2"></i>Buat Role Baru</h4>
                    <a href="{{ url('show-roles') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
                </div>

                <form method="POST" action="{{ URL('add-role') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" required name="name" class="form-control form-control-lg border-primary" placeholder="Contoh: Nasabah, Admin, Petugas" autofocus />
                        <div class="form-text">Gunakan nama <strong>Nasabah</strong> (Huruf besar diawal) agar fitur otomatis berjalan.</div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 py-3">
                                    <h5 class="mb-0"><i class="fa fa-key me-2 text-warning"></i>Pilih Hak Akses (Permissions)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check {{ str_contains($permission->name, 'nasabah') ? 'highlight-nasabah' : '' }}">
                                                <input class="form-check-input" type="checkbox" 
                                                    value="{{ $permission->name }}" 
                                                    name="permission[]" 
                                                    id="perm_{{ $permission->id }}">
                                                <label class="form-check-label permission-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    @if(str_contains($permission->name, 'nasabah'))
                                                        <i class="fa fa-star text-warning ms-1" title="Penting untuk Nasabah"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 py-3">
                                    <h5 class="mb-0"><i class="fa fa-users me-2 text-info"></i>Assign ke User</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info small">
                                        <i class="fa fa-info-circle me-1"></i> Pilih user yang langsung ingin diberikan role ini (Tekan Ctrl untuk pilih banyak).
                                    </div>
                                    <select class="form-select" name="users[]" id="users" multiple style="height: 250px;">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fa fa-save me-2"></i> SIMPAN ROLE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection