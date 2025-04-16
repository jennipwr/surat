@extends('layouts.index')

@section('content')
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Pengajuan Surat</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('mahasiswa.apply.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="jenis_surat" class="form-label">Jenis Surat</label>
                                    <select class="form-control" id="jenis_surat" name="jenis_surat" onchange="toggleFields()">
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        <option value="keterangan_aktif">Surat Keterangan Aktif</option>
                                        <option value="pengantar_tugas">Surat Pengantar Tugas Mata Kuliah</option>
                                        <option value="keterangan_lulus">Surat Keterangan Lulus</option>
                                        <option value="hasil_studi">Surat Laporan Hasil Studi</option>
                                    </select>
                                </div>

                                <!-- Surat Keterangan Aktif-->
                                <div id="keterangan_aktif" style="display: none;">
                                    <fieldset disabled>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nrp" class="form-label">NRP</label>
                                            <input type="text" class="form-control" id="mahasiswa_nrp" name="mahasiswa_nrp"
                                                   value="{{ auth()->user()->nrp ?? old('nrp') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="mahasiswa_nama" name="mahasiswa_nama"
                                                   value="{{ auth()->user()->nama ?? old('nama') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat"
                                                   value="{{ auth()->user()->mahasiswa->alamat ?? old('alamat') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="semester" class="form-label">Semester</label>
                                            <input type="text" class="form-control" id="semester" name="semester"
                                                   value="{{ auth()->user()->mahasiswa->semester ?? old('semester') }}" readonly>
                                        </div>
                                    </fieldset>
                                    <div class="mb-3">
                                        <label for="keperluan_aktif" class="form-label">Keperluan Pengajuan</label>
                                        <input type="text" class="form-control" id="keperluan_aktif" name="keperluan_aktif">
                                    </div>
                                </div>

                                <!--Surat Pengantar Tugas Mata Kuliah-->
                                <div id="pengantar_tugas" style="display: none;">
                                    <fieldset disabled>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nrp" class="form-label">NRP</label>
                                            <input type="text" class="form-control" id="mahasiswa_nrp" name="mahasiswa_nrp"
                                                   value="{{ auth()->user()->nrp ?? old('nrp') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="mahasiswa_nama" name="mahasiswa_nama"
                                                   value="{{ auth()->user()->nama ?? old('nama') }}" readonly>
                                        </div>
                                    </fieldset>
                                    <div class="mb-3">
                                        <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                                        <input type="text" class="form-control" id="kode_mk" name="kode_mk">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                                        <input type="text" class="form-control" id="nama_mk" name="nama_mk">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tujuan" class="form-label">Tujuan</label>
                                        <input type="text" class="form-control" id="tujuan" name="tujuan">
                                    </div>
                                    <div class="mb-3">
                                        <label for="topik" class="form-label">Topik</label>
                                        <input type="text" class="form-control" id="topik" name="topik">
                                    </div>
                                </div>

                                <!--Surat Keterangan Lulus-->
                                <div id="keterangan_lulus" style="display: none;">
                                    <fieldset disabled>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nrp" class="form-label">NRP</label>
                                            <input type="text" class="form-control" id="nrp" name="mahasiswa_nrp"
                                                   value="{{ auth()->user()->nrp ?? old('nrp') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="mahasiswa_nama"
                                                   value="{{ auth()->user()->nama ?? old('nama') }}" readonly>
                                        </div>
                                    </fieldset>
                                </div>

                                <!--Surat Laporan Hasil Studi-->
                                <div id="hasil_studi" style="display: none;">
                                    <fieldset disabled>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nrp" class="form-label">NRP</label>
                                            <input type="text" class="form-control" id="nrp" name="mahasiswa_nrp"
                                                   value="{{ auth()->user()->nrp ?? old('nrp') }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mahasiswa_nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="mahasiswa_nama"
                                                   value="{{ auth()->user()->nama ?? old('nama') }}" readonly>
                                        </div>
                                    </fieldset>
                                    <div class="mb-3">
                                        <label for="keperluan_hasil_studi" class="form-label">Keperluan Laporan Hasil Studi</label>
                                        <input type="text" class="form-control" id="keperluan_hasil_studi" name="keperluan_hasil_studi">
                                    </div>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

@endsection

@section('ExtraCss')

@endsection

@section('ExtraJS')
    <script>
        function toggleFields() {
            let jenisSurat = document.getElementById("jenis_surat").value;
            document.getElementById("keterangan_aktif").style.display = (jenisSurat === "keterangan_aktif") ? "block" : "none";
            document.getElementById("pengantar_tugas").style.display = (jenisSurat === "pengantar_tugas") ? "block" : "none";
            document.getElementById("keterangan_lulus").style.display = (jenisSurat === "keterangan_lulus") ? "block" : "none";
            document.getElementById("hasil_studi").style.display = (jenisSurat === "hasil_studi") ? "block" : "none";
        }

    </script>
@endsection
