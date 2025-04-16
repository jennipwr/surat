@extends('layouts.index')

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search dan Filter Global -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchGlobal" class="form-control" placeholder="Cari ID Surat atau Jenis Surat...">
                            <button class="btn btn-primary" id="btnSearchGlobal">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">Filter Tanggal</span>
                            <input type="date" id="filterDateStart" class="form-control" placeholder="Dari">
                            <input type="date" id="filterDateEnd" class="form-control" placeholder="Sampai">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="statusFilter" id="filterAll" value="all" checked>
                            <label class="btn btn-outline-secondary" for="filterAll">Semua Status</label>

                            <input type="radio" class="btn-check" name="statusFilter" id="filterWaiting" value="waiting">
                            <label class="btn btn-outline-warning" for="filterWaiting">Menunggu</label>

                            <input type="radio" class="btn-check" name="statusFilter" id="filterApproved" value="approved">
                            <label class="btn btn-outline-success" for="filterApproved">Disetujui</label>

                            <input type="radio" class="btn-check" name="statusFilter" id="filterDeclined" value="declined">
                            <label class="btn btn-outline-danger" for="filterDeclined">Ditolak</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-secondary w-100" id="resetFilter">Reset Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="noResultsAlert" class="alert alert-info d-none" role="alert">
            Data yang Anda cari tidak ditemukan.
        </div>

        <!-- Tabel Surat Menunggu Persetujuan -->
        <div class="card mb-4" id="section-waiting">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Surat Menunggu Persetujuan</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table-menunggu">
                    <thead>
                    <tr>
                        <th scope="col">ID Surat</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suratMenunggu as $index => $surat)
                        <tr data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('Y-m-d') }}" data-status="waiting" data-jenis="{{ $surat->jenis_surat }}">
                            <th scope="row">{{ $surat->id_surat }}</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                            <td><span class="badge bg-warning">Waiting</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary detail-btn"
                                        data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-jenis="{{ $surat->jenis_surat }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                        data-status="{{ $surat->status }}"
                                        data-nama="{{ $surat->mahasiswa_nama }}"
                                        data-nrp="{{ $surat->mahasiswa_nrp }}"
                                        data-alamat="{{ $surat->alamat ?? '-' }}"
                                        data-semester="{{ $surat->semester ?? '-' }}"
                                        data-kode_mk="{{ $surat->kode_mk ?? '-' }}"
                                        data-nama_mk="{{ $surat->nama_mk ?? '-' }}"
                                        data-topik="{{ $surat->topik ?? '-' }}"
                                        data-tujuan="{{ $surat->tujuan ?? '-' }}"
                                        data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                        data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                    Detail
                                </button>
                                <a href="{{ route('mahasiswa.surat.edit', $surat->id_surat) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('mahasiswa.surat.destroy', $surat->id_surat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-data">
                            <td colspan="5" class="text-center">Tidak ada surat yang menunggu persetujuan</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Surat Diterima -->
        <div class="card mb-4" id="section-approved">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Surat Diterima</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table-diterima">
                    <thead>
                    <tr>
                        <th scope="col">ID Surat</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Tanggal Persetujuan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suratDiterima as $index => $surat)
                        <tr data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('Y-m-d') }}" data-status="approved" data-jenis="{{ $surat->jenis_surat }}">
                            <th scope="row">{{ $surat->id_surat }}</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                            <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary detail-btn"
                                        data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-jenis="{{ $surat->jenis_surat }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                        data-tanggal_persetujuan="{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') }}"
                                        data-status="{{ $surat->status }}"
                                        data-nama="{{ $surat->mahasiswa_nama }}"
                                        data-nrp="{{ $surat->mahasiswa_nrp }}"
                                        data-alamat="{{ $surat->alamat ?? '-' }}"
                                        data-semester="{{ $surat->semester ?? '-' }}"
                                        data-kode_mk="{{ $surat->kode_mk ?? '-' }}"
                                        data-nama_mk="{{ $surat->nama_mk ?? '-' }}"
                                        data-topik="{{ $surat->topik ?? '-' }}"
                                        data-tujuan="{{ $surat->tujuan ?? '-' }}"
                                        data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                        data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                                <a href="{{ route('mahasiswa.surat.download', $surat->id_surat) }}"
                                   class="btn btn-sm btn-primary {{ $surat->file ? '' : 'disabled' }}"
                                   title="{{ $surat->file ? '' : 'TU belum upload surat' }}"
                                   data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('surat.preview.page', $surat->id_surat) }}"
                                   target="_blank" class="btn btn-sm btn-primary {{ $surat->file ? '' : 'disabled' }}"
                                   title="{{ $surat->file ? '' : 'TU belum upload surat' }}"
                                   data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-data">
                            <td colspan="6" class="text-center">Tidak ada surat yang diterima</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Surat Ditolak -->
        <div class="card mb-4" id="section-declined">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Surat Ditolak</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table-ditolak">
                    <thead>
                    <tr>
                        <th scope="col">ID Surat</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Tanggal Persetujuan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suratDitolak as $index => $surat)
                        <tr data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('Y-m-d') }}" data-status="declined" data-jenis="{{ $surat->jenis_surat }}">
                            <th scope="row">{{ $surat->id_surat }}</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                            <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                            <td><span class="badge bg-danger">Declined</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary detail-btn"
                                        data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-jenis="{{ $surat->jenis_surat }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                        data-tanggal_persetujuan="{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') }}"
                                        data-status="{{ $surat->status }}"
                                        data-catatan="{{ $surat->catatan }}"
                                        data-nama="{{ $surat->mahasiswa_nama }}"
                                        data-nrp="{{ $surat->mahasiswa_nrp }}"
                                        data-alamat="{{ $surat->alamat ?? '-' }}"
                                        data-semester="{{ $surat->semester ?? '-' }}"
                                        data-kode_mk="{{ $surat->kode_mk ?? '-' }}"
                                        data-nama_mk="{{ $surat->nama_mk ?? '-' }}"
                                        data-topik="{{ $surat->topik ?? '-' }}"
                                        data-tujuan="{{ $surat->tujuan ?? '-' }}"
                                        data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                        data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-data">
                            <td colspan="6" class="text-center">Tidak ada surat yang ditolak</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Surat -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr><th width="40%">Nama Mahasiswa</th><td id="modalNama"></td></tr>
                        <tr><th>NRP</th><td id="modalNRP"></td></tr>
                        <tr><th>Jenis Surat</th><td id="modalJenis"></td></tr>
                        <tr><th>Tanggal Pengajuan</th><td id="modalTanggal"></td></tr>
                        <tr><th>Tanggal Persetujuan</th><td id="modalTanggalPersetujuan"></td></tr>
                        <tr><th>Status</th><td id="modalStatus"></td></tr>
                        <tbody id="modalExtraData"></tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk search dan filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Detail Modal
            const detailButtons = document.querySelectorAll('.detail-btn');
            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const data = this.dataset;
                    document.getElementById('modalNama').textContent = data.nama;
                    document.getElementById('modalNRP').textContent = data.nrp;
                    document.getElementById('modalJenis').textContent = ucFirst(data.jenis.replace(/_/g, ' '));
                    document.getElementById('modalTanggal').textContent = data.tanggal;

                    if (data.tanggal_persetujuan) {
                        document.getElementById('modalTanggalPersetujuan').textContent = data.tanggal_persetujuan;
                    } else {
                        document.getElementById('modalTanggalPersetujuan').textContent = '-';
                    }

                    // Status badge
                    let statusBadge = '';
                    if (data.status === 'waiting') {
                        statusBadge = '<span class="badge bg-warning">Waiting</span>';
                    } else if (data.status === 'approved') {
                        statusBadge = '<span class="badge bg-success">Approved</span>';
                    } else if (data.status === 'declined') {
                        statusBadge = '<span class="badge bg-danger">Declined</span>';
                    }
                    document.getElementById('modalStatus').innerHTML = statusBadge;

                    // Extra data berdasarkan jenis surat
                    let extraData = '';

                    // Fields umum
                    if (data.alamat && data.alamat !== '-') {
                        extraData += `<tr><th>Alamat</th><td>${data.alamat}</td></tr>`;
                    }

                    if (data.semester && data.semester !== '-') {
                        extraData += `<tr><th>Semester</th><td>${data.semester}</td></tr>`;
                    }

                    // Jika ada catatan penolakan
                    if (data.catatan) {
                        extraData += `<tr><th>Catatan Penolakan</th><td>${data.catatan}</td></tr>`;
                    }

                    // Fields khusus
                    if (data.jenis === 'aktif_kuliah' && data.keperluan) {
                        extraData += `<tr><th>Keperluan</th><td>${data.keperluan}</td></tr>`;
                    }

                    if (data.jenis === 'hasil_studi' && data.keperluan_hasil_studi) {
                        extraData += `<tr><th>Keperluan</th><td>${data.keperluan_hasil_studi}</td></tr>`;
                    }

                    if (data.jenis === 'izin_penelitian') {
                        if (data.topik && data.topik !== '-') {
                            extraData += `<tr><th>Topik Penelitian</th><td>${data.topik}</td></tr>`;
                        }
                        if (data.tujuan && data.tujuan !== '-') {
                            extraData += `<tr><th>Tujuan</th><td>${data.tujuan}</td></tr>`;
                        }
                    }

                    if ((data.kode_mk && data.kode_mk !== '-') || (data.nama_mk && data.nama_mk !== '-')) {
                        extraData += `<tr><th>Mata Kuliah</th><td>${data.kode_mk} - ${data.nama_mk}</td></tr>`;
                    }

                    document.getElementById('modalExtraData').innerHTML = extraData;
                });
            });

            // Filter variables
            const sections = [
                { id: 'section-waiting', tableId: 'table-menunggu', status: 'waiting' },
                { id: 'section-approved', tableId: 'table-diterima', status: 'approved' },
                { id: 'section-declined', tableId: 'table-ditolak', status: 'declined' }
            ];

            // Event listeners
            document.getElementById('btnSearchGlobal').addEventListener('click', applyFilters);
            document.getElementById('searchGlobal').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilters();
            });
            document.getElementById('filterDateStart').addEventListener('change', applyFilters);
            document.getElementById('filterDateEnd').addEventListener('change', applyFilters);
            document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
                radio.addEventListener('change', applyFilters);
            });
            document.getElementById('resetFilter').addEventListener('click', resetFilters);

            // Function untuk menerapkan filter
            function applyFilters() {
                const searchTerm = document.getElementById('searchGlobal').value.toLowerCase();
                const dateStart = document.getElementById('filterDateStart').value;
                const dateEnd = document.getElementById('filterDateEnd').value;
                const statusFilter = document.querySelector('input[name="statusFilter"]:checked').value;

                let foundAny = false;

                sections.forEach(section => {
                    const sectionElement = document.getElementById(section.id);
                    const rows = document.querySelectorAll(`#${section.tableId} tbody tr:not(.no-data)`);
                    let foundInSection = false;

                    // Jika filter status aktif dan tidak cocok dengan bagian ini, sembunyikan seluruh bagian
                    if (statusFilter !== 'all' && statusFilter !== section.status) {
                        sectionElement.classList.add('d-none');
                        return;
                    }

                    // Tampilkan bagian karena statusnya cocok
                    sectionElement.classList.remove('d-none');

                    // Lakukan filter pada setiap baris dalam tabel
                    rows.forEach(row => {
                        const id = row.querySelector('th').textContent.toLowerCase();
                        const jenis = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const tanggal = row.getAttribute('data-tanggal');

                        // Filter berdasarkan pencarian
                        const matchesSearch = !searchTerm ||
                            id.includes(searchTerm) ||
                            jenis.includes(searchTerm);

                        // Filter berdasarkan tanggal
                        const matchesDate = isInDateRange(tanggal, dateStart, dateEnd);

                        // Aplikasikan filter
                        if (matchesSearch && matchesDate) {
                            row.style.display = '';
                            foundInSection = true;
                            foundAny = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Tampilkan pesan "tidak ada data" jika tidak ada data yang ditemukan di bagian ini
                    const noDataRow = sectionElement.querySelector('.no-data');
                    if (noDataRow) {
                        if (foundInSection) {
                            noDataRow.style.display = 'none';
                        } else {
                            noDataRow.style.display = '';
                        }
                    }
                });

                // Tampilkan pesan global jika tidak ada data yang ditemukan sama sekali
                const noResultsAlert = document.getElementById('noResultsAlert');
                if (foundAny) {
                    noResultsAlert.classList.add('d-none');
                } else {
                    noResultsAlert.classList.remove('d-none');
                }
            }

            // Function untuk reset filter
            function resetFilters() {
                document.getElementById('searchGlobal').value = '';
                document.getElementById('filterDateStart').value = '';
                document.getElementById('filterDateEnd').value = '';
                document.getElementById('filterAll').checked = true;

                sections.forEach(section => {
                    const sectionElement = document.getElementById(section.id);
                    sectionElement.classList.remove('d-none');

                    const rows = document.querySelectorAll(`#${section.tableId} tbody tr:not(.no-data)`);
                    rows.forEach(row => {
                        row.style.display = '';
                    });

                    const noDataRow = sectionElement.querySelector('.no-data');
                    if (noDataRow) {
                        noDataRow.style.display = '';
                    }
                });

                document.getElementById('noResultsAlert').classList.add('d-none');
            }

            // Helper function untuk cek apakah tanggal berada dalam rentang
            function isInDateRange(date, startDate, endDate) {
                if (!startDate && !endDate) return true;

                const targetDate = new Date(date);

                if (startDate && !endDate) {
                    return targetDate >= new Date(startDate);
                }

                if (!startDate && endDate) {
                    return targetDate <= new Date(endDate);
                }

                return targetDate >= new Date(startDate) && targetDate <= new Date(endDate);
            }

            // Helper function untuk capitalize first letter
            function ucFirst(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
@endsection

@section('ExtraJS')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".detail-btn").forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("modalNama").textContent = this.dataset.nama;
                    document.getElementById("modalNRP").textContent = this.dataset.nrp;

                    let jenisSurat;
                    switch(this.dataset.jenis) {
                        case 'keterangan_aktif': jenisSurat = 'Surat Keterangan Aktif'; break;
                        case 'pengantar_tugas': jenisSurat = 'Surat Pengantar Tugas'; break;
                        case 'keterangan_lulus': jenisSurat = 'Surat Keterangan Lulus'; break;
                        case 'hasil_studi': jenisSurat = 'Surat Laporan Hasil Studi'; break;
                        default: jenisSurat = this.dataset.jenis;
                    }
                    document.getElementById("modalJenis").textContent = jenisSurat;
                    document.getElementById("modalTanggal").textContent = this.dataset.tanggal;
                    document.getElementById("modalTanggalPersetujuan").textContent = this.dataset.tanggal_persetujuan || '-';
                    document.getElementById("modalStatus").textContent = this.dataset.status;

                    let statusText;
                    switch(this.dataset.status) {
                        case 'waiting': statusText = 'Menunggu Persetujuan'; break;
                        case 'approved': statusText = 'Disetujui'; break;
                        case 'declined': statusText = 'Ditolak'; break;
                        default: statusText = this.dataset.status;
                    }
                    document.getElementById("modalStatus").textContent = statusText;
                    let extraData = "";

                    if (this.dataset.jenis === "keterangan_aktif") {
                        extraData += `<tr><th>Keperluan</th><td>${this.dataset.keperluan || '-'}</td></tr>`;
                    } else if (this.dataset.jenis === "pengantar_tugas") {
                        extraData += `<tr><th>Kode MK</th><td>${this.dataset.kode_mk || '-'}</td></tr>`;
                        extraData += `<tr><th>Nama MK</th><td>${this.dataset.nama_mk || '-'}</td></tr>`;
                        extraData += `<tr><th>Tujuan</th><td>${this.dataset.tujuan || '-'}</td></tr>`;
                        extraData += `<tr><th>Topik</th><td>${this.dataset.topik || '-'}</td></tr>`;
                    } else if (this.dataset.jenis === "hasil_studi") {
                        extraData += `<tr><th>Keperluan Pengajuan</th><td>${this.dataset.keperluan_hasil_studi || '-'}</td></tr>`;
                    } else {
                        extraData += `<tr><th>Keterangan</th><td>-</td></tr>`;
                    }

                    if (this.dataset.status === 'declined') {
                        extraData += `<tr><th>Alasan Penolakan</th><td>${this.dataset.catatan || '-'}</td></tr>`;
                    }

                    document.getElementById("modalExtraData").innerHTML = extraData;

                    document.getElementById("formDecline").addEventListener("submit", function(e) {
                        // Salin nilai textarea ke input hidden
                        document.getElementById("catatanInput").value = document.getElementById("catatan").value;
                    });

                    document.getElementById("formDecline").addEventListener("submit", function(event) {
                        const catatanValue = document.getElementById("catatan").value.trim();
                        if (catatanValue === "") {
                            event.preventDefault(); // mencegah form submit
                            alert("Silakan isi catatan alasan penolakan terlebih dahulu.");
                        } else {
                            document.getElementById("catatanInput").value = catatanValue;
                        }
                    });
                });
            });
        });
    </script>
@endsection
