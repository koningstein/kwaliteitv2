<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $perm = Permission::firstOrCreate(['name' => 'manage-permissions', 'guard_name' => 'web']);
        Role::findByName('ok_medewerker', 'web')->givePermissionTo($perm);
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findByName('ok_medewerker', 'web');
        $role->revokePermissionTo('manage-permissions');

        Permission::findByName('manage-permissions', 'web')?->delete();
    }
};
