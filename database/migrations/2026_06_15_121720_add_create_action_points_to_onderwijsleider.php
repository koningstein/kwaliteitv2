<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $perm = Permission::firstOrCreate(['name' => 'create-action-points', 'guard_name' => 'web']);
        $role = Role::findByName('onderwijsleider', 'web');
        $role->givePermissionTo($perm);
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findByName('onderwijsleider', 'web');
        $role->revokePermissionTo('create-action-points');
    }
};
