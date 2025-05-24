    <?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetTableController;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
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
Route::middleware('auth')->group(function () {
    Route::get('/user/demandes', [UserController::class, 'userDemandes'])->name('user.demandes');
    Route::get('/user/alerts', [UserController::class, 'getAlerts'])->name('user.alerts');
    // Route::post('/user/demandes/{demande}/remplir', [UserController::class, 'remplirDemande'])->name('user.demandes.remplir');
    Route::get('user/demande/afficher/{id}', [DemandeController::class, 'show'])->name('user.demandes.voir');
    Route::get('user/demande/remplir/{id}', [DemandeController::class, 'showRemplir'])->name('user.demandes.showRemplir');
    Route::post('user/demande/remplir/{id}', [DemandeController::class, 'remplir'])->name('user.demandes.remplir');
    // Route::get('/user/demandes/afficher/{id}', [UserController::class, 'show'])->name('user.demandes.voir');

});
Route::get('user/dashboard', [UserController::class, 'userDashboard'])
    ->middleware(['auth', 'verified', 'user'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Dashboard administrateur
|--------------------------------------------------------------------------
*/

Route::view('admin/dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

    Route::get('/demande/{id}/remplir', [DemandeController::class, 'remplir'])
    ->middleware(['auth', 'verified', 'user'])
    ->name('demande.remplir');
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


});


Route::prefix('admin')->middleware(['auth', 'verified','admin'])->group(function () {
    Route::get('/budgetaires/create', [BudgetTableController::class, 'create'])->name('budget-tables.create');
    Route::post('/budgetaires', [BudgetTableController::class, 'store'])->name('budget-tables.store');
    Route::get('/tables-budgetaires', [BudgetTableController::class, 'index'])->name('budget-tables.index');
    Route::get('/tables-budgetaires/{id}', [BudgetTableController::class, 'show'])->name('budget-tables.show');
});




/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/





require __DIR__.'/auth.php';

