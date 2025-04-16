@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">List Mahasiswa</h5>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">NRP</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Alamat</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Semester</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Email</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Program Studi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Aksi</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mhs as $mahasiswa)
                                <tr>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">{{ $mahasiswa->nrp }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $mahasiswa->nama }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <p class="mb-0 fw-normal">{{ $mahasiswa->alamat }}</p>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <p class="mb-0 fw-normal">{{ $mahasiswa->semester }}</p>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-normal mb-0">{{ $mahasiswa->email }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-normal mb-0">{{ $mahasiswa->prodi->nama_prodi }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('mahasiswa.edit', $mahasiswa->nrp) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('mahasiswa.delete', $mahasiswa->nrp) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
