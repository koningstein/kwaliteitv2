<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findByName('onderwijsleider', 'web');

        $permissions = [
            'edit-action-points',
            'delete-action-points',
            'edit-criteria-scores',
            'manage-team-users',
        ];

        foreach ($permissions as $permName) {
            $perm = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $role->givePermissionTo($perm);
        }
    }

    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findByName('onderwijsleider', 'web');

        $role->revokePermissionTo([
            'edit-action-points',
            'delete-action-points',
            'edit-criteria-scores',
            'manage-team-users',
        ]);
    }
};
