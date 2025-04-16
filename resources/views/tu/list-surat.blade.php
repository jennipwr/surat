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

                            <input type="radio" class="btn-check" name="statusFilter" id="filterUploaded" value="uploaded">
                            <label class="btn btn-outline-success" for="filterUploaded">Sudah Terupload</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-secondary w-100" id="resetFilter">Reset Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Surat yang Perlu Diupload -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Surat yang Perlu Diupload</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID Surat</th>
                        <th scope="col">NRP</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Tanggal Persetujuan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suratBelumUpload as $surat)
                        <tr>
                            <th scope="row">{{ $surat->id_surat }}</th>
                            <td>{{ $surat->mahasiswa_nrp }}</td>
                            <td>{{ $surat->mahasiswa_nama }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td>{{ $surat->tanggal_pengajuan ? \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') : '-' }}</td>
                            <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info detail-btn" data-bs-toggle="modal" data-bs-target="#detailModal"
                                        data-id="{{ $surat->id_surat }}" data-nama="{{ $surat->mahasiswa_nama }}"
                                        data-nrp="{{ $surat->mahasiswa_nrp }}" data-jenis="{{ $surat->jenis_surat }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}" data-status="Pending"
                                        data-tanggal_persetujuan="{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') }}"
                                        data-keperluan="{{ $surat->keperluan ?? '' }}" data-kode_mk="{{ $surat->kode_mk ?? '' }}"
                                        data-nama_mk="{{ $surat->nama_mk ?? '' }}" data-tujuan="{{ $surat->tujuan ?? '' }}"
                                        data-topik="{{ $surat->topik ?? '' }}" data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                    Upload
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada surat yang perlu diunggah</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Surat yang Sudah Diupload -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Surat yang Sudah Diupload</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID Surat</th>
                        <th scope="col">NRP</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col">Tanggal Pengajuan</th>
                        <th scope="col">Tanggal Persetujuan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($suratSudahUpload as $surat)
                        <tr>
                            <th scope="row">{{ $surat->id_surat }}</th>
                            <td>{{ $surat->mahasiswa_nrp }}</td>
                            <td>{{ $surat->mahasiswa_nama }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            <td>{{ $surat->tanggal_pengajuan ? \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') : '-' }}</td>
                            <td>{{ $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') : '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info detail-btn" data-bs-toggle="modal" data-bs-target="#detailModal"
                                            data-id="{{ $surat->id_surat }}" data-nama="{{ $surat->mahasiswa_nama }}"
                                            data-nrp="{{ $surat->mahasiswa_nrp }}" data-jenis="{{ $surat->jenis_surat }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y') }}" data-status="Approved"
                                            data-tanggal_persetujuan="{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d M Y') }}"
                                            data-keperluan="{{ $surat->keperluan ?? '' }}" data-kode_mk="{{ $surat->kode_mk ?? '' }}"
                                            data-nama_mk="{{ $surat->nama_mk ?? '' }}" data-tujuan="{{ $surat->tujuan ?? '' }}"
                                            data-topik="{{ $surat->topik ?? '' }}" data-keperluan_hasil_studi="{{ $surat->keperluan_hasil_studi ?? '' }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <a href="{{ route('tu.surat.download', $surat->id_surat) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('surat.preview.page', $surat->id_surat) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada surat yang diunggah</td>
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
                    <table class="table">
                        <tr><th>Jenis Surat</th><td id="modalJenis"></td></tr>
                        <tr><th>Tanggal Pengajuan</th><td id="modalTanggal"></td></tr>
                        <tr><th>Tanggal Persetujuan</th><td id="modalTanggalPersetujuan"></td></tr>
                        <tr><th>Status</th><td id="modalStatus"></td></tr>
                        <tr><th>NRP</th><td id="modalNRP"></td></tr>
                        <tr><th>Nama</th><td id="modalNama"></td></tr>
                        <tbody id="modalExtraData"></tbody>
                        <tr id="modalUploadRow" style="display: none;">
                            <th>Upload Surat</th>
                            <td>
                                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group mb-2">
                                        <input type="file" name="file" id="uploadInput" class="form-control form-control-sm" required>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="clearUploadBtn" title="Hapus File">
                                            &times;
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success">Upload</button>
                                </form>

                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ExtraCSS')

@endsection

@section('ExtraJS')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variabel untuk elemen filter
            const searchGlobal = document.getElementById("searchGlobal");
            const btnSearchGlobal = document.getElementById("btnSearchGlobal");
            const filterDateStart = document.getElementById("filterDateStart");
            const filterDateEnd = document.getElementById("filterDateEnd");
            const filterStatusRadios = document.querySelectorAll('input[name="statusFilter"]');
            const resetFilterBtn = document.getElementById("resetFilter");

            // Event listeners untuk filter
            btnSearchGlobal.addEventListener("click", applyFilters);
            searchGlobal.addEventListener("keyup", function(event) {
                if (event.key === "Enter") {
                    applyFilters();
                }
            });

            filterDateStart.addEventListener("change", filterByDate);
            filterDateEnd.addEventListener("change", filterByDate);

            filterStatusRadios.forEach(radio => {
                radio.addEventListener("change", applyStatusFilter);
            });

            resetFilterBtn.addEventListener("click", resetFilters);

            // Fungsi untuk filter berdasarkan tanggal
            function filterByDate() {
                const startDate = new Date(filterDateStart.value);
                const endDate = new Date(filterDateEnd.value);
                const rows = document.querySelectorAll("table tbody tr");

                rows.forEach(row => {
                    const tanggalCell = row.querySelector("td:nth-child(5)");
                    if (!tanggalCell) return; // Skip jika tidak ada cell tanggal

                    const tanggalPengajuan = tanggalCell.textContent.trim();
                    if (tanggalPengajuan !== '-') {
                        const tanggalParts = tanggalPengajuan.split(" ");
                        const day = parseInt(tanggalParts[0]);
                        const month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'].indexOf(tanggalParts[1]);
                        const year = parseInt(tanggalParts[2]);
                        const rowDate = new Date(year, month, day);

                        // Check if date is within the selected range
                        if ((isNaN(startDate.getTime()) || rowDate >= startDate) &&
                            (isNaN(endDate.getTime()) || rowDate <= endDate)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    } else {
                        // Handle cases where there's no date
                        if (isNaN(startDate.getTime()) && isNaN(endDate.getTime())) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                });
            }

            // Fungsi untuk filter berdasarkan status
            function applyStatusFilter() {
                const selectedStatus = document.querySelector('input[name="statusFilter"]:checked').value;
                const waitingTable = document.querySelector('.card-header.bg-warning').closest('.card');
                const uploadedTable = document.querySelector('.card-header.bg-success').closest('.card');

                if (selectedStatus === "waiting") {
                    waitingTable.style.display = "";
                    uploadedTable.style.display = "none";
                } else if (selectedStatus === "uploaded") {
                    waitingTable.style.display = "none";
                    uploadedTable.style.display = "";
                } else {
                    // "all" option
                    waitingTable.style.display = "";
                    uploadedTable.style.display = "";
                }
            }

            // Fungsi untuk menerapkan semua filter
            function applyFilters() {
                const searchText = searchGlobal.value.toLowerCase();
                const rows = document.querySelectorAll("table tbody tr");

                rows.forEach(row => {
                    const idSurat = row.querySelector("th").textContent.toLowerCase();
                    const nrp = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    const nama = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                    const jenisSurat = row.querySelector("td:nth-child(4)").textContent.toLowerCase();

                    if (idSurat.includes(searchText) || nrp.includes(searchText) ||
                        nama.includes(searchText) || jenisSurat.includes(searchText)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            // Fungsi untuk mereset semua filter
            function resetFilters() {
                searchGlobal.value = "";
                filterDateStart.value = "";
                filterDateEnd.value = "";
                document.getElementById("filterAll").checked = true;

                const rows = document.querySelectorAll("table tbody tr");
                rows.forEach(row => {
                    row.style.display = "";
                });

                const waitingTable = document.querySelector('.card-header.bg-warning').closest('.card');
                const uploadedTable = document.querySelector('.card-header.bg-success').closest('.card');
                waitingTable.style.display = "";
                uploadedTable.style.display = "";
            }

            // Handler untuk tombol detail
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
                    document.getElementById("modalTanggalPersetujuan").textContent = this.dataset.tanggal_persetujuan;
                    document.getElementById("modalStatus").textContent = this.dataset.status;

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

                    document.getElementById("modalExtraData").innerHTML = extraData;

                    if (this.dataset.status === "Pending") {
                        const formUpload = document.getElementById("uploadForm");
                        const uploadRow = document.getElementById("modalUploadRow");
                        uploadRow.style.display = "table-row";
                        formUpload.action = `/tu/surat/upload/${this.dataset.id}`; // Atur action sesuai route
                    } else {
                        document.getElementById("modalUploadRow").style.display = "none";
                    }
                });
            });

            // Menangani clear button untuk input file
            document.getElementById("clearUploadBtn").addEventListener("click", function() {
                document.getElementById("uploadInput").value = "";
            });
        });
    </script>
@endsection
