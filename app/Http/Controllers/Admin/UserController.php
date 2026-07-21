<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('manage-users'), only: [
                'index', 'create', 'store', 'edit', 'update', 'destroy', 'teamOverview',
            ]),
        ];
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function teamOverview()
    {
        return view('admin.users.team-overzicht');
    }

    public function create()
    {
        $roles = $this->availableRoles();

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', "Gebruiker \"{$user->name}\" succesvol aangemaakt.");
    }

    public function edit(User $user)
    {
        $roles = $this->availableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            ...($request->filled('password') ? ['password' => Hash::make($request->password)] : []),
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', "Gebruiker \"{$user->name}\" succesvol bijgewerkt.");
    }

    private function availableRoles()
    {
        $query = Role::orderBy('name');

        if (! auth()->user()->hasRole('admin')) {
            $query->where('name', '!=', 'admin');
        }

        return $query->get();
    }

    public function destroy(User $user)
    {
        // Ontkoppel van teams en locaties voor verwijdering
        $user->teams()->detach();
        $user->managedTeams()->detach();
        $user->locations()->detach();

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Gebruiker \"{$name}\" succesvol verwijderd.");
    }
}
