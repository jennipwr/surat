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
                                <input type="text" id="searchGlobal" class="form-control" placeholder="Cari ID Surat, Nama Mahasiswa, atau Jenis Surat...">
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

            <div id="noResultsAlert" class="alert alert-info text-center" role="alert" style="display: none;">
                Data yang Anda cari tidak ditemukan.
            </div>

        <!-- Section Menunggu Persetujuan -->
        <div class="card mb-4" id="section-waiting">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Pengajuan Surat - Menunggu Persetujuan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-menunggu">
                        <thead>
                        <tr>
                            <th scope="col">ID Surat</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Jenis Surat</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($suratList->where('status', 'waiting') as $index => $surat)
                            <tr data-tanggal="{{ $surat->tanggal_pengajuan }}">
                                <th scope="row">{{ $surat->id_surat }}</th>
                                <td>{{ $surat->mahasiswa_nama }}</td>
                                <td>{{ $surat->mahasiswa_nrp }}</td>
                                <td>
                                    @switch($surat->jenis_surat)
                                        @case('keterangan_aktif') Keterangan aktif @break
                                        @case('pengantar_tugas') Pengantar tugas @break
                                        @case('keterangan_lulus') Keterangan lulus @break
                                        @case('hasil_studi') Laporan hasil studi @break
                                        @default -
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-warning">Menunggu</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary detail-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                data-id="{{ $surat->id_surat }}"
                                                data-jenis="{{ $surat->jenis_surat }}"
                                                data-nama="{{ $surat->mahasiswa_nama }}"
                                                data-nrp="{{ $surat->mahasiswa_nrp }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                                data-status="{{ $surat->status }}"
                                                data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                                data-kode_mk="{{ $surat->kode_mk ?? '' }}"
                                                data-nama_mk="{{ $surat->nama_mk ?? '' }}"
                                                data-tujuan="{{ $surat->tujuan ?? '' }}"
                                                data-topik="{{ $surat->topik ?? '' }}"
                                                data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="7" class="text-center text-muted">Belum ada surat yang menunggu persetujuan</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Disetujui -->
        <div class="card mb-4" id="section-approved">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Pengajuan Surat - Disetujui</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-diterima">
                        <thead>
                        <tr>
                            <th scope="col">ID Surat</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Jenis Surat</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Tanggal Persetujuan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($suratList->where('status', 'approved') as $index => $surat)
                            <tr data-tanggal="{{ $surat->tanggal_pengajuan }}">
                                <th scope="row">{{ $surat->id_surat }}</th>
                                <td>{{ $surat->mahasiswa_nama }}</td>
                                <td>{{ $surat->mahasiswa_nrp }}</td>
                                <td>
                                    @switch($surat->jenis_surat)
                                        @case('keterangan_aktif') Keterangan aktif @break
                                        @case('pengantar_tugas') Pengantar tugas @break
                                        @case('keterangan_lulus') Keterangan lulus @break
                                        @case('hasil_studi') Laporan hasil studi @break
                                        @default -
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                                <td>
                                    <span class="badge bg-success">Disetujui</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary detail-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                data-id="{{ $surat->id_surat }}"
                                                data-jenis="{{ $surat->jenis_surat }}"
                                                data-nama="{{ $surat->mahasiswa_nama }}"
                                                data-nrp="{{ $surat->mahasiswa_nrp }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                                data-tanggal_persetujuan="{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}"
                                                data-status="{{ $surat->status }}"
                                                data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                                data-kode_mk="{{ $surat->kode_mk ?? '' }}"
                                                data-nama_mk="{{ $surat->nama_mk ?? '' }}"
                                                data-tujuan="{{ $surat->tujuan ?? '' }}"
                                                data-topik="{{ $surat->topik ?? '' }}"
                                                data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                        <a href="{{ route('kaprodi.surat.download', $surat->id_surat) }}"
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="8" class="text-center text-muted">Belum ada surat yang disetujui</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Ditolak -->
        <div class="card mb-4" id="section-declined">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Pengajuan Surat - Ditolak</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-ditolak">
                        <thead>
                        <tr>
                            <th scope="col">ID Surat</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Jenis Surat</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Tanggal Persetujuan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($suratList->where('status', 'declined') as $index => $surat)
                            <tr data-tanggal="{{ $surat->tanggal_pengajuan }}">
                                <th scope="row">{{ $surat->id_surat }}</th>
                                <td>{{ $surat->mahasiswa_nama }}</td>
                                <td>{{ $surat->mahasiswa_nrp }}</td>
                                <td>
                                    @switch($surat->jenis_surat)
                                        @case('keterangan_aktif') Keterangan aktif @break
                                        @case('pengantar_tugas') Pengantar tugas @break
                                        @case('keterangan_lulus') Keterangan lulus @break
                                        @case('hasil_studi') Laporan hasil studi @break
                                        @default -
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                                <td>
                                    <span class="badge bg-danger">Ditolak</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary detail-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                data-id="{{ $surat->id_surat }}"
                                                data-jenis="{{ $surat->jenis_surat }}"
                                                data-nama="{{ $surat->mahasiswa_nama }}"
                                                data-nrp="{{ $surat->mahasiswa_nrp }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}"
                                                data-tanggal_persetujuan="{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}"
                                                data-status="{{ $surat->status }}"
                                                data-catatan="{{ $surat->catatan }}"
                                                data-keperluan="{{ $surat->keperluan_aktif ?? '' }}"
                                                data-kode_mk="{{ $surat->kode_mk ?? '' }}"
                                                data-nama_mk="{{ $surat->nama_mk ?? '' }}"
                                                data-tujuan="{{ $surat->tujuan ?? '' }}"
                                                data-topik="{{ $surat->topik ?? '' }}"
                                                data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="8" class="text-center text-muted">Belum ada surat yang ditolak</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
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
                        </tbody>
                        <tbody id="modalExtraData"></tbody>
                    </table>
                    <!-- Form Catatan untuk Decline -->
                    <div class="mb-3" id="catatanContainer">
                        <label for="catatan" class="form-label">Catatan (Alasan Penolakan)</label>
                        <textarea id="catatan" class="form-control" rows="3" placeholder="Masukkan alasan penolakan (jika ada)"></textarea>
                    </div>
                </div>
                <div class="modal-footer" id="modalFooterActions">
                    <!-- Button Action: Approve, Decline, Close -->
                    <form id="formApprove" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success">✔ Approve</button>
                    </form>
                    <form id="formDecline" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="declined">
                        <input type="hidden" name="catatan" id="catatanInput">
                        <button type="submit" class="btn btn-danger">✖ Decline</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Detail Modal setup
            const detailButtons = document.querySelectorAll('.detail-btn');
            const formApprove = document.getElementById('formApprove');
            const formDecline = document.getElementById('formDecline');
            const catatanContainer = document.getElementById('catatanContainer');
            const catatanInput = document.getElementById('catatanInput');
            const catatan = document.getElementById('catatan');
            const modalFooterActions = document.getElementById('modalFooterActions');

            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get data from button's data attributes
                    const data = this.dataset;

                    // Display basic information
                    document.getElementById('modalNama').textContent = data.nama;
                    document.getElementById('modalNRP').textContent = data.nrp;

                    // Format jenis surat untuk tampilan
                    let jenisText = '';
                    switch(data.jenis) {
                        case 'keterangan_aktif':
                            jenisText = 'Surat Keterangan Aktif';
                            break;
                        case 'pengantar_tugas':
                            jenisText = 'Surat Pengantar Tugas';
                            break;
                        case 'keterangan_lulus':
                            jenisText = 'Surat Keterangan Lulus';
                            break;
                        case 'hasil_studi':
                            jenisText = 'Surat Laporan Hasil Studi';
                            break;
                        default:
                            jenisText = data.jenis.replace(/_/g, ' ');
                            jenisText = jenisText.charAt(0).toUpperCase() + jenisText.slice(1);
                    }

                    document.getElementById('modalJenis').textContent = jenisText;
                    document.getElementById('modalTanggal').textContent = data.tanggal;

                    if (data.tanggal_persetujuan) {
                        document.getElementById('modalTanggalPersetujuan').textContent = data.tanggal_persetujuan;
                    } else {
                        document.getElementById('modalTanggalPersetujuan').textContent = '-';
                    }

                    // Status badge
                    let statusBadge = '';
                    if (data.status === 'waiting') {
                        statusBadge = '<span class="badge bg-warning">Menunggu</span>';
                        formApprove.style.display = 'inline-block';
                        formDecline.style.display = 'inline-block';
                        catatanContainer.style.display = 'block';
                    } else if (data.status === 'approved') {
                        statusBadge = '<span class="badge bg-success">Disetujui</span>';
                        formApprove.style.display = 'none';
                        formDecline.style.display = 'none';
                        catatanContainer.style.display = 'none';
                    } else if (data.status === 'declined') {
                        statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                        formApprove.style.display = 'none';
                        formDecline.style.display = 'none';
                        catatanContainer.style.display = 'none';
                    }
                    document.getElementById('modalStatus').innerHTML = statusBadge;

                    // Set route URL untuk form approval/decline
                    const suratId = data.id;
                    const baseUrl = `/kaprodi/surat/${suratId}/update`;
                    formApprove.action = baseUrl;
                    formDecline.action = baseUrl;

                    // Extra data berdasarkan jenis surat
                    let extraData = '';

                    // Jika ada catatan penolakan
                    if (data.catatan) {
                        extraData += `<tr><th>Catatan Penolakan</th><td>${data.catatan}</td></tr>`;
                    }

                    // Fields khusus berdasarkan jenis surat
                    if (data.jenis === 'keterangan_aktif' && data.keperluan) {
                        extraData += `<tr><th>Keperluan</th><td>${data.keperluan}</td></tr>`;
                    }

                    if (data.jenis === 'hasil_studi' && data.keperluan_hasil_studi) {
                        extraData += `<tr><th>Keperluan</th><td>${data.keperluan_hasil_studi}</td></tr>`;
                    }

                    if (data.jenis === 'pengantar_tugas') {
                        if (data.kode_mk) extraData += `<tr><th>Kode MK</th><td>${data.kode_mk || '-'}</td></tr>`;
                        if (data.nama_mk) extraData += `<tr><th>Nama MK</th><td>${data.nama_mk || '-'}</td></tr>`;
                        if (data.topik) extraData += `<tr><th>Topik Penelitian</th><td>${data.topik || '-'}</td></tr>`;
                        if (data.tujuan) extraData += `<tr><th>Tujuan</th><td>${data.tujuan || '-'}</td></tr>`;
                    }

                    document.getElementById('modalExtraData').innerHTML = extraData;
                });
            });

            // Event handler untuk form decline
            if (formDecline) {
                formDecline.addEventListener('submit', function(e) {
                    const catatanValue = catatan.value.trim();
                    if (catatanValue === "") {
                        e.preventDefault();
                        alert("Silakan isi catatan alasan penolakan terlebih dahulu.");
                    } else {
                        catatanInput.value = catatanValue;
                    }
                });
            }

            // Definisikan section dan tabel yang akan difilter
            const sections = [
                { id: 'section-waiting', tableId: 'table-menunggu', status: 'waiting' },
                { id: 'section-approved', tableId: 'table-diterima', status: 'approved' },
                { id: 'section-declined', tableId: 'table-ditolak', status: 'declined' }
            ];

            // Event listeners untuk filter dan search
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

                // Proses setiap section berdasarkan status
                sections.forEach(section => {
                    const sectionElement = document.getElementById(section.id);

                    // Jika filter status tidak cocok dengan section ini, sembunyikan section
                    if (statusFilter !== 'all' && statusFilter !== section.status) {
                        sectionElement.style.display = 'none';
                        return;
                    } else {
                        sectionElement.style.display = 'block';
                    }

                    const table = document.getElementById(section.tableId);
                    if (!table) return;

                    const rows = table.querySelectorAll('tbody tr:not(.no-data)');
                    let foundInSection = false;

                    // Filter tiap baris dalam tabel
                    rows.forEach(row => {
                        // Ambil data dari kolom
                        const id = row.querySelector('th').textContent.toLowerCase();
                        const nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const jenisSurat = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                        const tanggal = row.getAttribute('data-tanggal');

                        // Filter berdasarkan pencarian text
                        const matchesSearch = searchTerm === '' ||
                            id.includes(searchTerm) ||
                            nama.includes(searchTerm) ||
                            jenisSurat.includes(searchTerm);

                        // Filter berdasarkan rentang tanggal
                        const matchesDate = isInDateRange(tanggal, dateStart, dateEnd);

                        // Tampilkan atau sembunyikan baris berdasarkan hasil filter
                        if (matchesSearch && matchesDate) {
                            row.style.display = '';
                            foundInSection = true;
                            foundAny = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Tampilkan pesan "tidak ada data" jika sesuai
                    const noDataRow = table.querySelector('.no-data');
                    if (noDataRow) {
                        if (rows.length === 0 || !foundInSection) {
                            noDataRow.style.display = ''; // Tampilkan baris "tidak ada data"
                        } else {
                            noDataRow.style.display = 'none'; // Sembunyikan baris "tidak ada data"
                        }
                    }
                });

                // Tampilkan pesan global jika tidak ada hasil sama sekali
                const noResultsAlert = document.getElementById('noResultsAlert');
                if (foundAny) {
                    noResultsAlert.style.display = 'none';
                } else {
                    noResultsAlert.style.display = 'block';
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
                    sectionElement.style.display = 'block';

                    const table = document.getElementById(section.tableId);
                    if (!table) return;

                    const rows = table.querySelectorAll('tbody tr:not(.no-data)');
                    rows.forEach(row => row.style.display = '');

                    const noDataRow = table.querySelector('.no-data');
                    if (noDataRow) {
                        if (rows.length === 0) {
                            noDataRow.style.display = '';
                        } else {
                            noDataRow.style.display = 'none';
                        }
                    }
                });

                document.getElementById('noResultsAlert').style.display = 'none';
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
        });
    </script>
