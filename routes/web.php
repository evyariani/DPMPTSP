<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UangHarianController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SPTController;
use App\Http\Controllers\SPDController;
use App\Http\Controllers\LhpdController;
use App\Http\Controllers\RincianBidangController;

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
        Route::get('/export', [SPTController::class, 'export'])->name('spt.export');
        Route::get('/get-next-nomor-urut', [SPTController::class, 'apiGetNextNomorUrut'])->name('spt.api-get-next-nomor-urut');
        Route::get('/create', [SPTController::class, 'create'])->name('spt.create');
        Route::get('/', [SPTController::class, 'index'])->name('spt.index');
        Route::post('/', [SPTController::class, 'store'])->name('spt.store');
        Route::get('/print/{id}', [SPTController::class, 'print'])->name('spt.print');
        Route::get('/preview-pdf/{id}', [SPTController::class, 'previewPdf'])->name('spt.preview-pdf');
        Route::get('/get-pegawai/{id}', [SPTController::class, 'getPegawaiData'])->name('spt.get-pegawai');
        Route::get('/{id}/edit', [SPTController::class, 'edit'])->name('spt.edit');
        Route::put('/{id}', [SPTController::class, 'update'])->name('spt.update');
        Route::delete('/{id}', [SPTController::class, 'destroy'])->name('spt.destroy');
        Route::get('/{id}', [SPTController::class, 'show'])->name('spt.show');
    });
    
    // SPD MANAGEMENT - SEMUA USER BISA AKSES (PEGAWAI, ADMIN, KADIS)
    Route::prefix('spd')->group(function () {
        // ========== ROUTE TANPA PARAMETER (DARI YANG PALING SPESIFIK) ==========
        
        // 1. Route untuk EXPORT (tanpa parameter)
        Route::get('/export', [SPDController::class, 'export'])->name('spd.export');
        
        // 2. Route untuk API Get Next Nomor Urut
        Route::get('/get-next-nomor-urut', [SPDController::class, 'apiGetNextNomorUrut'])->name('spd.api.get-next-nomor-urut');
        
        // 3. Route untuk API Calculate Lama Perjadin
        Route::get('/calculate-lama-perjadin', [SPDController::class, 'calculateLamaPerjadin'])->name('spd.calculate-lama-perjadin');
        
        // 4. Route untuk API Detail SPD (untuk modal)
        Route::get('/api/detail/{id}', [SPDController::class, 'apiDetail'])->name('spd.api.detail');
        
        // 5. Route untuk INDEX (GET /spd) - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/', [SPDController::class, 'index'])->name('spd.index');
        
        // ========== ROUTE DENGAN PARAMETER SPESIFIK ==========
        
        // 6. Route untuk Halaman Belakang SPD
        Route::get('/belakang/{id}', [SPDController::class, 'belakang'])->name('spd.belakang');
        
        // 7. Route untuk UPDATE BELAKANG (Menyimpan Penanda Tangan)
        Route::post('/update-belakang/{id}', [SPDController::class, 'updateBelakang'])->name('spd.update-belakang');
        
        // 8. Route untuk CREATE FROM SPT (membuat SPD otomatis dari SPT)
        Route::get('/create-from-spt/{sptId}', [SPDController::class, 'createFromSpt'])->name('spd.create-from-spt');
        
        // 9. Route untuk API GET DATA (dengan parameter spesifik)
        Route::get('/api/get-spt-data/{id}', [SPDController::class, 'getSptDataForSpd'])->name('spd.api.get-spt-data');
        Route::get('/get-pegawai/{id}', [SPDController::class, 'getPegawaiData'])->name('spd.get-pegawai');
        Route::get('/get-daerah/{id}', [SPDController::class, 'getDaerahData'])->name('spd.get-daerah');
        Route::get('/get-program/{id}', [SPDController::class, 'getProgramData'])->name('spd.get-program');
        
        // 10. Route untuk PDF (dengan parameter spesifik)
        // Print Halaman Depan
        Route::get('/print-depan/{id}', [SPDController::class, 'printDepan'])->name('spd.print-depan');
        Route::get('/preview-depan/{id}', [SPDController::class, 'previewDepan'])->name('spd.preview-depan');
        
        // Print Halaman Belakang
        Route::get('/print-belakang/{id}', [SPDController::class, 'printBelakang'])->name('spd.print-belakang');
        Route::get('/preview-belakang/{id}', [SPDController::class, 'previewBelakang'])->name('spd.preview-belakang');
        
        // ========== ROUTE DENGAN PARAMETER {id} (DILETAKKAN PALING AKHIR) ==========
        Route::get('/{id}/edit', [SPDController::class, 'edit'])->name('spd.edit');
        Route::put('/{id}', [SPDController::class, 'update'])->name('spd.update');
        Route::delete('/{id}', [SPDController::class, 'destroy'])->name('spd.destroy');
        Route::get('/{id}', [SPDController::class, 'show'])->name('spd.show');
    });

    // ========== LHPD MANAGEMENT - SEMUA USER BISA AKSES ==========
    Route::prefix('lhpd')->group(function () {
        // API Routes (diletakkan paling atas)
        Route::get('/export', [LhpdController::class, 'export'])->name('lhpd.export');
        Route::get('/api/get-fotos/{id}', [LhpdController::class, 'getFotos'])->name('lhpd.api.get-fotos');
        Route::get('/api/get-by-spt/{sptId}', [LhpdController::class, 'getBySptId'])->name('lhpd.api.get-by-spt');
        Route::get('/api/get-by-spd/{spdId}', [LhpdController::class, 'getBySpdId'])->name('lhpd.api.get-by-spd');
        
        // Main Routes
        Route::get('/', [LhpdController::class, 'index'])->name('lhpd.index');
        Route::get('/create', [LhpdController::class, 'create'])->name('lhpd.create');
        Route::post('/', [LhpdController::class, 'store'])->name('lhpd.store');
        
        // PDF Routes
        Route::get('/print/{id}', [LhpdController::class, 'print'])->name('lhpd.print');
        Route::get('/preview-pdf/{id}', [LhpdController::class, 'previewPdf'])->name('lhpd.preview-pdf');
        
        // CRUD Routes (diletakkan paling akhir)
        Route::get('/{id}/edit', [LhpdController::class, 'edit'])->name('lhpd.edit');
        Route::put('/{id}', [LhpdController::class, 'update'])->name('lhpd.update');
        Route::delete('/{id}', [LhpdController::class, 'destroy'])->name('lhpd.destroy');
        Route::get('/{id}', [LhpdController::class, 'show'])->name('lhpd.show');
    });

    // ========== RINCIAN BIDANG - SEMUA USER BISA AKSES ==========
    Route::prefix('rincian')->name('rincian.')->group(function () {
        // ========== API ROUTES (diletakkan paling atas) ==========
        Route::post('/sync-all', [RincianBidangController::class, 'syncAll'])->name('sync-all');
        Route::get('/sync-all', [RincianBidangController::class, 'syncAll'])->name('sync-all.get'); // Alternative GET method
        Route::get('/spd/{spdId}/json', [RincianBidangController::class, 'getBySpd'])->name('get-by-spd');
        Route::get('/spd/{spdId}/cetak', [RincianBidangController::class, 'cetakBySpd'])->name('cetak-by-spd');
        
        // ========== PDF ROUTES ==========
        Route::get('/cetak/{id}', [RincianBidangController::class, 'cetak'])->name('cetak');
        Route::get('/preview/{id}', [RincianBidangController::class, 'previewPdf'])->name('preview');
        
        // ========== MAIN ROUTES (CRUD) ==========
        Route::get('/', [RincianBidangController::class, 'index'])->name('index');
        Route::get('/create', [RincianBidangController::class, 'create'])->name('create');
        Route::post('/', [RincianBidangController::class, 'store'])->name('store');
        Route::get('/{id}', [RincianBidangController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RincianBidangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RincianBidangController::class, 'update'])->name('update');
        Route::delete('/{id}', [RincianBidangController::class, 'destroy'])->name('destroy');
    });

    // MODUL LAINNYA (HANYA ADMIN & KADIS)
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