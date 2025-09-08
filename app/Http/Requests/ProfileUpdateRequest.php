<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'],
            'birthdate' => ['required', 'date', 'before_or_equal:'.now()->toDateString()],
            'code' => ['required', 'string', 'max:4', 'regex:/^[0-9]{4}$/', function ($attribute, $value, $fail) {
                $id = auth()->user()->id;
                $existingUser = User::where('code', $value)->where('id', '!=', $id)->first();
                if ($existingUser) {
                    $fail('Ce code est déjà utilisé.');
                }
            }],
            'note' => ['nullable', 'string', 'max:1000'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', function ($attribute, $value, $fail) {
                $id = auth()->user()->id;
                $existingUser = User::where('email', $value)->where('id', '!=', $id)->first();
                if ($existingUser) {
                    $fail('Ce courriel est déjà utilisé.');
                }
            }]
        ];
    }
}
