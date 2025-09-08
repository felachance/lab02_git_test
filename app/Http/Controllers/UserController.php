<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationUser;
use App\Models\Availability;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee/employeeList', [
            'users' => User::where('active', true)->get(),
            'roles' => Role::All()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee/addEmployeeForm', [
            'roles' => Role::All()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $user->roles()->attach($request->roles);

        // Ajout des disponibilités par défaut
        for($i=0; $i < 7; $i++) {
            $availability = new Availability();
            $availability->id_user = $user->id;
            $availability->day_of_week = $i;
            $availability->start_time = '00:00';
            $availability->end_time = '23:59';
            $availability->save();
        }

        Mail::to($request->email)->send(new ConfirmationUser($user));

        if ($user->exists)
            session()->flash('succes', 'L\'ajout de l\'employé a bien fonctionné.');
        else
            session()->flash('erreur', 'L\'ajout de l\'employé n\'a pas fonctionné.');

        return redirect(route('employee.index', absolute: false));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // API only
        $user = User::with('roles')->find($id);

        if (!$user)
            return response()->json(['error' => 'Utilisateur non trouvé.'], 404);

        return response()->json(['user' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('code', $id)->first();

        if (!$user) {
            session()->flash('erreur', 'L\'utilisateur n\'extiste pas.');
            return redirect()->route('employee.index');
        }
        if (!$user->active) {
            session()->flash('erreur', 'L\'utilisateur est désactivé et ne peut pas être modifié.');
            return redirect()->route('employee.index');
        }

        return view('employee/employeeDetail', [
            'user' => $user,
            'roles' => Role::All()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:'.now()->toDateString()],
            'code' => ['required', 'string', 'max:4', 'regex:/^[0-9]{4}$/', function ($attribute, $value, $fail) use ($id) {
            $existingUser = User::where('code', $value)->where('id', '!=', $id)->first();
            if ($existingUser)
                $fail('Ce code est déjà utilisé.');
            }],
            'note' => ['nullable', 'string', 'max:1000'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', function ($attribute, $value, $fail) use ($id) {
            $existingUser = User::where('email', $value)->where('id', '!=', $id)->first();
            if ($existingUser)
                $fail('Ce courriel est déjà utilisé.');
            }],
            'password' => ['nullable', 'confirmed', 'regex:/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*()\/\\?]).+/'],
            'roles' => ['nullable', 'array', function ($attribute, $value, $fail) {
            $invalidRoles = array_diff($value, Role::pluck('id')->toArray());
            if (!empty($invalidRoles))
                $fail('Un ou plusieurs rôles sont invalides.');
            }],
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
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        if ($validation->fails()) {
            if ($request->routeIs('userAPI.update'))
                return response()->json(['error' => $validation->errors()], 422);

            return redirect()->back()->withErrors($validation)->withInput();
        }

        $user = User::find($id);
        if (!$user) {
            if ($request->routeIs('userAPI.update'))
                return response()->json(['error' => 'Utilisateur non trouvé.'], 404);

            session()->flash('erreur', 'L\'utilisateur n\'existe pas.');
            return redirect()->route('employee.index');
        }

        if ($user->active == false) {
            if ($request->routeIs('userAPI.update'))
                return response()->json(['error' => 'Utilisateur désactivé.'], 403);

            session()->flash('erreur', 'La modification de l\'employé n\'a pas fonctionné. L\'utilisateur est désactivé.');
            return redirect()->route('employee.index');
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_number = $request->phone_number;
        $user->code = $request->code;
        $user->birthdate = $request->birthdate;
        $user->email = $request->email;
        $user->note = $request->note;

        if ($request->password)
            $user->password = Hash::make($request->password);
        $user->roles()->detach();
        $user->roles()->attach($request->roles);

        if ($user->save()) {
            if ($request->routeIs('userAPI.update'))
                return response()->json(['success' => 'La modification de l\'employé a bien fonctionné.'], 200);

            session()->flash('succes', 'La modification de l\'employé a bien fonctionné.');
        } else {
            if ($request->routeIs('userAPI.update')) {
                return response()->json(['error' => 'La modification de l\'employé n\'a pas été sauvegardée.'], 400);
            }
            session()->flash('erreur', 'La modification de l\'employé n\'a pas fonctionné.');
        }

        return redirect()->route('employee.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->active = false;

        if ($user->save())
            session()->flash('succes', 'La désactivation de l\'employé a bien fonctionné.');
        else
            session()->flash('erreur', 'La désactivation de l\'employé n\'a pas fonctionné.');

        return redirect(route('employee.index', absolute: false));
    }

    /**
     * Get the result of a research of users
     */
    public function getResearch(Request $request) {
        $request->validate([
            'research' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'int'],
            'seniority' => ['nullable', 'in:asc,desc'],
        ]);

        $query = User::with('roles');

        if ($request->filled('research')) {
            $search = $request->input('research');
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('code', 'like', "%{$search}%");
                } else {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('role')) {
            $roleId = $request->input('role');
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', $roleId);
            });
        }

        if ($request->filled('seniority')) {
            $direction = $request->input('seniority');
            $query->orderBy('created_at', $direction);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return response()->json(['error' => 'Aucun employé ne correspond à la recherche.'], 404);
        }

        return response()->json(['users' => $users], 200);
    }
}
