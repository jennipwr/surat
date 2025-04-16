@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100 shadow rounded-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4 text-success">✏️ Edit Data Karyawan</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Ada masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('karyawan.update', $karyawan->nik) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" value="{{ $karyawan->nik }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $karyawan->nama }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $karyawan->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-select" id="jabatan" name="jabatan" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="kaprodi" {{ $karyawan->jabatan == 'kaprodi' ? 'selected' : '' }}>Ketua Program Studi</option>
                                <option value="tu" {{ $karyawan->jabatan == 'tu' ? 'selected' : '' }}>Tata Usaha</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="prodi_id_prodi" class="form-label">Program Studi</label>
                            <select class="form-select" id="prodi_id_prodi" name="prodi_id_prodi" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id_prodi }}" {{ $karyawan->prodi_id_prodi == $prodi->id_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-device-floppy"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.listKaryawan') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-back"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
