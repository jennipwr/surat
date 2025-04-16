@extends('layouts.index')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Pengajuan Surat</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('mahasiswa.surat.update', $surat->id_surat) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="jenis_surat" class="form-label">Jenis Surat</label>
                                <select class="form-control" id="jenis_surat" name="jenis_surat" disabled>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    <option value="keterangan_aktif" {{ $surat->jenis_surat == 'keterangan_aktif' ? 'selected' : ''}}>Surat Keterangan Aktif</option>
                                    <option value="pengantar_tugas" {{ $surat->jenis_surat == 'pengantar_tugas' ? 'selected' : ''}}>Surat Pengantar Tugas Mata Kuliah</option>
                                    <option value="keterangan_lulus" {{ $surat->jenis_surat == 'keterangan_lulus' ? 'selected' : ''}}>Surat Keterangan Lulus</option>
                                    <option value="hasil_studi" {{ $surat->jenis_surat == 'hasil_studi' ? 'selected' : ''}}>Surat Laporan Hasil Studi</option>
                                </select>
                                <input type="hidden" name="jenis_surat" value="{{ $surat->jenis_surat }}">
                            </div>

                            @if($surat->jenis_surat == 'keterangan_aktif')
                                <div>
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
                                        <label for="keperluan_aktif" class="form-label">Keperluan Pengajuan</label>
                                        <input type="text" class="form-control" id="keperluan_aktif" name="keperluan_aktif" value="{{ $surat->keperluan_aktif }}">
                                    </div>
                                </div>
                            @endif

                            @if($surat->jenis_surat == 'pengantar_tugas')
                                <div>
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
                                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" value="{{ $surat->kode_mk }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" value="{{ $surat->nama_mk }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tujuan" class="form-label">Tujuan</label>
                                        <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ $surat->tujuan }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="topik" class="form-label">Topik</label>
                                        <input type="text" class="form-control" id="topik" name="topik" value="{{ $surat->topik }}">
                                    </div>
                                </div>
                            @endif

                            @if($surat->jenis_surat == 'keterangan_lulus')
                                <div>
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
                            @endif

                            @if($surat->jenis_surat == 'hasil_studi')
                                <div>
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
                                        <input type="text" class="form-control" id="keperluan_hasil_studi" name="keperluan_hasil_studi" value="{{ $surat->keperluan_hasil_studi }}">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
