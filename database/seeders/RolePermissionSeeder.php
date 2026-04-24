<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Alle permissies aanmaken
        $permissions = [
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'create-action-points',
            'edit-action-points',
            'edit-own-action-point-status',
            'edit-own-action-point-dates',
            'delete-action-points',
            'view-criteria-scores',
            'edit-criteria-scores',
            'manage-users',
            'assign-roles',
            'view-directie-dashboard',
            'view-team-dashboard',
            'manage-teams',
            'manage-themes',
            'manage-standards',
            'manage-criteria',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Rol: directie
        $directie = Role::create(['name' => 'directie']);
        $directie->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'view-criteria-scores',
            'view-directie-dashboard',
            'view-team-dashboard',
        ]);

        // Rol: ok_medewerker
        $ok = Role::create(['name' => 'ok_medewerker']);
        $ok->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'create-action-points',
            'view-criteria-scores',
            'edit-criteria-scores',
            'assign-roles',
            'view-directie-dashboard',
            'view-team-dashboard',
            'manage-teams',
            'manage-themes',
            'manage-standards',
            'manage-criteria',
        ]);

        // Rol: kwaliteitszorg
        $kwaliteit = Role::create(['name' => 'kwaliteitszorg']);
        $kwaliteit->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'create-action-points',
            'edit-action-points',
            'delete-action-points',
            'view-criteria-scores',
            'edit-criteria-scores',
            'manage-users',
            'view-team-dashboard',
            'manage-teams',
        ]);

        // Rol: onderwijsleider (= teamleider)
        $onderwijsleider = Role::create(['name' => 'onderwijsleider']);
        $onderwijsleider->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'view-criteria-scores',
            'view-team-dashboard',
        ]);

        // Rol: medewerker (= docent)
        $medewerker = Role::create(['name' => 'medewerker']);
        $medewerker->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'edit-own-action-point-status',
            'edit-own-action-point-dates',
            'view-team-dashboard',
        ]);
    }
}
