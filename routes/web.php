<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SettingController;

/* ---------- Halaman Publik (tanpa login) ---------- */
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/misi-kami', [PublicController::class, 'misi'])->name('misi');
Route::get('/harga', [PublicController::class, 'harga'])->name('harga');
Route::get('/lokasi', [PublicController::class, 'lokasi'])->name('lokasi');

/* ---------- Autentikasi ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Pengaturan profil (untuk nasabah & karyawan)
Route::middleware('auth')->group(function () {
    Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
});

/* ---------- Area Nasabah ---------- */
Route::middleware(['auth', 'role:nasabah'])->prefix('nasabah')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [NasabahController::class, 'dashboard'])->name('dashboard');
    Route::get('/katalog', [NasabahController::class, 'katalog'])->name('katalog');
    Route::get('/qr', [NasabahController::class, 'qr'])->name('qr');
    Route::get('/drop-off', [NasabahController::class, 'dropOffForm'])->name('dropoff');
    Route::post('/drop-off', [NasabahController::class, 'storeDropOff'])->name('dropoff.store');
    Route::post('/withdraw', [NasabahController::class, 'withdraw'])->name('withdraw');
    Route::post('/kartu/ajukan', [NasabahController::class, 'requestCard'])->name('card.request');
});

/* ---------- Area Karyawan ---------- */
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], '/scan', [KaryawanController::class, 'scan'])->name('scan');
    Route::get('/drop-off', [KaryawanController::class, 'dropOff'])->name('dropoff');
    Route::get('/input/{user}', [KaryawanController::class, 'input'])->name('input');
    Route::post('/input', [KaryawanController::class, 'storeTransaction'])->name('input.store');
    Route::post('/penarikan/{withdrawal}/verifikasi', [KaryawanController::class, 'verifyWithdrawal'])->name('withdrawal.verify');
    Route::post('/drop-off/{report}/verifikasi', [KaryawanController::class, 'verifyDropOff'])->name('dropoff.verify');
    Route::get('/kartu', [KaryawanController::class, 'cardRequests'])->name('card.index');
    Route::get('/kartu/{cardRequest}/cetak', [KaryawanController::class, 'showCard'])->name('card.show');
    Route::post('/kartu/{cardRequest}/selesai', [KaryawanController::class, 'markPrinted'])->name('card.printed');
});
