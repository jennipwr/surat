@extends('layouts.index')

@section('content')
    <!-- Main Dashboard Layout -->
    <div class="row">
        <div class="mb-4">
            <h2 class="fw-bold">Dashboard Tata Usaha {{ Auth::user()->karyawan->prodi->nama_prodi ?? 'Anda' }}</h2>
            <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
        </div>
        <!-- Left Column - Main Content -->
        <div class="col-lg-8 d-flex flex-column">
            <div class="card w-100 mb-4">
                <div class="card-body">

                    <!-- Status Cards Dipindah ke Atas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light-success p-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                    <span class="bg-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                        <i class="ti ti-file-check text-white fs-5"></i>
                                    </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-semibold">{{ $jumlahSuratSudahUpload }}</h4>
                                        <p class="mb-0">Surat Sudah Diupload</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light-danger p-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                    <span class="bg-danger p-2 rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                        <i class="ti ti-file-alert text-white fs-5"></i>
                                    </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-semibold">{{ $jumlahSuratBelumUpload }}</h4>
                                        <p class="mb-0">Surat Belum Diupload</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Yang Belum Diupload Section -->
                    <h5 class="card-title fw-semibold mb-3">Surat Yang Belum Diupload</h5>
                    @if($suratBelumUpload->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover small-table">
                                <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>ID Surat</th>
                                    <th>Jenis Surat</th>
                                    <th>Mahasiswa</th>
                                    <th>NRP</th>
                                    <th>Tanggal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($suratBelumUpload->take(5) as $index => $surat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $surat->id_surat ?? 'Belum ada' }}</td>
                                        <td>
                                            @if($surat->jenis_surat == 'keterangan_aktif')
                                                Keterangan Aktif
                                            @elseif($surat->jenis_surat == 'pengantar_tugas')
                                                Pengantar Tugas
                                            @elseif($surat->jenis_surat == 'keterangan_lulus')
                                                Keterangan Lulus
                                            @elseif($surat->jenis_surat == 'hasil_studi')
                                                Hasil Studi
                                            @else
                                                {{ $surat->jenis_surat }}
                                            @endif
                                        </td>
                                        <td>{{ $surat->mahasiswa->nama ?? 'Tidak tersedia' }}</td>
                                        <td>{{ $surat->mahasiswa_nrp }}</td>
                                        <td>{{ $surat->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('tu.listSurat') }}" class="btn btn-sm btn-primary">Upload Surat</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Tidak ada surat yang belum diupload
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Surat Terbaru -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Surat Terbaru</h5>
                    @if($suratSudahUpload->count() > 0)
                        <div class="list-group">
                            @foreach($suratSudahUpload->take(5) as $surat)
                                <a href="{{ route('tu.listSurat', $surat->id_surat) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            @if($surat->jenis_surat == 'keterangan_aktif')
                                                Keterangan Aktif
                                            @elseif($surat->jenis_surat == 'pengantar_tugas')
                                                Pengantar Tugas
                                            @elseif($surat->jenis_surat == 'keterangan_lulus')
                                                Keterangan Lulus
                                            @elseif($surat->jenis_surat == 'hasil_studi')
                                                Laporan Hasil Studi
                                            @else
                                                {{ $surat->jenis_surat }}
                                            @endif
                                        </h6>
                                        <small>{{ $surat->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $surat->mahasiswa->nama ?? 'Nama tidak tersedia' }}</p>
                                    <small>{{ $surat->id_surat ?? 'Nomor belum tersedia' }}</small>
                                </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('tu.listSurat') }}" class="btn btn-sm btn-primary">Lihat Semua Surat</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Belum ada surat yang diupload
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ExtraCss')
    <style>
        /* Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        /* Status Card Backgrounds */
        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Table Styling */
        .table {
            font-size: 0.875rem; /* slightly smaller */
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            font-size:0.8rem;
        }

        .table td {
            font-size:0.8rem;
        }

        /* List Group Item Styling */
        .list-group-item {
            border-left: 0;
            border-right: 0;
        }

        .list-group-item:first-child {
            border-top: 0;
        }
    </style>
@endsection
