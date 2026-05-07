<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Voeg nieuwe permissions toe aan een bestaande database
 * zonder migrate:fresh te hoeven draaien.
 *
 * Gebruik: php artisan db:seed --class=PermissionUpdateSeeder
 */
class PermissionUpdateSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Nieuwe permissions aanmaken (firstOrCreate = veilig om meerdere keren te draaien)
        $manageTeams = Permission::firstOrCreate(['name' => 'manage-teams']);
        $viewAllActionPoints = Permission::firstOrCreate(['name' => 'view-all-action-points']);

        // ok_medewerker krijgt manage-teams, view-all-action-points en manage-team-users
        $ok = Role::findByName('ok_medewerker');
        $ok->givePermissionTo([
            $manageTeams,
            $viewAllActionPoints,
            Permission::firstOrCreate(['name' => 'manage-team-users']), // was al aanwezig maar niet toegekend aan ok_medewerker
            Permission::firstOrCreate(['name' => 'create-action-points']),
            Permission::firstOrCreate(['name' => 'edit-action-points']),
            Permission::firstOrCreate(['name' => 'delete-action-points']),
            Permission::firstOrCreate(['name' => 'edit-criteria-scores']),
        ]);

        // directie krijgt view-all-action-points
        $directie = Role::findByName('directie');
        $directie->givePermissionTo($viewAllActionPoints);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Permissions bijgewerkt:');
        $this->command->info('  + manage-teams → ok_medewerker');
        $this->command->info('  + view-all-action-points → ok_medewerker, directie');
        $this->command->info('  + manage-team-users → ok_medewerker');
        $this->command->info('  + create-action-points → ok_medewerker');
        $this->command->info('  + edit-action-points → ok_medewerker');
        $this->command->info('  + delete-action-points → ok_medewerker');
        $this->command->info('  + edit-criteria-scores → ok_medewerker');
    }
}
