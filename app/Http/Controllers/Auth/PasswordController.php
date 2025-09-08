<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'regex:/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*()\/\\?]).+/'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated) {
            session()->flash('succes', 'Mot de passe mis à jour avec succès.');
        } else {
            session()->flash('erreur', 'Échec de la mise à jour du mot de passe.');
        }
        return back()->with('status', 'password-updated');
    }
}
