<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('manage-users');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', 'exists:roles,name', function ($attribute, $value, $fail) {
                if ($value === 'admin' && ! auth()->user()->hasRole('admin')) {
                    $fail('Je hebt geen rechten om de beheerder-rol toe te wijzen.');
                }
            }],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'naam',
            'email'    => 'e-mailadres',
            'password' => 'wachtwoord',
            'role'     => 'rol',
        ];
    }
}
