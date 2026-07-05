<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Zorg dat manage-users bestaat (kan gemist zijn als UserManagementPermissionSeeder niet draaide)
        $manageUsers = Permission::firstOrCreate(['name' => 'manage-users', 'guard_name' => 'web']);
        Role::findByName('ok_medewerker', 'web')->givePermissionTo($manageUsers);

        // Maak admin-rol aan en geef alle bestaande permissies
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::where('guard_name', 'web')->get());

        // manage-permissions hoort exclusief bij admin, niet bij ok_medewerker
        $okMedewerker = Role::findByName('ok_medewerker', 'web');
        $okMedewerker->revokePermissionTo('manage-permissions');
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::findByName('admin', 'web')?->delete();

        // Herstel manage-permissions voor ok_medewerker
        $perm = Permission::findByName('manage-permissions', 'web');
        if ($perm) {
            Role::findByName('ok_medewerker', 'web')?->givePermissionTo($perm);
        }
    }
};
