@extends('layouts.index')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Mahasiswa</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    <div class="row">
        <!-- Main Dashboard Content -->
        <div class="col-lg-8 d-flex flex-column">
            <div class="card w-100 mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Ringkasan Pengajuan Surat</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light-warning p-3 rounded text-center">
                                <h6 class="fw-semibold text-warning">Menunggu</h6>
                                <h3>{{ $pendingCount }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light-success p-3 rounded text-center">
                                <h6 class="fw-semibold text-success">Disetujui</h6>
                                <h3>{{ $approvedCount }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light-danger p-3 rounded text-center">
                                <h6 class="fw-semibold text-danger">Ditolak</h6>
                                <h3>{{ $rejectedCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pengajuan Surat -->
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Riwayat Pengajuan Surat Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestSubmissions as $submission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $submission->formatted_jenis }}</td>
                                    <td>{{ $submission->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($submission->status == 'waiting')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($submission->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($submission->status == 'declined')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->status == 'approved' && $submission->file)
                                            <a href="{{ route('mahasiswa.surat.download', $submission->id_surat) }}" class="btn btn-sm btn-success">
                                                Unduh
                                            </a>
                                        @elseif($submission->status == 'approved')
                                            <button class="btn btn-sm btn-info" disabled>
                                                Edit
                                            </button>
                                        @elseif($submission->status == 'waiting' || $submission->status == 'declined')
                                            <button class="btn btn-sm btn-info" disabled>
                                                Edit
                                            </button>
                                        @else
                                            <a href="{{ route('mahasiswa.surat.edit', $submission->id_surat) }}" class="btn btn-sm btn-info">
                                                Edit
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada pengajuan surat.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('mahasiswa.surat') }}" class="btn btn-outline-primary">Lihat Semua Surat</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Ajukan Surat Baru -->
                <div class="col-lg-12 mb-4">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h5 class="card-title mb-3 fw-semibold">Butuh Surat Baru?</h5>
                            <p class="mb-4 text-muted">Klik tombol di bawah untuk mengajukan surat.</p>
                            <a href="{{ route('mahasiswa.apply') }}" class="btn btn-primary">Ajukan Surat</a>
                        </div>
                    </div>
                </div>

                <!-- Info Penting -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-3">Tips Pengajuan</h5>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="ti ti-info-circle text-primary me-1"></i> Lengkapi data dengan benar sebelum kirim</li>
                                <li><i class="ti ti-info-circle text-primary me-1"></i> Cek status pengajuan secara berkala</li>
                                <li><i class="ti ti-info-circle text-primary me-1"></i> Surat yang disetujui bisa diunduh langsung jika TU sudah mengupload surat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('ExtraCss')
    <style>
        table.table td,
        table.table th {
            font-size: 0.8rem; /* atau bisa juga pakai 0.8rem, 12px, dll sesuai kebutuhan */
        }
    </style>
@endsection

@section('ExtraJS')

@endsection
