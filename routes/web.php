<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\UangHarianController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SPTController;
use App\Http\Controllers\RincianBidangController;
use App\Http\Controllers\KwitansiController;

/*
|--------------------------------------------------------------------------
| DEFAULT ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (HANYA UNTUK GUEST/TIDAK LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $level = session('user')['level'] ?? 'guest';
    if ($level == 'kadis') {
        return redirect('/spt');
    } elseif ($level == 'pegawai') {
        return redirect('/spt');
    } else {
        return redirect('/user');
    }
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES - SEMUA USER HARUS LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin|kadis|pegawai'])->group(function () {
    
    // USER MANAGEMENT - HANYA ADMIN & KADIS YANG BISA AKSES
    Route::prefix('user')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });
    
    // PEGAWAI MANAGEMENT - HANYA ADMIN & KADIS YANG BISA AKSES
    Route::prefix('pegawai')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });
    
    // PROGRAM MANAGEMENT - HANYA ADMIN & KADIS YANG BISA AKSES
    Route::prefix('program')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [ProgramController::class, 'index'])->name('program.index');
        Route::get('/create', [ProgramController::class, 'create'])->name('program.create');
        Route::post('/', [ProgramController::class, 'store'])->name('program.store');
        Route::get('/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('/{id}', [ProgramController::class, 'update'])->name('program.update');
        Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
    });
    
    // UANG HARIAN MANAGEMENT - HANYA ADMIN & KADIS YANG BISA AKSES
    Route::prefix('uang-harian')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [UangHarianController::class, 'index'])->name('uang-harian.index');
        Route::get('/create', [UangHarianController::class, 'create'])->name('uang-harian.create');
        Route::post('/', [UangHarianController::class, 'store'])->name('uang-harian.store');
        Route::get('/{id}/edit', [UangHarianController::class, 'edit'])->name('uang-harian.edit');
        Route::put('/{id}', [UangHarianController::class, 'update'])->name('uang-harian.update');
        Route::delete('/{id}', [UangHarianController::class, 'destroy'])->name('uang-harian.destroy');
        Route::get('/get-kabupaten', [UangHarianController::class, 'getKabupaten'])->name('uang-harian.get-kabupaten');
        Route::get('/get-kecamatan', [UangHarianController::class, 'getKecamatan'])->name('uang-harian.get-kecamatan');
    });
    
    // SPT MANAGEMENT - SEMUA USER BISA AKSES (PEGAWAI, ADMIN, KADIS)
    Route::prefix('spt')->group(function () {
        Route::get('/', [SPTController::class, 'index'])->name('spt.index');
        Route::get('/export', [SPTController::class, 'export'])->name('spt.export');
        Route::get('/get-pegawai/{id}', [SPTController::class, 'getPegawaiData'])->name('spt.get-pegawai');
        Route::get('/print/{id}', [SPTController::class, 'print'])->name('spt.print');
        Route::get('/preview-pdf/{id}', [SPTController::class, 'previewPdf'])->name('spt.preview-pdf');
        Route::get('/create', [SPTController::class, 'create'])->name('spt.create');
        Route::get('/{id}', [SPTController::class, 'show'])->name('spt.show');
        Route::get('/{id}/edit', [SPTController::class, 'edit'])->name('spt.edit');
        Route::put('/{id}', [SPTController::class, 'update'])->name('spt.update');
        Route::delete('/{id}', [SPTController::class, 'destroy'])->name('spt.destroy');
        Route::post('/', [SPTController::class, 'store'])->name('spt.store');
    });

    // RINCIAN BIDANG - SEMUA USER BISA AKSES
    Route::prefix('rincian')->group(function () {
        Route::get('/', [RincianBidangController::class, 'index'])->name('rincian.index');
        Route::get('/create', [RincianBidangController::class, 'create'])->name('rincian.create');
        Route::post('/', [RincianBidangController::class, 'store'])->name('rincian.store');
        Route::get('/{id}', [RincianBidangController::class, 'show'])->name('rincian.show');
        Route::get('/{id}/edit', [RincianBidangController::class, 'edit'])->name('rincian.edit');
        Route::put('/{id}', [RincianBidangController::class, 'update'])->name('rincian.update');
        Route::delete('/{id}', [RincianBidangController::class, 'destroy'])->name('rincian.destroy');
        Route::get('/rincian/cetak/{id}', [RincianBidangController::class, 'cetak'])->name('rincian.cetak');
        Route::get('/rincian/preview/{id}', [RincianBidangController::class, 'previewPdf'])->name('rincian.preview');
    });

    // KWITANSI - SEMUA USER BISA AKSES
    Route::prefix('kwitansi')->group(function () {
        Route::get('/', [KwitansiController::class, 'index'])->name('kwitansi.index');
        Route::get('/create', [KwitansiController::class, 'create'])->name('kwitansi.create');
        Route::post('/', [KwitansiController::class, 'store'])->name('kwitansi.store');
        Route::get('/{id}', [KwitansiController::class, 'show'])->name('kwitansi.show');
        Route::get('/{id}/edit', [KwitansiController::class, 'edit'])->name('kwitansi.edit');
        Route::put('/{id}', [KwitansiController::class, 'update'])->name('kwitansi.update');
        Route::delete('/{id}', [KwitansiController::class, 'destroy'])->name('kwitansi.destroy');
        Route::get('/cetak/{id}', [KwitansiController::class, 'cetak'])->name('kwitansi.cetak');
        Route::get('/print/{id}', [KwitansiController::class, 'print'])->name('kwitansi.print');
        Route::get('/preview/{id}', [KwitansiController::class, 'previewPdf'])->name('kwitansi.preview');
});
    
    // TRANSPORTASI MANAGEMENT
    Route::prefix('transportasi')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [TransportasiController::class, 'index'])->name('transportasi.index');
        Route::get('/create', [TransportasiController::class, 'create'])->name('transportasi.create');
        Route::post('/', [TransportasiController::class, 'store'])->name('transportasi.store');
        Route::get('/{id}/edit', [TransportasiController::class, 'edit'])->name('transportasi.edit');
        Route::put('/{id}', [TransportasiController::class, 'update'])->name('transportasi.update');
        Route::delete('/{id}', [TransportasiController::class, 'destroy'])->name('transportasi.destroy');
    });
    
    // REKENING MANAGEMENT
    Route::prefix('rekening')->middleware(['role:admin|kadis'])->group(function () {
        Route::get('/', [RekeningController::class, 'index'])->name('rekening.index');
        Route::get('/create', [RekeningController::class, 'create'])->name('rekening.create');
        Route::post('/', [RekeningController::class, 'store'])->name('rekening.store');
        Route::get('/{id}/edit', [RekeningController::class, 'edit'])->name('rekening.edit');
        Route::put('/{id}', [RekeningController::class, 'update'])->name('rekening.update');
        Route::delete('/{id}', [RekeningController::class, 'destroy'])->name('rekening.destroy');
    });
    
    // MODUL LAINNYA
    Route::middleware(['role:admin|kadis'])->group(function () {
        Route::get('/unit', function () {
            return view('admin.unit');
        })->name('unit.index');
        
        Route::get('/data-meter', function () {
            return view('admin.data-meter');
        })->name('data-meter.index');
    });
});

/*
|--------------------------------------------------------------------------
| TESTING ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/check-session', function () {
    return response()->json([
        'session' => session()->all(),
        'user' => session('user'),
        'isLoggedIn' => session()->has('user')
    ]);
});