<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free - Register</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <style>
        .hidden { display: none; }
    </style>
</head>

<body>
<div class="page-wrapper d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-8 col-lg-6 col-xxl-4">
        <div class="card shadow-lg">
            <div class="card-body">
                <h4 class="text-center mb-4">Register</h4>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" onchange="toggleFields()">
                            <option value="">-- Select Role --</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="karyawan">Karyawan (TU/Kaprodi)</option>
                        </select>
                    </div>

                    <!-- Mahasiswa Fields -->
                    <div id="mahasiswaFields" class="hidden">
                        <div class="mb-3">
                            <label for="nrp" class="form-label">NRP</label>
                            <input type="text" class="form-control" id="nrp" name="nrp">
                        </div>
                        <div class="mb-3">
                            <label for="nama_mhs" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama_mhs" name="nama_mhs">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" class="form-control" id="semester" name="semester" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="email_mhs" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_mhs" name="email_mhs">
                        </div>
                        <div class="mb-3">
                            <label for="prodi_mhs" class="form-label">Prodi</label>
                            <select class="form-control" id="prodi_mhs" name="prodi_mhs">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodiList as $prodi)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password_mhs" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_mhs" name="password_mhs">
                        </div>
                        <div class="mb-3">
                            <label for="password_mhs_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_mhs_confirmation" name="password_mhs_confirmation">
                        </div>
                        <div id="mhs-password-error" class="text-danger small"></div>
                    </div>

                    <!-- Karyawan Fields -->
                    <div id="karyawanFields" class="hidden">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik">
                        </div>
                        <div class="mb-3">
                            <label for="nama_karyawan" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan">
                        </div>
                        <div class="mb-3">
                            <label for="email_karyawan" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_karyawan" name="email_karyawan">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-control" id="jabatan" name="jabatan">
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="tu">Tata Usaha</option>
                                <option value="kaprodi">Kepala Program Studi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prodi_karyawan" class="form-label">Prodi</label>
                            <select class="form-control" id="prodi_karyawan" name="prodi_karyawan">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodiList as $prodi)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password_karyawan" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_karyawan" name="password_karyawan">
                        </div>
                        <div class="mb-3">
                            <label for="password_karyawan_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_karyawan_confirmation" name="password_karyawan_confirmation">
                        </div>
                        <div id="karyawan-password-error" class="text-danger small"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function toggleFields() {
        const role = document.getElementById("role").value;
        document.getElementById("mahasiswaFields").classList.toggle("hidden", role !== "mahasiswa");
        document.getElementById("karyawanFields").classList.toggle("hidden", role !== "karyawan");
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Listen to all password fields
        document.querySelectorAll("input[type='password']").forEach(field => {
            field.addEventListener("input", function () {
                const role = document.getElementById("role").value;
                if (role === "mahasiswa") {
                    const pass = document.getElementById("password_mhs").value;
                    const confirm = document.getElementById("password_mhs_confirmation").value;
                    document.getElementById("mhs-password-error").innerText = (pass !== confirm) ? "Password dan konfirmasi tidak cocok." : "";
                } else if (role === "karyawan") {
                    const pass = document.getElementById("password_karyawan").value;
                    const confirm = document.getElementById("password_karyawan_confirmation").value;
                    document.getElementById("karyawan-password-error").innerText = (pass !== confirm) ? "Password dan konfirmasi tidak cocok." : "";
                }
            });
        });
    });
</script>

<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
