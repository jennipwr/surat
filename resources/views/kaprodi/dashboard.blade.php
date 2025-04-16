@extends('layouts.index')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Kepala Program Studi {{ Auth::user()->karyawan->prodi->nama_prodi ?? 'Anda' }}</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
    </div>

    <div class="row">
        <!-- Grafik Statistik Pengajuan -->
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Statistik Pengajuan Surat</h5>
                        </div>
                        <div>
                            <select class="form-select" id="monthSelector">
                                <option value="1">Januari 2025</option>
                                <option value="2">Februari 2025</option>
                                <option value="3">Maret 2025</option>
                                <option value="4" selected>April 2025</option>
                                <option value="5">Mei 2025</option>
                            </select>
                        </div>
                    </div>
                    <div id="chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Ringkasan dan Info Pengajuan -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Total Pengajuan Tahun Ini -->
                <div class="col-lg-12 mb-4">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-semibold">Total Pengajuan Tahun Ini</h5>
                            <h4 class="fw-semibold mb-3">{{ $yearlyTotal }} Surat</h4>
                            <div class="d-flex align-items-center">
                                <span class="me-2 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">+{{ $yearlyGrowth }}%</p>
                                <p class="fs-3 mb-0">dari tahun lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengajuan Bulan Ini -->
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3 fw-semibold">Pengajuan Bulan Ini</h5>
                            <h4 class="fw-semibold mb-3">{{ $monthlyTotal }} Surat</h4>
                            <div class="d-flex align-items-center">
                                <span class="me-2 rounded-circle bg-light-primary round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-file-text text-primary"></i>
                                </span>
                                <p class="fs-3 mb-0">Update per {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div id="earning" style="height: 80px;"></div>
                    </div>
                </div>

                <!-- Ringkasan Status -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-3">Status Pengajuan</h5>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="ti ti-clock text-warning me-2"></i> Menunggu</span>
                                    <span class="badge bg-warning">{{ $pendingCount }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="ti ti-check text-success me-2"></i> Disetujui</span>
                                    <span class="badge bg-success">{{ $approvedCount }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="ti ti-x text-danger me-2"></i> Ditolak</span>
                                    <span class="badge bg-danger">{{ $rejectedCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pengajuan Terbaru -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                        <h5 class="card-title fw-semibold">Pengajuan Terbaru</h5>
                        <div class="d-flex gap-2">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari...">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="#" data-filter="all">Semua</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="waiting">Menunggu</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="approved">Disetujui</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="declined">Ditolak</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>NRP</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($latestSubmissions as $index => $submission)
                                <tr class="submission-row" data-status="{{ $submission->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $submission->mahasiswa->nrp }}</td>
                                    <td>{{ $submission->mahasiswa->nama }}</td>
                                    <td>
                                        @if($submission->jenis_surat == 'keterangan_aktif')
                                            Keterangan Aktif
                                        @elseif($submission->jenis_surat == 'pengantar_tugas')
                                            Pengantar Tugas
                                        @elseif($submission->jenis_surat == 'keterangan_lulus')
                                            Keterangan Lulus
                                        @elseif($submission->jenis_surat == 'hasil_studi')
                                            Laporan Hasil Studi
                                        @else
                                            {{ $submission->jenis_surat }}
                                        @endif
                                    </td>
                                    <td>{{ $submission->created_at->format('d M Y, H:i') }}</td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('kaprodi.surat', $submission->id_surat) }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($submission->status == 'pending')
                                                <button type="button" class="btn btn-sm btn-success approve-btn" data-id="{{ $submission->id_surat }}">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger reject-btn" data-id="{{ $submission->id_surat }}">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada pengajuan surat terbaru</td>
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

@section('ExtraCss')
    <!-- Tambahkan CSS tambahan jika dibutuhkan -->
    <style>
        .round-20 {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .submission-row:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('ExtraJS')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Jumlah Pengajuan',
                    data: @json($monthlySubmissions)
                }],
                xaxis: {
                    categories: @json($months)
                },
                colors: ['#5D87FF'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '45%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#e0e6ed',
                    strokeDashArray: 5,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        show: true
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            // Filter tabel berdasarkan status
            document.querySelectorAll('[data-filter]').forEach(item => {
                item.addEventListener('click', event => {
                    event.preventDefault();
                    const filter = item.getAttribute('data-filter');

                    document.querySelectorAll('.submission-row').forEach(row => {
                        if (filter === 'all' || row.getAttribute('data-status') === filter) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });

            // Pencarian pada tabel
            document.getElementById('searchButton').addEventListener('click', function() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                searchTable(searchTerm);
            });

            document.getElementById('searchInput').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    const searchTerm = this.value.toLowerCase();
                    searchTable(searchTerm);
                }
            });

            // Filter berdasarkan bulan yang dipilih
            document.getElementById('monthSelector').addEventListener('change', function() {
                const month = this.value;
                const year = 2025; // Bisa diganti dengan tahun dinamis jika diperlukan

                fetch(`/tu/filter-month?month=${month}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        chart.updateOptions({
                            xaxis: {
                                categories: data.days
                            }
                        });
                        chart.updateSeries([{
                            name: 'Jumlah Pengajuan',
                            data: data.submissions
                        }]);
                    });
            });

            // Fungsi approve dan reject
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    // Implementasi approval
                    if (confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
                        window.location.href = `/submissions/${id}/approve`;
                    }
                });
            });

            document.querySelectorAll('.reject-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    // Implementasi rejection
                    if (confirm('Apakah Anda yakin ingin menolak pengajuan ini?')) {
                        window.location.href = `/submissions/${id}/reject`;
                    }
                });
            });

            // Fungsi pencarian
            function searchTable(term) {
                document.querySelectorAll('.submission-row').forEach(row => {
                    let found = false;
                    row.querySelectorAll('td').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(term)) {
                            found = true;
                        }
                    });

                    if (found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection
