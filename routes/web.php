<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

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
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN AREA (Admin, Pemimpin, Admin Keuangan)
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class . ':admin|pemimpin|admin_keuangan'])->group(function () {
    
    // Redirect dashboard admin langsung ke Data User
    Route::get('/dashboard-admin', function () {
        return redirect('/user');
    });
    
    // User Management Routes
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });
    
    // Data Unit (placeholder)
    Route::get('/unit', function () {
        return view('admin.unit');
    })->name('unit.index');
    
    // Data Meter (placeholder)
    Route::get('/data-meter', function () {
        return view('admin.data-meter');
    })->name('data-meter.index');
});

/*
|--------------------------------------------------------------------------
| USER AREA (Pegawai Biasa)
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class . ':pegawai'])->group(function () {
    Route::get('/dashboard-user', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

/*
|--------------------------------------------------------------------------
| TESTING ROUTES (Bisa dihapus di production)
|--------------------------------------------------------------------------
*/
Route::get('/check-session', function () {
    return response()->json([
        'session' => session()->all(),
        'user' => session('user'),
        'isLoggedIn' => session()->has('user')
    ]);
});

Route::get('/test-admin', function () {
    return 'Halaman Test Admin';
})->middleware(RoleMiddleware::class . ':admin');

Route::get('/test-pegawai', function () {
    return 'Halaman Test Pegawai';
})->middleware(RoleMiddleware::class . ':pegawai');