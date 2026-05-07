<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExportReportsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissie aanmaken (of ophalen als die al bestaat)
        $permission = Permission::firstOrCreate(['name' => 'export-reports', 'guard_name' => 'web']);

        // Toewijzen aan rollen die mogen exporteren
        foreach (['onderwijsleider', 'kwaliteitszorg', 'ok_medewerker'] as $roleName) {
            $role = Role::findByName($roleName, 'web');
            $role->givePermissionTo($permission);
        }

        $this->command->info('Permissie "export-reports" aangemaakt en toegewezen aan onderwijsleider, kwaliteitszorg en ok_medewerker.');
    }
}
