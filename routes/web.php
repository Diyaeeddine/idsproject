    <?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth/login');
})->name('admin.login');
Route::get('/register', function () {
    return view('auth/register');
});

Route::get('/user/login', [UserController::class, 'create'])->name('user.login');

Route::post('/user/login', [UserController::class, 'store'])->name('user.login.submit');


/*
|--------------------------------------------------------------------------
| Dashboard utilisateurs
|--------------------------------------------------------------------------
*/

Route::get('user/dashboard', function () {
    return view('user/dashboard');
})->middleware(['auth', 'verified', 'user'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard administrateur
|--------------------------------------------------------------------------
*/

Route::view('admin/dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

    Route::get('user/demandes',[DemandeController::class,'userDemandes'])
    ->middleware(['auth', 'verified', 'user'])
    ->name('user.demandes');
/*
|--------------------------------------------------------------------------
| Gestion du profil utilisateur (authentifié)
|--------------------------------------------------------------------------
*/

Route::middleware('auth', 'verified', 'admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
/*
|--------------------------------------------------------------------------
| Gestion des demandes (admin)
|--------------------------------------------------------------------------
*/

Route::get('/demandes', [DemandeController::class, 'create'])
    ->name('demandes');

Route::get('admin/demandes', [DemandeController::class, 'index'])
    ->name('admin.demandes');

Route::get('admin/demandes/add-demande', [DemandeController::class, 'create'])
    ->name('demande.add-demande');

Route::post('admin/demandes/add-demande', [DemandeController::class, 'store'])
    ->name('demande.store-demande');

Route::get('/admin/demandes/affecter/{id?}', [DemandeController::class, 'affecterPage'])
    ->name('demandes.affecter');

Route::post('/admin/demandes/affecter/{id}', [DemandeController::class, 'affecterUsers'])
    ->name('demandes.affecterUsers');
    Route::post('/admin/demandes/affecter/{id}', [DemandeController::class, 'affecterChamps'])
    ->name('demande.affecterChamps');

    Route::get('/admin/demandes/{id?}', [DemandeController::class, 'demandePage'])
    ->name('demande');
});




/*
|--------------------------------------------------------------------------
| Gestion des utilisateurs (admin)
|--------------------------------------------------------------------------
*/

// Afficher tous les profils
Route::middleware('auth', 'verified', 'admin')->group(function () {

Route::get('admin/profiles', [UserController::class, 'index'])
    ->name('acce.index');

Route::get('admin/profile/add-profile', [UserController::class, 'createProfile'])
    ->name('profile.add-profile');

Route::post('admin/profile/add-profile', [UserController::class, 'store'])
    ->name('storeProfile');

Route::get('admin/profiles/edit/{id}', [UserController::class, 'edit'])
    ->name('acce.edit');

Route::put('profiles/update/{id}', [UserController::class, 'update'])
    ->name('acce.update');

Route::delete('profiles/delete/{id}', [UserController::class, 'destroy'])
    ->name('acce.delete');

Route::get('demande/{id}/pdf', [PDFController::class, 'generatePDF'])->name('demande.pdf');

Route::get('demande/{id}', [DemandeController::class, 'show'])->name('demande.show');

});






/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/





require __DIR__.'/auth.php';

