@extends('layouts.index')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">List Mahasiswa</h5>
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
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mhs as $mhs)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $mhs->nrp }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{ $mhs->nama }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $mhs->alamat }}</p>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <p class="mb-0 fw-normal">{{ $mhs->semester }}</p>
{{--                                        <span class="badge bg-primary rounded-3 fw-semibold">Low</span>--}}
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-normal mb-0">{{ $mhs->email }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-normal mb-0">{{ $mhs->prodi->nama_prodi }}</h6>
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

@section('ExtraCss')

@endsection

@section('ExtraJS')

@endsection
