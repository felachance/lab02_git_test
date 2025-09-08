<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // Si un Manager exist deja, on refuse l'inscription
        if (User::whereHas('roles', function ($query) {
            $query->where('role', 'manager');
        })->exists())
            return redirect()->route('login')->with('erreur', 'Un gérant existe déjà. Inscription refusé.');

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'],
            'birthdate' => ['required', 'date', 'before_or_equal:'.now()->toDateString()],
            'code' => ['required', 'string', 'max:4', 'unique:'.User::class, 'regex:/^[0-9]{4}$/'],
            'note' => ['nullable', 'string', 'max:1000'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'regex:/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*()\/\\?]).+/'],
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'first_name.regex' => 'Le prénom contient des caractères invalides.',
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.regex' => 'Le nom contient des caractères invalides.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'phone_number.regex' => 'Le format du numéro de téléphone est invalide.',
            'birthdate.required' => 'La date de naissance est obligatoire.',
            'birthdate.before_or_equal' => 'La date de naissance doit être antérieure ou égale à aujourd\'hui.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé.',
            'code.regex' => 'Le code doit être composé de 4 chiffres.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        $managerRole = Role::where('role', 'manager')->first();

        // Si un Manager exist deja, on refuse l'inscription
        if (User::whereHas('roles', function ($query) use ($managerRole) {
            $query->where('id', $managerRole->id);
        })->exists())
            return redirect()->route('login')->with('erreur', 'Un gérant exist déjà. Inscription refusé.');

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'birthdate' => $request->birthdate,
            'code' => $request->code,
            'note' => $request->note,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->roles()->attach($managerRole->id);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function show(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'required'
        ], [
            'email.required' => 'Veuillez entrer le courriel de l\'utilisateur.',
            'email.email' => 'Le courriel de l\'utilisateur doit être valide.',
            'password.required' => 'Veuillez entrer le mot de passe de l\'utilisateur.',
            'token_name.required' => 'Veuillez inscrire le nom souhaité pour le token.'
        ]);
        if ($validation->fails())
            return response()->json(['error' => $validation->errors()], 400);

        $contenuDecode = $validation->validated();
        $utilisateur = User::where('email', '=', $contenuDecode['email'])->first();

        if (($utilisateur === null) || !Hash::check($contenuDecode['password'], $utilisateur->password))
            return response()->json(['error' => 'Informations d\'authentification invalides'], 500);

        $token = $utilisateur->createToken($contenuDecode['token_name'])->plainTextToken;
        $utilisateur->api_token = Hash::make($token);

        if ($utilisateur->save())
            return response()->json(['token' => $token, 'user' => $utilisateur], 200);
        else
            return response()->json(['error' => 'Le token n\'a pas bien été sauvegardé.']);
    }
}
