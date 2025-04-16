@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">List Karyawan</h5>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">NIK</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Email</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Jabatan</h6>
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
                            @forelse($karyawan as $k)
                                <tr>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">{{ $k->nik }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $k->nama }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-normal mb-0">{{ $k->email }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <p class="mb-0 fw-normal">{{ ucfirst($k->jabatan) }}</p>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-normal mb-0">{{ $k->prodi->nama_prodi }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('karyawan.edit', $k->nik) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('karyawan.delete', $k->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="mb-0 fw-normal">Tidak ada data karyawan</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
