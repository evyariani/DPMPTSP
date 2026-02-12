<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\UangHarianController;
use App\Http\Controllers\ProgramController;

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
    return redirect('/user');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES - SEMUA USER HARUS LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin|pemimpin|admin_keuangan|pegawai'])->group(function () {
    
    // USER MANAGEMENT
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/', [UserController::class, 'store'])->name('user.store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });
    });
    
    // PEGAWAI MANAGEMENT
    Route::prefix('pegawai')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
            Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
            Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
            Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
            Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        });
    });
    
    // PROGRAM MANAGEMENT
    Route::prefix('program')->group(function () {
        Route::get('/', [ProgramController::class, 'index'])->name('program.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [ProgramController::class, 'create'])->name('program.create');
            Route::post('/', [ProgramController::class, 'store'])->name('program.store');
            Route::get('/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
            Route::put('/{id}', [ProgramController::class, 'update'])->name('program.update');
            Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
        });
    });
    
    // TRANSPORTASI MANAGEMENT
    Route::prefix('transportasi')->group(function () {
        Route::get('/', [TransportasiController::class, 'index'])->name('transportasi.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [TransportasiController::class, 'create'])->name('transportasi.create');
            Route::post('/', [TransportasiController::class, 'store'])->name('transportasi.store');
            Route::get('/{id}/edit', [TransportasiController::class, 'edit'])->name('transportasi.edit');
            Route::put('/{id}', [TransportasiController::class, 'update'])->name('transportasi.update');
            Route::delete('/{id}', [TransportasiController::class, 'destroy'])->name('transportasi.destroy');
        });
    });
    
    // REKENING MANAGEMENT
    Route::prefix('rekening')->group(function () {
        Route::get('/', [RekeningController::class, 'index'])->name('rekening.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [RekeningController::class, 'create'])->name('rekening.create');
            Route::post('/', [RekeningController::class, 'store'])->name('rekening.store');
            Route::get('/{id}/edit', [RekeningController::class, 'edit'])->name('rekening.edit');
            Route::put('/{id}', [RekeningController::class, 'update'])->name('rekening.update');
            Route::delete('/{id}', [RekeningController::class, 'destroy'])->name('rekening.destroy');
        });
    });
    
    // ============================================
    // UANG HARIAN MANAGEMENT - LENGKAP DENGAN API
    // ============================================
    Route::prefix('uang-harian')->group(function () {
        // CRUD Routes
        Route::get('/', [UangHarianController::class, 'index'])->name('uang-harian.index');
        
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [UangHarianController::class, 'create'])->name('uang-harian.create');
            Route::post('/', [UangHarianController::class, 'store'])->name('uang-harian.store');
            Route::get('/{id}/edit', [UangHarianController::class, 'edit'])->name('uang-harian.edit');
            Route::put('/{id}', [UangHarianController::class, 'update'])->name('uang-harian.update');
            Route::delete('/{id}', [UangHarianController::class, 'destroy'])->name('uang-harian.destroy');
        });
        
        // ========== API ROUTES UNTUK AJAX ==========
        // Ambil kabupaten berdasarkan provinsi (query string)
        Route::get('/get-kabupaten', [UangHarianController::class, 'getKabupaten'])
            ->name('uang-harian.get-kabupaten');
        
        // Ambil kecamatan berdasarkan kabupaten (query string)
        Route::get('/get-kecamatan', [UangHarianController::class, 'getKecamatan'])
            ->name('uang-harian.get-kecamatan');
    });
    
    // MODUL LAINNYA
    Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
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