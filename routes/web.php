<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TUController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
}) ->name('login');

Route::get('/testing', [DemoController::class, 'index']);
Route::get('/login', [LoginController::class, 'index']);

// Kaprodi
Route::get('/login/kaprodi', [AuthenticatedSessionController::class, 'createKaprodi']) -> name('login.kaprodi');
Route::post('/login/kaprodi/post', [AuthenticatedSessionController::class, 'storeKaprodi']) -> name('login.kaprodi.post');
Route::get('/kaprodi/dashboard', [KaprodiController::class, 'index']) -> name('kaprodi.dashboard');

// Mahasiswa
Route::get('/login/mahasiswa', [AuthenticatedSessionController::class, 'createMahasiswa']) -> name('login.mahasiswa');
Route::post('/login/mahasiswa/post', [AuthenticatedSessionController::class, 'storeMahasiswa']) -> name('login.mahasiswa.post');
Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index']) -> name('mahasiswa.dashboard');

//TU
Route::get('/login/tu', [AuthenticatedSessionController::class, 'createTU']) -> name('login.tu');
Route::post('/login/tu/post', [AuthenticatedSessionController::class, 'storeTU']) -> name('login.tu.post');
Route::get('/tu/dashboard', [TUController::class, 'index']) -> name('tu.dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
