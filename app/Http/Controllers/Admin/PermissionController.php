<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('manage-permissions'), only: [
                'index', 'store', 'destroy',
            ]),
        ];
    }

    public function index()
    {
        return view('admin.permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-z][a-z0-9-]*$/', 'unique:permissions,name'],
        ], [
            'name.required'  => 'Naam is verplicht.',
            'name.regex'     => 'Naam mag alleen kleine letters, cijfers en koppeltekens bevatten, en moet beginnen met een letter.',
            'name.unique'    => 'Deze permissienaam bestaat al.',
            'name.max'       => 'Naam mag maximaal 100 tekens bevatten.',
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permissie \"{$request->name}\" succesvol aangemaakt.");
    }

    public function destroy(Permission $permission)
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $name = $permission->name;
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permissie \"{$name}\" verwijderd.");
    }
}
