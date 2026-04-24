<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // ok_medewerker gaat naar de admin/beheer dashboard
        if ($user->hasRole('ok_medewerker')) {
            return redirect()->intended(route('dashboard'));
        }

        // Alle andere rollen gaan naar het teacher dashboard
        return redirect()->intended(route('teacher.dashboard'));
    }
}
