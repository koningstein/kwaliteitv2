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
            // Algemeen
            'view-dashboard',

            // Thema's / kwaliteitsstructuur (alleen O&K)
            'view-themes',
            'manage-themes',
            'manage-standards',
            'manage-criteria',
            'manage-indicators',
            'copy-period',          // criteria van jaar X kopiëren naar jaar Y

            // Actiepunten
            'view-action-points',
            'create-action-points',
            'edit-action-points',
            'delete-action-points',
            'edit-own-action-point-status',  // medewerker: eigen actiepunt status wijzigen
            'edit-own-action-point-dates',   // medewerker: eigen actiepunt datums wijzigen

            // Criteriumscores
            'view-criteria-scores',
            'edit-criteria-scores',

            // Gebruikers- en teambeheer
            'manage-team-users',            // kwaliteitsmedewerker: users van eigen team beheren
            'assign-team-quality-member',   // O&K + teamleider: kwaliteitsmedewerker toewijzen aan team

            // Dashboards
            'view-directie-dashboard',
            'view-team-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // -----------------------------------------------------------------------
        // Rol: directie
        // Mag alles inzien, niets bewerken
        // -----------------------------------------------------------------------
        $directie = Role::create(['name' => 'directie']);
        $directie->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'view-criteria-scores',
            'view-directie-dashboard',
            'view-team-dashboard',
        ]);

        // -----------------------------------------------------------------------
        // Rol: ok_medewerker (Onderwijs & Kwaliteit)
        // Beheert de kwaliteitsstructuur (thema's/standaarden/criteria/indicatoren)
        // voor alle teams. Mag ook kwaliteitsmedewerkers toewijzen aan teams.
        // Mag alles inzien.
        // -----------------------------------------------------------------------
        $ok = Role::create(['name' => 'ok_medewerker']);
        $ok->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'manage-themes',
            'manage-standards',
            'manage-criteria',
            'manage-indicators',
            'copy-period',
            'view-action-points',
            'view-criteria-scores',
            'assign-team-quality-member',
            'view-directie-dashboard',
            'view-team-dashboard',
        ]);

        // -----------------------------------------------------------------------
        // Rol: kwaliteitszorg (1 per team)
        // Vult criteriumscores in voor zijn eigen team.
        // Beheert actiepunten voor zijn eigen team.
        // Beheert gebruikers van zijn eigen team.
        // -----------------------------------------------------------------------
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
            'manage-team-users',
            'view-team-dashboard',
        ]);

        // -----------------------------------------------------------------------
        // Rol: onderwijsleider (= teamleider)
        // Mag kwaliteitsmedewerkers toewijzen aan zijn team(s).
        // Mag alles inzien van zijn eigen team(s).
        // -----------------------------------------------------------------------
        $onderwijsleider = Role::create(['name' => 'onderwijsleider']);
        $onderwijsleider->givePermissionTo([
            'view-dashboard',
            'view-themes',
            'view-action-points',
            'view-criteria-scores',
            'assign-team-quality-member',
            'view-team-dashboard',
        ]);

        // -----------------------------------------------------------------------
        // Rol: medewerker (= docent)
        // Mag zijn eigen toegewezen actiepunten bekijken en de status/datums wijzigen.
        // -----------------------------------------------------------------------
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