@endsection

@section('ExtraJS')
{{--    <script>--}}
{{--        document.addEventListener("DOMContentLoaded", function() {--}}
{{--            document.querySelectorAll(".detail-btn").forEach(button => {--}}
{{--                button.addEventListener("click", function() {--}}
{{--                    document.getElementById("modalNama").textContent = this.dataset.nama;--}}
{{--                    document.getElementById("modalNRP").textContent = this.dataset.nrp;--}}

{{--                    let jenisSurat;--}}
{{--                    switch(this.dataset.jenis) {--}}
{{--                        case 'keterangan_aktif': jenisSurat = 'Surat Keterangan Aktif'; break;--}}
{{--                        case 'pengantar_tugas': jenisSurat = 'Surat Pengantar Tugas'; break;--}}
{{--                        case 'keterangan_lulus': jenisSurat = 'Surat Keterangan Lulus'; break;--}}
{{--                        case 'hasil_studi': jenisSurat = 'Surat Laporan Hasil Studi'; break;--}}
{{--                        default: jenisSurat = this.dataset.jenis;--}}
{{--                    }--}}
{{--                    document.getElementById("modalJenis").textContent = jenisSurat;--}}

{{--                    document.getElementById("modalTanggal").textContent = this.dataset.tanggal;--}}
{{--                    document.getElementById("modalTanggalPersetujuan").textContent = this.dataset.tanggal_persetujuan || '-';--}}

{{--                    // Tampilkan status dengan format yang lebih baik--}}
{{--                    let statusText;--}}
{{--                    switch(this.dataset.status) {--}}
{{--                        case 'waiting': statusText = 'Menunggu Persetujuan'; break;--}}
{{--                        case 'approved': statusText = 'Disetujui'; break;--}}
{{--                        case 'declined': statusText = 'Ditolak'; break;--}}
{{--                        default: statusText = this.dataset.status;--}}
{{--                    }--}}
{{--                    document.getElementById("modalStatus").textContent = statusText;--}}

{{--                    let extraData = "";--}}

{{--                    if (this.dataset.jenis === "keterangan_aktif") {--}}
{{--                        extraData += `<tr><th>Keperluan</th><td>${this.dataset.keperluan || '-'}</td></tr>`;--}}
{{--                    } else if (this.dataset.jenis === "pengantar_tugas") {--}}
{{--                        extraData += `<tr><th>Kode MK</th><td>${this.dataset.kode_mk || '-'}</td></tr>`;--}}
{{--                        extraData += `<tr><th>Nama MK</th><td>${this.dataset.nama_mk || '-'}</td></tr>`;--}}
{{--                        extraData += `<tr><th>Tujuan</th><td>${this.dataset.tujuan || '-'}</td></tr>`;--}}
{{--                        extraData += `<tr><th>Topik</th><td>${this.dataset.topik || '-'}</td></tr>`;--}}
{{--                    } else if (this.dataset.jenis === "hasil_studi") {--}}
{{--                        extraData += `<tr><th>Keperluan Pengajuan</th><td>${this.dataset.keperluan_hasil_studi || '-'}</td></tr>`;--}}
{{--                    } else {--}}
{{--                        extraData += `<tr><th>Keterangan</th><td>-</td></tr>`;--}}
{{--                    }--}}

{{--                    if (this.dataset.status === 'declined') {--}}
{{--                        extraData += `<tr><th>Alasan Penolakan</th><td>${this.dataset.catatan || '-'}</td></tr>`;--}}
{{--                    }--}}

{{--                    document.getElementById("modalExtraData").innerHTML = extraData;--}}

{{--                    let suratId = this.dataset.id;--}}

{{--                    document.getElementById("formApprove").action = `/kaprodi/surat/${suratId}/update`;--}}
{{--                    document.getElementById("formDecline").action = `/kaprodi/surat/${suratId}/update`;--}}

{{--                    if (this.dataset.status === 'waiting') {--}}
{{--                        document.getElementById("formApprove").style.display = 'inline';--}}
{{--                        document.getElementById("formDecline").style.display = 'inline';--}}
{{--                        document.querySelector(".mb-3").style.display = 'block';--}}
{{--                    } else {--}}
{{--                        document.getElementById("formApprove").style.display = 'none';--}}
{{--                        document.getElementById("formDecline").style.display = 'none';--}}
{{--                        document.querySelector(".mb-3").style.display = 'none'; // Hide textarea--}}
{{--                    }--}}

{{--                    document.getElementById("formDecline").addEventListener("submit", function(e) {--}}
{{--                        document.getElementById("catatanInput").value = document.getElementById("catatan").value;--}}
{{--                    });--}}

{{--                    document.getElementById("formDecline").addEventListener("submit", function(event) {--}}
{{--                        const catatanValue = document.getElementById("catatan").value.trim();--}}
{{--                        if (catatanValue === "") {--}}
{{--                            event.preventDefault();--}}
{{--                            alert("Silakan isi catatan alasan penolakan terlebih dahulu.");--}}
{{--                        } else {--}}
{{--                            document.getElementById("catatanInput").value = catatanValue;--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
@endsection

