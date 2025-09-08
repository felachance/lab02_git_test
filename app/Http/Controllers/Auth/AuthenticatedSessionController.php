<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        // Si pas de manager. Fait en un
        if (!User::whereHas('roles', function ($query) {
            $query->where('role', 'manager');
        })->exists())
            return redirect()->route('register')->with('error', 'Il n\'y a pas de gérant dans le système. Veuillez en créer un.');

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if ($request->routeIs('loginAPI'))
            return response()->json(['success' => 'L\'utilisateur est valide.'], 200);

        else if (!Auth::user()->roles->contains('role', 'manager')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('erreur', 'Vous devez être un gérant pour accéder à cette section.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
