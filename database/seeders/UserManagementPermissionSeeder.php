<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Voegt manage-users permission toe aan ok_medewerker.
 * Gebruik: php artisan db:seed --class=UserManagementPermissionSeeder
 */
class UserManagementPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::firstOrCreate(['name' => 'manage-users']);

        $ok = Role::findByName('ok_medewerker');
        $ok->givePermissionTo($permission);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Permissions bijgewerkt:');
        $this->command->info('  + manage-users → ok_medewerker');
    }
}
