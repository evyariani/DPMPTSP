<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransportasiController;

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
    // RoleMiddleware akan menangani cek session dan role
    
    // USER MANAGEMENT (SEMUA USER YANG LOGIN BISA LIHAT)
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        
        // CREATE, EDIT, DELETE hanya untuk admin roles
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/', [UserController::class, 'store'])->name('user.store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });
    });
    
    // PEGAWAI MANAGEMENT - SUDAH ADA
    Route::prefix('pegawai')->group(function () {
        // Semua user yang login bisa melihat data pegawai
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        
        // CREATE, EDIT, DELETE hanya untuk admin roles
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
            Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
            Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
            Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
            Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        });
    });
    
    // TRANSPORTASI MANAGEMENT - TAMBAHAN BARU
    Route::prefix('transportasi')->group(function () {
        // Semua user yang login bisa melihat data transportasi
        Route::get('/', [TransportasiController::class, 'index'])->name('transportasi.index');
        
        // CREATE, EDIT, DELETE hanya untuk admin roles
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [TransportasiController::class, 'create'])->name('transportasi.create');
            Route::post('/', [TransportasiController::class, 'store'])->name('transportasi.store');
            Route::get('/{id}/edit', [TransportasiController::class, 'edit'])->name('transportasi.edit');
            Route::put('/{id}', [TransportasiController::class, 'update'])->name('transportasi.update');
            Route::delete('/{id}', [TransportasiController::class, 'destroy'])->name('transportasi.destroy');
        });
    });
    
    // MODUL LAINNYA hanya untuk admin roles
    Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
        // HAPUS SEMUA BARIS INI YANG TERKAIT PEGAWAI:
        // Route::get('/pegawai', function () {
        //     return view('admin.pegawai');
        // })->name('pegawai.index');
        
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
| TESTING ROUTES (Opsional)
|--------------------------------------------------------------------------
*/
Route::get('/check-session', function () {
    return response()->json([
        'session' => session()->all(),
        'user' => session('user'),
        'isLoggedIn' => session()->has('user')
    ]);
});