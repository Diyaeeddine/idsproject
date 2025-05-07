<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('user/dashboard', function () {
    return view('user/dashboard');
})->middleware(['auth', 'verified','user'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::view('admin/dashboard','admin.dashboard')->middleware(['auth','verified','admin'])->name('admin.dashboard');
Route::get('/demandes', [DemandeController::class,'create'])->middleware(['auth','verified','admin'])->name('demandes');  
Route::get('admin/demandes', [DemandeController::class, 'adminIndex'])->middleware(['auth', 'verified', 'admin'])->name('admin.demandes');
// Route::get('admin/profiles', [UserController::class, 'showUsers'])->middleware(['auth','verified', 'admin'])->name('admin.profiles');
Route::get('admin/profile/add-profile', [UserController::class, 'create'])->middleware(['auth','verified','admin'])->name('profile.add-profile');
Route::post('admin/profile/add-profile', [UserController::class, 'store'])->middleware(['auth','verified','admin'])->name('storeProfile');
Route::get('admin/profiles/edit/{id}', [UserController::class, 'edit'])->middleware(['auth','verified','admin'])->name('acce.edit');
Route::delete('profiles/delete/{id}', [UserController::class, 'destroy'])->middleware(['auth','verified','admin'])->name('acce.delete');
Route::put('profiles/update/{id}', [UserController::class, 'update'])->middleware(['auth','verified','admin'])->name('acce.update');
Route::get('admin/profiles', [UserController::class, 'index'])->middleware(['auth','verified','admin'])->name('acce.index');
Route::get('admin/demandes/add-demande',[DemandeController::class,'create'])->middleware(['auth','verified','admin'])->name('demande.add-demande');
Route::post('admin/demandes/add-demande',[DemandeController::class,'store'])->middleware(['auth','verified','admin'])->name('demande.store-demande');
Route::get('/admin/demandes/affecter/{id?}', [DemandeController::class, 'affecterPage'])->name('demandes.affecter');
Route::post('/admin/demandes/affecter/{id}', [DemandeController::class, 'affecterUser'])->name('demandes.affecterUser');

// Route::post('admin/demandes/add-demande', [DemandeController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('demande.store');

##----------
// Route::get('admin/demande/add-demand', [DemandeController::class, 'create'])->middleware(['auth','verified','admin'])->name('demande.add-demand');
// Route::post('demande/store', [DemandeController::class, 'store'])->middleware(['auth', 'verified','admin'])->name('demande.store');

require __DIR__.'/auth.php';
