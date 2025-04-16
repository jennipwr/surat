@extends('layouts.index')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <i class="ti ti-users fs-3 mb-3 text-primary"></i>
                        <h5 class="card-title fw-semibold">Data Mahasiswa</h5>
                        <p class="text-muted">Lihat dan kelola daftar mahasiswa</p>
                    </div>
                    <a href="{{ route('admin.listMahasiswa') }}" class="btn btn-outline-primary w-100 mt-3">Lihat Mahasiswa</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <i class="ti ti-briefcase fs-3 mb-3 text-success"></i>
                        <h5 class="card-title fw-semibold">Data Karyawan</h5>
                        <p class="text-muted">Kelola data Kaprodi dan TU</p>
                    </div>
                    <a href="{{ route('admin.listKaryawan') }}" class="btn btn-outline-success w-100 mt-3">Lihat Karyawan</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <i class="ti ti-user-plus fs-3 mb-3 text-warning"></i>
                        <h5 class="card-title fw-semibold">Registrasi</h5>
                        <p class="text-muted">Daftarkan pengguna baru</p>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-outline-warning w-100 mt-3">Buka Form Registrasi</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ExtraCss')
    <style>
        .card i {
            font-size: 2rem;
        }
    </style>
@endsection

@section('ExtraJS')

@endsection
