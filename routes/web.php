<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;





Route::get('/', function () {
    return view('beranda');
});

 Route::get('/dashboard', [UsersController::class, 'index'])
->middleware(['auth'])->name('dashboard');


    Route::prefix('pengajuan')->group(function () {
        Route::get('/', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
        Route::get('/status', [PengajuanController::class, 'status'])->name('pengajuan.status');
    });

    Route::prefix('anggota')->group(function () {
        Route::get('/{pengajuan_id}', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/{pengajuan_id}/create', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('/{pengajuan_id}', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('/update/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/delete/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    });
        
     Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   });

        Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/testadmin', [AdminController::class, 'testadmin'])->name('admin.testadmin');

   });



require __DIR__.'/auth.php';