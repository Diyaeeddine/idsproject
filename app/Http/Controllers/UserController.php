<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;  // Importer la classe Hash
use Illuminate\Validation\Rules\Password;  // Importer la classe Password
use Illuminate\Auth\Events\Registered;  // Importer la classe Registered

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = User::query();

    // Si tu veux filtrer uniquement les utilisateurs (pas les admins) :
    $query->where('role', UserRole::User);

    // Recherche par nom
    if ($request->has('search') && $request->search !== null) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $users = $query->latest()->get();

    return view('admin.profiles.profiles', compact('users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.profiles.add-profile');
    }
    public function create_demande(){
        return view('admin.demandes.add-demande');
    }
    public function store_demande(){
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]); 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        return redirect()->route('admin.profiles')->with('success', 'Profil créé avec succès');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user=User::find($id);
        return view('admin.profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string',
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ]);

    return redirect()->route('admin.profiles')->with('success', 'Utilisateur mis à jour avec succès.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.profiles')->with('success', 'Utilisateur supprimé avec succès.');

    }
}
