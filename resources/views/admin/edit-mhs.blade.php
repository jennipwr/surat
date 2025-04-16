@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Edit Data Mahasiswa</h5>

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

                    <form action="{{ route('mahasiswa.update', $mahasiswa->nrp) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nrp" class="form-label">NRP</label>
                            <input type="text" class="form-control" id="nrp" name="nrp" value="{{ $mahasiswa->nrp }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $mahasiswa->nama }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $mahasiswa->alamat }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" class="form-control" id="semester" name="semester" value="{{ $mahasiswa->semester }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $mahasiswa->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="prodi_id_prodi" class="form-label">Program Studi</label>
                            <select class="form-select" id="prodi_id_prodi" name="prodi_id_prodi" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id_prodi }}" {{ $mahasiswa->prodi_id_prodi == $prodi->id_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('admin.listMahasiswa') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
