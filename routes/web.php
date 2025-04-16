<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TUController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
}) ->name('login');

Route::get('/testing', [DemoController::class, 'index']);
Route::get('/login', [LoginController::class, 'index']);

Route::get('/login/kaprodi', [AuthenticatedSessionController::class, 'createKaprodi'])->name('login.kaprodi');
Route::post('/login/kaprodi/post', [AuthenticatedSessionController::class, 'storeKaprodi'])->name('login.kaprodi.post');

Route::get('/login/mahasiswa', [AuthenticatedSessionController::class, 'createMahasiswa'])->name('login.mahasiswa');
Route::post('/login/mahasiswa/post', [AuthenticatedSessionController::class, 'storeMahasiswa'])->name('login.mahasiswa.post');

Route::get('/login/tu', [AuthenticatedSessionController::class, 'createTU'])->name('login.tu');
Route::post('/login/tu/post', [AuthenticatedSessionController::class, 'storeTU'])->name('login.tu.post');

Route::get('/login/admin', [AuthenticatedSessionController::class, 'createAdmin'])->name('login.admin');
Route::post('/login/admin/post', [AuthenticatedSessionController::class, 'storeAdmin'])->name('login.admin.post');

Route::middleware(['auth', 'is.mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/apply', [SuratController::class, 'create'])->name('mahasiswa.apply');
    Route::post('/mahasiswa/apply/store', [SuratController::class, 'store'])->name('mahasiswa.apply.store');
    Route::get('/mahasiswa/surat', [SuratController::class, 'index'])->name('mahasiswa.surat');
    Route::get('/mahasiswa/surat/{id_surat}/edit', [SuratController::class, 'edit'])->name('mahasiswa.surat.edit');
    Route::put('/mahasiswa/surat/{id_surat}', [SuratController::class, 'update'])->name('mahasiswa.surat.update');
    Route::delete('/mahasiswa/surat/{id_surat}', [SuratController::class, 'destroy'])->name('mahasiswa.surat.destroy');
    Route::get('/mahasiswa/surat/download/{id_surat}', [MahasiswaController::class, 'download'])->name('mahasiswa.surat.download');
});

Route::middleware(['auth', 'is.kaprodi'])->group(function () {
    Route::get('/kaprodi/dashboard', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');
    Route::get('/kaprodi/surat', [SuratController::class, 'indexKaprodi'])->name('kaprodi.surat');
    Route::patch('/kaprodi/surat/{id_surat}/update', [SuratController::class, 'updateStatus'])->name('kaprodi.surat.update');
    Route::get('/kaprodi/surat/download/{id_surat}', [KaprodiController::class, 'download'])->name('kaprodi.surat.download');
});

Route::middleware(['auth', 'is.tu'])->group(function () {
    Route::get('/tu/dashboard', [TUController::class, 'index'])->name('tu.dashboard');
    Route::get('/tu/list-mhs', [TUController::class, 'listMhs'])->name('tu.listMahasiswa');
    Route::get('/tu/list-karyawan', [TUController::class, 'listKaryawan'])->name('tu.listKaryawan');
    Route::get('/tu/list-surat', [TUController::class, 'listSurat'])->name('tu.listSurat');
    Route::post('/tu/surat/upload/{id_surat}', [TUController::class, 'upload'])->name('tu.surat.upload');
    Route::get('/download/{id_surat}', [TUController::class, 'downloadSurat'])->name('tu.surat.download');
});

Route::get('/surat/preview-page/{id_surat}', [SuratController::class, 'previewPage'])->name('surat.preview.page');
Route::get('/surat/preview/{id_surat}', [SuratController::class, 'preview'])->name('surat.preview');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/list-karyawan', [AdminController::class, 'listKaryawan'])->name('admin.listKaryawan');
    Route::get('/admin/list-mahasiswa', [AdminController::class, 'listMahasiswa'])->name('admin.listMahasiswa');
    Route::get('/mahasiswa/{nrp}/edit', [AdminController::class, 'editMahasiswa'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{nrp}/update', [AdminController::class, 'updateMahasiswa'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{nrp}/delete', [AdminController::class, 'deleteMahasiswa'])->name('mahasiswa.delete');
    Route::get('/karyawan/{nik}/edit', [AdminController::class, 'editKaryawan'])->name('karyawan.edit');
    Route::put('/karyawan/{nik}/update', [AdminController::class, 'updateKaryawan'])->name('karyawan.update');
    Route::delete('/karyawan/{nik}/delete', [AdminController::class, 'deleteKaryawan'])->name('karyawan.delete');
    Route::get('register/admin', [AdminController::class, 'createUser'])->name('register.admin');
    Route::post('register/admin', [AdminController::class, 'storeUser']);
});


require __DIR__.'/auth.php';
