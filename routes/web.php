<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UangHarianController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SptController;
use App\Http\Controllers\SpdController;

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
Route::middleware(['role:admin|pemimpin|admin_keuangan|pegawai'])->group(function () {

    // ============================================
    // USER MANAGEMENT
    // ============================================
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');

        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // PEGAWAI MANAGEMENT
    // ============================================
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');

        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [PegawaiController::class, 'create'])->name('create');
            Route::post('/', [PegawaiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PegawaiController::class, 'update'])->name('update');
            Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // PROGRAM MANAGEMENT
    // ============================================
    Route::prefix('program')->name('program.')->group(function () {
        Route::get('/', [ProgramController::class, 'index'])->name('index');

        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [ProgramController::class, 'create'])->name('create');
            Route::post('/', [ProgramController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProgramController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProgramController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // TRANSPORTASI MANAGEMENT
    // ============================================
    Route::prefix('transportasi')->name('transportasi.')->group(function () {
        Route::get('/', [TransportasiController::class, 'index'])->name('index');

        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [TransportasiController::class, 'create'])->name('create');
            Route::post('/', [TransportasiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [TransportasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TransportasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransportasiController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // REKENING MANAGEMENT
    // ============================================
    Route::prefix('rekening')->name('rekening.')->group(function () {
        Route::get('/', [RekeningController::class, 'index'])->name('index');

        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [RekeningController::class, 'create'])->name('create');
            Route::post('/', [RekeningController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [RekeningController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RekeningController::class, 'update'])->name('update');
            Route::delete('/{id}', [RekeningController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // UANG HARIAN MANAGEMENT
    // ============================================
    Route::prefix('uang-harian')->name('uang-harian.')->group(function () {
        // CRUD Routes - Semua bisa lihat
        Route::get('/', [UangHarianController::class, 'index'])->name('index');

        // Routes untuk operasi CRUD (hanya role tertentu)
        Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
            Route::get('/create', [UangHarianController::class, 'create'])->name('create');
            Route::post('/', [UangHarianController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UangHarianController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UangHarianController::class, 'update'])->name('update');
            Route::delete('/{id}', [UangHarianController::class, 'destroy'])->name('destroy');
        });

        // API Routes untuk AJAX
        Route::get('/get-kabupaten', [UangHarianController::class, 'getKabupaten'])->name('get-kabupaten');
        Route::get('/get-kecamatan', [UangHarianController::class, 'getKecamatan'])->name('get-kecamatan');
    });

    // ============================================
    // SPT MANAGEMENT - SURAT PERINTAH TUGAS
    // ============================================
    // 🔥 PERBAIKAN UTAMA: URUTAN ROUTE YANG BENAR
    Route::prefix('spt')->name('spt.')->group(function () {

        // ========== ROUTES TANPA PARAMETER (URUTAN PENTING!) ==========
        // 1. Route CREATE harus PALING ATAS sebelum route dengan {id}
        Route::get('/create', [SptController::class, 'create'])
            ->name('create')
            ->middleware('role:admin|pemimpin|admin_keuangan');

        // 2. Route INDEX
        Route::get('/', [SptController::class, 'index'])
            ->name('index');

        // 3. Route STORE (POST)
        Route::post('/', [SptController::class, 'store'])
            ->name('store')
            ->middleware('role:admin|pemimpin|admin_keuangan');

        // 4. Route GET PEGAWAI (API)
        Route::get('/get-pegawai', [SptController::class, 'getPegawai'])
            ->name('get-pegawai');

        // ========== ROUTES DENGAN PARAMETER {id} ==========
        // 5. Route SHOW (detail)
        Route::get('/{id}', [SptController::class, 'show'])
            ->name('show');

        // 6. Route CETAK
        Route::get('/{id}/cetak', [SptController::class, 'cetak'])
            ->name('cetak');

        // 7. Route EDIT
        Route::get('/{id}/edit', [SptController::class, 'edit'])
            ->name('edit')
            ->middleware('role:admin|pemimpin|admin_keuangan');

        // 8. Route UPDATE
        Route::put('/{id}', [SptController::class, 'update'])
            ->name('update')
            ->middleware('role:admin|pemimpin|admin_keuangan');

        // 9. Route DELETE
        Route::delete('/{id}', [SptController::class, 'destroy'])
            ->name('destroy')
            ->middleware('role:admin|pemimpin|admin_keuangan');
    });

    // ============================================
    // SPD MANAGEMENT
    // ============================================
    Route::prefix('spd')->name('spd.')->group(function () {

    // INDEX
    Route::get('/', [SpdController::class, 'index'])->name('index');

    // CREATE & STORE
    Route::middleware(['role:admin|pemimpin|admin_keuangan'])->group(function () {
        Route::get('/create', [SpdController::class, 'create'])->name('create');
        Route::post('/', [SpdController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [SpdController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SpdController::class, 'update'])->name('update');
        Route::delete('/{id}', [SpdController::class, 'destroy'])->name('destroy');
    });

    // SHOW (DETAIL)
    Route::get('/{id}', [SpdController::class, 'show'])->name('show');
});

    // ============================================
    // MODUL LAINNYA
    // ============================================
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

// 🔥 ROUTE DEBUG UNTUK SPT (HAPUS SETELAH SELESAI)
Route::get('/debug-spt-routes', function() {
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        if (str_contains($route->uri(), 'spt')) {
            $routes[] = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'methods' => implode(', ', $route->methods()),
                'middleware' => implode(', ', $route->middleware()),
            ];
        }
    }

    // Urutkan berdasarkan URI
    usort($routes, function($a, $b) {
        return strcmp($a['uri'], $b['uri']);
    });

    return response()->json([
        'spt_routes' => $routes,
        'create_route_exists' => collect($routes)->contains('uri', 'spt/create'),
        'create_route_details' => collect($routes)->firstWhere('uri', 'spt/create'),
        'url_create' => url('spt/create')
    ]);
})->middleware('role:admin|pemimpin|admin_keuangan');

// 🔥 ROUTE DEBUG USER LEVEL
Route::get('/debug-user-level', function() {
    $user = session('user');

    return response()->json([
        'is_logged_in' => session()->has('user'),
        'user_data' => $user,
        'user_level' => $user['level'] ?? 'Tidak ada level',
        'allowed_levels_for_create' => ['admin', 'pemimpin', 'admin_keuangan'],
        'can_access_create' => in_array($user['level'] ?? '', ['admin', 'pemimpin', 'admin_keuangan'])
    ]);
});
